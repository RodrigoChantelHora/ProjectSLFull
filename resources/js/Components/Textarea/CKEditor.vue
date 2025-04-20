<!-- CkEditor.vue -->
<script setup>
import { ClassicEditor } from '@ckeditor/ckeditor5-build-classic'
import CKEditor from '@ckeditor/ckeditor5-vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const editorConfig = {
  // Configurações opcionais do editor
}
</script>

<template>
  <component
    :is="CKEditor.component"
    :editor="ClassicEditor"
    :model-value="modelValue"
    @update:model-value="value => emit('update:modelValue', value)"
    :config="editorConfig"
  />
</template>
