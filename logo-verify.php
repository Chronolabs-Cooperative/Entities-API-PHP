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

	$hash = !isset($_REQUEST['hash'])?md5(NULL):$_REQUEST['hash'];
	foreach(explode("--", $hash) as $emailid)
	{
		$sql = sprintf("UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `verified` = '%s' WHERE `email-id` LIKE '%s'",time(), $emailid);
		if (!$results = $GLOBALS['APIDB']->queryF($sql))
			die('SQL Failed: ' . $sql);
		$sql = sprintf("INSERT INTO `" . $GLOBALS['APIDB']->prefix('emails_networking') . "` (`email-id`, `ip-id`, `when`) VALUES ('%s', '%s', '%s')", $emailid, $GLOBALS['ipid'], time());
		if (!$results = $GLOBALS['APIDB']->queryF($sql))
			die('SQL Failed: ' . $sql);
	}
	header("Context-Type: image/png");
	readfile(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . '200x200.png');
	exit(0);
?>
