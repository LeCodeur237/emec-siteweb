<template>
    <section class="crud-panel">
        <header class="crud-header">
            <div>
                <h2>{{ resource.title }}</h2>
                <p>{{ resource.description }}</p>
            </div>
            <button type="button" @click="startCreate">Nouveau</button>
        </header>

        <div class="crud-toolbar">
            <input
                v-model="filters.search"
                type="search"
                :placeholder="`Rechercher ${resource.title.toLowerCase()}`"
                @keyup.enter="loadItems(1)"
            >
            <select v-if="hasStatusFilter" v-model="filters.status" @change="loadItems(1)">
                <option value="">Tous les statuts</option>
                <option v-for="option in availableStatusOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
            <select v-if="hasActiveFilter" v-model="filters.active" @change="loadItems(1)">
                <option value="">Tous</option>
                <option value="true">Actifs</option>
                <option value="false">Inactifs</option>
            </select>
            <template v-for="filter in customFilters" :key="filter.key">
                <select
                    v-if="filter.type === 'select'"
                    v-model="filters.custom[filter.key]"
                    @change="loadItems(1)"
                >
                    <option value="">{{ filter.placeholder || filter.label }}</option>
                    <option v-for="option in filter.options" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
                <input
                    v-else
                    v-model="filters.custom[filter.key]"
                    :type="filter.type || 'text'"
                    :placeholder="filter.placeholder || filter.label"
                    @keyup.enter="loadItems(1)"
                >
            </template>
            <button type="button" @click="loadItems(1)">Filtrer</button>
        </div>

        <div class="crud-table-wrap">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th v-for="column in resource.columns" :key="column.key">{{ column.label }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td :colspan="resource.columns.length + 1">Chargement...</td>
                    </tr>
                    <tr v-else-if="items.length === 0">
                        <td :colspan="resource.columns.length + 1">Aucun resultat.</td>
                    </tr>
                    <template v-else>
                        <tr v-for="item in items" :key="item.id">
                            <td v-for="column in resource.columns" :key="column.key">
                                <span v-if="column.badge" class="status-badge">{{ formatValue(item, column) }}</span>
                                <span v-else>{{ formatValue(item, column) }}</span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" @click="startEdit(item)">Editer</button>
                                    <button type="button" class="danger" @click="deleteItem(item)">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <footer class="crud-footer">
            <span>Page {{ meta.current_page || 1 }} / {{ meta.last_page || 1 }}</span>
            <div>
                <button type="button" :disabled="!links.prev" @click="loadItems(meta.current_page - 1)">Precedent</button>
                <button type="button" :disabled="!links.next" @click="loadItems(meta.current_page + 1)">Suivant</button>
            </div>
        </footer>

        <div v-if="editing" class="modal-backdrop" @click.self="cancelEdit">
            <form class="crud-form modal-panel" @submit.prevent="save">
                <header>
                    <h3>{{ editing.id ? 'Modifier' : 'Creer' }} {{ resource.singular }}</h3>
                    <button class="modal-close" type="button" aria-label="Fermer" @click="cancelEdit">
                        <i class="ti ti-x" aria-hidden="true"></i>
                    </button>
                </header>

                <div class="form-grid">
                    <label v-for="field in resource.fields" :key="field.key" :class="{ wide: ['textarea', 'richtext', 'image-upload'].includes(field.type) }">
                        {{ field.label }}
                        <textarea
                            v-if="field.type === 'textarea'"
                            v-model="form[field.key]"
                            rows="4"
                        />
                        <AdminRichTextEditor
                            v-else-if="field.type === 'richtext'"
                            v-model="form[field.key]"
                        />
                        <span v-else-if="field.type === 'image-upload'" class="upload-field">
                            <img v-if="form[field.key]" :src="form[field.key]" :alt="field.label">
                            <small v-if="form[field.key]">{{ form[field.key] }}</small>
                            <input
                                type="file"
                                accept="image/*"
                                @change="setUploadFile(field.key, $event)"
                            >
                        </span>
                        <select
                            v-else-if="field.type === 'multiselect'"
                            v-model="form[field.key]"
                            multiple
                            size="6"
                        >
                            <option v-for="option in fieldOptions(field)" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                        <select v-else-if="field.type === 'select'" v-model="form[field.key]">
                            <option :value="field.nullable ? null : ''">{{ field.placeholder || 'Selectionner' }}</option>
                            <option v-for="option in fieldOptions(field)" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                        <input
                            v-else-if="field.type === 'checkbox'"
                            v-model="form[field.key]"
                            type="checkbox"
                        >
                        <input
                            v-else
                            v-model="form[field.key]"
                            :type="field.type || 'text'"
                        >
                    </label>
                </div>

                <p v-if="formError" class="form-error">{{ formError }}</p>
                <div class="form-actions">
                    <button type="button" @click="cancelEdit">Annuler</button>
                    <button type="submit" :disabled="saving">{{ saving ? 'Enregistrement...' : 'Enregistrer' }}</button>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import AdminRichTextEditor from './AdminRichTextEditor.vue';

const props = defineProps({
    api: {
        type: Function,
        required: true,
    },
    resource: {
        type: Object,
        required: true,
    },
});

const items = ref([]);
const meta = ref({});
const links = ref({});
const loading = ref(false);
const saving = ref(false);
const editing = ref(null);
const form = reactive({});
const uploadFiles = reactive({});
const formError = ref('');
const optionCache = reactive({});

const filters = reactive({
    search: '',
    status: '',
    active: '',
    custom: {},
});

const statusOptions = [
    { value: 'draft', label: 'Brouillon' },
    { value: 'published', label: 'Publie' },
    { value: 'archived', label: 'Archive' },
];

const hasStatusFilter = computed(() => props.resource.filters?.includes('status'));
const hasActiveFilter = computed(() => props.resource.filters?.includes('active'));
const availableStatusOptions = computed(() => props.resource.statusOptions ?? statusOptions);
const customFilters = computed(() => props.resource.customFilters ?? []);

onMounted(async () => {
    await loadOptions();
    await loadItems();
});

watch(() => props.resource.key, async () => {
    cancelEdit();
    filters.search = '';
    filters.status = '';
    filters.active = '';
    filters.custom = {};
    await loadOptions();
    await loadItems();
});

async function loadItems(page = 1) {
    loading.value = true;
    formError.value = '';

    try {
        const params = new URLSearchParams({
            page,
            per_page: 10,
            sort: props.resource.defaultSort || 'created_at',
            direction: props.resource.defaultDirection || 'desc',
        });

        if (filters.search) {
            params.set('search', filters.search);
        }

        if (hasStatusFilter.value && filters.status) {
            params.set('status', filters.status);
        }

        if (hasActiveFilter.value && filters.active) {
            params.set('active', filters.active);
        }

        for (const filter of customFilters.value) {
            const value = filters.custom[filter.key];
            if (value !== undefined && value !== null && value !== '') {
                params.set(filter.key, value);
            }
        }

        const response = await props.api(`${props.resource.endpoint}?${params.toString()}`);
        items.value = response.data ?? [];
        meta.value = response.meta ?? {};
        links.value = response.links ?? {};
    } catch (error) {
        formError.value = error.message || 'Chargement impossible.';
    } finally {
        loading.value = false;
    }
}

async function loadOptions() {
    const optionFields = props.resource.fields.filter((field) => field.optionsEndpoint);

    for (const field of optionFields) {
        if (optionCache[field.optionsEndpoint]) {
            continue;
        }

        const response = await props.api(`${field.optionsEndpoint}?per_page=100&sort=${field.optionSort || 'name'}&direction=asc`);
        optionCache[field.optionsEndpoint] = (response.data ?? []).map((item) => ({
            value: item.id,
            label: item[field.optionLabel || 'name'] || item.title || `#${item.id}`,
        }));
    }
}

function startCreate() {
    editing.value = {};
    fillForm({});
}

function startEdit(item) {
    editing.value = item;
    fillForm(item);
}

function fillForm(item) {
    formError.value = '';

    for (const key of Object.keys(form)) {
        delete form[key];
    }

    for (const key of Object.keys(uploadFiles)) {
        delete uploadFiles[key];
    }

    for (const field of props.resource.fields) {
        if (field.type === 'checkbox') {
            form[field.key] = Boolean(item[field.key] ?? field.default ?? false);
            continue;
        }

        form[field.key] = inputValue(item[field.key], field, item);
    }
}

function cancelEdit() {
    editing.value = null;
    formError.value = '';
}

async function save() {
    saving.value = true;
    formError.value = '';

    try {
        const payload = {};

        for (const field of props.resource.fields) {
            const value = uploadFiles[field.key]
                ? await uploadImage(field, uploadFiles[field.key])
                : form[field.key];

            payload[field.key] = payloadValue(value, field);
        }

        const path = editing.value?.id
            ? `${props.resource.endpoint}/${editing.value.id}`
            : props.resource.endpoint;

        await props.api(path, {
            method: editing.value?.id ? 'PATCH' : 'POST',
            body: JSON.stringify(payload),
        });

        cancelEdit();
        await loadItems(meta.value.current_page || 1);
    } catch (error) {
        formError.value = error.message || 'Enregistrement impossible.';
    } finally {
        saving.value = false;
    }
}

function setUploadFile(key, event) {
    uploadFiles[key] = event.target.files?.[0] ?? null;
}

async function uploadImage(field, file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('title', field.label);

    const response = await props.api('/admin/media', {
        method: 'POST',
        body: formData,
    });

    return response.data?.url ?? response.url ?? null;
}

async function deleteItem(item) {
    if (!confirm(`Supprimer "${item.title || item.name}" ?`)) {
        return;
    }

    try {
        await props.api(`${props.resource.endpoint}/${item.id}`, { method: 'DELETE' });
        await loadItems(meta.value.current_page || 1);
    } catch (error) {
        formError.value = error.message || 'Suppression impossible.';
    }
}

function fieldOptions(field) {
    if (field.options) {
        return field.options;
    }

    return optionCache[field.optionsEndpoint] ?? [];
}

function formatValue(item, column) {
    const value = column.key.split('.').reduce((current, key) => current?.[key], item);

    if (column.format === 'boolean') {
        return value ? 'Oui' : 'Non';
    }

    if (column.format === 'list') {
        return Array.isArray(value)
            ? value.map((entry) => entry?.[column.listKey || 'name'] ?? entry).filter(Boolean).join(', ') || '-'
            : '-';
    }

    if (column.format === 'weekday') {
        return weekdays[value] ?? value ?? '-';
    }

    if (column.format === 'datetime' && value) {
        return new Date(value).toLocaleString('fr-FR', {
            dateStyle: 'short',
            timeStyle: 'short',
        });
    }

    if (column.format === 'date' && value) {
        return new Date(value).toLocaleDateString('fr-FR');
    }

    return value ?? '-';
}

function inputValue(value, field, item = {}) {
    const fallback = field.default ?? (field.nullable ? null : '');

    if (field.type === 'multiselect') {
        const source = field.relationKey && Array.isArray(item[field.relationKey])
            ? item[field.relationKey]
            : value;

        return Array.isArray(source)
            ? source.map((entry) => typeof entry === 'object' ? entry.id : entry).filter(Boolean)
            : [];
    }

    if (value === undefined || value === null) {
        return fallback;
    }

    if (field.type === 'datetime-local') {
        return String(value).slice(0, 16);
    }

    return value;
}

function payloadValue(value, field) {
    if (value === '' && field.nullable) {
        return null;
    }

    if (field.type === 'datetime-local' && value) {
        return String(value).replace('T', ' ');
    }

    if (field.type === 'multiselect') {
        return Array.isArray(value) ? value : [];
    }

    return value;
}

const weekdays = {
    1: 'Lundi',
    2: 'Mardi',
    3: 'Mercredi',
    4: 'Jeudi',
    5: 'Vendredi',
    6: 'Samedi',
    7: 'Dimanche',
};
</script>
