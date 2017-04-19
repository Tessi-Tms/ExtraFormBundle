/* global Vue */
Vue.component('form-editor-raw', {

  template:
    '<div class="extra-form-editor raw-mode">' +
        '<textarea ' +
          ':data-textarea-id="textarea.id" ' +
          'v-model="raw" ' +
          ':name="textarea.name" ' +
        '>' +
        '</textarea>' +
        '<div class="json-errors"></div>' +
        '<button @click.prevent="generateFields">' +
          'Fill the visual mode form fields from this json' +
        '</button>' +
    '</div>',

  props: ['fields'],

  data: function () {
    return {
      raw: '',
      textarea: this.$store.state.formProperties
    };
  },

  /* global rawMixin, rawModalMixin */
  mixins: [rawMixin, rawModalMixin],

  created: function () {
    // If the textarea is empty, do not attempt to generate fields
    if (this.textarea.value !== '') {
      this.raw = this.textarea.value;
      this.generateFields();
    }
  },

  watch: {
    fields: {
      handler: function (newFields) {
        this.raw = this.generateRaw(newFields);
        this.updateInitialTextareaValue();
      },
      deep: true
    }
  },

  methods: {

    /**
     * Generate the form fields from the textarea raw
     */
    generateFields: function (event) {
      var newFields = [];

      try {
        /* global transformRawToJson */
        var raw = JSON.parse(transformRawToJson(this.raw));

        newFields = this.createFieldsRecursively(raw);

        this.$emit('generated', newFields);
        this.closeModal(event);

      // Json parsing error
      } catch (error) {
        this.displayJsonParseErrors(event, error);
      }
    },

    /**
     * Generate the raw (the json for the textarea)
     *
     * @param fields
     */
    generateRaw: function (fields) {
      var raw = this.createExtraFormRawRecursively(fields);

      /* global transformJsonToRaw */
      return transformJsonToRaw(JSON.stringify(raw, null, 4));
    }

  }
});
