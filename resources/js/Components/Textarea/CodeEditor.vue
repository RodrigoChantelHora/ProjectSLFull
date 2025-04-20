<template>
    <div ref="editor" class="border border-gray-300 rounded-md overflow-auto"></div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { EditorView, basicSetup } from 'codemirror'
import { EditorState } from '@codemirror/state'
import { sql } from '@codemirror/lang-sql'

const props = defineProps({
    modelValue: String
})
const emit = defineEmits(['update:modelValue'])

const editor = ref(null)
let view = null

onMounted(() => {
    view = new EditorView({
        state: EditorState.create({
            doc: props.modelValue || '',
            extensions: [
                basicSetup,
                sql(),
                EditorView.updateListener.of(update => {
                    if (update.docChanged) {
                        const value = update.state.doc.toString()
                        emit('update:modelValue', value)
                    }
                })
            ]
        }),
        parent: editor.value
    })
})

watch(() => props.modelValue, (newVal) => {
    if (view && view.state.doc.toString() !== newVal) {
        view.dispatch({
            changes: { from: 0, to: view.state.doc.length, insert: newVal }
        })
    }
})
</script>

<style scoped>
.cm-editor {
    font-family: monospace;
    font-size: 14px;
    line-height: 20px;
    min-height: calc(20px * 10); /* 10 linhas visíveis */
}
</style>
