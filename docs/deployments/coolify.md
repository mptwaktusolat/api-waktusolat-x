# Coolify

This document provides a guide for deploying the application in production on a VPS using Coolify. :rocket:

This guide assumes that:

- The server allows access to run commands (eg not a shared hosting).
- You own a domain and able to manage its DNS settings.

## 1. Preparing the Server

Provision a Linux VPS on cloud. I'd recommend Hetzner because it's quite cheap, but the cheap server located far away from Malaysia. See the server OS and specification requirements for installing Coolify [here](https://coolify.io/docs/get-started/installation#_1-server-requirements).

(Optional, but Recommended) Enable SWAP space on your server. This is useful if your server has low RAM (eg 1GB). First check if SWAP is already enabled:

```bash
swapon --show
```

If there is no output, it means SWAP is not enabled. To enable SWAP, run the following commands:

```bash
sudo fallocate -l 1G /swapfile && \
sudo chmod 600 /swapfile && \
sudo mkswap /swapfile && \
sudo swapon /swapfile && \
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

The script will create 1GB swap file, secure it, enable, and persist in `/etc/fstab`. Check again if SWAP is enabled using `swapon --show` or `free -h` command.

### 1.1. Install Coolify

> Coolify is a self-hosted platform that allows you to deploy and manage applications easily. It uses Docker under the hood.

Install [Coolify](https://www.coolify.io/). Consult the official [documentation](https://coolify.io/docs/get-started/installation#self-hosted-installation) for more details on installing self-hosted version of Coolify.

Once installed, access the Coolify dashboard by navigating to `http://<your-server-ip>:8000` in your web browser. Follow the setup wizard to create an admin account.

At the end, you'll see the Coolify dashboard something like this:

<img width="1920" height="1080" alt="coolify-deployment-guide (16)" src="https://github.com/user-attachments/assets/6b3895d0-08bf-4e13-89d8-361de1df6d86" />

### 1.2. Create a project and add a MySQL resource

Create a new project (eg: "Waktu Solat Project"), click the default environment (ie `Production`), and then click "Add Resource".

<!-- Add Add resource > MySQL. -->
<img width="1647" height="333" alt="coolify-deployment-guide (15)" src="https://github.com/user-attachments/assets/7698fb55-25c6-44a0-bf60-50769f455738" />

Click on **MySQL** under the "Databases" section. 

<img width="1618" height="444" alt="image" src="https://github.com/user-attachments/assets/ffc8c634-2844-4229-8998-23174d801948" />

A configuration panel will appear. You can modify the settings as needed, or leave it as is. Then, click on **Start**.

<img width="1641" height="946" alt="image" src="https://github.com/user-attachments/assets/d04da626-6422-4488-842b-4e79187400ef" />

<img width="1649" height="888" alt="coolify-deployment-guide (12)" src="https://github.com/user-attachments/assets/8a91765d-38b0-4d0e-a297-f04b422af009" />

The message above indicates that the MySQL resource has been successfully created and is running. Note the container ID because we will need it later. In my case, it is the `mwws08w8cw8k8cowsws08g8g`.  

### 1.3. Deploy Application

Next, we will run the API Waktu Solat image. Back to the Resources page, click on Resources > New. And select **Docker Image** option.

<img width="1212" height="526" alt="coolify-deployment-guide (11)" src="https://github.com/user-attachments/assets/87fb72db-398d-402b-b670-da9111fe5dd7" />

<img width="1618" height="261" alt="image" src="https://github.com/user-attachments/assets/c6106431-004d-4181-9080-8eb07fc78d80" />

Visit this [page](https://github.com/mptwaktusolat/api-waktusolat-x/pkgs/container/api-waktusolat-x) to get the image artifact URL. Copy the URL of the latest version. Eg: `ghcr.io/mptwaktusolat/api-waktusolat-x:latest`. Paste the URL into the **Docker Image** field in Coolify. Then, click on **Save**.

<img width="1619" height="430" alt="coolify-deployment-guide (9)" src="https://github.com/user-attachments/assets/b2466976-9aac-414c-af0e-9f43c0307f89" />

You'll be directed to the Configuration page. Click on the **Environment Variables** tab. Copy the [`.env.example`](https://github.com/mptwaktusolat/api-waktusolat-x/blob/main/.env.example) from this repository. In the Environment Variables tab, switch to **Developer Mode** and paste the content of `.env.example` file.

<img width="1619" height="1055" alt="coolify-deployment-guide (8)" src="https://github.com/user-attachments/assets/924b2efe-9347-4782-8e3c-9f5cacca4284" />

Update the Database part to match our deployed MySQL resource earlier. Use the root user username and password, and the initial database created.

<img width="1240" height="484" alt="image" src="https://github.com/user-attachments/assets/7bcf8d2f-9de3-4b4f-acbb-680527c3974d" />

Example:

```env
DB_CONNECTION=mysql
DB_HOST=mwws08w8cw8k8cowsws08g8g # replace with your container ID
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=root
DB_PASSWORD=your_root_password
```
And then Click on Save All Environment Variables. Go back to the Configuration tab.

<img width="1598" height="1078" alt="coolify-deployment-guide (7)" src="https://github.com/user-attachments/assets/012fc313-7a8f-4b0c-bd87-fb890e932bb1" />

In the **Port Exposes** section, set the value to `8080`. This is the port that NGINX will listen to proxy requests to the application. (Use `8443` for HTTPS)

<img width="1265" height="186" alt="coolify-deployment-guide (5)" src="https://github.com/user-attachments/assets/a367a712-ebdc-4d53-b8a7-b7ed180583b5" />

Now, click on the **Deploy** button at the top right corner. This will start the deployment process. Wait for the process to finish.

<img width="1634" height="700" alt="coolify-deployment-guide (6)" src="https://github.com/user-attachments/assets/221e0cc1-a94c-4fdf-b330-b81e70fc085f" />

The message above indicates that the deployment has completed successfully. Click on the Logs tab to see the application logs. If everything is set up correctly, you should see the application running without any errors.

<img width="1391" height="847" alt="coolify-deployment-guide (4)" src="https://github.com/user-attachments/assets/c8d931e4-e354-43e6-ad9f-f8519a6a1ea6" />

The to the Configuration tab, and open the generated domain to see the live app.

<!-- Add screenshot of the app -->
<img width="1233" height="555" alt="coolify-deployment-guide (2)" src="https://github.com/user-attachments/assets/5104208e-6310-4f6f-a60f-8f2352a7acfd" />

Now, we can see our Laravel app (ignore the errors for now), which means our configuration is set up correctly and the reverse proxy server can reach our application. Now, we will have to set up the app itself.

<img width="1412" height="909" alt="coolify-deployment-guide (1)" src="https://github.com/user-attachments/assets/b1741dcf-2815-4d1a-942f-56e244f1889d" />

### 1.4. Set up the Application

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

### 4.2. The usual dance

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

![image](https://github.com/user-attachments/assets/8cad737a-68d5-4f4c-8487-676d72b96ff0)

And the browser warning is gone.

![image](https://github.com/user-attachments/assets/dea11ab2-6a55-41d8-b358-9b7497634801)

### 5.2. Setup Logging & Monitoring (Optional)

This section is optional. It is recommended to monitor your service while it is running in production. Historically, this app used [Laravel Telescope](https://github.com/laravel/telescope) before recently adopting [Laravel Nightwatch](https://nightwatch.laravel.com/).

On the surface level, Laravel Telescope runs inside your app. It's free and quick, useful for development or debugging scenarios. Meanwhile, Laravel Nightwatch is a managed service provided by Laravel and could incur some costs.

#### 5.2.1. Setup Laravel Nightwatch

Register your account at https://nightwatch.laravel.com/. Follow the [instructions given](https://nightwatch.laravel.com/docs/getting-started/start-guide) to register your application with Nightwatch.

Update the `.env` file with the Nightwatch token:

```env
NIGHTWATCH_TOKEN=your_token
```

Then start the agent:

```bash
php artisan nightwatch:agent
```

<img width="759" height="646" alt="Screenshot 2025-08-17 at 6 36 19 AM" src="https://github.com/user-attachments/assets/9e7fb8c2-5a77-4617-aefc-2b8dfcb45e73" />

You should begin to see the dashboard load with some activity from your application, which indicates that your setup is working.

However, for **production** deployment, the [documentation](https://nightwatch.laravel.com/docs/guides/other-providers#running-as-a-systemd-service) suggests running the agent as a systemd service. This will ensure that the agent is always running and automatically restarted if it fails. Follow the following steps:

Stop the previously runnning `php artisan nightwatch:agent`. (Ctrl+C in the terminal)

SSH to the server as the root user:

```bash
ssh sakinah-root
```

Create the nightwatch-agent.service file:

```bash
sudo nano /etc/systemd/system/nightwatch-agent.service
```

And paste the following file:

```ini
[Unit]
Description=Laravel Nightwatch Agent
After=network.target

[Service]
Type=simple
User=waktusolat-api
Group=waktusolat-api
WorkingDirectory=/home/waktusolat-api/htdocs/api.waktusolat.app
ExecStart=/usr/bin/php artisan nightwatch:agent
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

Replace `User`, `Group`, and `WorkingDirectory` with the appropriate values for your application. You can determine these values by referring to the image below:

<img width="1017" height="701" alt="Screenshot 2025-08-18 at 3 20 34 PM" src="https://github.com/user-attachments/assets/0b06d7cc-f3b2-425d-885c-4d8afea8be42" />

Save the file. Then run the following commands to start the service.

```bash
sudo systemctl daemon-reload
sudo systemctl enable nightwatch-agent
sudo systemctl start nightwatch-agent
```

You can check the service status using the command:

```bash
sudo systemctl status nightwatch-agent
```

<img width="1369" height="827" alt="Screenshot 2025-08-18 at 3 24 30 PM" src="https://github.com/user-attachments/assets/49bd53af-772a-4bcb-aec6-abd732fbb7c7" />

If everything is green across the board, you have set up the agent correctly. You should check again if the dashboard is receiving data from the agent.

<img width="2032" height="1167" alt="Screenshot 2025-08-18 at 3 46 03 PM" src="https://github.com/user-attachments/assets/33670da3-4549-4f2c-b440-5fc4628dffc6" />

To learn more about Nightwatch, visit the [official documentation](https://nightwatch.laravel.com/docs).

#### 5.2.2. Setup Laravel Telescope

The application includes Laravel Telescope, a useful tool for debugging and monitoring your application.

Enable the Telescope feature by setting the `TELESCOPE_ENABLED` environment variable to `true` in the `.env` file:

```env
TELESCOPE_ENABLED=true
```

The Telescope route is protected by authentication. You can add a user to access the Telescope dashboard using the following artisan command:

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
