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

if (!defined('API_INSTALL')) {
    die('API Custom Installation die');
}

$configs = array();

// setup config site info
$configs['db_types'] = array('mysql' => 'mysqli');

// setup config site info
$configs['conf_names'] = array(
);

// languages config files
$configs['language_files'] = array(
    'global');

// extension_loaded
$configs['extensions'] = array(
    'mbstring' => array('MBString', sprintf(PHP_EXTENSION, CHAR_ENCODING)),
    'xml'      => array('XML', sprintf(PHP_EXTENSION, XML_PARSING)),
    'curl'     => array('Curl', sprintf(PHP_EXTENSION, CURL_HTTP)),
);

// Writable files and directories
$configs['writable'] = array(
    'uploads/',
    'data/',
    'include/',
    'mainfile.php',
    'include/license.php',
    'include/dbconfig.php',
    'include/constants.php',
    );

$configs['imap'] = array(
    'imap' => 'imap.'.parse_url($_SESSION['settings']['URL'], PHP_URL_HOST),
    'smtp' => 'smtp.'.parse_url($_SESSION['settings']['URL'], PHP_URL_HOST),
    'imapport' => '143',
    'smtpport' => '25',
    'catchall' => 'catchall@'.parse_url($_SESSION['settings']['URL'], PHP_URL_HOST),
    'username' => 'catchall@'.parse_url($_SESSION['settings']['URL'], PHP_URL_HOST),
    'password'  => ''
);

$configs['twitter'] = array(
    'consumer_key' => '',
    'consumer_secret' => '',
    'access_token_key' => '',
    'access_token_secret' => ''
);

$configs['facebook'] = array(
    'app_id' => '',
    'app_secret' => '',
    'app_client_token' => ''
);

$configs['linkedin'] = array(
    'client_id' => '',
    'client_secret' => ''
);

// Modules to be installed by default
$configs['modules'] = array();

// api_lib, api_tmp directories
$configs['apiPathDefault'] = array(
    'lib'  => 'data');

// writable api_lib, api_tmp directories
$configs['tmpPath'] = array(
    'caches'  => __DIR__ . '/caches',
    'includes' => __DIR__ . '/include',
    'tmp'    => '/tmp');
