# Nginx des fronts EMEC

Ce document prepare le deploiement des trois fronts Vite sur le VPS.

## Dossiers VPS

Creer un dossier par front :

```bash
sudo mkdir -p /home/projects/emec-projects/emec/dist
sudo mkdir -p /home/projects/emec-projects/messages-emec/dist
sudo mkdir -p /home/projects/emec-projects/dosc-emec/dist

sudo chown -R deploy:www-data /home/projects/emec-projects/emec
sudo chown -R deploy:www-data /home/projects/emec-projects/messages-emec
sudo chown -R deploy:www-data /home/projects/emec-projects/dosc-emec

sudo chmod -R 775 /home/projects/emec-projects/emec
sudo chmod -R 775 /home/projects/emec-projects/messages-emec
sudo chmod -R 775 /home/projects/emec-projects/dosc-emec
```

## Variables GitHub

Dans chaque depot front, creer les environnements `staging` et `production`.

Secrets requis :

```text
SERVER_HOST=173.212.200.200
SERVER_USER=deploy
SERVER_PORT=22
SERVER_SSH_KEY=<cle privee SSH du deploy GitHub Actions>
```

Variables requises par environnement :

```text
VITE_API_BASE_URL=https://staging-api.egliseemec.org/api/v1
FRONTEND_TARGET_DIR=/home/projects/emec-projects/<front>/dist
```

En production :

```text
VITE_API_BASE_URL=https://api.egliseemec.org/api/v1
FRONTEND_TARGET_DIR=/home/projects/emec-projects/<front>/dist
```

Remplacer `<front>` par `emec`, `messages-emec` ou `dosc-emec`.

## Configuration Nginx

Chaque front Vite est une SPA. Le bloc Nginx doit donc renvoyer les routes vers
`index.html`.

Des fichiers prets a copier sont disponibles dans `docs/nginx-sites/`.

### Site principal EMEC

```nginx
server {
    listen 80;
    server_name egliseemec.org www.egliseemec.org;

    root /home/projects/emec-projects/emec/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }
}
```

### Site des predications

```nginx
server {
    listen 80;
    server_name messages.egliseemec.org;

    root /home/projects/emec-projects/dosc-emec/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }
}
```

### Site DOSC

```nginx
server {
    listen 80;
    server_name dosc.egliseemec.org;

    root /home/projects/emec-projects/messages-emec/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }
}
```

Les dossiers `messages-emec` et `dosc-emec` gardent ici leur mapping actuel :
`dosc-emec` contient le site des predications, et `messages-emec` contient le
site DOSC.

## Activation Nginx

Adapter les noms de fichiers selon le site, puis activer :

```bash
sudo nginx -t
sudo systemctl reload nginx
```

Apres pointage DNS, activer HTTPS avec Certbot :

```bash
sudo certbot --nginx -d egliseemec.org -d www.egliseemec.org
sudo certbot --nginx -d messages.egliseemec.org
sudo certbot --nginx -d dosc.egliseemec.org
```

## Verification

Verifier que chaque site retourne le HTML Vite :

```bash
curl -I https://egliseemec.org
curl -I https://messages.egliseemec.org
curl -I https://dosc.egliseemec.org
```

Verifier aussi dans le navigateur que les appels API partent vers :

```text
https://api.egliseemec.org/api/v1
```
