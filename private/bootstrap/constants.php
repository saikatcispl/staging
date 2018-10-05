<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__) . DS . 'public_html';
}

$_SERVER['DOCUMENT_ROOT'] = str_replace(['\\', '/'], DS, $_SERVER['DOCUMENT_ROOT']);

if (empty($_SERVER['REMOTE_ADDR'])) {
    $_SERVER['REMOTE_ADDR'] = '0.0.0.0';
}

if ((isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ||
        (isset($_SERVER['HTTP_CF_VISITOR']) && $_SERVER['HTTP_CF_VISITOR'] == '{"scheme":"https"}')) {
    $_SERVER['HTTPS'] = 'on';
}

if (!defined('PRODUCTION_DOMAIN')) {
    define('PRODUCTION_DOMAIN', 'member.localhost.com');
}

if (!defined('STAGING_DOMAIN')) {
    define('STAGING_DOMAIN', 'member.localhost.com');
}

if (!defined('LOCAL_DOMAIN')) {
    define('LOCAL_DOMAIN', 'member.localhost.com');
}

/* Detect environment based on which, some constants need to be set accordingly */
$check = '';

if (php_sapi_name() == 'cli') {
    $check = filter_var((isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT']), FILTER_SANITIZE_STRING);
} elseif (!empty($_SERVER['SERVER_NAME'])) {
    $check = filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_STRING);
}

if (strpos($check, LOCAL_DOMAIN) !== false) {
    define('ENVIRONMENT', 'local');
} elseif (strpos($check, STAGING_DOMAIN) !== false) {
    define('ENVIRONMENT', 'staging');
} else {
    define('ENVIRONMENT', 'production');
}

/* Utility Constants */
if (!defined('TIMEZONE')) {
    define('TIMEZONE', 'UTC');
    date_default_timezone_set(TIMEZONE);
}

/* Domain and Site URL */
if (!defined('PROTOCOL')) {
    define('PROTOCOL', (ENVIRONMENT == 'local' ? 'http:' : 'http:'));
}

if (!defined('SUBDOMAIN')) {
    define('SUBDOMAIN', (ENVIRONMENT == 'staging' ? 'staging.' : ''));
}

if (!defined('DOMAIN')) {
    define('DOMAIN', (ENVIRONMENT == 'local' ? 'member.localhost.com' : 'member.localhost.com'));
}

if (!defined('WEBSITE')) {
    define('WEBSITE', PROTOCOL . '//' . SUBDOMAIN . DOMAIN . '/');
}

if (!defined('SUBDIR')) {
    define('SUBDIR', '');
}

if (!defined('SITEURL')) {
    define('SITEURL', WEBSITE . (trim(SUBDIR) != '' ? SUBDIR . '/' : ''));
}

if (!defined('FILE_VERSION')) {
    define('FILE_VERSION', 1);
}

if (!defined('JS_PATH')) {
    define('JS_PATH', 'assets/js/');
}

if (!defined('VIEW_JS')) {
    define('VIEW_JS', SITEURL . 'assets/js/views/');
}

if (!defined('DB_DATE_FORMAT')) {
    define('DB_DATE_FORMAT', '%m/%d/%Y %h:%i %p');
}

if (!defined('ONLY_DATE_FORMAT')) {
    define('ONLY_DATE_FORMAT', 'm/d/Y');
}
if (!defined('DB_ONLY_DATE_FORMAT')) {
    define('DB_ONLY_DATE_FORMAT', '%m-%d-%Y');
}
if (!defined('CURR_DATE_TIME')) {
    define('CURR_DATE_TIME', date('Y-m-d H:i:s'));
}

if (!defined('MAX_FAILED_ATTEMPTS_ALLOWED')) {
    define('MAX_FAILED_ATTEMPTS_ALLOWED', 5); // in number of times  
}

if (!defined('LOCKED_ACCOUNT_TIME')) {
    define('LOCKED_ACCOUNT_TIME', 20); // in minutes
}

if (!defined('PASSWORD_RESET_REQUEST_TOKEN_EXPIRATION')) {
    define('PASSWORD_RESET_REQUEST_TOKEN_EXPIRATION', 24); // in hours
}

if (!defined('IMG_PATH')) {
    define('IMG_PATH', 'assets/images/');
}

if (!defined('IMG_URL')) {
    define('IMG_URL', SITEURL . IMG_PATH);
}