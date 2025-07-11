<?php

$siteDomain = $_SERVER['HTTP_HOST'] ?? '';
$siteDomain = preg_replace('/^www\./', '', $siteDomain);

switch ($siteDomain) {
    case 'domain-one.com':
        return [
            'gtm_url' => 'https://gtm-docker-server.domain.com/prefix-one'
        ];
    case 'domain-two.com':
        return [
            'gtm_url' => 'https://gtm-docker-server.domain.com/prefix-two'
        ];
    default:
        http_response_code(404);
        printf("ERROR: Invalid domain '%s'", $siteDomain);
        exit();
}
