<template>
  <div v-if="message" :class="[
    'p-4 rounded-md mb-4',
    type === 'error' ? 'bg-red-50 text-red-700 border-red-400' : 'bg-yellow-50 text-yellow-700 border-yellow-400'
  ]">
    {{ message }}
  </div>
</template>

<script setup>
defineProps({
  message: String,
  type: {
    type: String,
    default: 'error'
  }
});
</script>
