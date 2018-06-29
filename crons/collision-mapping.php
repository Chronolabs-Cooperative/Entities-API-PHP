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
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '400M');
require_once dirname(__DIR__).'/apiconfig.php';
require_once dirname(__DIR__).'/class/xcp/xcp.class.php';
error_reporting(E_ERROR);
set_time_limit(7200);

$entities = array();
$sql = "SELECT DISTINCT `a`.* FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` as `a` INNER JOIN `" . $GLOBALS['APIDB']->prefix('entities') . "` as `b` ON `a`.`hash-referer` LIKE `b`.`hash-referer` AND `a`.`entity-id` NOT LIKE `b`.`entity-id` ORDER BY RAND() LIMIT 99";
if ($result = $GLOBALS['APIDB']->queryF($sql))
{
	while($row = $GLOBALS['APIDB']->fetchArray($result))
	{
		$entities[$row['entity-id']] = $row;
	}
}
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE LENGTH(`hash-referer`) > 5 WHERE `peer-id` = '".$GLOBALS['peerid']."' ORDER BY RAND() LIMIT 99";
if ($result = $GLOBALS['APIDB']->queryF($sql))
{
	while($row = $GLOBALS['APIDB']->fetchArray($result))
	{
		$entities[$row['entity-id']] = $row;
	}
} else 
	die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());
$entitys = $importids = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `entity-id` IN ('".implode("','", array_keys($entities))."')) AND `offlined` = 0";
if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))>=1)
{
	while($row = $GLOBALS['APIDB']->fetchArray($result))
	{
		$entitys[$row['entity-id']] = $row;
		$importids[$row['import-id']] = $row['import-id'];
	}
} else
	die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());
$imports = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE `import-id` IN ('".implode("','", array_keys($importids))."'))";
if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))>=1)
{
	while($row = $GLOBALS['APIDB']->fetchArray($result))
	{
		$imports[$row['import-id']] = $row;
	}
} else
	die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());
$fingers = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` WHERE (`type` = 'emails' AND `peer-id` = '".$GLOBALS['peerid']."' AND `entity-id` IN ('".implode("','", array_keys($entities))."')) AND `offlined` = 0";
if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
{
	while($row = $GLOBALS['APIDB']->fetchArray($result))
	{
		$fingers[$row['fingerprint']] = $row;
	}
} else 
	 die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());
$emails = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('emails') . "` WHERE `email-id` IN ('".implode("','", array_keys($fingers))."') AND `offlined` = 0";
if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
{
	while($row = $GLOBALS['APIDB']->fetchArray($result))
	{
		$emails[$fingers[$row['email-id']]['entity-id']][$row['email-id']] = $row;
	}
} else 
	die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());

foreach($emails as $entityid => $values)
{
	$updated = false;
	$xcp = new xcp(NULL, mt_rand(0, 254), mt_rand(2,5));
	$hash = $xcp->calc((string)$entities[$entityid]['hash-referer'].microtime(true).sha1(json_encode($entities[$entityid], true)).md5(json_encode($emails[$entityid], true)));
	$width = mt_rand(280, 750);
	$height = mt_rand(280, 750);
	$pixels = mt_rand(880, 2750);
	$keys = array_keys($values);
	$cc = $to = array();
	$emails = $values;
	
	$mailer = new APIMailer("chronolabscoop@users.sourceforge.net", "Entities Repository API");
	if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "SMTPAuth.diz"))
		$smtpauths = explode("\n", str_replace(array("\r\n", "\n\n", "\n\r"), "\n", file_get_contents($file)));
	if (count($smtpauths)>=1)
		$auth = explode("||", $smtpauths[mt_rand(0, count($smtpauths)-1)]);
	if (!empty($auth[0]) && !empty($auth[1]) && !empty($auth[2]))
		$mailer->multimailer->setSMTPAuth($auth[0], $auth[1], $auth[2]);
	$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'collision-mapping.html');
	$html = str_replace("{X_API_URL}", API_URL, $html);
	$html = str_replace("{X_API_SHORT_URL}", sprintf(API_URL_SHORT, $hash), $html);
	$html = str_replace("{X_AVATAR}", sprintf(API_URL_SHORT, $hash) . "/avatar.png", $html);
	$html = str_replace("{X_HASH}", $hash, $html);
	$html = str_replace("{X_PIXELS}", $pixels, $html);
	$html = str_replace("{X_WIDTH}", $pixels, $html);
	$html = str_replace("{X_HEIGHT}", $pixels, $html);
	$html = str_replace("{X_COLUMNS}", $rc, $html);
	$html = str_replace("{X_VERIFYLOGO}", API_URL . '/v2/' . implode('--', $keys) . '/logo.png', $html);
	if (isset($emails[$keys[0]]) && !empty($emails[$keys[0]]))
	{
		$to = array($emails[$keys[0]]['email'] => $emails[$keys[0]]['display-name']);
		$html = str_replace("{X_TONAME}", $values[$keys[0]]['display-name'], $html);
		unset($emails[$keys[0]]);
		foreach($emails as $emailid => $email)
		{
			$cc[$email['email']] = $email['display-name'];
		}
		
	if ($mailer->sendMail($to, $cc,  array(), "Potential Collision Avoided with Hash Referee Code for your Entity Details!", $html, array(), NULL, true))
	{
		$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('entities') . "` SET `hash-referer` = '".$hash."' WHERE `entity-id` LIKE '" . $entityid . "'";
		if (!$GLOBALS['APIDB']->queryF($sql))
			die('SQL Failed: ' . $sql);
		
		foreach($values as $emailid => $email)
		{
			$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `verified` = -10000 WHERE `email-id` LIKE '" . $emailid . "'";
			if (!$GLOBALS['APIDB']->queryF($sql))
				die('SQL Failed: ' . $sql);
			echo "Email Sent to: " . $uploader['display-name'] . " {".$email . "}<br/>";
			$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `contacted` = '" . time() . "' WHERE `email-id` = '" . $emailid . "'";
			if (!$GLOBALS['APIDB']->queryF($sql))
				die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
			}
		}
		
		if (isset($imports[$entitys[$entity-id]['import-id']]['callback']) && !empty($imports[$entitys[$entity-id]['import-id']]['callback']))
			@getURIData($imports[$entitys[$entity-id]['import-id']]['callback'], 45, 45, array('action'=>'collison-mapping', 'import' => $imports[$entitys[$entity-id]['import-id']], 'entity' => $entity[$entityid], 'hash-referer' => $hash, 'email-ids' => $keys, 'emails' => $emails, 'peer-id'=>$GLOBALS['peerid']));
	}
}
?>
