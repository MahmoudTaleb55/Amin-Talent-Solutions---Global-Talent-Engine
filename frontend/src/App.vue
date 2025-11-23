<template>
  <div id="app" class="min-h-screen bg-secondary-50 text-secondary-900 ds-font-sans transition-colors">
    <Header />
    <main class="flex-1">
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script>
import Header from './components/common/Header.vue';
import Footer from './components/common/Footer.vue';

export default {
  name: 'App',
  components: { Header, Footer }
};
</script>

<style>
/* Global layout styling handled by Tailwind + design system */
</style>
