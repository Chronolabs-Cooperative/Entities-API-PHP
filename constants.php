<?php
/**
 * Chronolabs Fonting Repository Services REST API API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         fonts
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Fonting Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */

	/**
	 *
	 * @var string
	 */
	define('API_URL', 'http://entities.localhost');
	define('API_URL_SHORT', 'http://%s.entities.localhost');
	define('API_URL_CALLBACK', 'http://entities.localhost/v2/%s/callback.api');
	define('API_POLINATING', (strpos(API_URL, 'localhost')?false:true));
	
	/**
	 * 
	 * @var string
	 */
	define('ENTITIES_RESOURCES_UNPACKING', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'entities-data' . DIRECTORY_SEPARATOR . 'unpacking');
	define('ENTITIES_RESOURCES_MAPPING', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'entities-data' . DIRECTORY_SEPARATOR . 'mapping');
	define('ENTITIES_RESOURCES_IMPORTING', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'entities-data' . DIRECTORY_SEPARATOR . 'importing');
	define('ENTITIES_UPLOAD_PATH', '/tmp/Entities-Uploads');
	define('ENTITIES_CACHE', '/tmp/Entities-Cache');
	
	/******* DO NOT CHANGE THIS VARIABLE ****
	 * @var string
	 */
	define('API_VERSION', '2.1.6');
	define('API_ROOT_NODE', 'http://entities.labs.coop');
	
?>