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


	require_once  __DIR__ . DIRECTORY_SEPARATOR . "header.php";

	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` LIKE '%s'";
	if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))==1)
	{
		$peer = $GLOBALS['APIDB']->fetchArray($results);
	}
	
	$mode = !isset($_REQUEST['mode'])?md5(NULL):$_REQUEST['mode'];
	
	switch ($mode)
	{
		case "register":
			$required = array('peer-id', 'api-url', 'api-short-url', 'version', 'callback', 'polinating');
			foreach($required as $field)
				if (!in_array($field, array_keys($_POST)))
					die("Field \$_POST[$field] is required to operate this function!");
			
			$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('peers') . "` (`peer-id`, `api-url`, `api-short-url`, `version`, `callback`, `polinating`, `created`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
			if ($GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($_POST['peer-id']), mysql_escape_string($_POST['api-url']), mysql_escape_string($_POST['api-short-url']), mysql_escape_string($_POST['version']), mysql_escape_string($_POST['callback']), ($_POST['polinating']==true?'Yes':'No'), time())))
			{
				if ($_POST['polinating']==true)
				{
					@getURIData(sprintf($_POST['callback'], $mode), 145, 145, array('peer-id'=>$peer['peer-id'], 'api-url' => $peer['api-url'], 'api-short-url' => $peer['api-short-url'], 'version' => $peer['version'], 'callback' => $peer['callback'], 'polinating' => ($peer['polinating']=='Yes'?true:false)));
					if (API_URL === API_ROOT_NODE)
					{
						$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND  `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
						if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']), mysql_escape_string($_POST['peer-id']))))>=1)
						{
							while($other = $GLOBALS['APIDB']->fetchArray($results))
							{
								@getURIData(sprintf($other['callback'], $mode), 145, 145, array('peer-id'=>$_POST['peer-id'], 'api-url' => $_POST['api-url'], 'api-short-url' => $_POST['api-short-url'],  'version' => $_POST['version'], 'callback' => $_POST['callback'], 'polinating' => $_POST['polinating']));
							}
						}
					}
				}
				
			}
			break;
		default:
			
			break;
	}
	exit(0);
?>
