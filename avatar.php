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

$sql = array();
ini_set('display_errors', false);
ini_set('log_errors', false);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '400M');

require_once __DIR__ . '/apiconfig.php';
require_once __DIR__ . '/class/entitiesages.php';
require_once __DIR__ . '/class/WideImage/WideImage.php';

set_time_limit(7200);

$entityids = array();
$sql = "SELECT `entity-id` FROM `entities` WHERE `avatar` LIKE 'blank.gif' ORDER BY RAND() LIMIT 99";
if ($GLOBALS['EntitiesDB']->getRowsNum($result = $GLOBALS['EntitiesDB']->queryF($sql))>=1)
{
	while($row = $GLOBALS['EntitiesDB']->fetchArray($result))
	{
		$entityids[$row['entity-id']] = $row['entity-id'];
	}
} else 
	echo ("SQL Failed: $sql ::: " . $GLOBALS['EntitiesDB']->error());
$sql = "SELECT `entity-id` FROM `avatars` as `a` INNER JOIN `avatars_entities` as `b` ON `a`.`avatar-id` =  `b`.`avatar-id` WHERE `a`.`source` = 'gravatar' AND ((`a`.`created` <= '%s' AND `a`.`edited` = 0) OR (`a`.`edited` <> '0' AND `a`.`edited` <= '%s')) ORDER BY RAND() LIMIT 99";
if ($GLOBALS['EntitiesDB']->getRowsNum($result = $GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
{
	while($row = $GLOBALS['EntitiesDB']->fetchArray($result))
	{
		$entityids[$row['entity-id']] = $row['entity-id'];
	}
} else 
	echo ("SQL Failed: $sql ::: " . $GLOBALS['EntitiesDB']->error());
$fingers = array();
$sql = "SELECT * FROM `fingerprints` WHERE (`type` = 'emails' AND `peer-id` = '".$GLOBALS['peerid']."' AND `entity-id` IN ('".implode("','", $entityids)."')) AND `offlined` = 0";
if ($GLOBALS['EntitiesDB']->getRowsNum($result = $GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
{
	while($row = $GLOBALS['EntitiesDB']->fetchArray($result))
	{
		$fingers[$row['fingerprint']] = $row['entity-id'];
	}
} else 
	 echo ("SQL Failed: $sql ::: " . $GLOBALS['EntitiesDB']->error());
$emails = array();
$sql = "SELECT * FROM `emails` WHERE `email-id` IN ('".implode("','", array_keys($fingers))."') AND `offlined` = 0";
if ($GLOBALS['EntitiesDB']->getRowsNum($result = $GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
{
	while($row = $GLOBALS['EntitiesDB']->fetchArray($result))
	{
		$emails[$fingers[$row['email-id']]][$row['email-id']] = $row['email'];
	}
} else 
	echo ("SQL Failed: $sql ::: " . $GLOBALS['EntitiesDB']->error());

foreach($emails as $entityid => $values)
{
	$updated = false;
	foreach($values as $emailid => $email)
	{
		$image = getURIData(get_gravatar($email));
		$sql = "SELECT * FROM `avatars` WHERE `avatar-id` LIKE '%s'";
		if ($GLOBALS['EntitiesDB']->getRowsNum($result = $GLOBALS['EntitiesDB']->queryF(sprintf($sql, $finger = md5($image))))==1)
		{
			$sql = "DELETE FROM `avatars_entities` WHERE `entity-id` LIKE '%s'";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $entityid)))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "DELETE FROM `avatars_emails` WHERE `entity-id` LIKE '%s' AND `email-id` LIKE '%s'";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $entityid, $emailid)))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "INSERT INTO `avatars_entities` (`avatar-id`, `entity-id`, `when`) VALUES('%s', '%s', '%s')";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, $entityid, time())))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "INSERT INTO `avatars_emails` (`avatar-id`, `entity-id`, `email-id`, `when`) VALUES('%s', '%s', '%s', '%s')";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, $entityid, $emailid, time())))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "UPDATE `avatars` SET `instances` = `instances` + 1 WHERE `avatar-id` LIKE '%s'";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger)))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "UPDATE `entities` SET `avatar` = '%s', `updated` = '%s' WHERE `entity-id` = '%s'";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, time(), $entityid )))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
		} else {
			$images = array();
			$images['originals'] = WideImage::loadFromString($image);
			$images['large'] = $images['originals']->resize(400,400,'inside','any');
			$images['medium'] = $images['originals']->resize(160,160,'inside','any');
			$images['small'] = $images['originals']->resize(80,80,'inside','any');
			foreach($images as $path => $img)
			{
				$img->saveToFile(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $finger . ".png", 8, PNG_FILTER_AVG);
			}
			$sql = "INSERT INTO `avatars` (`avatar-id`, `source`, `instances`, `original-path`, `original-image`, `original-width`, `original-height`, `original-format`, `small-path`, `small-image`, `small-width`, `small-height`, `small-format`, `medium-path`, `medium-image`, `medium-width`, `medium-height`, `medium-format`, `large-path`, `large-image`, `large-width`, `large-height`, `large-format`, `created`) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, 'gravatar', 1, 'avatars' . DIRECTORY_SEPARATOR . 'originals' . DIRECTORY_SEPARATOR, $finger.".png", $images['originals']->getWidth(), $images['originals']->getHeight(), 'png', 'avatars' . DIRECTORY_SEPARATOR . 'small' . DIRECTORY_SEPARATOR, $finger.".png", $images['small']->getWidth(), $images['small']->getHeight(), 'png', 'avatars' . DIRECTORY_SEPARATOR . 'medium' . DIRECTORY_SEPARATOR, $finger.".png", $images['medium']->getWidth(), $images['medium']->getHeight(), 'png', 'avatars' . DIRECTORY_SEPARATOR . 'large' . DIRECTORY_SEPARATOR, $finger.".png", $images['large']->getWidth(), $images['large']->getHeight(), 'png', time())))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "INSERT INTO `avatars_entities` (`avatar-id`, `entity-id`, `when`) VALUES('%s', '%s', '%s')";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, $entityid, time())))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "INSERT INTO `avatars_emails` (`avatar-id`, `entity-id`, `email-id`, `when`) VALUES('%s', '%s', '%s', '%s')";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, $entityid, $emailid, time())))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
			$sql = "UPDATE `entities` SET `avatar` = '%s', `updated` = '%s' WHERE `entity-id` = '%s'";
			if (!$GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $finger, time(), $entityid )))
				die("SQL Error: $sql ::: " . $GLOBALS['EntitiesDB']->error());
			else
				echo "\n<br/>SQL Successful: $sql";
		}
	}
}
?>
