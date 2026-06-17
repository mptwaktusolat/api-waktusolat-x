# Using Docker Compose

## Quick Start

Create a `compose.yml` file with content from [docker-compose.prod.yml](../../docker-compose.prod.yml).

Create a `stack.env` file in same directory as compose file, with the content from [.env.example](../../.env.example). At least, update the following values with your own values:

```
APP_KEY=base64:Mmjn17WyYcRph3vjH3rmvpllUZkUmi4/1aLterMfSWY=
APP_URL=https://api-docker.waktusolat.app
DB_HOST=db
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
| DB_HOST       | The hostname of the database server. This should match the service name defined in the compose file. |

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
