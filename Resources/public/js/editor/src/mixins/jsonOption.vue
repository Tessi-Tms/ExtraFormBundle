<script>

import optionMixin from './option.vue'
import waitForItMixin from './waitForIt.vue'
import JsonToTwigTransformer from 'ExtraFormBundle/utils/JsonToTwigTransformer.js'

/**
 * Option where we can put json in (input type text and textareas)
 */

export default {

  mixins: [optionMixin, waitForItMixin],

  data: function () {
    return {
      classes: ''
    };
  },

  /**
   * Update the value on component creation
   */
  created: function () {
    if ('object' === typeof this.value) {
      this.data = JsonToTwigTransformer.toRaw(JSON.stringify(this.value, null, 4));
      this.setJsonAttemptClass(this.data);
    }
  },

  /**
   * Update the value on component update
   */
  watch: {
    value: {
      handler: function (value) {
        if ('object' === typeof value) {
          this.data = JsonToTwigTransformer.toRaw(JSON.stringify(value, null, 4));
          this.setJsonAttemptClass(this.data);
        }
      }
    }
  },

  methods: {
    onOptionValueChanged: function (value) {
      var self = this;

      return new Promise(function (resolve) {
        self.waitForIt(function () {
          self.updateOption(value);
          self.setJsonAttemptClass(value);
          resolve();
        }, 300);
      });

    },

    /**
     * Set a class on the input or textarea to add an icon indicating if the json is valid
     * A json attempt is considered id '{' or '[' are typed first
     *
     * @param value
     */
    setJsonAttemptClass: function (value) {
      if (
        0 !== value.indexOf('{{') &&
        (0 === value.indexOf('{') || 0 === value.indexOf('['))
      ) {
        try {
          JSON.parse(JsonToTwigTransformer.toJson(value));
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

</script>
