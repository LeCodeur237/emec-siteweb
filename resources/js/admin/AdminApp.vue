<template>
    <main class="admin-shell">
        <section v-if="!token" class="auth-view">
            <div class="brand-panel">
                <span class="brand-mark">EMEC</span>
                <h1>Administration</h1>
                <p>Gestion du contenu, des dons, de DOSC, des communications et des acces.</p>
            </div>

            <form class="login-panel" @submit.prevent="login">
                <h2>Connexion</h2>
                <label>
                    Email
                    <input v-model="credentials.email" type="email" autocomplete="email" required>
                </label>
                <label>
                    Mot de passe
                    <input v-model="credentials.password" type="password" autocomplete="current-password" required>
                </label>
                <p v-if="authError" class="form-error">{{ authError }}</p>
                <button type="submit" :disabled="loading">
                    {{ loading ? 'Connexion...' : 'Se connecter' }}
                </button>
            </form>
        </section>

        <section v-else class="workspace">
            <aside class="sidebar">
                <div class="sidebar-brand">
                    <span class="brand-mark">EMEC</span>
                    <div>
                        <strong>Admin</strong>
                        <small>{{ user?.name || 'Utilisateur' }}</small>
                    </div>
                </div>

                <nav>
                    <button
                        v-for="item in navigation"
                        :key="item.key"
                        type="button"
                        :class="{ active: activeSection === item.key }"
                        @click="activeSection = item.key"
                    >
                        <span>{{ item.icon }}</span>
                        {{ item.label }}
                    </button>
                </nav>

                <button class="logout-button" type="button" @click="logout">Deconnexion</button>
            </aside>

            <div class="content">
                <header class="topbar">
                    <div>
                        <p>{{ activeNav?.eyebrow }}</p>
                        <h1>{{ activeNav?.label }}</h1>
                    </div>
                    <button type="button" @click="refreshDashboard" :disabled="loading">
                        Actualiser
                    </button>
                </header>

                <section v-if="activeSection === 'dashboard'" class="dashboard-grid">
                    <article v-for="metric in metrics" :key="metric.key" class="metric-tile">
                        <span>{{ metric.label }}</span>
                        <strong>{{ metric.value }}</strong>
                    </article>
                </section>

                <section v-else-if="activeSection === 'messages'" class="module-stack">
                    <div class="module-tabs">
                        <button
                            v-for="resource in messageResources"
                            :key="resource.key"
                            type="button"
                            :class="{ active: activeMessageResource === resource.key }"
                            @click="activeMessageResource = resource.key"
                        >
                            {{ resource.title }}
                        </button>
                    </div>

                    <AdminResourceCrud
                        :api="api"
                        :resource="currentMessageResource"
                    />
                </section>

                <section v-else-if="activeSection === 'emec'" class="module-stack">
                    <div class="module-tabs">
                        <button
                            v-for="resource in emecResources"
                            :key="resource.key"
                            type="button"
                            :class="{ active: activeEmecResource === resource.key }"
                            @click="activeEmecResource = resource.key"
                        >
                            {{ resource.title }}
                        </button>
                    </div>

                    <AdminResourceCrud
                        :api="api"
                        :resource="currentEmecResource"
                    />
                </section>

                <section v-else-if="activeSection === 'dosc'" class="module-stack">
                    <div class="module-tabs">
                        <button
                            v-for="resource in doscResources"
                            :key="resource.key"
                            type="button"
                            :class="{ active: activeDoscResource === resource.key }"
                            @click="activeDoscResource = resource.key"
                        >
                            {{ resource.title }}
                        </button>
                    </div>

                    <AdminResourceCrud
                        :api="api"
                        :resource="currentDoscResource"
                    />
                </section>

                <section v-else-if="activeSection === 'donations'" class="module-stack">
                    <div class="module-tabs">
                        <button
                            v-for="resource in donationResources"
                            :key="resource.key"
                            type="button"
                            :class="{ active: activeDonationResource === resource.key }"
                            @click="activeDonationResource = resource.key"
                        >
                            {{ resource.title }}
                        </button>
                    </div>

                    <AdminResourceCrud
                        :api="api"
                        :resource="currentDonationResource"
                    />
                </section>

                <section v-else-if="activeSection === 'communication'" class="module-stack">
                    <div class="module-tabs">
                        <button
                            v-for="resource in communicationResources"
                            :key="resource.key"
                            type="button"
                            :class="{ active: activeCommunicationResource === resource.key }"
                            @click="activeCommunicationResource = resource.key"
                        >
                            {{ resource.title }}
                        </button>
                    </div>

                    <AdminResourceCrud
                        :api="api"
                        :resource="currentCommunicationResource"
                    />
                </section>

                <section v-else-if="activeSection === 'settings'" class="module-stack">
                    <AdminResourceCrud
                        :api="api"
                        :resource="siteSettingsResource"
                    />
                </section>

                <section v-else-if="activeSection === 'rbac'" class="module-stack">
                    <div class="module-tabs">
                        <button
                            v-for="resource in rbacResources"
                            :key="resource.key"
                            type="button"
                            :class="{ active: activeRbacResource === resource.key }"
                            @click="activeRbacResource = resource.key"
                        >
                            {{ resource.title }}
                        </button>
                    </div>

                    <AdminResourceCrud
                        :api="api"
                        :resource="currentRbacResource"
                    />
                </section>

                <section v-else-if="activeSection === 'media'" class="module-stack">
                    <AdminMediaManager :api="api" />
                </section>

                <section v-else-if="activeSection === 'notifications'" class="module-stack">
                    <AdminNotifications :api="api" />
                </section>

                <section v-else class="module-panel">
                    <div>
                        <h2>{{ activeNav?.label }}</h2>
                        <p>{{ activeNav?.description }}</p>
                    </div>
                    <div class="module-actions">
                        <button type="button" disabled>Nouveau</button>
                        <button type="button" disabled>Filtres</button>
                    </div>
                </section>

                <p v-if="apiError" class="api-error">{{ apiError }}</p>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import AdminResourceCrud from './components/AdminResourceCrud.vue';
