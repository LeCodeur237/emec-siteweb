<template>
    <section class="crud-panel">
        <header class="crud-header">
            <div>
                <h2>Medias</h2>
                <p>Importer, filtrer et maintenir les images et documents du site.</p>
            </div>
            <button type="button" @click="showUpload = true">Ajouter</button>
        </header>

        <div v-if="showUpload" class="modal-backdrop" @click.self="closeUpload">
            <form class="crud-form modal-panel" @submit.prevent="uploadMedia">
                <header>
                    <div class="modal-title">
                        <span>Bibliotheque media</span>
                        <h3>Nouveau media</h3>
                    </div>
                    <button class="modal-close" type="button" aria-label="Fermer" @click="closeUpload">
                        <i class="ti ti-x" aria-hidden="true"></i>
                    </button>
                </header>

                <div class="form-grid">
                    <label>
                        <span class="form-label-text">Fichier</span>
                        <input type="file" required @change="onFileChange">
                    </label>
                    <label>
                        <span class="form-label-text">Titre</span>
                        <input v-model="upload.title" type="text">
                    </label>
                    <label>
                        <span class="form-label-text">Texte alternatif</span>
                        <input v-model="upload.alt_text" type="text">
                    </label>
                    <label>
                        <span class="form-label-text">Type rattachement</span>
                        <select v-model="upload.mediaable_type">
                            <option value="">Aucun</option>
                            <option value="message">Message</option>
                            <option value="preacher">Predicateur</option>
                            <option value="church">Eglise</option>
                            <option value="social_project">Projet DOSC</option>
                            <option value="social_action">Action DOSC</option>
                            <option value="user">Utilisateur</option>
                        </select>
                    </label>
                    <label>
                        <span class="form-label-text">ID rattachement</span>
                        <input v-model="upload.mediaable_id" type="number" min="1">
                    </label>
                    <label class="wide">
                        <span class="form-label-text">Description</span>
                        <textarea v-model="upload.description" rows="3" />
                    </label>
                </div>

                <p v-if="error" class="form-error">{{ error }}</p>
                <div class="form-actions">
                    <button type="button" @click="closeUpload">
                        <i class="ti ti-x" aria-hidden="true"></i>
                        Annuler
                    </button>
                    <button type="submit" :disabled="saving">
                        <i :class="saving ? 'ti ti-loader-2' : 'ti ti-upload'" aria-hidden="true"></i>
                        {{ saving ? 'Upload...' : 'Uploader' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="crud-toolbar">
            <input v-model="filters.search" type="search" placeholder="Rechercher un media" @keyup.enter="loadMedia(1)">
            <select v-model="filters.file_type" @change="loadMedia(1)">
                <option value="">Tous les types</option>
                <option value="image">Images</option>
                <option value="document">Documents</option>
            </select>
            <select v-model="filters.orphaned" @change="loadMedia(1)">
                <option value="">Tous</option>
                <option value="1">Non rattaches</option>
            </select>
            <button type="button" @click="loadMedia(1)">Filtrer</button>
        </div>

        <div class="crud-table-wrap">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Fichier</th>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Taille</th>
                        <th>Upload</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="6">Chargement...</td>
                    </tr>
                    <tr v-else-if="items.length === 0">
                        <td colspan="6">Aucun media.</td>
                    </tr>
                    <tr v-for="item in items" v-else :key="item.id">
                        <td>
                            <a :href="item.url" target="_blank" rel="noreferrer">{{ item.file_name }}</a>
                        </td>
                        <td><span class="status-badge">{{ item.file_type }}</span></td>
                        <td>{{ item.title || item.alt_text || '-' }}</td>
                        <td>{{ formatSize(item.size) }}</td>
                        <td>{{ item.created_at ? new Date(item.created_at).toLocaleDateString('fr-FR') : '-' }}</td>
                        <td>
                            <div class="row-actions">
                                <button type="button" @click="startEdit(item)">Editer</button>
                                <button type="button" class="danger" @click="deleteMedia(item)">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer class="crud-footer">
            <span>Page {{ meta.current_page || 1 }} / {{ meta.last_page || 1 }}</span>
            <div>
                <button type="button" :disabled="!links.prev" @click="loadMedia(meta.current_page - 1)">Precedent</button>
                <button type="button" :disabled="!links.next" @click="loadMedia(meta.current_page + 1)">Suivant</button>
            </div>
        </footer>

        <div v-if="editing" class="modal-backdrop" @click.self="closeEdit">
            <form class="crud-form modal-panel" @submit.prevent="updateMedia">
                <header>
                    <div class="modal-title">
                        <span>Bibliotheque media</span>
                        <h3>Modifier media</h3>
                    </div>
                    <button class="modal-close" type="button" aria-label="Fermer" @click="closeEdit">
                        <i class="ti ti-x" aria-hidden="true"></i>
                    </button>
                </header>

                <div class="form-grid">
                    <label>
                        <span class="form-label-text">Titre</span>
                        <input v-model="editForm.title" type="text">
                    </label>
                    <label>
                        <span class="form-label-text">Texte alternatif</span>
                        <input v-model="editForm.alt_text" type="text">
                    </label>
                    <label class="wide">
                        <span class="form-label-text">Description</span>
                        <textarea v-model="editForm.description" rows="3" />
                    </label>
                </div>

                <p v-if="error" class="form-error">{{ error }}</p>
                <div class="form-actions">
                    <button type="button" @click="closeEdit">
                        <i class="ti ti-x" aria-hidden="true"></i>
                        Annuler
                    </button>
                    <button type="submit" :disabled="saving">
                        <i :class="saving ? 'ti ti-loader-2' : 'ti ti-device-floppy'" aria-hidden="true"></i>
                        {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';

const props = defineProps({
    api: {
        type: Function,
        required: true,
    },
});

const items = ref([]);
const meta = ref({});
const links = ref({});
const loading = ref(false);
const saving = ref(false);
const showUpload = ref(false);
const editing = ref(null);
const selectedFile = ref(null);
const error = ref('');

const filters = reactive({
    search: '',
    file_type: '',
    orphaned: '',
});

const upload = reactive({
    title: '',
    alt_text: '',
    description: '',
    mediaable_type: '',
    mediaable_id: '',
});

const editForm = reactive({
    title: '',
    alt_text: '',
    description: '',
});

onMounted(() => loadMedia());

async function loadMedia(page = 1) {
    loading.value = true;
    error.value = '';

    try {
        const params = new URLSearchParams({
            page,
            per_page: 10,
            sort: 'created_at',
            direction: 'desc',
        });

        if (filters.search) {
            params.set('search', filters.search);
        }

        if (filters.file_type) {
            params.set('file_type', filters.file_type);
        }

        if (filters.orphaned) {
            params.set('orphaned', filters.orphaned);
        }

        const response = await props.api(`/admin/media?${params.toString()}`);
        items.value = response.data ?? [];
        meta.value = response.meta ?? {};
        links.value = response.links ?? {};
    } catch (err) {
        error.value = err.message || 'Chargement impossible.';
    } finally {
        loading.value = false;
    }
}

function onFileChange(event) {
    selectedFile.value = event.target.files?.[0] ?? null;
}

async function uploadMedia() {
    if (!selectedFile.value) {
        error.value = 'Selectionne un fichier.';
        return;
    }

    saving.value = true;
    error.value = '';

    try {
        const formData = new FormData();
        formData.append('file', selectedFile.value);

        for (const key of ['title', 'alt_text', 'description', 'mediaable_type', 'mediaable_id']) {
            if (upload[key] !== '') {
                formData.append(key, upload[key]);
            }
        }

        await props.api('/admin/media', {
            method: 'POST',
            body: formData,
        });

        resetUpload();
        showUpload.value = false;
        await loadMedia(1);
    } catch (err) {
        error.value = err.message || 'Upload impossible.';
    } finally {
        saving.value = false;
    }
}

function startEdit(item) {
    editing.value = item;
    editForm.title = item.title ?? '';
    editForm.alt_text = item.alt_text ?? '';
    editForm.description = item.description ?? '';
}

function closeEdit() {
    editing.value = null;
    error.value = '';
}

function closeUpload() {
    resetUpload();
    showUpload.value = false;
}

async function updateMedia() {
    saving.value = true;
    error.value = '';

    try {
        await props.api(`/admin/media/${editing.value.id}`, {
            method: 'PATCH',
            body: JSON.stringify({
                title: editForm.title || null,
                alt_text: editForm.alt_text || null,
                description: editForm.description || null,
            }),
        });

        closeEdit();
        await loadMedia(meta.value.current_page || 1);
    } catch (err) {
        error.value = err.message || 'Enregistrement impossible.';
    } finally {
        saving.value = false;
    }
}

async function deleteMedia(item) {
    if (!confirm(`Supprimer "${item.file_name}" ?`)) {
        return;
    }

    try {
        await props.api(`/admin/media/${item.id}`, { method: 'DELETE' });
        await loadMedia(meta.value.current_page || 1);
    } catch (err) {
        error.value = err.message || 'Suppression impossible.';
    }
}

function resetUpload() {
    selectedFile.value = null;
    upload.title = '';
    upload.alt_text = '';
    upload.description = '';
    upload.mediaable_type = '';
    upload.mediaable_id = '';
    error.value = '';
}

function formatSize(size) {
    if (!size) {
        return '-';
    }

    if (size < 1024 * 1024) {
        return `${Math.round(size / 1024)} Ko`;
    }

    return `${(size / 1024 / 1024).toFixed(1)} Mo`;
}
</script>
