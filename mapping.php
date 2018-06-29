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
				if (in_array($mode, array('mapping')) && !in_array($_SERVER["REQUEST_METHOD"], array('POST', 'post')))
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
			case "mapping":
				$category = false;
				$sql = sprintf("SELECT * FROM `imports` WHERE md5(concat(`maps-id`, `import-id`)) LIKE '%s'",$clause);
				if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				if (!$import = $GLOBALS['EntitiesDB']->fetchArray($results))
					die('Recordset Failed: ' . $sql);
				$sql = "SELECT * FROM `imports_maps` WHERE `maps-id` LIKE '".$import['maps-id']."'";
				if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				if (!$maps = $GLOBALS['EntitiesDB']->fetchArray($results))
					die('Recordset Failed: ' . $sql);
					$columns = array();
				$sql = "SELECT * FROM `imports_columns` WHERE `maps-id` LIKE '".$import['maps-id']."' ORDER BY `position` ASC";
				if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
					die('SQL Failed: ' . $sql);
				while ($column = $GLOBALS['EntitiesDB']->fetchArray($results))
				{
					$sql = "UPDATE `imports_columns` SET `field` = '" . $_POST['field'][$column['column-id']] . "', `type` = '" . $_POST['type'][$column['column-id']] . "' WHERE `column-id` = '" . $column['column-id'] . "'";
					if (!$GLOBALS['EntitiesDB']->queryF($sql))
						die('SQL Failed: ' . $sql);
					if ($_POST['field'][$column['column-id']] == "Category")
						$category = true;

					$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
					if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
					{
						while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
						{
							@getURIData(sprintf($other['callback'], 'mapping-columns-update'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'field'=> $_POST['field'][$column['column-id']], 'type' => $_POST['type'][$column['column-id']], 'column-id' => $column['column-id']));
						}
					}
				}
				if ($category==false)
				{
					$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
					if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
					{
						while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
						{
							@getURIData(sprintf($other['callback'], 'mapping-maps-update'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'title'=> mysql_escape_string($_POST['title']), 'state' => 'Defined', 'maps-id' => $maps['maps-id']));
						}
					}
						
					$sql = "UPDATE `imports_maps` SET `title` = '" . mysql_escape_string($_POST['title']) . "', `state` = 'Defined' WHERE `maps-id` = '" . $maps['maps-id'] . "'";
					if (!$GLOBALS['EntitiesDB']->queryF($sql))
						die('SQL Failed: ' . $sql);
				} else {
					$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
					if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
					{
						while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
						{
							@getURIData(sprintf($other['callback'], 'mapping-maps-update'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'title'=> mysql_escape_string($_POST['title']), 'state' => 'Waiting', 'maps-id' => $maps['maps-id']));
						}
					}
						
					$sql = "UPDATE `imports_maps` SET `title` = '" . mysql_escape_string($_POST['title']) . "', `state` = 'Waiting' WHERE `maps-id` = '" . $maps['maps-id'] . "'";
					if (!$GLOBALS['EntitiesDB']->queryF($sql))
						die('SQL Failed: ' . $sql);
					header("Location: " . API_URL . '/v2/categories/'.$clause."/html.api");
					exit;
				}
			break;
		}
	}
	
	if (function_exists('http_response_code'))
		http_response_code(400);
	include dirname(__FILE__).'/mapper.php';
	exit;
?>		