import AdminMediaManager from './components/AdminMediaManager.vue';
import AdminNotifications from './components/AdminNotifications.vue';

const storageKey = 'emec_admin_token';
const userKey = 'emec_admin_user';

const token = ref(localStorage.getItem(storageKey) || '');
const user = ref(readStoredUser());
const loading = ref(false);
const authError = ref('');
const apiError = ref('');
const activeSection = ref('dashboard');
const activeMessageResource = ref('messages');
const activeEmecResource = ref('churches');
const activeDoscResource = ref('social-projects');
const activeDonationResource = ref('donation-campaigns');
const activeCommunicationResource = ref('contact-messages');
const activeRbacResource = ref('users');
const dashboard = ref({});

const credentials = reactive({
    email: '',
    password: '',
});

const navigation = [
    { key: 'dashboard', label: 'Dashboard', eyebrow: 'Vue globale', icon: '01', description: 'Indicateurs principaux du back-office EMEC.' },
    { key: 'messages', label: 'Messages', eyebrow: 'Contenu', icon: '02', description: 'Messages, predicateurs, categories et series.' },
    { key: 'emec', label: 'EMEC', eyebrow: 'Vie de l Eglise', icon: '03', description: 'Eglises, responsables, groupes, evenements et programmes.' },
    { key: 'dosc', label: 'DOSC', eyebrow: 'Action sociale', icon: '04', description: 'Projets sociaux, actions, temoignages et statistiques.' },
    { key: 'donations', label: 'Dons', eyebrow: 'Finance', icon: '05', description: 'Campagnes, methodes de don et donations enregistrees.' },
    { key: 'communication', label: 'Communication', eyebrow: 'Relations', icon: '06', description: 'Messages de contact et abonnes newsletter.' },
    { key: 'settings', label: 'Configuration', eyebrow: 'Parametres', icon: '07', description: 'Parametres fonctionnels du site et de l API.' },
    { key: 'rbac', label: 'Utilisateurs', eyebrow: 'Acces', icon: '08', description: 'Utilisateurs, roles et permissions.' },
    { key: 'media', label: 'Medias', eyebrow: 'Bibliotheque', icon: '09', description: 'Images, documents et metadonnees.' },
    { key: 'notifications', label: 'Notifications', eyebrow: 'Alertes', icon: '10', description: 'Notifications administrateur et etat de lecture.' },
];

const activeNav = computed(() => navigation.find((item) => item.key === activeSection.value));

const metrics = computed(() => [
    { key: 'messages_count', label: 'Messages', value: dashboard.value.messages_count ?? '-' },
    { key: 'events_count', label: 'Evenements', value: dashboard.value.events_count ?? '-' },
    { key: 'churches_count', label: 'Eglises', value: dashboard.value.churches_count ?? '-' },
    { key: 'groups_count', label: 'Groupes', value: dashboard.value.groups_count ?? '-' },
    { key: 'social_projects_count', label: 'Projets DOSC', value: dashboard.value.social_projects_count ?? '-' },
    { key: 'social_actions_count', label: 'Actions DOSC', value: dashboard.value.social_actions_count ?? '-' },
    { key: 'users_count', label: 'Utilisateurs', value: dashboard.value.users_count ?? '-' },
]);

