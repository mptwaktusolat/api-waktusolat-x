![Laravel](https://img.shields.io/badge/laravel-12-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)

This service provides prayer times data based on JAKIM.

This is **not** a wrapper for JAKIM's API. Instead, this service fetches prayer times data periodically from JAKIM and stores it in a database.

Note that this service is **not affiliated** with JAKIM nor endorsed by them in any way.

## Getting Started

To get started, clone the repository. Then, install Composer & Node dependencies:

```bash
composer install
npm install
```

Set up your `.env` file:

```bash
cp .env.example .env
```

In the `.env` file, fill in the database connection info. To get started quickly, you can use SQLite:

```env
DB_CONNECTION=sqlite
```

Then, run the migrations & seeder:

```bash
php artisan migrate --seed
```

Generate the api documentation page:

```bash
php artisan script:generate
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

And start the node server (This is a helper server to process geojson data):

```bash
node node-api/geojson-helper/server.js
```

You can now access the application at `http://localhost:8000`. The api docs will be available at `http://localhost:8000/docs`.

## Architecture

TODO

## Motivation

This project was created to provide prayer times data based on JAKIM. The main motivation behind this project is to provide a simple and easy-to-use API for developers who want to integrate prayer times data into their applications.

This project is a port from the same API application that was written using NextJs: [`api-waktusolat`](https://github.com/mptwaktusolat/api-waktusolat). However, I find that it is more simpler and maintained to use Laravel for this project. Some of the reason is; able to manage database schema effectively using migrations, better file structure, and easier to generate API documentation from code comments. Though we may be able to achieve the same in NextJs, but it require more efforts.

## Deployments

See [deployments.md](docs/deployments.md) for deployment details.
