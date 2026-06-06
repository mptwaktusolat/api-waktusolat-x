# Using Docker Compose

## Quick Start

Create a `compose.yml` file with the following content:

```yaml
services:
  app:
    image: ghcr.io/mptwaktusolat/api-waktusolat-x
    ports:
      - "8080:8080" # HTTP
      - "8443:8443" # HTTPS
    environment:
      - PHP_OPCACHE_ENABLE=1
      - APP_KEY=${APP_KEY:?APP_KEY must be set}
      - APP_URL=${APP_URL}
      - APP_ENV=${APP_ENV}
      - APP_DEBUG=${APP_DEBUG}
      - ENABLE_TRUSTED_PROXY_CONFIG=${ENABLE_TRUSTED_PROXY_CONFIG}
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - DB_PORT=3306
      - DB_DATABASE=waktusolat
      - DB_USERNAME=root
      - DB_PASSWORD=
      - SSL_MODE=mixed
    depends_on:
      - db
  db:
    image: mysql:8.0
    environment:
      MYSQL_ALLOW_EMPTY_PASSWORD: 'yes'
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

Create a `.env` file in same directory as compose file, with the following content:

```env
APP_KEY=
APP_URL=
APP_ENV=
APP_DEBUG=
ENABLE_TRUSTED_PROXY_CONFIG=true
```

See the [Environment Variables](#environment-variables) section below for more details.

Then, start the services:

```bash
docker compose -f compose.yml up -d
```

## First setup

Access the app container:

```bash
docker compose exec app sh
cd /var/www/html
```

Run the migration and seeder:

```bash
php artisan migrate --seed
```

Generate the API documentation:

```bash
php artisan scribe:generate
```

API is now ready to serve requests. Open site at `http://<your-server-ip-or-domain>:8080`

## Environment Variables

### app service

The environment variables details are as follows:

| Variable       | Description                                      |
|----------------|--------------------------------------------------|
| APP_KEY        | Application key for encryption. Run `php artisan key:generate --show` to create one, or use any online tool. |                 |
| APP_URL        | The base URL of the application. Used to generate the swagger documentation.                 |
| APP_ENV       | The application environment (e.g., `local`, `production`).  |
| APP_DEBUG     | Enable or disable debug mode (`true` or `false`). If first time setting up, it might be helpful to set to `true` to see any errors. |

Additional environment variables provided by the image can be found here: https://serversideup.net/open-source/docker-php/docs/reference/environment-variable-specification.

### mysql service

If you choose to protect the mysql database with a password, you can set the following environment variable in the `db` service:

```env
MYSQL_ALLOW_EMPTY_PASSWORD=no
MYSQL_ROOT_PASSWORD=
MYSQL_USER=
MYSQL_PASSWORD=
```

See details here: https://hub.docker.com/_/mysql#environment-variables

## Reverse proxy

If you run the app behind a reverse proxy, you may need to set `ENABLE_TRUSTED_PROXY_CONFIG=true` to properly handle client IP and HTTPS scheme. This will configure Laravel's trusted proxy settings to trust all proxies, which is suitable for most setups. However, if you want to specify trusted proxies manually, you can set `TRUSTED_PROXIES` environment variable with a comma-separated list of proxy IPs or CIDR ranges.

Then, in your Caddyfile (for example), you can add the following header to forward the original client IP:

```
api-docker.waktusolat.app {
    reverse_proxy api-solat-app-1:8080 {
        header_up X-Forwarded-Proto {scheme}
        header_up X-Forwarded-Host {host}
        header_up X-Forwarded-For {remote_host}
        header_up X-Real-IP {remote_host}
    }
}
```

## Extras

- Recommended tools to run Docker stack: [Dockge](https://github.com/louislam/dockge), [Portainer](https://docs.portainer.io/start/install-ce/server/docker/linux).
- Old [guide](https://github.com/mptwaktusolat/api-waktusolat-x/blob/640341f05a391f2edf7772c96a19caee537d869a/docs/deployments/coolify.md) to host on Coolify.


## References

- About ServerSideUp images: https://serversideup.net/open-source/docker-php/docs/getting-started/default-configurations
