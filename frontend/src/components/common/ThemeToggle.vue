<template>
  <button @click="toggle" :aria-pressed="isDark" class="ml-3 p-2 rounded-md border border-secondary-200 bg-secondary-50 hover:shadow-glow" :title="isDark ? 'Switch to light' : 'Switch to dark'">
    <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary-700" viewBox="0 0 20 20" fill="currentColor">
      <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zM4.22 5.47a1 1 0 011.42 0l.7.7a1 1 0 11-1.42 1.42l-.7-.7a1 1 0 010-1.42zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zM14.66 5.47a1 1 0 010 1.42l-.7.7a1 1 0 11-1.42-1.42l.7-.7a1 1 0 011.42 0zM16 9a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM10 14a4 4 0 100-8 4 4 0 000 8z" />
    </svg>
    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary-700" viewBox="0 0 20 20" fill="currentColor">
      <path d="M17.293 13.293A8 8 0 116.707 2.707a7 7 0 1010.586 10.586z" />
    </svg>
  </button>
</template>

<script>
export default {
  name: 'ThemeToggle',
  data() {
    return { isDark: false };
  },
  mounted() {
    // prefer saved preference, else system
    const saved = localStorage.getItem('theme');
    if (saved) this.isDark = saved === 'dark';
    else this.isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Apply theme immediately
    this.apply();
    
    // Listen for system theme changes
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
          this.isDark = e.matches;
          this.apply();
        }
      });
    }
  },
  methods: {
    toggle() {
      this.isDark = !this.isDark;
      localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
      this.apply();
    },
    apply() {
      // Use html class for Tailwind dark mode support
      document.documentElement.classList.toggle('dark', this.isDark);
      // Also toggle theme-dark for CSS variable system
      document.documentElement.classList.toggle('theme-dark', this.isDark);
      document.body.classList.toggle('theme-dark', this.isDark);
      // Force Tailwind to re-evaluate
      document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light');
    }
  }
};
</script>

<style scoped>
button { transition: box-shadow 150ms ease; }
</style>
