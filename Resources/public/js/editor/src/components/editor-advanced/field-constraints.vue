<template>

  <div>
    <label>Constraints : </label>
    <div class="field-constraint collapsed-block" :key="index" v-for="(constraint, index) in fieldConstraints">
      <button @click.prevent="removeConstraint(index)" aria-label="Close" class="close">
        <span aria-hidden="true">×</span>
      </button>
      <strong>{{ constraint.extra_form_constraint }}</strong>
      <field-constraint-options :fieldConstraint="constraint" :index="index" @optionChanged="updateOption"/>
    </div>
  </div>

</template>

<script>

import editorAdvancedFieldConstraintOptions from './field-constraint-options.vue';

export default {

  props: ['fieldConstraints'],

  components: {

    'field-constraint-options': editorAdvancedFieldConstraintOptions
  },

  methods: {

    /**
     * Update a field constraint option on a field constraint
     *
     * @param option
     */
    updateOption: function (option) {
      this.$set(this.fieldConstraints.options, option.name, option.value);
    },

    /**
     * Remove a constraint on a field
     *
     * @param index
     */
    removeConstraint: function (index) {
      this.fieldConstraints.splice(index, 1);
    }
  }
};

</script>
