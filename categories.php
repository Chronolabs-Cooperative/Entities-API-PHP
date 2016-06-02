<?php
/**
 * Chronolabs Entitiesing Repository Services REST API API
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
 * @package         entities
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Entitiesing Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
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
				if (in_array($mode, array('categories')) && !in_array($_SERVER["REQUEST_METHOD"], array('POST', 'post')))
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
			case "categories":
				$sql = sprintf("SELECT * FROM `imports` WHERE md5(concat(`maps-id`, `import-id`)) LIKE '%s'",$clause);
				if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				if (!$import = $GLOBALS['EntitiesDB']->fetchArray($results))
					die('Recordset Failed: ' . $sql);
				
				$categories = array();
				$sql = "SELECT * FROM `categories_codes` WHERE `maps-id` LIKE '".$import['maps-id']."' AND `code` IN ('" . implode("', '", array_keys($_POST['category'])) . "') ORDER BY `code` ASC";
				if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				while ($category = $GLOBALS['EntitiesDB']->fetchArray($results))
				{
					if ($_POST['category'][$category['code']]=='--user--')
					{
						$create = false;
						$sql = "SELECT * FROM `categories` where `category` LIKE '" . ($catstr = ucwords($_POST['user'][$category['code']])) . "'";
						if ($results = $GLOBALS['EntitiesDB']->queryF($sql))
							if ($cat = $GLOBALS['EntitiesDB']->fetchArray($results))
							{
								$sql = "UPDATE `categories_codes` SET `category-id` = '".$cat['category-id'] . "' WHERE `category-code-id` = '" . $cat['category-code-id'] . "'";
								if (!$GLOBALS['EntitiesDB']->queryF($sql))
									die('SQL Failed: ' . $sql);
							} else {
								$create = true;
							}
						else 
							$create = true;
					
						if ($create==true)
						{
							$categoryid = md5(microtime(true).$catstr.$GLOBALS['peerid']);
							$sql = "INSERT INTO `categories` (`category-id`, `category`) VALUES('$categoryid', '".mysql_escape_string($catstr)."')";
							if (!$GLOBALS['EntitiesDB']->queryF($sql))
								die('SQL Failed: ' . $sql);
							$sql = "INSERT INTO `categories_codes` (`category-id`, `maps-id`, `code`) VALUES('$categoryid', '".$import['maps-id']."','".mysql_escape_string($category['code'])."')";
							if (!$GLOBALS['EntitiesDB']->queryF($sql))
								die('SQL Failed: ' . $sql);

							$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
							if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
							{
								while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
								{
									@getURIData(sprintf($other['callback'], 'mapping-category'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'category-id'=> $categoryid, 'category' => $catstr));
									@getURIData(sprintf($other['callback'], 'mapping-category-code'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'category-id'=> $categoryid, 'maps-id'=> $import['maps-id'], 'code' => $code));
								}
							}	
						}
					} else {
						$sql = "UPDATE `categories_codes` SET `category-id` = '" . $_POST['category'][$category['code']] . "' WHERE `category-id` = '" . $category['category-id'] . "'";
						if (!$GLOBALS['EntitiesDB']->queryF($sql))
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
							$sql = "SELECT * FROM `categories` where `category` LIKE '" . $catstr = ucwords($_POST['user'][$category['code']]) . "'";
							if ($results = $GLOBALS['EntitiesDB']->queryF($sql))
								if ($cat = $GLOBALS['EntitiesDB']->fetchArray($results))
								{
									$sql = "UPDATE `categories_codes` SET `category-id` = '".$cat['category-id'] . "' WHERE `category-code-id` = '" . $cat['category-code-id'] . "'";
									if (!$GLOBALS['EntitiesDB']->queryF($sql))
										die('SQL Failed: ' . $sql);
								} else {
									$create = true;
								}
								else
									$create = true;
										
									if ($create==true)
									{
										$categoryid = md5(microtime(true).$catstr.$GLOBALS['peerid']);
										$sql = "INSERT INTO `categories` (`category-id`, `category`) VALUES('$categoryid', '".mysql_escape_string($catstr)."')";
										if (!$GLOBALS['EntitiesDB']->queryF($sql))
											die('SQL Failed: ' . $sql);
										$sql = "INSERT INTO `categories_codes` (`category-id`, `maps-id`, `code`) VALUES('$categoryid', '".$import['maps-id']."','".mysql_escape_string($code)."')";
										if (!$GLOBALS['EntitiesDB']->queryF($sql))
											die('SQL Failed: ' . $sql);
										
										$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
										if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
										{
											while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
											{
												@getURIData(sprintf($other['callback'], 'mapping-category'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'category-id'=> $categoryid, 'category' => $catstr));
												@getURIData(sprintf($other['callback'], 'mapping-category-code'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'category-id'=> $categoryid, 'maps-id'=> $import['maps-id'], 'code' => $code));
											}
										}
													
									}
						} else {
							$sql = "INSERT INTO `categories_codes` (`category-id`, `maps-id`, `code`) VALUES('".$_POST['category'][$code]."', '".$import['maps-id']."','".mysql_escape_string($code)."')";
							if (!$GLOBALS['EntitiesDB']->queryF($sql))
								die('SQL Failed: ' . $sql);
							$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
							if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
							{
								while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
								{
									@getURIData(sprintf($other['callback'], 'mapping-category-code'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'category-id'=> $_POST['category'][$code], 'maps-id'=> $import['maps-id'], 'code' => $code));
								}
							}
						}	
					}
				}
				$sql = "UPDATE `imports_maps` SET `state` = 'Defined' WHERE `maps-id` = '" . $import['maps-id'] . "'";
				if (!$GLOBALS['EntitiesDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				header("Location: " . API_URL);
				exit(0);
			break;
		}
	}
	
	if (function_exists('http_response_code'))
		http_response_code(400);
	include dirname(__FILE__).'/categorier.php';
	exit;
?>		