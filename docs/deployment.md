# Deployment

This document provides a guide for deploying the application in production. :rocket:

This guide assumes that:

- The website is being installed on a Linux server.
- The server allows access to run commands.
- You own a domain and have access to the DNS settings.

> [!NOTE]
> This guide is not the sole method for deploying the application. You're free to use other methods, services, or tools beyond those mentioned here.

## 1. Preparing the Server

Provision a Linux VPS on cloud. I'd recommend Hetzner because it's quite cheap, but the cheap server located far away from Malaysia. Minimum 1 vCPU and 1GB RAM.

### 1.1. Install CloudPanel

Install [Cloudpanel](https://www.cloudpanel.io/). The guide to install Cloudpanel for each provider is available on their documentation. See [here](https://www.cloudpanel.io/docs/v2/getting-started/).

> CloudPanel is a server control panel for managing PHP/JS applications on cloud servers. It offers a clean UI, performance optimizations, and simplified deployment. Alternatives include cPanel, Plesk, CyberPanel, aaPanel, and RunCloud.

You can choose the Database Engine you prefer, MySQL or MariaDB. But, I never tried MariaDB. In this guide, I will use MySQL.

### 1.2. Create a Site

Once CloudPanel is setup, add a new **PHP site**. Settings:

- Application: Laravel 12
- Domain Name: api.waktusolat.app (use your own domain)
- PHP Version: 8.4 (Default)
- Fill in the Site User & Site User Password. Keep this information safe.

Click on "Create".

### 1.3. Connect to the Server via SSH

On your PC, open the terminal and run:

```powershell
ssh <site-user>@<your-server-ip>
```

Replace `<site-user>` with the Site User you created in the [earlier](#12-create-a-site), and `<your-server-ip>` with the IP address of your server.

In my case, it would be:

```powershell
ssh waktusolat-api@178.128.81.43
```

Enter the password that has been set earlier in [previous step](#12-create-a-site).

To make life easier, I'd recommend you to use SSH keys for authentication. You can generate them (using PuTTYgen for example) and amend the SSH keys to the "Site User Settings" section in CloudPanel.

And then, add the credential to the SSH config file (On Windows, it can be found at `<USERPROFILE>\.ssh\config`) on your local machine:

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

Commands after this point is meant to be run on the **host server**.

### 1.4. Setup the environment

We'll need to install npm and provision a database for the application.

#### 1.4.1. Install Node.js

Node.js is required to build the frontend assets and run the helper server (more on this later). To install Node.js, run the following commands:

1. Install nvm with the following command:

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash
```

2. Update the current shell environment:

```bash
source ~/.bashrc
```

3. Install your required Node.js version e.g. 22:

```bash
nvm install 22
```

4. Activate the installed Node.js version:

```bash
nvm use 22
```

Refer to the following link for information:

- https://www.cloudpanel.io/docs/v2/php/guides/nodejs/
- https://nodejs.org/en/download

#### 1.4.2. Create a Database

In the CloudPanel site dashboard, go to the "Databases" section and create a new database. Fill in the following information:

- Database Name: `waktusolat-api` (or any name you prefer)
- Database User: `waktusolat-api` (or any name you prefer)
- Password: `your_password`

Note down the database name, user, and password as we will need them later.

## Setup the Application

Now that the server is ready, we can proceed to clone the application and set it up.

### 2.1. Clone the Repository

Navigate to the web root directory of your site. You can find the path in the CloudPanel site dashboard, under the "Web Root" section. For example, it might be `/home/waktusolat-api/htdocs/api.waktusolat.app/public`.

```bash
cd /home/waktusolat-api/htdocs/api.waktusolat.app/public
cd ..
```

Now, we are in the `api.waktusolat.app` directory:

```bash
pwd # /home/waktusolat-api/htdocs/api.waktusolat.app
```

We want to clone the repository into the `api.waktusolat.app` directory. We'll need to empty the directory first:

```bash
rm -rf /home/waktusolat-api/htdocs/api.waktusolat.app/*
```

> [!CAUTION]
> Careful with the `rm -rf` command. Double check the path before running it to avoid deleting important files.

Now, clone the repository:

```bash
git clone https://github.com/mptwaktusolat/api-waktusolat-x.git .
```

Note the `.` at the end of the command, which indicates that we want to clone the repository into the current directory.

### 2.2. Start the subserver

As stated in the project's README, there is one node js application that is used to process geojson data. This application is located in the `node-api/geojson-helper` directory. Some endpoints in the main application (the Laravel app) will call this subserver endpoint to get some information.

Navigate to the `node-api/geojson-helper` directory:

```bash
cd node-api/geojson-helper
```

Install the dependencies:

```bash
npm install
```

Now, you could start the subserver using `node server.js`, but I would like to use a process manager like `pm2` to keep the server running in the background and handle restarts automatically.

Install `pm2` globally:

```bash
npm install pm2@latest -g
```

Start the subserver using `pm2`:

```bash
pm2 start node-api/geojson-helper/server.js --name geo-resolver -- start
```

You can give whatever name you want to the subserver, here I name it `geo-resolver`. Then, save the configuration.

```bash
pm2 save
```

To ensure that this subserver starts after a reboot, we need to configure a cron job. Run the following command to generate the startup script:

First copy the output of the PATH variable:

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

### 2.2. The usual dance

Now for the usual Laravel app setup routine, i.e. installing dependencies, setting up the environment, and running migrations.

Install Composer Dependencies:

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

Generate the API documentation page:

```bash
php artisan scribe:generate
```

Build the vite assets:

```bash
npm install
npm run build
```

To optimise the application, you can run the following command:

```bash
php artisan optimize
```
