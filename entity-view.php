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
	
	global $version, $output, $name, $clause, $callback, $mode, $state;
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
	$help=false;
	if (isset($_GET['output']) || !empty($_GET['output']) ) {
		$version = isset($_GET['version'])?(string)$_GET['version']:'v2';
		$output = isset($_GET['output'])?(string)$_GET['output']:'';
		$name = isset($_GET['name'])?(string)$_GET['name']:'';
		$clause = isset($_GET['clause'])?(string)$_GET['clause']:'';
		$callback = isset($_REQUEST['callback'])?(string)$_REQUEST['callback']:'';
		$mode = isset($_GET['mode'])?(string)$_GET['mode']:'';
		$state = isset($_GET['state'])?(string)$_GET['state']:'';
		switch($output)
		{
			default:
			case "html":
				if (in_array($mode, array('entity')) && !in_array($_SERVER["REQUEST_METHOD"], array('POST', 'post')))
					$help=true;
				break;	
		}
	} else {
		header("Location: " . API_URL);
		exit(0);
	}
	
	if ($help==false) {
		
		
		switch($_POST['op'])
		{
			
			case "password":
				if (!empty($_POST['password']))
				{
					if ($_POST['request'] == 'Yes') {
						setNewPassword($clause, 'view');
					}
					$sql = sprintf("SELECT md5(concat(`entity-id`, `view-password`, '%s', '%s')) as `clause` FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `view-password` = md5('%s') AND `entity-id` LIKE '%s' AND `view-protected` = 'Yes'", $GLOBALS['peerid'], date("Y-M-W"), $_POST['password'], $clause);
					if ($results = $GLOBALS['APIDB']->queryF($sql))
						if ($entity = $GLOBALS['APIDB']->fetchArray($results))
						{
							header("Location: " . API_URL . '/v2/' . $entity['clause'] . '/view.api');
							exit(0);
						}
				} elseif ($_POST['request'] == 'Yes') {
					setNewPassword($clause, 'view');
					if (function_exists('http_response_code'))
						http_response_code(301);
					header("Location: " . API_URL);
					exit(0);
				}
				
			case "entity":
				$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE md5(concat(`maps-id`, `import-id`)) LIKE '%s'",$clause);
				if (!$results = $GLOBALS['APIDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				if (!$import = $GLOBALS['APIDB']->fetchArray($results))
					die('Recordset Failed: ' . $sql);
				
				$categories = array();
				$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` WHERE `maps-id` LIKE '".$import['maps-id']."' AND `code` IN ('" . implode("', '", array_keys($_POST['category'])) . "') ORDER BY `code` ASC";
				if (!$results = $GLOBALS['APIDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				while ($category = $GLOBALS['APIDB']->fetchArray($results))
				{
					if ($_POST['category'][$category['code']]=='--user--')
					{
						$create = false;
						$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories') . "` where `category` LIKE '" . ($catstr = ucwords($_POST['user'][$category['code']])) . "'";
						if ($results = $GLOBALS['APIDB']->queryF($sql))
							if ($cat = $GLOBALS['APIDB']->fetchArray($results))
							{
								$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` SET `category-id` = '".$cat['category-id'] . "' WHERE `category-code-id` = '" . $cat['category-code-id'] . "'";
								if (!$GLOBALS['APIDB']->queryF($sql))
									die('SQL Failed: ' . $sql);
							} else {
								$create = true;
							}
						else 
							$create = true;
					
						if ($create==true)
						{
							$categoryid = md5(microtime(true).$catstr);
							$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('categories') . "` (`category-id`, `category`) VALUES('$categoryid', '".mysql_escape_string($catstr)."')";
							if (!$GLOBALS['APIDB']->queryF($sql))
								die('SQL Failed: ' . $sql);
							$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` (`category-id`, `maps-id`, `code`) VALUES('$categoryid', '".$import['maps-id']."','".mysql_escape_string($category['code'])."')";
							if (!$GLOBALS['APIDB']->queryF($sql))
								die('SQL Failed: ' . $sql);
									
						}
					} else {
						$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` SET `category-id` = '" . $_POST['category'][$category['code']] . "' WHERE `category-id` = '" . $category['category-id'] . "'";
						if (!$GLOBALS['APIDB']->queryF($sql))
							die('SQL Failed: ' . $sql);
					}
					unset($_POST['category'][$category['code']]);
					unset($_POST['user'][$category['code']]);
				}
				if (count($_POST['category'])>0)
				{
					foreach(array_keys($_POST['category']) as $code)
					{
						if ($_POST['category'][$code]=='--user--')
						{
							$create = false;
							$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories') . "` where `category` LIKE '" . $catstr = ucwords($_POST['user'][$category['code']]) . "'";
							if ($results = $GLOBALS['APIDB']->queryF($sql))
								if ($cat = $GLOBALS['APIDB']->fetchArray($results))
								{
									$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` SET `category-id` = '".$cat['category-id'] . "' WHERE `category-code-id` = '" . $cat['category-code-id'] . "'";
									if (!$GLOBALS['APIDB']->queryF($sql))
										die('SQL Failed: ' . $sql);
								} else {
									$create = true;
								}
								else
									$create = true;
										
									if ($create==true)
									{
										$categoryid = md5(microtime(true).$catstr);
										$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('categories') . "` (`category-id`, `category`) VALUES('$categoryid', '".mysql_escape_string($catstr)."')";
										if (!$GLOBALS['APIDB']->queryF($sql))
											die('SQL Failed: ' . $sql);
										$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` (`category-id`, `maps-id`, `code`) VALUES('$categoryid', '".$import['maps-id']."','".mysql_escape_string($code)."')";
										if (!$GLOBALS['APIDB']->queryF($sql))
											die('SQL Failed: ' . $sql);
													
									}
						} else {
							$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` (`category-id`, `maps-id`, `code`) VALUES('".$_POST['category'][$code]."', '".$import['maps-id']."','".mysql_escape_string($code)."')";
							if (!$GLOBALS['APIDB']->queryF($sql))
								die('SQL Failed: ' . $sql);
						}	
					}
				}
				$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` SET `state` = 'Defined' WHERE `maps-id` = '" . $import['maps-id'] . "'";
				if (!$GLOBALS['APIDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				header("Location: " . API_URL);
				exit(0);
			break;
		}
	}
	
	
	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE md5(concat(`entity-id`, `view-password`, '%s', '%s')) LIKE '%s' AND `view-protected` = 'Yes'", $GLOBALS['peerid'], date("Y-M-W"), $clause);
	if ($results = $GLOBALS['APIDB']->queryF($sql))
		if ($entity = $GLOBALS['APIDB']->fetchArray($results))
			$clause = $entity['entity-id'];
	else {
		$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `entity-id` LIKE '%s' AND `view-protected` = 'Yes'", $clause);
		if ($results = $GLOBALS['APIDB']->queryF($sql))
			if ($entity = $GLOBALS['APIDB']->fetchArray($results))
			{
				if (function_exists('http_response_code'))
					http_response_code(400);
				include dirname(__FILE__).'/entity-view-password.php';
				exit(0);
			}
	}
		
	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `entity-id` LIKE '%s'", $clause);
	if ($results = $GLOBALS['APIDB']->queryF($sql))
		if ($entity = $GLOBALS['APIDB']->fetchArray($results))
		{
			if (function_exists('http_response_code'))
				http_response_code(400);
			include dirname(__FILE__).'/entity-view-form.php';
			exit;
		}
	
	if (function_exists('http_response_code'))
		http_response_code(301);
	header("Location: " . API_URL);
?>		
