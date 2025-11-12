# CI/CD for hng-stage-4

This document describes the GitHub Actions CI/CD workflow and how to configure secrets and the server for deployment.

## What the workflow does

-   Runs tests (PHP 8.3 + Node 18)
-   Builds and pushes Docker images for `app` and `nginx` to Docker Hub
-   SSH to the deployment host, updates `docker-compose.prod.yml` image names and restarts the services

## Required GitHub secrets

Add these to the repository's Secrets (Settings → Secrets and variables → Actions):

-   `DOCKERHUB_USERNAME` — your Docker Hub username or registry namespace
-   `DOCKERHUB_TOKEN` — Docker Hub access token (or password)
-   `SSH_HOST` — IP or hostname of the production server
-   `SSH_USER` — SSH user (with permission to run docker/docker-compose)
-   `SSH_PRIVATE_KEY` — Private key (PEM) for `SSH_USER`
-   `SSH_PORT` — (optional) SSH port (default 22)

## Server setup (production host)

1. Install Docker and Docker Compose (v2 recommended):

```bash
# Ubuntu example
sudo apt update
sudo apt install -y docker.io
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
sudo usermod -aG docker $USER
```

2. Clone your repository on the server and ensure `docker-compose.prod.yml` is present.

3. Update `docker-compose.prod.yml` to use images from your Docker Hub namespace (the workflow will attempt to replace the local image names with the registry image names during deployment). Example:

```yaml
services:
    app:
        image: your-dockerhub-username/hng-stage-4-app:latest
    nginx:
        image: your-dockerhub-username/hng-stage-4-nginx:latest
```

4. Create or copy your `.env` file on the server and set production values. Make sure `APP_KEY` is present.

5. Start services:

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

## How deployment works

-   On push to `main`, the workflow builds multi-arch images for app and nginx and pushes them to Docker Hub.
-   The workflow then SSHes to the server, updates `docker-compose.prod.yml` to reference the pushed images using the current commit SHA, runs `docker compose pull`, and brings the services up.

## Safety notes

-   The workflow will not run migrations on its own. If you want automated migrations, enable them by adding `RUN_MIGRATIONS=true` to the `app` service environment (and backup the DB first).
-   Keep secrets secure and rotate them regularly.

## Troubleshooting

-   If the deploy step fails with permission errors, ensure the `SSH_USER` can run `docker` and `docker compose` (either directly or via sudo without password).
-   If Nginx returns 502, check:
    -   `docker compose -f docker-compose.prod.yml ps`
    -   `docker compose -f docker-compose.prod.yml logs nginx`
    -   `docker compose -f docker-compose.prod.yml logs app`

**_ End of document _**
