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
 * YOU SHOULD NEVER USE THE FOLLOWING TO CONSTANTS, THEY WILL BE REMOVED
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('NWLINE') or define('NWLINE', "\n");

/**
 * Include files with definitions
 */
@include_once dirname(__DIR__) . DS . 'mainfile.php';
@include_once __DIR__ . DS . 'functions.php';
@include_once __DIR__ . DS . 'version.php';
@include_once __DIR__ . DS . 'license.php';
@include_once __DIR__ . DS . 'constants.php';

/**
 * Include APILoad
 */
require_once API_ROOT_PATH . DS . 'class' . DS . 'apiload.php';
require_once API_ROOT_PATH . DS . 'class' . DS . 'preload.php';

/**
 * Create Instance of apiSecurity Object and check Supergolbals
 */
APILoad::load('apisecurity');
$apiSecurity = new APISecurity();
$apiSecurity->checkSuperglobals();

/**
 * Create Instantance APILogger Object
 */
APILoad::load('apilogger');
$apiLogger       = APILogger::getInstance();
$apiErrorHandler = APILogger::getInstance();
$apiLogger->startTime();
$apiLogger->startTime('XOOPS Boot');

/**
 * Include Required Files
 */
include_once API_ROOT_PATH . DS . 'class' . DS . 'criteria.php';
include_once API_ROOT_PATH . DS . 'class' . DS . 'module.textsanitizer.php';
include_once API_ROOT_PATH . DS . 'include' . DS . 'functions.php';
/**
 * Get database for making it global
 * Requires APILogger, API_DB_PROXY;
 */
include_once API_ROOT_PATH . DS . 'include' . DS . 'dbconfig.php';
include_once API_ROOT_PATH . DS . 'class' . DS . 'database' . DS . 'databasefactory.php';
if (class_exists('APIDatabaseFactory'))
    $GLOBALS['APIDB'] = APIDatabaseFactory::getDatabaseConnection();
