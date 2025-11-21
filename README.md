![Laravel](https://img.shields.io/badge/laravel-12-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)

<img width="1920" height="540" alt="GitHub API Server Readme Banner" src="https://github.com/user-attachments/assets/4605bb5f-d8b7-41be-8552-b6bb773eb086" />

# Malaysia Prayer Time API

This service provides prayer times data for all states in Malaysia. Data is based on [E-Solat JAKIM](https://www.e-solat.gov.my/).

*Disclaimer:* This service is **not affiliated** with JAKIM nor endorsed by them in any way.

## Getting Started

### Bare Metal

To get started, clone the repository. Then, install Composer & Node dependencies:

```bash
composer install
npm install
```

Set up your `.env` file:

```bash
cp .env.example .env
```

In the `.env` file, fill in the database connection info.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=waktusolat
DB_USERNAME=root
DB_PASSWORD=
```

> [!NOTE]
> SQLite database is currently not supported because it doesn't have a spatial driver. If you are limited to using SQLite, you can either:
> - Install a spatial extension for SQLite.
> - Avoid using spatial queries. This includes removing the `ZonePolygonSeeder`, `zone_polygons` migration, etc. Note that this will cause some endpoints that require geographic resolution to not work.
> - If you still want spatial queries to work, refer to the [geojson-helper server](https://github.com/mptwaktusolat/api-waktusolat-x/tree/0e81a5b837dc4832e49da1ef84cf4b8cec8bc0cb/node-api/geojson-helper) that was previously implemented.

Then, run the migrations & seeder:

```bash
php artisan migrate --seed
```

Generate the api documentation page:

```bash
php artisan scribe:generate
```

Build and start the application:

```bash
composer run dev
```

> [!NOTE]
> When running `composer run dev`, it will automatically listen for changes in the `app/Http/Controllers/api/**/*.php` files and regenerate the API documentation. However, sometimes it may not work. In such cases, try running the following command separately in another terminal:
>
> ```bash
> npx chokidar 'app/Http/Controllers/api/**/*.php' -c 'php artisan scribe:generate'
> ```

You can now access the application at `http://localhost:8000`. The api docs will be available at `http://localhost:8000/docs`.

### Dev Container

[![Open in GitHub Codespaces](https://github.com/codespaces/badge.svg)](https://codespaces.new/mptwaktusolat/api-waktusolat-x)

You may need to create/edit the `.env` file:

```dotenv
DB_CONNECTION=mysql
DB_HOST=db # use service name instead of local ip
```

Then, run
```bash
composer setup
```

Start the application:

```bash
composer run dev
```

The app should be available at `http://localhost:8000`. PhpMyAdmin should be available at `http://localhost:9001` (username: `root`, password: leave empty).

## Troubleshooting

When error "Failed to Fetch" appear in the Swagger UI. Update the `APP_URL` value in the `.env` file to match the app URL. Example:

```dotenv
APP_URL=http://127.0.0.1:8000
```

## Testing

Create a `.env.testing` file:

```bash
cp .env.example .env.testing
```

Set the database connection to production database. It must run MySQL or other supported database with spatial support.

To run the test suite, execute the following command:

```bash
php artisan test --env=testing
```

## Debugging

This project contains [Laradumps](https://laradumps.dev/) package. LaraDumps is a powerful and user-friendly debugging app.

To use it, first install the Laradumps desktop app from https://laradumps.dev/download.

Then, run the following command:

```bash
php artisan ds:init $(pwd)
```

Get further information from the documentation: https://laradumps.dev/debug/introduction.html

## Architecture

TODO

## Motivation

In early days of App Waktu Solat API, I rely on API server provided by others. But sometimes, the server got shut down, discontinued, out of date etc. Hence, I think I need to maintain my own server.

History of API Server implementation, sorted from latest to oldest:
1. This API
2. [MPT Server](https://github.com/mptwaktusolat/api-waktusolat) - _Discontinued_
3. [MPT Backup API](https://github.com/mptwaktusolat/mpt-backup-api) - _Discontinued_

Wonder why so many changes? [Here's why](https://example.com). (TODO)

## Deployments

Interested in self-hosting the API Waktu Solat server? See [Deployments](docs#deployments) for details.
