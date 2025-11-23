<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">{{ label }}</label>
    <component
      :is="tag"
      :id="id"
      v-bind="inputAttrs"
      :class="inputClass"
      :value="modelValue"
      @input="onInput"
      @change="$emit('change', $event)"
    >
      <slot v-if="tag === 'select'" />
    </component>
    <p v-if="help" class="text-xs text-secondary-500 dark:text-secondary-400 mt-1">{{ help }}</p>
  </div>
</template>

<script>
export default {
  name: 'BaseInput',
  props: {
    modelValue: [String, Number, File, null],
    id: { type: String, default: null },
    label: { type: String, default: '' },
    help: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    type: { type: String, default: 'text' },
    tag: { type: String, default: 'input' },
    disabled: { type: Boolean, default: false }
  },
  computed: {
    inputAttrs() {
      const common = {
        placeholder: this.placeholder,
        disabled: this.disabled,
        autocomplete: 'off',
        class: null
      };
      if (this.tag === 'input') return { ...common, type: this.type };
      return common;
    },
    inputClass() {
      return 'block w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg shadow-sm placeholder-secondary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200';
    }
  },
  methods: {
    onInput(e) {
      const value = e.target.value;
      this.$emit('update:modelValue', value);
    }
  }
};
</script>

<style scoped>
/* Use Tailwind utilities from global CSS */
</style>
