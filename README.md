# ServerSide Google Tag Manager Proxy in PHP

## Goal 

- Place this proxy script on your webserver to be able to use your website's domain as `server_container_url` in GTM.
- You can also use your website's domain as 'Server container URLs' on the Container Settings so you can preview requests without cross-domain cookie problems.

## Requirements

- PHP 7.4 or greater
- Composer 2
- Apache 2.4 or greater

If you are not running Apache, you will need to manually implement the contents of the `.htaccess` in your webserver.

## Installation

### 0. Prerequisites
- You need to run the 2 [GTM Docker containers](https://developers.google.com/tag-platform/tag-manager/server-side/manual-setup-guide) on your own server
  - one for the GTM Server 
  - one for the GTM Preview Server
- These Docker containers should have a public available HTTPS proxy in front, with a public available URL with a working certificate. 
  - In the examples we use `https://gtm-docker-server.domain.com/prefix` as the URL of this proxy.

- URL mappings in the proxy server should be like this:

| Incomming URL     | Edit Path       | Pass to Container  | Container Port |
|-------------------|-----------------|--------------------|----------------|
| /prefix/gtm/*     | Strip /prefix   | GTM Preview Server | 8080           |
| /prefix/g/*       | Strip /prefix   | GTM Server         | 8080           |
| /prefix/g/gtm.js  | Strip /prefix/g | GTM Server         | 8080           |
| /prefix/g/ns.html | Strip /prefix/g | GTM Server         | 8080           |
| /prefix/gtag/*    | Strip /prefix   | GTM Server         | 8080           |

- Test the installation:
  - https://gtm-docker-server.domain.com/prefix/gtm/healthy
  - https://gtm-docker-server.domain.com/prefix/g/healthy
  - https://gtm-docker-server.domain.com/prefix/gtag/healthy

- These URLs will return a 400 error until you complete step 6
  - https://gtm-docker-server.domain.com/prefix/g/gtm.js
  - https://gtm-docker-server.domain.com/prefix/g/ns.html
  - these URLs also need the correct `?id=GTM-[WEBCONTAINER]` parameter to work

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

- Visit https://yourwebsite.com/gtm/healthy, this should also show `ok`
- Visit https://yourwebsite.com/g/healthy, this should show `ok`
- Visit https://yourwebsite.com/gtag/healthy, this should show `ok`

### 4. Configure GTM
I assume you already know how Server Side GTM works.
- On your GTM Web Container a Tag of type 'Google Tag' must exist, containing your [Analytics Tag ID](https://support.google.com/tagmanager/answer/12002338#find-tag-id)
  - On this Tag set the configuration parameter `server_container_url` to your website URL, for example `https://yourwebsite.com`
  - This tag should have a trigger on 'Initialisation - All Pages'
  - Submit/Publish the Web Container
- On your GTM Server Container go to 'Admin' in the top menu, then to 'Container Settings'
  - In the 'Server container URLs' field add your website URL, for example `https://yourwebsite.com` as the only item
  - Submit/Publish the Server Container

### 5. Test
- If you open PREVIEW on the GTM Server Container, leave this tab open
- Visit your website in the same browser on another tab, you should see the request on the preview page.
- Check your Google Analytics Realtime report to see if the hits are coming in.

### 6. Serve GTM JavaScript from your own domain
To prevent any cross domain traffic you can [serve the GTM JavaScript from your own domain](https://developers.google.com/tag-platform/tag-manager/server-side/dependency-serving?tag=gtm). 

- On your GTM Server Container add the ['Google Tag Manager: Web Container'](https://developers.google.com/tag-platform/tag-manager/server-side/dependency-serving?tag=gtm) Client
  - Allow to serve `gtm.js` for the ID of your GTM Web Container
  - Only enable Region Specific settings if either:
    - Your website is behind [one of the proxies](https://developers.google.com/tag-platform/tag-manager/server-side/enable-region-specific-settings#step_1_set_up_the_request_header_)
      that provide region information like Cloudflare.
    - You have [manually provided](https://developers.google.com/tag-platform/tag-manager/server-side/enable-region-specific-settings#custom-headers) the geo location headers
  - Publish the Server Container
- Publish the GTM Server Container
- Test the installation: https://gtm-docker-server.domain.com/prefix/gtm.js?id=GTM-[WEBCONTAINER] this should display the GTM JavaScript.
- Test the proxy: https://yourwebsite.com/g/gtm.js?id=GTM-[WEBCONTAINER]
- On your website where GTM is loaded, you should replace:
  - `https://www.googletagmanager.com/gtm.js` by `https://yourwebsite.com/g/gtm.js`
  - `https://www.googletagmanager.com/ns.html` by `https://yourwebsite.com/g/ns.html`

------
- Created by [DeoVero BV](https://deovero.com) / [Jeroen Vermeulen](https://www.linkedin.com/in/jeroenvermeuleneu/)
- Thanks to [@jenssegers](https://www.linkedin.com/in/jenssegers/) for his excellent [PHP Proxy](https://github.com/jenssegers/php-proxy) script which does the heavy lifting.
