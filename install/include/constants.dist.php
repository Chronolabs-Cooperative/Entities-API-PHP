<?php
/**
 * API constants file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2016 API Project (www.api.org)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 */


/**
 *
 * @var string
 */
define('API_URL_CALLBACK', API_URL . '/v2/%s/callback.api');
define('API_POLINATING', (strpos(API_URL, 'localhost')?false:true));

/**
 * Resolution Paths
 *
 * @var string
 */
define('ENTITIES_RESOURCES_UNPACKING', API_PATH . DIRECTORY_SEPARATOR . 'unpacking');
define('ENTITIES_RESOURCES_MAPPING', API_PATH . DIRECTORY_SEPARATOR . 'mapping');
define('ENTITIES_RESOURCES_IMPORTING', API_PATH . DIRECTORY_SEPARATOR . 'importing');
define('ENTITIES_UPLOAD_PATH', API_VAR_PATH . DIRECTORY_SEPARATOR . 'entities-uploads-'.md5(date('Y-m-d')));
define('ENTITIES_CACHE', API_VAR_PATH . DIRECTORY_SEPARATOR . 'entities-cache-'.md5(date('Y-m')));

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