const messageResources = [
    {
        key: 'messages',
        title: 'Messages',
        singular: 'message',
        description: 'Publier et organiser les messages audio, video et documents.',
        endpoint: '/admin/messages',
        filters: ['status'],
        defaultSort: 'created_at',
        columns: [
            { key: 'title', label: 'Titre' },
            { key: 'preacher.name', label: 'Predicateur' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'featured', label: 'Mis en avant', format: 'boolean' },
            { key: 'preached_at', label: 'Date', format: 'date' },
        ],
        fields: [
            { key: 'title', label: 'Titre' },
            { key: 'preacher_id', label: 'Predicateur', type: 'select', nullable: true, optionsEndpoint: '/admin/preachers' },
            { key: 'message_category_id', label: 'Categorie', type: 'select', nullable: true, optionsEndpoint: '/admin/message-categories' },
            { key: 'message_series_id', label: 'Serie', type: 'select', nullable: true, optionsEndpoint: '/admin/message-series' },
            { key: 'excerpt', label: 'Resume', type: 'textarea', nullable: true },
            { key: 'content', label: 'Contenu', type: 'textarea', nullable: true },
            { key: 'preached_at', label: 'Date de predication', type: 'date', nullable: true },
            { key: 'duration', label: 'Duree', nullable: true },
            { key: 'youtube_video_id', label: 'ID YouTube', nullable: true },
            { key: 'youtube_url', label: 'URL YouTube', type: 'url', nullable: true },
            { key: 'audio_url', label: 'URL audio', type: 'url', nullable: true },
            { key: 'pdf_url', label: 'URL PDF', type: 'url', nullable: true },
            { key: 'thumbnail', label: 'Miniature', type: 'url', nullable: true },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'draft', label: 'Brouillon' },
                    { value: 'published', label: 'Publie' },
                    { value: 'archived', label: 'Archive' },
                ],
                default: 'draft',
            },
            { key: 'featured', label: 'Mis en avant', type: 'checkbox', default: false },
        ],
    },
    {
        key: 'preachers',
        title: 'Predicateurs',
        singular: 'predicateur',
        description: 'Gerer les predicateurs associes aux messages.',
        endpoint: '/admin/preachers',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'role', label: 'Role' },
            { key: 'active', label: 'Actif', format: 'boolean' },
            { key: 'messages_count', label: 'Messages' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'role', label: 'Role', nullable: true },
            { key: 'bio', label: 'Biographie', type: 'textarea', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'active', label: 'Actif', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'categories',
        title: 'Categories',
        singular: 'categorie',
        description: 'Classer les messages par categories editoriales.',
        endpoint: '/admin/message-categories',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'slug', label: 'Slug' },
            { key: 'active', label: 'Active', format: 'boolean' },
            { key: 'messages_count', label: 'Messages' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'series',
        title: 'Series',
        singular: 'serie',
        description: 'Regrouper les messages en series thematiques.',
        endpoint: '/admin/message-series',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'slug', label: 'Slug' },
            { key: 'active', label: 'Active', format: 'boolean' },
            { key: 'messages_count', label: 'Messages' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'cover_image', label: 'Image de couverture', nullable: true },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
];

const currentMessageResource = computed(() => messageResources.find((resource) => resource.key === activeMessageResource.value) ?? messageResources[0]);

