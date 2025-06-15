# Deployment

This document provides a guide for deploying the application in production. :rocket:

This guide assumes that:

- The website is being installed on a Linux server.
- The server allows access to run commands.
- You own a domain and have access to its DNS settings.

> [!NOTE]
> This guide is not the only method for deploying the application. You are free to use other methods, services, or tools beyond those mentioned here.

## 1. Preparing the Server

Provision a Linux VPS on cloud. I'd recommend Hetzner because it's quite cheap, but the cheap server located far away from Malaysia.

See the server requirements [here](https://www.cloudpanel.io/docs/v2/requirements/). I think the minimum you can go is 1 vCPU and 1GB RAM.

Reference:

### 1.1. Install CloudPanel

Install [CloudPanel](https://www.cloudpanel.io/). Refer to the CloudPanel [documentation](https://www.cloudpanel.io/docs/v2/getting-started/) for instructions on installing CloudPanel on your server.

> CloudPanel is a server control panel for managing PHP/JS applications on cloud servers. It offers a clean UI, performance optimizations, and simplified deployment. Alternatives include cPanel, Plesk, CyberPanel, aaPanel, and RunCloud.

You can choose your preferred database engine, either MySQL or MariaDB. However, I have not tried MariaDB. In this guide, I will use MySQL.

### 1.2. Create a Site

Once CloudPanel is set up, add a new **PHP site**. For example, if you want to use the domain `api.waktusolat.app`, here are the settings:

- Application: Laravel 12
- Domain Name: api.waktusolat.app (use your own domain)
- PHP Version: 8.4 (Default)
- Fill in the Site User & Site User Password. Keep this information safe.

Click "Create".

![image](https://github.com/user-attachments/assets/996055d5-b875-4bba-93bb-642ea3767166)

## 2. Configure DNS

Next, configure the DNS settings for the domain `api.waktusolat.app`.

Log in to your domain registrar's control panel or DNS name server dashboard, and create an `A` record for the domain `api.waktusolat.app` pointing to your server's IP address.

![image](https://github.com/user-attachments/assets/b1829e70-ce48-454a-bd83-9e520ede682f)

## 3. Prepare the Server Environment

### 3.1. Connect to the Server via SSH

On your PC, open the terminal and run:

```powershell
ssh <site-user>@<your-server-ip>
```

Replace `<site-user>` with the Site User you created [earlier](#12-create-a-site), and `<your-server-ip>` with your server's IP address.

In my case, it would be:

```powershell
ssh waktusolat-api@178.128.81.43
```

Enter the password set earlier in the [previous step](#12-create-a-site).

To simplify authentication, I recommend using SSH keys. You can generate them (using PuTTYgen, for example) and add the SSH keys to the "Site User Settings" section in CloudPanel.

Then, add the credentials to the SSH config file (on Windows, it can be found at `<USERPROFILE>\.ssh\config`) on your local machine:

```powershell
notepad $HOME\.ssh\config
```

In the config file, add the following:

```config
Host api-waktusolat
    HostName 178.128.81.43
    User waktusolat-api
    IdentityFile "<path-to-your-private-key>"
```

Now you can connect to the server using the following command:

```powershell
ssh api-waktusolat
```

![image](https://github.com/user-attachments/assets/f4bd9f04-cefd-4011-9c82-6ae84f3e893d)

Commands from this point onward are meant to be run on the **host server**.

We'll need to install npm and provision a database for the application.

### 3.2. Install Node.js

Node.js is required to build the frontend assets and run the helper server (more on this later). To install Node.js, run the following commands:

1. Install nvm using the following command:

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash
```

2. Update the current shell environment:

```bash
source ~/.bashrc
```

3. Install the required Node.js version, e.g., 22:

```bash
nvm install 22
```

4. Activate the installed Node.js version:

```bash
nvm use 22
```

Refer to the following links for more information:

- https://www.cloudpanel.io/docs/v2/php/guides/nodejs/
- https://nodejs.org/en/download

### 3.3. Create a Database

In the CloudPanel site dashboard, go to the "Databases" section and create a new database. Fill in the following information:

- Database Name: `waktusolat-api` (or any name you prefer)
- Database User: `waktusolat-api` (or any name you prefer)
- Password: `your_password`

Note down the database name, user, and password, as we will need them later.

![image](https://github.com/user-attachments/assets/86595bec-6e58-4367-9017-ce5ed8673bbb)

## 4. Deploy the Application

Now that the server is ready, we can proceed to clone the application and set it up.

### 4.1. Clone the Repository

Navigate to the web root directory of your site. You can find the path in the CloudPanel site dashboard, under the "Web Root" section. For example, it might be `/home/waktusolat-api/htdocs/api.waktusolat.app/public`.

![image](https://github.com/user-attachments/assets/1d9d90c2-9408-4e6a-8bbd-9b68ab9dd6b5)

```bash
cd /home/waktusolat-api/htdocs/api.waktusolat.app/public
cd ..
```

Now, we are in the `api.waktusolat.app` directory:

```bash
pwd # /home/waktusolat-api/htdocs/api.waktusolat.app
```

We want to clone the repository into the `api.waktusolat.app` directory. First, empty the directory:

```bash
rm -rf /home/waktusolat-api/htdocs/api.waktusolat.app/*
```

> [!CAUTION]
> Be careful with the `rm -rf` command. Double-check the path before running it to avoid deleting important files.

Now, clone the repository:

```bash
git clone https://github.com/mptwaktusolat/api-waktusolat-x.git .
```

Note the `.` at the end of the command, which indicates that we want to clone the repository into the current directory.

### 4.2. Start the Subserver

As stated in the project's README, there is a Node.js application used to process GeoJSON data. This application is located in the `node-api/geojson-helper` directory. Some endpoints in the main application (the Laravel app) will call this subserver endpoint to retrieve information.

Navigate to the `node-api/geojson-helper` directory:

```bash
cd node-api/geojson-helper
```

Install the dependencies:

```bash
npm install
```

You could start the subserver using `node server.js`, but I recommend using a process manager like `pm2` to keep the server running in the background and handle restarts automatically.

Install `pm2` globally:

```bash
npm install pm2@latest -g
```

Start the subserver using `pm2`:

```bash
pm2 start node-api/geojson-helper/server.js --name geo-resolver -- start
```

![image](https://github.com/user-attachments/assets/624a6765-8d0f-4917-8e0c-546aec518cce)

You can name the subserver whatever you like; here, I named it `geo-resolver`. Then, save the configuration:

```bash
pm2 save
```

To ensure this subserver starts after a reboot, configure a cron job. Run the following command to generate the startup script:

First, copy the output of the PATH variable:

```bash
echo $PATH
```

The output will look similar to this:

```
/home/waktusolat-api/.nvm/versions/node/v22.16.0/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/snap/bin
```

Edit the crontab file:

```bash
crontab -e
```

Add the following lines to it:

```bash
PATH=$PASTE_THE_OUTPUT_OF_$PATH
@reboot pm2 resurrect &> /dev/null
```

![image](https://github.com/user-attachments/assets/0f5856c0-f6d0-4c66-be47-9516acccc838)

Refer to the following links for more information:

- https://www.cloudpanel.io/docs/v2/nodejs/deployment/pm2/
- https://pm2.keymetrics.io/

### 4.3. The usual dance

Now for the usual Laravel app setup routine, i.e. installing dependencies, setting up the environment, and running migrations.

Install Composer dependencies:

```bash
composer install
```

Create the `.env` file:

```bash
cp .env.example .env
```

Edit the `.env` file and fill in the database connection information:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=waktusolat-api
DB_USERNAME=waktusolat-api
DB_PASSWORD=your_password
```

Still in the `.env` file, set the `APP_URL` to your domain. This is needed to generate the OpenAPI documentation correctly later:

```env
APP_URL=https://api.waktusolat.app
```

Generate the application key:

```bash
php artisan key:generate
```

Run the migrations and seed the database:

```bash
php artisan migrate --seed
```

The seeder may take some time to complete, as there is a lot of data (about 57,000 rows) to be seeded into the database.

Generate the API documentation page:

```bash
php artisan scribe:generate
```

Build the Vite assets:

```bash
npm install
npm run build
```

To optimize the application, run the following command:

```bash
php artisan optimize
```

By now, if you visit the URL `https://api.waktusolat.app`, the browser will warn you about the SSL certificate. Click "Proceed (unsafe)" and you should see the Waktu Solat API homepage. :tada:

## 5. Post Deployment

### 5.1. Setup SSL Certificate

To remove the browser security warning, install a trusted SSL certificate for your domain.

Go to the CloudPanel dashboard and click the "SSL/TLS" tab. Then click the "Actions" button > "New Let's Encrypt Certificate".

![image](https://github.com/user-attachments/assets/33285a7d-5e23-4d3a-9aa9-5cc17b9ff7b4)

Make sure you have already added the DNS record as shown in [Step 2](#2-configure-dns). Click "Create & Install".

![image](https://github.com/user-attachments/assets/69d562ed-44ea-4e90-8dfa-5f6713cf8cd5)

> [!TIP]
> If the certificate installation fails, try again. It may take some time for the DNS record to propagate.

Now, your application should have a trusted certificate installed.

![image](https://github.com/user-attachments/assets/dea11ab2-6a55-41d8-b358-9b7497634801)

### 5.2. Add a User

This step is optional. The application includes Laravel Telescope, a useful tool for debugging and monitoring your application. However, the Telescope route is protected by authentication. You can add a user to access the Telescope dashboard using the following artisan command:

```bash
php artisan app:create-user
```

Provide the user with a name, email, and password.

![image](https://github.com/user-attachments/assets/873447ef-4b12-4f65-9385-c17c6a50d25d)

This user will be able to access the Telescope dashboard at `https://api.waktusolat.app/telescope`.

![image](https://github.com/user-attachments/assets/fdb48a55-eaf1-444b-b2b9-257e6a6d0543)

Any authenticated user can access the Telescope dashboard. You can control access in the `TelescopeServiceProvider.php` file.

```php
/**
 * Register the Telescope gate.
 *
 * This gate determines who can access Telescope in non-local environments.
 */
protected function gate(): void
{
    Gate::define('viewTelescope', function ($user) {
        return $user !== null;
    });
}
```

For more information about Telescope, refer to:

- https://laravel.com/docs/12.x/telescope

## 6. Conclusion

Alhamdulillah! You have successfully deployed the Waktu Solat API application in production. :tada:

To update the application, see the [Updating Application](./updating.md) document.

> Found an error or typo in this document? Please [open an issue](https://github.com/mptwaktusolat/api-waktusolat-x/issues) or [submit a pull request](https://github.com/mptwaktusolat/api-waktusolat-x/pulls).
