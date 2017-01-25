var numberOption = {

  template:
    '<div>' +
      '<label :for="name">{{ name }}</label>' +
      '<input :required="option.options.required" :value="value" @input="updateOption($event.target.value)" type="number" :name="name">' +
    '</div>'
  ,

  props: ['option', 'value', 'name'],

  methods: {
    updateOption: function(value) {
      this.$emit('changed', {
        'name': this.name,
        'value': value
      });
    }
  }
};
