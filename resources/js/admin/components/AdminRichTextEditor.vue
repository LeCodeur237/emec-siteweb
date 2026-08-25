<template>
    <div class="rich-editor">
        <div ref="editorElement"></div>
    </div>
</template>

<script setup>
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const editorElement = ref(null);
let quill = null;
let syncing = false;

const toolbar = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['blockquote', 'link'],
    ['clean'],
];

onMounted(async () => {
    await nextTick();

    quill = new Quill(editorElement.value, {
        modules: { toolbar },
        placeholder: 'Rediger le contenu du message...',
        theme: 'snow',
    });

    quill.root.innerHTML = props.modelValue || '';
    quill.on('text-change', () => {
        syncing = true;
        emit('update:modelValue', quill.root.innerHTML);
        syncing = false;
    });
});

watch(() => props.modelValue, (value) => {
    if (!quill || syncing || value === quill.root.innerHTML) {
        return;
    }

    const selection = quill.getSelection();
    quill.root.innerHTML = value || '';

    if (selection) {
        quill.setSelection(selection);
    }
});

onBeforeUnmount(() => {
    quill = null;
});
</script>
