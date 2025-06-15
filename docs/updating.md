# Updating the Application

This guide provides instructions for updating the application to the latest version. Follow these steps to ensure a smooth update process.

## 1. Prerequisites

Before updating, ensure the following:

- You have SSH access to the production server.
- The application is already deployed and running.
- You have the necessary credentials (SSH key, username, etc.).

## 2. Update Process

### 2.1. Connect to the Server

Open your terminal and connect to the server via SSH:

```powershell
ssh <site-user>@<your-server-ip>
```

Replace `<site-user>` and `<your-server-ip>` with the appropriate values. If you have configured SSH keys, you can use:

```powershell
ssh api-waktusolat
```

### 2.2. Navigate to the Application Directory

Once connected, navigate to the application directory:

```bash
cd htdocs/api.waktusolat.app
```

### 2.3. Pull the Latest Code

Pull the latest changes from the repository:

```bash
git pull
```

### 2.4. Install Dependencies

Update PHP dependencies:

```bash
composer install
```

Update Node.js dependencies:

```bash
npm install
```

### 2.5. Restart the Subserver

Restart the Node.js subserver using `pm2`:

```bash
pm2 restart geo-resolver
```

### 2.6. Clear and Optimize the Application

Run the following commands to clear caches and optimize the application:

```bash
php artisan optimize:clear
npm run build
php artisan scribe:generate
php artisan optimize
```

### 2.7. Verify the Update

Visit the application URL to ensure everything is working correctly. If you encounter any issues, check the server logs for debugging.

Also, check out `deploy_prod.yml` for automating updates when code is pushed.
