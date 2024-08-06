# ServerSide Google Tag Manager Proxy

## Goal 

- Place this proxy script on your webserver to be able to use your website's domain as `server_container_url` in GTM.
- You can also use your website's domain as 'Server container URLs' on the Container Settings so you can preview requests without cross-domain cookie problems.

## Requirements

- PHP, did not test minimum version yet.
- Composer 2
- Apache 2.4 or greater

If you are not running Apache, you will need to implement the contents of the `.htaccess` in your webserver.

## Installation

### 0. Prerequisites
You need to run the 2 [GTM Docker containers](https://developers.google.com/tag-platform/tag-manager/server-side/manual-setup-guide) on your own server, one for the GTM Server and one for the GTM Preview Server.

URL mappings should be like this:

| Incomming URL     | Edit Path       | Pass to Container  |
|-------------------|-----------------|--------------------|
| /prefix/gtm/*     | Strip /prefix   | GTM Preview Server |
| /prefix/g/*       | Strip /prefix   | GTM Server         |
| /prefix/g/gtm.js  | Strip /prefix/g | GTM Server         |
| /prefix/g/ns.html | Strip /prefix/g | GTM Server         |
| /prefix/gtag/*    | Strip /prefix   | GTM Server         |

### 1. Install
Clone this repository, and execute the installation steps:
```
cd ~/httpdocs
git clone https://github.com/deovero/ss-gtm-proxy gtm
ln -s gtm g
ln -s gtm gtag
cd gtm
cp config.example.php config.php
composer install
```

### 2. Update config.php
- Update `~/httpdocs/gtm/config.php` with the path to your GTM server, for example `https://gtm-docker-server.domain.com/prefix`

### 3. Verify
- Visit https://yourwebsite.com/g/healthy, this should show `ok`
- Visit https://yourwebsite.com/gtm/healthy, this should also show `ok`

### 4. Configure GTM
I assume you already know how Server Side GTM works.
- On your Frontend GTM Container (Web) update the `server_container_url` parameter of your 'Google Tag' to your website URL, for example `https://yourwebsite.com`
- On your Backend GTM Container (Server) go to 'Admin' in the top menu, then to 'Container Settings' and set the 'Server container URLs' field also to your website URL, for example `https://yourwebsite.com`
- Publish both containers

### 5. Test
- If you open PREVIEW on the Backend GTM Container (Server) and you visit your website in the same browser, you should see the request on the preview screen.
- Check your Google Analytics Realtime report to see if the hits are coming in.

### 6. Bonus: Serve GTM JavaScript from your own domain
On your website where GTM is loaded, you can replace `https://www.googletagmanager.com/gtm.js` by `https://yourwebsite.com/gtm/gtm.js` to serve the GTM JavaScript from your own domain.

------
- Created by [DeoVero BV](https://deovero.com) / [Jeroen Vermeulen](https://www.linkedin.com/in/jeroenvermeuleneu/)
- Thanks to [@jenssegers](https://www.linkedin.com/in/jenssegers/) for his excellent [PHP Proxy](https://github.com/jenssegers/php-proxy) script which does the heavy lifting.
