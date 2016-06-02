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

	$hash = !isset($_REQUEST['hash'])?md5(NULL):$_REQUEST['hash'];
	foreach(explode("--", $hash) as $emailid)
	{
		$sql = sprintf("UPDATE `emails` SET `verified` = '%s' WHERE `email-id` LIKE '%s'",time(), $emailid);
		if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
			die('SQL Failed: ' . $sql);
		$sql = sprintf("INSERT INTO `emails_networking` (`email-id`, `ip-id`, `when`) VALUES ('%s', '%s', '%s')", $emailid, $GLOBALS['ipid'], time());
		if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
			die('SQL Failed: ' . $sql);
	}
	header("Context-Type: image/png");
	readfile(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . '200x200.png');
	exit(0);
?>