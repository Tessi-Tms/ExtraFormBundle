/**
 * Base component for a field option
 */
/* exported optionMixin */

var optionMixin = {

  props: ['option', 'name', 'value'],

  data: function () {
    return {
      // Default value from the api
      data: this.option.options.data
    };
  },

  /**
   * Update the value on component creation
   */
  created: function () {
    if (typeof this.value !== 'undefined') {
      this.data = this.value;
      if (typeof this.value !== 'string') {
        this.data = JSON.stringify(this.value);
      }
    }
  },

  /**
   * Update the value on component update
   */
  watch: {
    value: {
      handler: function (value) {
        this.data = value;
        if (typeof value !== 'string') {
          this.data = JSON.stringify(value);
        }
      }
    }
  },

  methods: {

    /**
     * Update an option value for the raw
     *
     * @param value
     */
    updateOption: function (value) {
      this.data = value;
      this.$emit('changed', {
        name: this.name,
        value: value
      });
    },

    /**
     * Set a class on the input or textarea to add an icon indicating if the json is valid
     * A json attempt is considered id '{' or '[' are typed first
     *
     * @param value
     */
    setJsonAttemptClass: function (value) {
      if (0 === value.indexOf('{') || 0 === value.indexOf('[')) {
        try {
          JSON.parse(value);
          this.classes = 'fa fa-check success feedback';
        } catch (e) {
          this.classes = 'fa fa-exclamation-circle warning feedback';
        }
      } else {
        this.classes = '';
      }
    }
  }
};