const emecResources = [
    {
        key: 'churches',
        title: 'Eglises',
        singular: 'eglise',
        description: 'Gerer les eglises locales, leurs coordonnees et leur statut de publication.',
        endpoint: '/admin/churches',
        filters: ['status', 'active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'city', label: 'Ville' },
            { key: 'region', label: 'Region' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'active', label: 'Active', format: 'boolean' },
            { key: 'leaders_count', label: 'Responsables' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'baptism_name', label: 'Nom de bapteme', nullable: true },
            { key: 'city', label: 'Ville', nullable: true },
            { key: 'address', label: 'Adresse', nullable: true },
            { key: 'neighborhood', label: 'Quartier', nullable: true },
            { key: 'locality', label: 'Localite', nullable: true },
            { key: 'sector', label: 'Secteur', nullable: true },
            { key: 'district', label: 'District', nullable: true },
            { key: 'circumscription', label: 'Circonscription', nullable: true },
            { key: 'mission_field', label: 'Champ missionnaire', nullable: true },
            { key: 'region', label: 'Region', nullable: true },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'pastor_vision', label: 'Vision pastorale', type: 'textarea', nullable: true },
            { key: 'contact', label: 'Contact', nullable: true },
            { key: 'map_url', label: 'URL carte', type: 'url', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'draft', label: 'Brouillon' },
                    { value: 'published', label: 'Publie' },
                    { value: 'archived', label: 'Archive' },
                ],
                default: 'draft',
            },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'church-leaders',
        title: 'Responsables eglises',
        singular: 'responsable d eglise',
        description: 'Associer les responsables aux eglises locales.',
        endpoint: '/admin/church-leaders',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'church.name', label: 'Eglise' },
            { key: 'responsibility', label: 'Responsabilite' },
            { key: 'start_date', label: 'Debut', format: 'date' },
            { key: 'active', label: 'Actif', format: 'boolean' },
        ],
        fields: [
            { key: 'church_id', label: 'Eglise', type: 'select', optionsEndpoint: '/admin/churches' },
            { key: 'name', label: 'Nom' },
            { key: 'responsibility', label: 'Responsabilite' },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'start_date', label: 'Date de debut', type: 'date', nullable: true },
            { key: 'end_date', label: 'Date de fin', type: 'date', nullable: true },
            { key: 'active', label: 'Actif', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'administrative-leaders',
        title: 'Responsables administratifs',
        singular: 'responsable administratif',
        description: 'Administrer les responsables nationaux ou administratifs EMEC.',
        endpoint: '/admin/administrative-leaders',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'responsibility', label: 'Responsabilite' },
            { key: 'start_date', label: 'Debut', format: 'date' },
            { key: 'active', label: 'Actif', format: 'boolean' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'responsibility', label: 'Responsabilite' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'start_date', label: 'Date de debut', type: 'date', nullable: true },
            { key: 'end_date', label: 'Date de fin', type: 'date', nullable: true },
            { key: 'active', label: 'Actif', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'groups',
        title: 'Groupes',
        singular: 'groupe',
        description: 'Gerer les groupes et departements internes de l EMEC.',
        endpoint: '/admin/groups',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'email', label: 'Email' },
            { key: 'active', label: 'Actif', format: 'boolean' },
            { key: 'leaders_count', label: 'Responsables' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'short_description', label: 'Resume', type: 'textarea', nullable: true },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'color', label: 'Couleur', nullable: true },
            { key: 'contact', label: 'Contact', nullable: true },
            { key: 'email', label: 'Email', type: 'email', nullable: true },
            { key: 'active', label: 'Actif', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'group-leaders',
        title: 'Responsables groupes',
        singular: 'responsable de groupe',
        description: 'Associer les responsables aux groupes EMEC.',
        endpoint: '/admin/group-leaders',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'group.name', label: 'Groupe' },
            { key: 'responsibility', label: 'Responsabilite' },
            { key: 'active', label: 'Actif', format: 'boolean' },
        ],
        fields: [
            { key: 'group_id', label: 'Groupe', type: 'select', optionsEndpoint: '/admin/groups' },
            { key: 'name', label: 'Nom' },
            { key: 'responsibility', label: 'Responsabilite' },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'active', label: 'Actif', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'event-categories',
        title: 'Categories evenements',
        singular: 'categorie d evenement',
        description: 'Classer les evenements par categories.',
        endpoint: '/admin/event-categories',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'slug', label: 'Slug' },
            { key: 'active', label: 'Active', format: 'boolean' },
            { key: 'events_count', label: 'Evenements' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'events',
        title: 'Evenements',
        singular: 'evenement',
        description: 'Publier les evenements EMEC et les rattacher a une categorie.',
        endpoint: '/admin/events',
        filters: ['status'],
        defaultSort: 'start_at',
        defaultDirection: 'desc',
        columns: [
            { key: 'title', label: 'Titre' },
            { key: 'category.name', label: 'Categorie' },
            { key: 'city', label: 'Ville' },
            { key: 'start_at', label: 'Debut', format: 'datetime' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'featured', label: 'Mis en avant', format: 'boolean' },
        ],
        fields: [
            { key: 'event_category_id', label: 'Categorie', type: 'select', nullable: true, optionsEndpoint: '/admin/event-categories' },
            { key: 'title', label: 'Titre' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'start_at', label: 'Date de debut', type: 'datetime-local' },
            { key: 'end_at', label: 'Date de fin', type: 'datetime-local', nullable: true },
            { key: 'location', label: 'Lieu', nullable: true },
            { key: 'city', label: 'Ville', nullable: true },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'draft', label: 'Brouillon' },
                    { value: 'published', label: 'Publie' },
                    { value: 'cancelled', label: 'Annule' },
                    { value: 'completed', label: 'Termine' },
                ],
                default: 'draft',
            },
            { key: 'featured', label: 'Mis en avant', type: 'checkbox', default: false },
        ],
    },
    {
        key: 'weekly-programs',
        title: 'Programmes hebdomadaires',
        singular: 'programme hebdomadaire',
        description: 'Planifier les rendez-vous recurrentes de la semaine.',
        endpoint: '/admin/weekly-programs',
        filters: ['active'],
        defaultSort: 'day_of_week',
        defaultDirection: 'asc',
        columns: [
            { key: 'title', label: 'Titre' },
            { key: 'day_of_week', label: 'Jour', format: 'weekday' },
            { key: 'start_time', label: 'Debut' },
            { key: 'location', label: 'Lieu' },
            { key: 'active', label: 'Actif', format: 'boolean' },
        ],
        fields: [
            { key: 'title', label: 'Titre' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            {
                key: 'day_of_week',
                label: 'Jour',
                type: 'select',
                options: [
                    { value: 1, label: 'Lundi' },
                    { value: 2, label: 'Mardi' },
                    { value: 3, label: 'Mercredi' },
                    { value: 4, label: 'Jeudi' },
                    { value: 5, label: 'Vendredi' },
                    { value: 6, label: 'Samedi' },
                    { value: 7, label: 'Dimanche' },
                ],
            },
            { key: 'start_time', label: 'Heure de debut', type: 'time' },
            { key: 'end_time', label: 'Heure de fin', type: 'time', nullable: true },
            { key: 'location', label: 'Lieu', nullable: true },
            { key: 'active', label: 'Actif', type: 'checkbox', default: true },
        ],
    },
];

const currentEmecResource = computed(() => emecResources.find((resource) => resource.key === activeEmecResource.value) ?? emecResources[0]);

