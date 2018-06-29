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
	
	if (!isset($_GET['token'])||empty($_GET['token']))
		die("\$_GET['token'] missing and required!");
	
	$help=false;
	if (isset($_GET['output']) || !empty($_GET['output']) ) {
		$version = isset($_GET['version'])?(string)$_GET['version']:'v2';
		$output = isset($_GET['output'])?(string)$_GET['output']:'';
		$name = isset($_GET['name'])?(string)$_GET['name']:'';
		$clause = isset($_GET['clause'])?(string)$_GET['clause']:'';
		$callback = isset($_REQUEST['callback'])?(string)$_REQUEST['callback']:'';
		$mode = isset($_GET['mode'])?(string)$_GET['mode']:'';
		$state = isset($_GET['state'])?(string)$_GET['state']:'';
	} else {
		header("Location: " . API_URL);
		exit(0);
	}
	
	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `entity-id` LIKE '%s'",$clause);
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$entity = $GLOBALS['APIDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$entityarray = getEntityArray($entity);
		
	require_once __DIR__ . '/class/apimailer.php';
	$fingers = $emails = array();
	
	if (!empty($entity['email-address-one-id']))
		$fingers[$entity['email-address-one-id']] = $entity['email-address-one-id'];
	if (!empty($entity['email-address-two-id']))
		$fingers[$entity['email-address-two-id']] = $entity['email-address-two-id'];
	if (!empty($entity['email-address-three-id']))
		$fingers[$entity['email-address-three-id']] = $entity['email-address-three-id'];

	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('emails') . "` WHERE `email-id` IN ('".implode("','", array_keys($fingers))."') AND `offlined` = 0";
	if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
	{
		while($row = $GLOBALS['APIDB']->fetchArray($result))
		{
			$emails[$clause][$row['email-id']] = $row;
		}
	} else
		die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());

	foreach($emails as $entityid => $values)
	{
		foreach($values as $emailid => $email)
		{
			if ($_GET['token']==md5($entityid.$emailid.$GLOBALS['peerid']))
			{
				$mailer = new APIMailer("wishcraft@users.sourceforge.net", "Entities Repository API");
				if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "SMTPAuth.diz"))
					$smtpauths = explode("\n", str_replace(array("\r\n", "\n\n", "\n\r"), "\n", file_get_contents($file)));
				if (count($smtpauths)>=1)
					$auth = explode("||", $smtpauths[mt_rand(0, count($smtpauths)-1)]);
				if (!empty($auth[0]) && !empty($auth[1]) && !empty($auth[2]))
					$mailer->multimailer->setSMTPAuth($auth[0], $auth[1], $auth[2]);
				$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'email-recieve-new-password.html');
				$html = str_replace("{X_API_URL}", API_URL, $html);
				$html = str_replace("{X_API_SHORT_URL}", sprintf(API_URL_SHORT, $entity['hash-referer']), $html);
				$html = str_replace("{X_ENTITY_AVATAR}", sprintf(API_URL_SHORT, $entity['hash-referer']) . "/avatar.png", $html);
				foreach($entity as $key => $value)
					$html = str_replace("{X_ENTITY_".strtoupper($key)."}", $value, $html);
				foreach($entityarray as $key => $value)
				{
					if (is_array($values) && isset($values['data']) && is_array($values['data']))
					{
						foreach($values['data'] as $keyb => $data)
							$html = str_replace("{X_ENTITY_".strtoupper($key)."_".strtoupper($keyb)."}", $data, $html);
					} elseif (is_array($values) && isset($values['data']) && is_string($values['data']))
					{
						$html = str_replace("{X_ENTITY_".strtoupper($key)."}", $values['data'], $html);
					} elseif (is_array($values) && !isset($values['data']))
					{
						$html = str_replace("{X_ENTITY_".strtoupper($key)."}", implode(", ", $values), $html);
					} elseif (is_string($values))
					{
						$html = str_replace("{X_ENTITY_".strtoupper($key)."}", $values, $html);
					}
				}
				$html = str_replace("{X_MODE}", $state, $html);
				$html = str_replace("{X_NEWPASSWORD}", $newpass = generateNewPassword(microtime(true)), $html);
				$html = str_replace("{X_VERIFYLOGO}", API_URL . '/v2/' . $emailid . '/logo.png', $html);
				$to = array($email['email'] => $email['display-name']);
				$html = str_replace("{X_TONAME}", $email['display-name'], $html);
				if ($mailer->sendMail($to, array(),  array(), "New $mode password requested please utilise this email to recieve one!", $html, array(), NULL, true))
				{
					$sql = sprintf("UPDATE `" . $GLOBALS['APIDB']->prefix('entities') . "` SET `%s-password` = md5('%s') WHERE `entity-id` LIKE '%s'", $state, $newpass, $entityid);
					if (!$GLOBALS['APIDB']->queryF($sql))
						die('SQL Failed: ' . $sql);
					$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `verified` = -100001 WHERE `email-id` LIKE '" . $emailid . "'";
					if (!$GLOBALS['APIDB']->queryF($sql))
						die('SQL Failed: ' . $sql);
					$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `contacted` = '" . time() . "' WHERE `email-id` = '" . $emailid . "'";
					if (!$GLOBALS['APIDB']->queryF($sql))
						die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
					header("Location: " . API_URL . '/v2/' . $clause . '/' . $state . '.api');
				}
			}	
		}
	}
	
	header("Location: " . API_URL . '/v2/' . $clause . '/' . $state . '.api');
	exit(0);
