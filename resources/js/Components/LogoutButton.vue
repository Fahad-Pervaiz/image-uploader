<!-- LogoutButton.vue -->
<script setup>
import { router } from '@inertiajs/vue3';

function logout() {
  router.post(route('logout'), {}, {
    preserveState: false,
    preserveScroll: false,
    onSuccess: () => {
      window.location.reload(); // ✅ full reload fixes 419 issues
    },
  });
}
</script>

<template>
  <button @click="logout" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 w-full">
    Log Out
  </button>
</template>