const doscResources = [
    {
        key: 'social-projects',
        title: 'Projets sociaux',
        singular: 'projet social',
        description: 'Gerer les projets DOSC, leurs objectifs et leur avancement.',
        endpoint: '/admin/dosc/projects',
        filters: ['status'],
        defaultSort: 'created_at',
        defaultDirection: 'desc',
        statusOptions: [
            { value: 'draft', label: 'Brouillon' },
            { value: 'active', label: 'Actif' },
            { value: 'archived', label: 'Archive' },
        ],
        columns: [
            { key: 'title', label: 'Titre' },
            { key: 'goal_amount', label: 'Objectif' },
            { key: 'raised_amount', label: 'Collecte' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'featured', label: 'Mis en avant', format: 'boolean' },
            { key: 'actions_count', label: 'Actions' },
        ],
        fields: [
            { key: 'title', label: 'Titre' },
            { key: 'short_description', label: 'Resume', type: 'textarea', nullable: true },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'goal_amount', label: 'Montant objectif', type: 'number', nullable: true },
            { key: 'raised_amount', label: 'Montant collecte', type: 'number', default: 0 },
            { key: 'beneficiaries_count', label: 'Beneficiaires', type: 'number', default: 0 },
            { key: 'deadline', label: 'Date limite', type: 'date', nullable: true },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'draft', label: 'Brouillon' },
                    { value: 'active', label: 'Actif' },
                    { value: 'archived', label: 'Archive' },
                ],
                default: 'draft',
            },
            { key: 'featured', label: 'Mis en avant', type: 'checkbox', default: false },
        ],
    },
    {
        key: 'social-actions',
        title: 'Actions sociales',
        singular: 'action sociale',
        description: 'Publier les actions DOSC rattachees ou non a un projet social.',
        endpoint: '/admin/dosc/actions',
        filters: ['status'],
        defaultSort: 'action_date',
        defaultDirection: 'desc',
        columns: [
            { key: 'title', label: 'Titre' },
            { key: 'project.title', label: 'Projet' },
            { key: 'category', label: 'Categorie' },
            { key: 'action_date', label: 'Date', format: 'date' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'beneficiaries_count', label: 'Beneficiaires' },
        ],
        fields: [
            { key: 'social_project_id', label: 'Projet social', type: 'select', nullable: true, optionsEndpoint: '/admin/dosc/projects', optionLabel: 'title', optionSort: 'title' },
            { key: 'title', label: 'Titre' },
            { key: 'category', label: 'Categorie', nullable: true },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'location', label: 'Lieu', nullable: true },
            { key: 'action_date', label: 'Date de l action', type: 'date', nullable: true },
            { key: 'image', label: 'Image', nullable: true },
            { key: 'youtube_video_id', label: 'ID YouTube', nullable: true },
            { key: 'beneficiaries_count', label: 'Beneficiaires', type: 'number', default: 0 },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'draft', label: 'Brouillon' },
                    { value: 'published', label: 'Publie' },
                    { value: 'archived', label: 'Archive' },
                ],
                default: 'draft',
            },
        ],
    },
    {
        key: 'social-action-stats',
        title: 'Stats actions',
        singular: 'statistique d action',
        description: 'Associer des indicateurs chiffres aux actions sociales DOSC.',
        endpoint: '/admin/dosc/action-stats',
        defaultSort: 'created_at',
        defaultDirection: 'desc',
        columns: [
            { key: 'label', label: 'Libelle' },
            { key: 'value', label: 'Valeur' },
            { key: 'action.title', label: 'Action' },
        ],
        fields: [
            { key: 'social_action_id', label: 'Action sociale', type: 'select', optionsEndpoint: '/admin/dosc/actions', optionLabel: 'title', optionSort: 'title' },
            { key: 'label', label: 'Libelle' },
            { key: 'value', label: 'Valeur' },
        ],
    },
    {
        key: 'impact-stats',
        title: 'Impact global',
        singular: 'statistique d impact',
        description: 'Gerer les chiffres globaux affiches pour DOSC.',
        endpoint: '/admin/dosc/impact-stats',
        filters: ['active'],
        defaultSort: 'sort_order',
        defaultDirection: 'asc',
        columns: [
            { key: 'label', label: 'Libelle' },
            { key: 'value', label: 'Valeur' },
            { key: 'suffix', label: 'Suffixe' },
            { key: 'sort_order', label: 'Ordre' },
            { key: 'active', label: 'Active', format: 'boolean' },
        ],
        fields: [
            { key: 'label', label: 'Libelle' },
            { key: 'value', label: 'Valeur' },
            { key: 'suffix', label: 'Suffixe', nullable: true },
            { key: 'icon', label: 'Icone', nullable: true },
            { key: 'sort_order', label: 'Ordre', type: 'number', default: 0 },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'testimonials',
        title: 'Temoignages',
        singular: 'temoignage',
        description: 'Publier les temoignages lies aux actions DOSC.',
        endpoint: '/admin/dosc/testimonials',
        defaultSort: 'created_at',
        defaultDirection: 'desc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'location', label: 'Lieu' },
            { key: 'action.title', label: 'Action' },
            { key: 'published', label: 'Publie', format: 'boolean' },
        ],
        fields: [
            { key: 'social_action_id', label: 'Action sociale', type: 'select', nullable: true, optionsEndpoint: '/admin/dosc/actions', optionLabel: 'title', optionSort: 'title' },
            { key: 'name', label: 'Nom', nullable: true },
            { key: 'location', label: 'Lieu', nullable: true },
            { key: 'quote', label: 'Temoignage', type: 'textarea' },
            { key: 'avatar', label: 'Avatar', nullable: true },
            { key: 'published', label: 'Publie', type: 'checkbox', default: false },
        ],
    },
];

