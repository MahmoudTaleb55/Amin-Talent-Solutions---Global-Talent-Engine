<template>
  <div class="skeleton-card p-4">
    <div class="flex items-center space-x-4">
      <div class="h-12 w-12 rounded bg-secondary-200"></div>
      <div class="flex-1">
        <div class="h-4 bg-secondary-200 rounded w-3/4 mb-2"></div>
        <div class="h-3 bg-secondary-200 rounded w-1/2"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SkeletonCard'
};
</script>

<style scoped>
.skeleton-card { @apply bg-white rounded-xl p-4 shadow-soft; }
.skeleton-card > div > .bg-secondary-200 { @apply animate-pulse; }
</style>
