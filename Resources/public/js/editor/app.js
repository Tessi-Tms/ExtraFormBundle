/**
 * The function that will trigger the editor
 *
 * @param element : string|Object the dom element to trigger the editor
 * @param defaultTextarea : object with textarea default values
 */
function triggerEditor(element, defaultTextarea) {

  Vue.component('Multiselect', VueMultiselect.default);
  Vue.use(VueResource);

  new Vue({

    el: element,
    data: {
      fields: [],
      textarea: defaultTextarea,
      // default values
      configuration: {
        enableTextareaOutput: true
      }
    },

    methods: {

      /**
       * Update the fields
       *
       * @param fields
       */
      updateFields: function (fields) {
        this.$set(this, 'fields', fields);
      }
    }

  });
}