const currentDoscResource = computed(() => doscResources.find((resource) => resource.key === activeDoscResource.value) ?? doscResources[0]);

const donationResources = [
    {
        key: 'donation-campaigns',
        title: 'Campagnes',
        singular: 'campagne de don',
        description: 'Gerer les campagnes de dons et leur rattachement aux projets DOSC.',
        endpoint: '/admin/donation-campaigns',
        filters: ['active'],
        defaultSort: 'title',
        defaultDirection: 'asc',
        columns: [
            { key: 'title', label: 'Titre' },
            { key: 'project.title', label: 'Projet DOSC' },
            { key: 'goal_amount', label: 'Objectif' },
            { key: 'active', label: 'Active', format: 'boolean' },
            { key: 'donations_count', label: 'Dons' },
        ],
        fields: [
            { key: 'social_project_id', label: 'Projet DOSC', type: 'select', nullable: true, optionsEndpoint: '/admin/dosc/projects', optionLabel: 'title', optionSort: 'title' },
            { key: 'title', label: 'Titre' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'goal_amount', label: 'Montant objectif', type: 'number', default: 0 },
            { key: 'start_date', label: 'Date de debut', type: 'date', nullable: true },
            { key: 'end_date', label: 'Date de fin', type: 'date', nullable: true },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'donation-methods',
        title: 'Methodes',
        singular: 'methode de don',
        description: 'Configurer les moyens de paiement affiches pour les dons.',
        endpoint: '/admin/donation-methods',
        filters: ['active'],
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'type', label: 'Type' },
            { key: 'provider', label: 'Operateur' },
            { key: 'account_number', label: 'Compte' },
            { key: 'active', label: 'Active', format: 'boolean' },
            { key: 'donations_count', label: 'Dons' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            {
                key: 'type',
                label: 'Type',
                type: 'select',
                options: [
                    { value: 'mobile_money', label: 'Mobile Money' },
                    { value: 'bank_transfer', label: 'Virement bancaire' },
                    { value: 'cash', label: 'Especes' },
                    { value: 'other', label: 'Autre' },
                ],
            },
            { key: 'provider', label: 'Operateur', nullable: true },
            { key: 'account_name', label: 'Nom du compte', nullable: true },
            { key: 'account_number', label: 'Numero du compte', nullable: true },
            { key: 'instructions', label: 'Instructions', type: 'textarea', nullable: true },
            { key: 'active', label: 'Active', type: 'checkbox', default: true },
        ],
    },
    {
        key: 'donations',
        title: 'Dons',
        singular: 'don',
        description: 'Suivre et mettre a jour les dons enregistres.',
        endpoint: '/admin/donations',
        filters: ['status'],
        defaultSort: 'created_at',
        defaultDirection: 'desc',
        statusOptions: [
            { value: 'pending', label: 'En attente' },
            { value: 'paid', label: 'Paye' },
            { value: 'failed', label: 'Echoue' },
            { value: 'cancelled', label: 'Annule' },
            { value: 'refunded', label: 'Rembourse' },
        ],
        columns: [
            { key: 'donor_name', label: 'Donateur' },
            { key: 'campaign.title', label: 'Campagne' },
            { key: 'method.name', label: 'Methode' },
            { key: 'amount', label: 'Montant' },
            { key: 'currency', label: 'Devise' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'paid_at', label: 'Paiement', format: 'datetime' },
        ],
        fields: [
            { key: 'donation_campaign_id', label: 'Campagne', type: 'select', nullable: true, optionsEndpoint: '/admin/donation-campaigns', optionLabel: 'title', optionSort: 'title' },
            { key: 'donation_method_id', label: 'Methode', type: 'select', nullable: true, optionsEndpoint: '/admin/donation-methods' },
            { key: 'donor_name', label: 'Nom du donateur', nullable: true },
            { key: 'donor_email', label: 'Email du donateur', type: 'email', nullable: true },
            { key: 'donor_phone', label: 'Telephone du donateur', nullable: true },
            { key: 'amount', label: 'Montant', type: 'number', default: 0 },
            { key: 'currency', label: 'Devise', default: 'XAF' },
            { key: 'transaction_reference', label: 'Reference transaction', nullable: true },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'pending', label: 'En attente' },
                    { value: 'paid', label: 'Paye' },
                    { value: 'failed', label: 'Echoue' },
                    { value: 'cancelled', label: 'Annule' },
                    { value: 'refunded', label: 'Rembourse' },
                ],
                default: 'pending',
            },
            { key: 'anonymous', label: 'Anonyme', type: 'checkbox', default: false },
            { key: 'paid_at', label: 'Date de paiement', type: 'datetime-local', nullable: true },
        ],
    },
];

