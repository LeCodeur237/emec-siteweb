# Deploiement des fronts EMEC

Le backend expose l'API publique via :

- staging : `https://staging-api.egliseemec.org/api/v1`
- production : `https://api.egliseemec.org/api/v1`

Chaque front Vite doit recevoir la variable :

```env
VITE_API_BASE_URL=https://api.egliseemec.org/api/v1
```

## Dossiers

- `../emec` : site principal EMEC, Vue/Vite
- `../dosc-emec` : site des messages et predications, React/Vite
- `../messages-emec` : site DOSC, React/Vite

Les noms des dossiers sont actuellement inverses pour `dosc-emec` et `messages-emec`. La configuration ci-dessus suit le contenu reel des dossiers.

## Builds locaux

Depuis chaque dossier :

```bash
npm install
npm run build
```

Les fichiers statiques a publier sont dans `dist/`.

## Variables par environnement

Pour staging, copier `.env.staging.example` vers `.env` dans le dossier du front concerne.

Pour production, copier `.env.production.example` vers `.env` dans le dossier du front concerne.

Ne jamais mettre de secrets dans les fronts Vite : les variables `VITE_*` sont publiques dans le navigateur.

## GitHub Actions

Chaque front contient maintenant deux workflows :

- `.github/workflows/deploy-staging.yml`
- `.github/workflows/deploy-production.yml`

Les workflows publient uniquement le contenu de `dist/` vers le VPS.

Important : GitHub Actions lit les workflows uniquement dans le dossier
`.github/workflows` situe a la racine du depot GitHub. Si `emec`, `messages-emec`
et `dosc-emec` sont trois depots separes, les fichiers ajoutes dans chaque
dossier sont directement utilisables. Si les trois dossiers restent dans un seul
monorepo, il faut creer des workflows a la racine du monorepo avec
`working-directory` pour cibler chaque front.

Secrets GitHub requis dans chaque depot front :

```text
SERVER_HOST
SERVER_USER
SERVER_PORT
SERVER_SSH_KEY
```

Variables GitHub requises par environnement `staging` et `production` :

```text
FRONTEND_TARGET_DIR
VITE_API_BASE_URL
```

Valeurs recommandees :

```text
staging VITE_API_BASE_URL=https://staging-api.egliseemec.org/api/v1
production VITE_API_BASE_URL=https://api.egliseemec.org/api/v1
```

`FRONTEND_TARGET_DIR` doit pointer vers le dossier servi par Nginx, par exemple :

```text
/home/projects/emec-projects/emec/dist
/home/projects/emec-projects/messages-emec/dist
/home/projects/emec-projects/dosc-emec/dist
```

Les workflows verifient que `FRONTEND_TARGET_DIR` est renseigne avant l'upload, puis verifient que `index.html` existe apres copie.

## Nginx

Chaque site doit pointer vers le dossier `dist/` correspondant :

```nginx
root /home/projects/emec-projects/<nom-du-front>/dist;
index index.html;

location / {
    try_files $uri $uri/ /index.html;
}
```

Les domaines peuvent ensuite etre relies selon l'organisation choisie :

- `egliseemec.org` vers `emec/dist`
- `messages.egliseemec.org` vers le front des predications
- `dosc.egliseemec.org` vers le front DOSC
