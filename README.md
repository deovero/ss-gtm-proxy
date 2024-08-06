# ServerSide Google Tag Manager Proxy in PHP

## Goal 

- Place this proxy script on your webserver to be able to use your website's domain as `server_container_url` in GTM.
- You can also use your website's domain as 'Server container URLs' on the Container Settings so you can preview requests without cross-domain cookie problems.

## Requirements

- PHP, did not test minimum version yet.
- Composer 2
- Apache 2.4 or greater

If you are not running Apache, you will need to implement the contents of the `.htaccess` in your webserver.

## Installation

### 1. Install
Clone this repository, and execute the installation steps:
```
cd ~/httpdocs
git clone https://github.com/deovero/ss-gtm-proxy gtm
ln -s gtm g
cd gtm
cp config.example.php config.php
composer install
```

### 2. Update config.php
- Update `~/httpdocs/gtm/config.php` with the path to your GTM server.
- The GTM server can contain a path, for example `https://gtmserver.com/some/site` as long as you remove the `/some/site` part before forwarding the request to the GTM Docker container.

### 3. Configure GTM
I assume you already know how Server Side GTM works.
- On your Frontend GTM Container (Web) update the `server_container_url` parameter of your 'Google Tag' to your website URL, for example `https://yourwebsite.com`
- On your Backend GTM Container (Server) go to 'Admin' in the top menu, then to 'Container Settings' and set the 'Server container URLs' field also to your website URL, for example `https://yourwebsite.com`

------
- Created by [DeoVero BV](https://deovero.com) / [Jeroen Vermeulen](https://www.linkedin.com/in/jeroenvermeuleneu/)
- Thanks to [@jenssegers](https://www.linkedin.com/in/jenssegers/) for his excellent [PHP Proxy](https://github.com/jenssegers/php-proxy) script which does the heavy lifting.
