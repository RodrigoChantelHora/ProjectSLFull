<template>
  <div>
    <div ref="editorContainer" class="quill-editor bg-white"></div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

const props = defineProps({
  modelValue: String
})
const emit = defineEmits(['update:modelValue'])

const editorContainer = ref(null)
let quill

onMounted(() => {
  quill = new Quill(editorContainer.value, {
    theme: 'snow',
    placeholder: 'Área dedicada ao corpo do e-mail',
    modules: {
      toolbar: [
        [{ font: [] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ size: [ 'small', false, 'large', 'huge' ]}],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ align: [] }],
        ['link', 'image'],
        ['clean']
      ]
    }
  })

  // Set initial content
  quill.root.innerHTML = props.modelValue || ''

  // Emit changes
  quill.on('text-change', () => {
    emit('update:modelValue', quill.root.innerHTML)
  })
})

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (quill && quill.root.innerHTML !== newValue) {
    quill.root.innerHTML = newValue
  }
})
</script>

<style scoped>
.quill-editor {
  min-height: 200px;
}
</style>
