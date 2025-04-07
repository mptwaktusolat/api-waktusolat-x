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
php artisan scribe:generate
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

## Docker Setup

### Development Environment

To run the application using Docker for development:

1. Make sure you have Docker and Docker Compose installed on your machine.

2. Set up your environment variables in the `.env` file:

    ```bash
    cp .env.example .env
    ```

3. Update the `.env` file with database settings for Docker:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=laravel
    DB_PASSWORD=root

    REDIS_HOST=redis
    CACHE_DRIVER=redis
    QUEUE_CONNECTION=redis
    SESSION_DRIVER=redis
    ```

4. Build and start the Docker containers:

    ```bash
    docker-compose up -d
    ```

5. Run the database migrations:

    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

6. Generate the API documentation:

    ```bash
    docker-compose exec app php artisan scribe:generate
    ```

7. Access the application at `http://localhost` and the API documentation at `http://localhost/docs`.

### Production Environment

For production deployment:

1. Set up your production environment variables:
    ```bash
    cp .env.example .env.production
    ```
2. Configure the `.env.production` file with appropriate production settings.

3. Build the Docker image:

    ```bash
    docker build -t waktusolat-app:latest .
    ```

4. Deploy using Docker Compose:

    ```bash
    docker-compose -f docker-compose.prod.yml --env-file .env.production up -d
    ```

5. Run migrations (only needed for first deploy or when there are new migrations):

    ```bash
    docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
    ```

6. The application will be available on port 80 of your server.
