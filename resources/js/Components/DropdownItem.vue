<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

// Estado do dropdown
const isOpen = ref(false);
const dropdownButton = ref(null);
const dropdownMenu = ref(null);

// Props para os links e ID do cliente
defineProps({
  links: {
    type: Array,
    required: true,
    default: () => [] // Um array vazio como padrão
  },
  dataId: {
    type: Number,
    required: true
  },
  dataToken: {
    type: String,
    required: true
  }
});

// Funções para controlar o dropdown
function toggleDropdown() {
  isOpen.value = !isOpen.value;
}

function closeDropdown(event) {
  if (
    dropdownMenu.value &&
    !dropdownMenu.value.contains(event.target) &&
    !dropdownButton.value.contains(event.target)
  ) {
    isOpen.value = false;
  }
}
// Adiciona/Remove o listener global
onMounted(() => {
  document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown);
});
</script>

<template>
  <div class="relative inline-block text-left">
    <!-- Botão do Dropdown -->
    <button
      @click="toggleDropdown"
      ref="dropdownButton"
      class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2"
      aria-haspopup="true"
      :aria-expanded="isOpen"
    >
    <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Conteúdo do Dropdown -->
    <div
      v-if="isOpen"
      ref="dropdownMenu"
      class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
      role="menu"
      aria-orientation="vertical"
      aria-labelledby="menu-button"
    >
      <div class="py-1" role="none">
        <!-- Renderização dinâmica dos links com ID -->
        <Link
          v-for="(link, index) in links"
          :key="index"
          :href="`${link.href}/${dataId}/${dataToken}`"
          class="grid grid-cols-4 gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
          role="menuitem"
        >
          <div class="col-span-1"><i :class="link.icon"></i></div>
          <div class="col-span-3"><span>{{ link.label }}</span></div>
        </Link>
      </div>
    </div>
  </div>
</template>
