<template>
  <button
    :type="type"
    :class="buttonClass"
    :disabled="disabled || loading"
    @click="$emit('click', $event)"
    aria-busy="false"
    :aria-disabled="disabled || loading"
  >
    <span v-if="loading" class="inline-flex items-center mr-2">
      <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
      </svg>
    </span>
    <slot />
  </button>
</template>

<script>
export default {
  name: 'BaseButton',
  props: {
    type: { type: String, default: 'button' },
    variant: { type: String, default: 'primary' },
    size: { type: String, default: 'md' },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false }
  },
  computed: {
    buttonClass() {
      const base = 'inline-flex items-center justify-center rounded-md font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2';
      const sizeMap = {
        sm: 'px-3 py-2 text-sm',
        md: 'px-4 py-2 text-sm',
        lg: 'px-6 py-3 text-base'
      };

      const variantMap = {
        primary: 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
        secondary: 'bg-secondary-100 text-secondary-900 hover:bg-secondary-200 focus:ring-secondary-500',
        success: 'bg-success-600 text-white hover:bg-success-700 focus:ring-success-500',
        danger: 'bg-danger-600 text-white hover:bg-danger-700 focus:ring-danger-500',
        outline: 'bg-transparent border-2 border-current hover:bg-current hover:text-white'
      };

      const classes = [base, sizeMap[this.size] || sizeMap.md, variantMap[this.variant] || variantMap.primary];
      if (this.disabled || this.loading) classes.push('opacity-60 cursor-not-allowed');
      return classes.join(' ');
    }
  }
};
</script>

<style scoped>
/* Nothing custom here; Tailwind utility classes are used */
</style>
