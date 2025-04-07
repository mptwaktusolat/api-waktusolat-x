![Laravel](https://img.shields.io/badge/laravel-12-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)

This service provides prayer times data based on JAKIM.

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

> [!TIP]
> To run the scribe generator automatically on file changes. Run
> `npx chokidar 'app/Http/Controllers/api/**/*.php' -c 'php artisan scribe:generate'`.

Build and start the application:

```bash
composer run dev
```

And start the node server (This is a helper server to process geojson data):

```bash
node node-api/geojson-helper/server.js
```

You can now access the application at `http://localhost:8000`. The api docs will be available at `http://localhost:8000/docs`.
