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

if (!defined('API_MAINFILE_INCLUDED')) {
    define('API_MAINFILE_INCLUDED', 1);

    // API Physical Paths

    // Physical path to the API documents (served) directory WITHOUT trailing slash
    define('API_ROOT_PATH', '');

    // For forward compatibility
    // Physical path to the API library directory WITHOUT trailing slash
    define('API_PATH', '');
    
    // Physical path to the API datafiles (writable) directory WITHOUT trailing slash
    define('API_VAR_PATH', '');
    
    // Alias of API_PATH, for compatibility, temporary solution
    define('API_TRUST_PATH', API_PATH);

    // URL Association for SSL and Protocol Compatibility
    $http = 'http://';
    if (!empty($_SERVER['HTTPS'])) {
        $http = ($_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
    }
    define('API_PROT', $http);

    // API Virtual Path (URL)
    // Virtual path to your main API directory WITHOUT trailing slash
    // Example: define("API_URL", "http://url_to_API_directory");
    define('API_URL', 'http://');
    
    // API Shortened URL Virtual Path
    // must contain [ %s ] for specification of subdomain field
    // Example: define("API_SHORT_URL", "http://%s.url_to_API_directory");
    define('API_SHORT_URL', 'http://');
    
    // API Geospatial Places URL Virtual Path
    // Download: http://github.com/Chronolabs-Cooperative/Places-API-PHP
    // Example: define("API_PLACES_URL", "http://places.example.com");
    define('API_PLACES_URL', 'http://');
    
    // API Geospatial Places URL Virtual Path
    // Download: http://github.com/Chronolabs-Cooperative/Lookups-API-PHP
    // Example: define("API_LOOKUPS_URL", "http://lookups.example.com");
    define('API_LOOKUPS_URL', 'http://');
    
    // API Internet IPv4, IPv6, TLD, gTLD Whois URL Virtual Path
    // Download: http://github.com/Chronolabs-Cooperative/WhoIS-API-PHP
    // Example: define("API_WHOIS_URL", "http://whois.example.com");
    define('API_WHOIS_URL', 'http://');
    
    // API Internet Strata URL Virtual Path
    // Download: http://github.com/Chronolabs-Cooperative/Strata-API-PHP
    // Example: define("API_STRATA_URL", "http://strata.example.com");
    define('API_STRATA_URL', 'http://');
    
    // API Cookie Domain to specify when creating cookies. May be blank (i.e. for IP address host),
    // full host from API_URL (i.e. www.example.com) or just the registered domain (i.e. example.com)
    // to share cookies across multiple subdomains (i.e. www.example.com and blog.example.com)
    define('API_COOKIE_DOMAIN', '');

    // Shall be handled later, don't forget!
    define('API_CHECK_PATH', 0);
    
    // Protect against external scripts execution if safe mode is not enabled
    if (API_CHECK_PATH && !@ini_get('safe_mode')) {
        if (function_exists('debug_backtrace')) {
            $APIScriptPath = debug_backtrace();
            if (!count($APIScriptPath)) {
                die('API path check: this file cannot be requested directly');
            }
            $APIScriptPath = $APIScriptPath[0]['file'];
        } else {
            $APIScriptPath = isset($_SERVER['PATH_TRANSLATED']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
        }
        if (DIRECTORY_SEPARATOR !== '/') {
            // IIS6 may double the \ chars
            $APIScriptPath = str_replace(strpos($APIScriptPath, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, '/', $APIScriptPath);
        }
        if (strcasecmp(substr($APIScriptPath, 0, strlen(API_ROOT_PATH)), str_replace(DIRECTORY_SEPARATOR, '/', API_ROOT_PATH))) {
            exit('API path check: Script is not inside API_ROOT_PATH and cannot run.');
        }
    }

    // Secure file
    require API_ROOT_PATH . '/include/dbconfig.php';

    if (!isset($APIOption['nocommon']) && API_ROOT_PATH != '') {
        include API_ROOT_PATH . '/include/common.php';
    }
}