const currentDonationResource = computed(() => donationResources.find((resource) => resource.key === activeDonationResource.value) ?? donationResources[0]);

const communicationResources = [
    {
        key: 'contact-messages',
        title: 'Messages de contact',
        singular: 'message de contact',
        description: 'Traiter les demandes envoyees depuis le formulaire de contact public.',
        endpoint: '/admin/contact-messages',
        filters: ['status'],
        defaultSort: 'created_at',
        defaultDirection: 'desc',
        statusOptions: [
            { value: 'new', label: 'Nouveau' },
            { value: 'read', label: 'Lu' },
            { value: 'answered', label: 'Repondu' },
            { value: 'archived', label: 'Archive' },
        ],
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'email', label: 'Email' },
            { key: 'subject', label: 'Sujet' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'created_at', label: 'Reception', format: 'datetime' },
            { key: 'answered_at', label: 'Reponse', format: 'datetime' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'email', label: 'Email', type: 'email' },
            { key: 'phone', label: 'Telephone', nullable: true },
            { key: 'subject', label: 'Sujet', nullable: true },
            { key: 'message', label: 'Message', type: 'textarea' },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'new', label: 'Nouveau' },
                    { value: 'read', label: 'Lu' },
                    { value: 'answered', label: 'Repondu' },
                    { value: 'archived', label: 'Archive' },
                ],
                default: 'new',
            },
            { key: 'read_at', label: 'Date de lecture', type: 'datetime-local', nullable: true },
            { key: 'answered_at', label: 'Date de reponse', type: 'datetime-local', nullable: true },
        ],
    },
    {
        key: 'newsletter-subscribers',
        title: 'Abonnes newsletter',
        singular: 'abonne newsletter',
        description: 'Gerer les inscriptions et desinscriptions a la newsletter.',
        endpoint: '/admin/newsletter-subscribers',
        filters: ['status'],
        defaultSort: 'email',
        defaultDirection: 'asc',
        statusOptions: [
            { value: 'subscribed', label: 'Inscrit' },
            { value: 'unsubscribed', label: 'Desinscrit' },
        ],
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'email', label: 'Email' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'subscribed_at', label: 'Inscription', format: 'datetime' },
            { key: 'unsubscribed_at', label: 'Desinscription', format: 'datetime' },
        ],
        fields: [
            { key: 'name', label: 'Nom', nullable: true },
            { key: 'email', label: 'Email', type: 'email' },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'subscribed', label: 'Inscrit' },
                    { value: 'unsubscribed', label: 'Desinscrit' },
                ],
                default: 'subscribed',
            },
            { key: 'subscribed_at', label: 'Date inscription', type: 'datetime-local', nullable: true },
            { key: 'unsubscribed_at', label: 'Date desinscription', type: 'datetime-local', nullable: true },
        ],
    },
];

const currentCommunicationResource = computed(() => communicationResources.find((resource) => resource.key === activeCommunicationResource.value) ?? communicationResources[0]);

const siteSettingsResource = {
    key: 'site-settings',
    title: 'Parametres du site',
    singular: 'parametre',
    description: 'Gerer les valeurs de configuration utilisees par le site et l API.',
    endpoint: '/admin/site-settings',
    defaultSort: 'key',
    defaultDirection: 'asc',
    customFilters: [
        {
            key: 'type',
            label: 'Type',
            placeholder: 'Tous les types',
            type: 'select',
            options: [
                { value: 'string', label: 'Texte court' },
                { value: 'text', label: 'Texte long' },
                { value: 'integer', label: 'Entier' },
                { value: 'float', label: 'Decimal' },
                { value: 'boolean', label: 'Booleen' },
                { value: 'json', label: 'JSON' },
                { value: 'url', label: 'URL' },
                { value: 'email', label: 'Email' },
            ],
        },
        {
            key: 'group',
            label: 'Groupe',
            placeholder: 'Filtrer par groupe',
        },
    ],
    columns: [
        { key: 'key', label: 'Cle' },
        { key: 'value', label: 'Valeur' },
        { key: 'type', label: 'Type' },
        { key: 'group', label: 'Groupe' },
        { key: 'updated_at', label: 'Mise a jour', format: 'datetime' },
    ],
    fields: [
        { key: 'key', label: 'Cle' },
        { key: 'value', label: 'Valeur', type: 'textarea', nullable: true },
        {
            key: 'type',
            label: 'Type',
            type: 'select',
            options: [
                { value: 'string', label: 'Texte court' },
                { value: 'text', label: 'Texte long' },
                { value: 'integer', label: 'Entier' },
                { value: 'float', label: 'Decimal' },
                { value: 'boolean', label: 'Booleen' },
                { value: 'json', label: 'JSON' },
                { value: 'url', label: 'URL' },
                { value: 'email', label: 'Email' },
            ],
            default: 'string',
        },
        { key: 'group', label: 'Groupe', nullable: true },
    ],
};

