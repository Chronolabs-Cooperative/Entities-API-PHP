<?php
/**
 * Chronolabs Cooperative Entitisms Repository Services REST API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         entities-api
 * @since           2.2.1
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         2.2.8
 * @description		A REST API for the storage and management of entities + persons + beingness collaterated!
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 */


/**
 *
 * @var string
 */
define('API_URL_CALLBACK', API_URL . '/v2/%s/callback.api');
define('API_POLINATING', (strpos(API_URL, 'localhost')?false:true));
define('API_GRAVATAR_PIXELS', 256);

/**
 * Resolution Paths
 *
 * @var string
 */
define('ENTITIES_RESOURCES_UNPACKING', API_PATH . DIRECTORY_SEPARATOR . 'unpacking');
define('ENTITIES_RESOURCES_MAPPING', API_PATH . DIRECTORY_SEPARATOR . 'mapping');
define('ENTITIES_RESOURCES_IMPORTING', API_PATH . DIRECTORY_SEPARATOR . 'importing');
define('ENTITIES_UPLOAD_PATH', API_VAR_PATH . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'uploads');
define('ENTITIES_CACHE', API_VAR_PATH . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'cache');

if (!is_dir(ENTITIES_RESOURCES_UNPACKING))
    mkdirSecure(ENTITIES_RESOURCES_UNPACKING, 0777, true);
if (!is_dir(ENTITIES_RESOURCES_MAPPING))
    mkdirSecure(ENTITIES_RESOURCES_MAPPING, 0777, true);
if (!is_dir(ENTITIES_RESOURCES_IMPORTING))
    mkdirSecure(ENTITIES_RESOURCES_IMPORTING, 0777, true);
if (!is_dir(ENTITIES_UPLOAD_PATH))
    mkdirSecure(ENTITIES_UPLOAD_PATH, 0777, true);
if (!is_dir(ENTITIES_CACHE))
    mkdirSecure(ENTITIES_CACHE, 0777, true);
    

// Email Services
define('API_IMAP_IMAP', '');
define('API_IMAP_SMTP', '');
define('API_IMAP_IMAPPORT', '');
define('API_IMAP_SMTPPORT', '');
define('API_IMAP_CATCHALL', '');
define('API_IMAP_USERNAME', '');
define('API_IMAP_PASSWORD', '');

// Social Network Keys
define('API_TWITTER_CONSUMER_KEY', '');
define('API_TWITTER_CONSUMER_SECRET', '');
define('API_TWITTER_ACCESS_TOKEN_KEY', '');
define('API_TWITTER_ACCESS_TOKEN_SECRET', '');
define('API_FACEBOOK_APP_ID', '');
define('API_FACEBOOK_APP_SECRET', '');
define('API_FACEBOOK_APP_CLIENT_TOKEN', '');
define('API_LINKEDIN_CLIENT_ID', '');
define('API_LINKEDIN_CLIENT_SECRET', '');

// REST API URLs
define('API_URLS_STRATA_URL', '');
define('API_URLS_LOOKUPS_URL', '');
define('API_URLS_WHOIS_URL', '');
define('API_URLS_PLACES_URL', '');


?>