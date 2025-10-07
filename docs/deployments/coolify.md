# Coolify

This document provides a guide for deploying the application in production on a VPS using Coolify. :rocket:

This guide assumes that:

- The server allows access to run commands (e.g., not shared hosting).
- You own a domain and can manage its DNS settings.

## 1. Preparing the Server

Provision a Linux VPS on the cloud. I'd recommend Hetzner because it's quite cheap, but the cheaper servers are located far away from Malaysia. See the server OS and specification requirements for installing Coolify [here](https://coolify.io/docs/get-started/installation#_1-server-requirements).

(Optional, but Recommended) Enable SWAP space on your server. This is useful if your server has low RAM (e.g., 1GB). First, check if SWAP is already enabled:

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

The script will create a 1GB swap file, secure it, enable it, and persist it in `/etc/fstab`. Check again if SWAP is enabled using the `swapon --show` or `free -h` command.

### 1.1. Install Coolify

> Coolify is a self-hosted platform that allows you to deploy and manage applications easily. It uses Docker under the hood.

Install [Coolify](https://www.coolify.io/). Consult the official [documentation](https://coolify.io/docs/get-started/installation#self-hosted-installation) for more details on installing the self-hosted version of Coolify.

Once installed, access the Coolify dashboard by navigating to `http://<your-server-ip>:8000` in your web browser. Follow the setup wizard to create an admin account.

At the end, you'll see the Coolify dashboard, which looks something like this:

<img width="1920" height="1080" alt="coolify-deployment-guide (16)" src="https://github.com/user-attachments/assets/6b3895d0-08bf-4e13-89d8-361de1df6d86" />

## 2. Application Deployment

### 2.1. Create a Project and Add a MySQL Resource

Create a new project (e.g., "Waktu Solat Project"), click the default environment (i.e., `Production`), and then click "Add Resource".

<!-- Add Add resource > MySQL. -->
<img width="1647" height="333" alt="coolify-deployment-guide (15)" src="https://github.com/user-attachments/assets/7698fb55-25c6-44a0-bf60-50769f455738" />

Click on **MySQL** under the "Databases" section. 

<img width="1618" height="444" alt="image" src="https://github.com/user-attachments/assets/ffc8c634-2844-4229-8998-23174d801948" />

A configuration panel will appear. You can modify the settings as needed or leave them as is. Then, click on **Start**.

<img width="1641" height="946" alt="image" src="https://github.com/user-attachments/assets/d04da626-6422-4488-842b-4e79187400ef" />

<img width="1649" height="888" alt="coolify-deployment-guide (12)" src="https://github.com/user-attachments/assets/8a91765d-38b0-4d0e-a297-f04b422af009" />

The message above indicates that the MySQL resource has been successfully created and is running. Note the container ID because we will need it later. In my case, it is `mwws08w8cw8k8cowsws08g8g`.  

### 2.2. Deploy the Application

Next, we will run the API Waktu Solat image. Go back to the Resources page, click on Resources > New, and select the **Docker Image** option.

<img width="1212" height="526" alt="coolify-deployment-guide (11)" src="https://github.com/user-attachments/assets/87fb72db-398d-402b-b670-da9111fe5dd7" />

<img width="1618" height="261" alt="image" src="https://github.com/user-attachments/assets/c6106431-004d-4181-9080-8eb07fc78d80" />

Visit this [page](https://github.com/mptwaktusolat/api-waktusolat-x/pkgs/container/api-waktusolat-x) to get the image artifact URL. Copy the URL of the latest version. E.g., `ghcr.io/mptwaktusolat/api-waktusolat-x:latest`. Paste the URL into the **Docker Image** field in Coolify. Then, click on **Save**.

<img width="1619" height="430" alt="coolify-deployment-guide (9)" src="https://github.com/user-attachments/assets/b2466976-9aac-414c-af0e-9f43c0307f89" />

You'll be directed to the Configuration page. Click on the **Environment Variables** tab. Copy the [`.env.example`](https://github.com/mptwaktusolat/api-waktusolat-x/blob/main/.env.example) from this repository. In the Environment Variables tab, switch to **Developer Mode** and paste the content of the `.env.example` file.

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
And then click on Save All Environment Variables. Go back to the Configuration tab.

<img width="1598" height="1078" alt="coolify-deployment-guide (7)" src="https://github.com/user-attachments/assets/012fc313-7a8f-4b0c-bd87-fb890e932bb1" />

In the **Port Exposes** section, set the value to `8080`. This is the port that NGINX will listen to proxy requests to the application. (Use `8443` for HTTPS)

<img width="1265" height="186" alt="coolify-deployment-guide (5)" src="https://github.com/user-attachments/assets/a367a712-ebdc-4d53-b8a7-b7ed180583b5" />

Now, click on the **Deploy** button at the top right corner. This will start the deployment process. Wait for the process to finish.

<img width="1634" height="700" alt="coolify-deployment-guide (6)" src="https://github.com/user-attachments/assets/221e0cc1-a94c-4fdf-b330-b81e70fc085f" />

The message above indicates that the deployment has completed successfully. Click on the Logs tab to see the application logs. If everything is set up correctly, you should see the application running without any errors.

<img width="1391" height="847" alt="coolify-deployment-guide (4)" src="https://github.com/user-attachments/assets/c8d931e4-e354-43e6-ad9f-f8519a6a1ea6" />

Go to the Configuration tab, and open the generated domain to see the live app.

<!-- Add screenshot of the app -->
<img width="1233" height="555" alt="coolify-deployment-guide (2)" src="https://github.com/user-attachments/assets/5104208e-6310-4f6f-a60f-8f2352a7acfd" />

Now, we can see our Laravel app (ignore the errors for now), which means our configuration is set up correctly and the reverse proxy server can reach our application. Now, we will have to set up the app itself.

<img width="1412" height="909" alt="coolify-deployment-guide (1)" src="https://github.com/user-attachments/assets/b1741dcf-2815-4d1a-942f-56e244f1889d" />

### 2.3. Set up the Application

Enter your **container terminal**. _Note: You can enter the terminal from the Coolify UI, but I prefer using my local terminal instead._

<details>
<summary>Using Coolify UI</summary>
Click on the Terminal tab.

<img width="1596" height="752" alt="Screenshot 2025-10-07 052754" src="https://github.com/user-attachments/assets/a1651679-9a58-4f4b-aba9-1837eb925ec9" />
</details>

<details>
<summary>Using local terminal</summary>
First, connect to the server using SSH. Then, find which container is our deployed application:

```bash
docker ps
```

I like to use [dops](https://github.com/Mikescher/better-docker-ps) instead of `docker ps`, just saying.

```bash
docker exec -it <container_id> /bin/sh
```
<img width="1235" height="628" alt="Screenshot 2025-10-07 052819" src="https://github.com/user-attachments/assets/d5bec4ac-acc8-41b8-bdb6-9e8b07deba51" />
</details>

First, install dependencies:

```bash
composer install
```

and the Node.js dependencies:

```bash
npm install
```

Show the application key:

```bash
php artisan key:generate --show
```

Copy the key shown and set it to the Environment Variables in Coolify.

```env
APP_KEY=base64:your_generated_key
```
While you are at it, set the `APP_URL` variable to your domain as well. This is needed to generate the OpenAPI documentation correctly later.

```env
APP_URL=https://api.waktusolat.app
```
<img width="1206" height="237" alt="Screenshot 2025-10-07 053648" src="https://github.com/user-attachments/assets/b60fccab-98d5-410f-984a-8760cdd0253b" />

<img width="1221" height="222" alt="Screenshot 2025-10-07 053705" src="https://github.com/user-attachments/assets/08df826d-f29b-4515-bc50-f8d325e6f9b1" />

Restart/redeploy the application from Coolify UI to ensure that the environment variables are up to date.

<img width="634" height="314" alt="image" src="https://github.com/user-attachments/assets/4c6358e9-1fdd-48db-9f63-811b57ce45ce" />

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
npm run build
```

To optimize the application, run the following command:

```bash
php artisan optimize
```

:tada: Now, your application should be ready. Visit your domain to see the application in action.

<img width="1684" height="967" alt="Screenshot 2025-10-07 054351" src="https://github.com/user-attachments/assets/03e73cbb-b363-4e4c-84ac-65769a9ceff0" />
<img width="1684" height="967" alt="Screenshot 2025-10-07 054401" src="https://github.com/user-attachments/assets/6d85b7b3-3775-4954-8885-92f4db768860" />


## 5. Post Deployment

### 5.1 Custom Domain

Until now, we have been using the default sslip.io domain provided by Coolify. To use our own domain, we need to configure the DNS settings of our domain.

Open up the DNS management page of your domain registrar. Create an A record pointing to the server IP address. Say I want to use `api-ksdfj.waktusolat.app`, create a A record as follows:

<img width="1281" height="429" alt="Screenshot 2025-10-08 030104" src="https://github.com/user-attachments/assets/a1c33b82-0bbd-49a1-9990-016bc6ff7029" />

Open Coolify dashboard > Configurations > Domains, add the new domain and Save. Then, click Redeploy. Coolify will automatically issue an SSL certificate for your domain.

<img width="1233" height="135" alt="image" src="https://github.com/user-attachments/assets/053402b3-2153-4494-9b6f-8b50e04ff06e" />

> [!NOTE]
> DNS propagation may take some time.

After a while, you should be able to access the application using your custom domain.

<img width="1403" height="918" alt="image" src="https://github.com/user-attachments/assets/419ef93d-f620-41db-84e5-d3b02f2a155b" />

### 5.2. Finalize Environment Settings

If the application is ready to be used, you can turn off the debug mode, and set the environment to `production` in the Environment Variables.

```env
APP_ENV=production
APP_DEBUG=false
```

Using Laravel Telescope/Laravel Nightwatch is not covered in this guide. You are free to explore and set it up on your own.

## 6. Caveats

1. New to setup [Auto Deploy](https://coolify.io/docs/applications/#auto-deploy) feature in Coolify so it can redeploy the application automatically when you push a new commit on Github.
2. Need to run migrations manually after each deployment 
(if any). 
3. Need to run `php artisan scribe:generate` manually after each deployment to regenerate the API documentation. (If needed)
4. I couldn't get the Swagger page to load the docs correctly. Because Laravel route helper return routes in `http` instead of `https`? So when fetching the page, the browser block the request (`no-referrer-when-downgrade`)


## 7. Conclusion

Alhamdulillah! You have successfully deployed the Waktu Solat API application in production using Coolify. :tada:

To update the application when new images are available, simply redeploy the application from the Coolify dashboard. Remember to run the necessary commands in the container terminal if there are any database migrations or other setup steps required.
