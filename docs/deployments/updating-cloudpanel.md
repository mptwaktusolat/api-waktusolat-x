# Updating the Application

This guide provides instructions for updating the application to the latest version. The following guides is specific for application deployed using CloudPanel, so, YMMV. Follow these steps to ensure a smooth update process.

SSH into the server and navigate to the application directory:

```bash
cd htdocs/api.waktusolat.app
```

Pull the latest changes from the repository:

```bash
git pull
```

Update PHP dependencies:

```bash
composer install
```

Update Node.js dependencies:

```bash
npm install
```

Run the following commands to clear caches and optimize the application:

```bash
php artisan optimize:clear
npm run build
php artisan scribe:generate
php artisan optimize
```

Visit the application URL to ensure everything is working correctly. If you encounter any issues, check the server logs for debugging.

Also, check out [`deploy_prod.yml`](https://github.com/mptwaktusolat/api-waktusolat-x/blob/main/.github/workflows/deploy_prod.yml) for automating updates when code is pushed.