const rbacResources = [
    {
        key: 'users',
        title: 'Utilisateurs',
        singular: 'utilisateur',
        description: 'Gerer les comptes administrateurs et leurs roles.',
        endpoint: '/admin/users',
        filters: ['status'],
        defaultSort: 'created_at',
        defaultDirection: 'desc',
        statusOptions: [
            { value: 'active', label: 'Actif' },
            { value: 'inactive', label: 'Inactif' },
        ],
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'email', label: 'Email' },
            { key: 'phone', label: 'Telephone' },
            { key: 'status', label: 'Statut', badge: true },
            { key: 'roles', label: 'Roles', format: 'list', listKey: 'name' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'email', label: 'Email', type: 'email' },
            { key: 'password', label: 'Mot de passe', type: 'password', nullable: true },
            { key: 'phone', label: 'Telephone', nullable: true },
            { key: 'avatar', label: 'Avatar', nullable: true },
            {
                key: 'status',
                label: 'Statut',
                type: 'select',
                options: [
                    { value: 'active', label: 'Actif' },
                    { value: 'inactive', label: 'Inactif' },
                ],
                default: 'active',
            },
            { key: 'role_ids', label: 'Roles', type: 'multiselect', relationKey: 'roles', optionsEndpoint: '/admin/roles' },
        ],
    },
    {
        key: 'roles',
        title: 'Roles',
        singular: 'role',
        description: 'Composer les roles a partir des permissions disponibles.',
        endpoint: '/admin/roles',
        defaultSort: 'name',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'slug', label: 'Slug' },
            { key: 'permissions', label: 'Permissions', format: 'list', listKey: 'slug' },
            { key: 'users_count', label: 'Utilisateurs' },
            { key: 'permissions_count', label: 'Total permissions' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
            { key: 'permission_ids', label: 'Permissions', type: 'multiselect', relationKey: 'permissions', optionsEndpoint: '/admin/permissions', optionLabel: 'slug', optionSort: 'slug' },
        ],
    },
    {
        key: 'permissions',
        title: 'Permissions',
        singular: 'permission',
        description: 'Administrer les permissions fines utilisees par les roles.',
        endpoint: '/admin/permissions',
        defaultSort: 'slug',
        defaultDirection: 'asc',
        columns: [
            { key: 'name', label: 'Nom' },
            { key: 'slug', label: 'Slug' },
            { key: 'description', label: 'Description' },
            { key: 'roles_count', label: 'Roles' },
        ],
        fields: [
            { key: 'name', label: 'Nom' },
            { key: 'slug', label: 'Slug', nullable: true },
            { key: 'description', label: 'Description', type: 'textarea', nullable: true },
        ],
    },
];

const currentRbacResource = computed(() => rbacResources.find((resource) => resource.key === activeRbacResource.value) ?? rbacResources[0]);

onMounted(() => {
    if (token.value) {
        ensureAdminPath();
        refreshDashboard();
    }
});

async function login() {
    loading.value = true;
    authError.value = '';
    apiError.value = '';

    try {
        const response = await api('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials),
            publicRequest: true,
        });

        token.value = response.access_token;
        user.value = response.user?.data ?? response.user ?? null;
        localStorage.setItem(storageKey, token.value);
        localStorage.setItem(userKey, JSON.stringify(user.value));
        credentials.password = '';
        ensureAdminPath();
        await refreshDashboard();
    } catch (error) {
        authError.value = error.message || 'Connexion impossible.';
    } finally {
        loading.value = false;
    }
}

async function refreshDashboard() {
    loading.value = true;
    apiError.value = '';

    try {
        const profile = await api('/auth/me');
        user.value = profile.data ?? profile;
        localStorage.setItem(userKey, JSON.stringify(user.value));

        const response = await api('/admin/dashboard');
        dashboard.value = response.data ?? {};
    } catch (error) {
        apiError.value = error.message || 'Impossible de charger le dashboard.';
        if (error.status === 401) {
            clearSession();
        }
    } finally {
        loading.value = false;
    }
}

async function logout() {
    try {
        await api('/auth/logout', { method: 'POST' });
    } finally {
        clearSession();
    }
}

async function api(path, options = {}) {
    const isFormData = options.body instanceof FormData;
    const headers = {
        Accept: 'application/json',
        ...(options.headers || {}),
    };

    if (!isFormData) {
        headers['Content-Type'] = 'application/json';
    }

    if (!options.publicRequest && token.value) {
        headers.Authorization = `Bearer ${token.value}`;
    }

    const response = await fetch(`/api/v1${path}`, {
        ...options,
        headers,
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const message = payload.message || Object.values(payload.errors || {})?.[0]?.[0] || 'Requete refusee.';
        const error = new Error(message);
        error.status = response.status;
        throw error;
    }

    return payload;
}

function clearSession() {
    token.value = '';
    user.value = null;
    dashboard.value = {};
    localStorage.removeItem(storageKey);
    localStorage.removeItem(userKey);
}

function readStoredUser() {
    try {
        return JSON.parse(localStorage.getItem(userKey));
    } catch {
        return null;
    }
}

function ensureAdminPath() {
    if (window.location.pathname !== '/admin') {
        window.history.replaceState({}, '', '/admin');
    }
}
</script>
