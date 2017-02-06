var editorAdvancedFieldOptions = {

  template:
      '<div>' +
          '<label>Options : </label>' +

          '<component v-if="option.component_name !== \'editor\'" :is="option.component_name" v-for="(option, key) in options" :option="option" :name="key" :value="fieldOptions[key]" @changed="updateOption"></component>' +

          '<div v-for="(option, key) in options" v-if="option.component_name === \'editor\'">' +
              '<button :id="id" @click.prevent="triggerModal">Advanced visual mode</button> '+
              '<div class="modal fade modal-fullscreen" :id="\'modal_\' + id">' +
                  '<div class="modal-dialog" role="document">' +
                      '<div class="modal-content">' +
                          '<div class="modal-header">' +
                              '<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                              '<h4 class="modal-title">Advanced visual mode</h4>' +
                          '</div>' +
                          '<div class="modal-body">' +
                              '<editor-advanced :fields="fieldOptions[key]"></editor-advanced>' +
                          '</div>' +
                          '<div class="modal-footer">' +
                              '<button class="close-advanced-visual-mode">Close the editor</button>' +
                              '<em>All your changes are automatically saved</em>' +
                          '</div>' +
                      '</div>' +
                  '</div>' +
              '</div>' +
          '</div>' +
      '</div>'
  ,

  props: ['type', 'fieldOptions'],

  data: function () {
    return {
      options: {}
    }
  },

  computed: {
    id: function() {
      return this.generateUniqueId();
    }
  },

  components: {
    'option-checkbox': checkboxOption,
    'option-textarea': textareaOption,
    'option-choice': choiceOption,
    'option-text': textOption,
    'option-number': numberOption,
    'option-integer': integerOption
  },

  beforeMount: function() {
    this.getExtraFormTypeOptions(this.type);
  },

  watch: {
    type: {
      handler: function(newType) {
        this.getExtraFormTypeOptions(newType);
      }
    }
  },

  methods: {

    triggerModal: function (event) {
      $modal = $(event.target).siblings('#modal_' + event.target.id).first();
      $modal.modal('show');

      $modal.find('.close').on('click', function(event) {
        event.preventDefault();
        $(this).closest('.modal').modal('hide');
      });
    },

    /**
     * Generate a unique id for the fields default names
     *
     * @returns string
     */
    generateUniqueId: function() {
      return Math.random().toString(36).substr(2, 9);
    },

    updateOption: function(option) {
      this.$set(this.fieldOptions, option.name, option.value);
    },

    /**
     * Get the form type options
     *
     * @param type
     */
    getExtraFormTypeOptions: function(type) {
      this.$http.get('/extra-form-types/'+ type +'/options.json')
        .then(
        function(response) {
          var options = response.body;
          for (var option in options) {
            if (options.hasOwnProperty(option)) {
              if (option === 'configuration') {
                options[option]['component_name'] = 'editor';
                if (typeof this.fieldOptions[option] === 'undefined') {
                  // initialize the configuration fields for the first time
                  this.$set(this.fieldOptions, option, []);
                }
              } else {
                options[option]['component_name'] = 'option-' + options[option].extra_form_type;
              }
            }
          }
          this.options = options;
        },
        function (response) {
          console.log(response.status + ' ' + response.statusText);
        }
      )
      ;
    }
  }
};