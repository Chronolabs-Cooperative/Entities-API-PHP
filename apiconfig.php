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

if (!is_file(__DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php') || !is_file(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'license.php'))
{
    if (strpos($_SERVER["REQUEST_URI"], 'install/')>0)
        return false;
    header('Location: ' . "./install");
    exit(0);
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'constants.php';


/**
 * Opens Access Origin Via networking Route NPN
 */
header('Access-Control-Allow-Origin: *');
header('Origin: *');

/**
 * Turns of GZ Lib Compression for Document Incompatibility
 */
ini_set("zlib.output_compression", 'Off');
ini_set("zlib.output_compression_level", -1);

?>
