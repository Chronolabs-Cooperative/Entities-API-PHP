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


	require_once  __DIR__ . DIRECTORY_SEPARATOR . "header.php";

	$sql = "SELECT * FROM `peers` WHERE `peer-id` LIKE '%s'";
	if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))==1)
	{
		$peer = $GLOBALS['EntitiesDB']->fetchArray($results);
	}
	
	$mode = !isset($_REQUEST['mode'])?md5(NULL):$_REQUEST['mode'];
	
	switch ($mode)
	{
		case "register":
			$required = array('peer-id', 'api-url', 'api-short-url', 'version', 'callback', 'polinating');
			foreach($required as $field)
				if (!in_array($field, array_keys($_POST)))
					die("Field \$_POST[$field] is required to operate this function!");
			
			$sql = "INSERT INTO `peers` (`peer-id`, `api-url`, `api-short-url`, `version`, `callback`, `polinating`, `created`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
			if ($GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($_POST['peer-id']), mysql_escape_string($_POST['api-url']), mysql_escape_string($_POST['api-short-url']), mysql_escape_string($_POST['version']), mysql_escape_string($_POST['callback']), ($_POST['polinating']==true?'Yes':'No'), time())))
			{
				if ($_POST['polinating']==true)
				{
					@getURIData(sprintf($_POST['callback'], $mode), 145, 145, array('peer-id'=>$peer['peer-id'], 'api-url' => $peer['api-url'], 'api-short-url' => $peer['api-short-url'], 'version' => $peer['version'], 'callback' => $peer['callback'], 'polinating' => ($peer['polinating']=='Yes'?true:false)));
					if (API_URL === API_ROOT_NODE)
					{
						$sql = "SELECT * FROM `peers` WHERE `peer-id` NOT LIKE '%s' AND  `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
						if ($GLOBALS['EntitiesDB']->getRowsNum($results = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']), mysql_escape_string($_POST['peer-id']))))>=1)
						{
							while($other = $GLOBALS['EntitiesDB']->fetchArray($results))
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