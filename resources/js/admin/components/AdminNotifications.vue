<template>
    <section class="crud-panel">
        <header class="crud-header">
            <div>
                <h2>Notifications</h2>
                <p>Consulter les alertes administrateur et suivre leur lecture.</p>
            </div>
            <button type="button" @click="loadNotifications(1)" :disabled="loading">Actualiser</button>
        </header>

        <div class="crud-toolbar">
            <select v-model="mode" @change="loadNotifications(1)">
                <option value="all">Toutes</option>
                <option value="unread">Non lues</option>
            </select>
            <button type="button" @click="loadNotifications(1)">Filtrer</button>
        </div>

        <div class="crud-table-wrap">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Contenu</th>
                        <th>Reception</th>
                        <th>Lecture</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="5">Chargement...</td>
                    </tr>
                    <tr v-else-if="items.length === 0">
                        <td colspan="5">Aucune notification.</td>
                    </tr>
                    <tr v-for="item in items" v-else :key="item.id">
                        <td><span class="status-badge">{{ shortType(item.type) }}</span></td>
                        <td>{{ summarize(item.data) }}</td>
                        <td>{{ formatDate(item.created_at) }}</td>
                        <td>{{ item.read_at ? formatDate(item.read_at) : 'Non lue' }}</td>
                        <td>
                            <div class="row-actions">
                                <button v-if="!item.read_at" type="button" @click="markRead(item)">Marquer lu</button>
                                <button type="button" class="danger" @click="deleteNotification(item)">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer class="crud-footer">
            <span>Page {{ meta.current_page || 1 }} / {{ meta.last_page || 1 }}</span>
            <div>
                <button type="button" :disabled="!links.prev" @click="loadNotifications(meta.current_page - 1)">Precedent</button>
                <button type="button" :disabled="!links.next" @click="loadNotifications(meta.current_page + 1)">Suivant</button>
            </div>
        </footer>

        <p v-if="error" class="form-error">{{ error }}</p>
    </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    api: {
        type: Function,
        required: true,
    },
});

const items = ref([]);
const meta = ref({});
const links = ref({});
const mode = ref('all');
const loading = ref(false);
const error = ref('');

onMounted(() => loadNotifications());

async function loadNotifications(page = 1) {
    loading.value = true;
    error.value = '';

    try {
        const endpoint = mode.value === 'unread' ? '/admin/notifications/unread' : '/admin/notifications';
        const params = new URLSearchParams({ page, per_page: 10 });
        const response = await props.api(`${endpoint}?${params.toString()}`);

        items.value = response.data ?? [];
        meta.value = response.meta ?? {};
        links.value = response.links ?? {};
    } catch (err) {
        error.value = err.message || 'Chargement impossible.';
    } finally {
        loading.value = false;
    }
}

async function markRead(item) {
    try {
        await props.api(`/admin/notifications/${item.id}/read`, { method: 'PATCH' });
        await loadNotifications(meta.value.current_page || 1);
    } catch (err) {
        error.value = err.message || 'Action impossible.';
    }
}

async function deleteNotification(item) {
    if (!confirm('Supprimer cette notification ?')) {
        return;
    }

    try {
        await props.api(`/admin/notifications/${item.id}`, { method: 'DELETE' });
        await loadNotifications(meta.value.current_page || 1);
    } catch (err) {
        error.value = err.message || 'Suppression impossible.';
    }
}

function shortType(type) {
    return String(type || '').split('\\').pop() || '-';
}

function summarize(data) {
    if (!data) {
        return '-';
    }

    if (typeof data === 'string') {
        return data;
    }

    return data.message || data.title || data.subject || JSON.stringify(data);
}

function formatDate(value) {
    return value
        ? new Date(value).toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'short' })
        : '-';
}
</script>
