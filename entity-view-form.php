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

	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `entity-id` LIKE '%s'",$clause);
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$entity = $GLOBALS['APIDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$entityarray = getEntityArray($entity);
	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE `import-id` LIKE '%s'", $entity['import-id']);
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$import = $GLOBALS['APIDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE `records` = 0 AND `maps-id` LIKE '%s'",$import['maps-id']);
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	$categories = array();
	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories') . "` ORDER BY `category` ASC";
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($category = $GLOBALS['APIDB']->fetchArray($results))
		$categories[$category['category-id']] = $category['category'];
	$countries = json_decode(getURIData('http://places.labs.coop/v1/list/list/json.api', 120, 120), true);
	$editlink = "<a href='" . API_URL . "/v2/$clause/edit.api'>...</a>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<?php 	$servicename = "Entities Repository Services"; 
		$servicecode = "ERS"; ?>
	<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"]; ?>" />
	<meta property="og:site_name" content="<?php echo $servicename; ?> Open Services API's (With Source-code)"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="rating" content="general" />
	<meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
	<meta http-equiv="copyright" content="Chronolabs Cooperative &copy; <?php echo date("Y")-1; ?>-<?php echo date("Y")+1; ?>" />
	<meta http-equiv="generator" content="wishcraft@users.sourceforge.net" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="//labs.partnerconsole.net/execute2/external/reseller-logo">
	<link rel="icon" href="//labs.partnerconsole.net/execute2/external/reseller-logo">
	<link rel="apple-touch-icon" href="//labs.partnerconsole.net/execute2/external/reseller-logo">
	<meta property="og:image" content="//labs.partnerconsole.net/execute2/external/reseller-logo"/>
	<link rel="stylesheet" href="/style.css" type="text/css" />
	<link rel="stylesheet" href="//css.ringwould.com.au/3/gradientee/stylesheet.css" type="text/css" />
	<link rel="stylesheet" href="//css.ringwould.com.au/3/shadowing/styleheet.css" type="text/css" />
	<title><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Entity Viewer || Chronolabs Cooperative</title>
	<meta property="og:title" content="<?php echo $servicecode; ?> API"/>
	<meta property="og:type" content="<?php echo strtolower($servicecode); ?>-api"/>
</head>
<body>
<form id="entity-view" name="entity-view" action="<?php echo API_URL . $_SERVER["REQUEST_URI"]; ?>" method='post' enctype='multipart/form-data' >
<div class="main">
    <h1><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Entity Viewer || Chronolabs Cooperative</h1>
    <p>You have below all the fields available for this entity, please update the details that are required and remove any old data, if the data is shared across multiple entities you will be asked in a secondard form after you submit this one; whether you want to split the records or update all the records!</p>
    <h2>Titles, Names, Business Names + Dates</h2>
    <p>This is the titles, name, suffix and company information as well as other names like spouse and dates surround this such and likes on the entity contact!</p>
    <blockquote>
        <table width="100%">
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="title">Title:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['title'])?$entityarray['title']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="first-name">First name:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['first-name'])?$entityarray['first-name']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="middle-name">Middle name(s):</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<?php echo (isset($entityarray['middle-name'])?$entityarray['middle-name']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="last-name">Last Name:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['last-name'])?$entityarray['last-name']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<label for="suffix">Suffix:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<?php echo (isset($entityarray['suffix'])?$entityarray['suffix']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="company">Company:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['company'])?$entityarray['company']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<label for="department">Job Title:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['department'])?$entityarray['department']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="job-title">Job Title:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['job-title'])?$entityarray['job-title']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="assistants-name">Assistant's name:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['assistants-name'])?$entityarray['assistants-name']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="spouse">Spouse:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['spouse'])?$entityarray['spouse']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="children">Children:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['children'])?$entityarray['children']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="refereed-by">Refereed By:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<?php echo (isset($entityarray['refereed-by'])?$entityarray['refereed-by']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="gender">Gender:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo ($entityarray['gender']); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="account">Username/Account Ref:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['account'])?$entityarray['account']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="intials">Intials:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<?php echo (isset($entityarray['intials'])?$entityarray['intials']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="birthday">Birthday Date:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['birthday'])?date("Y-m-d", $entityarray['birthday']):$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<label for="anniversary">Anniversary Date:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<?php echo (isset($entityarray['anniversary'])?date("Y-m-d", $entityarray['anniversary']):$editlink); ?>
        		</td>
        	</tr>
        </table>
    </blockquote>
    <h2>Avatar/Image</h2>
    <p>This is the avatar the image for the entity contact!</p>
    <blockquote>
        <table width="100%">
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<img src="<?php echo API_URL . '/v2/avatar/'.$entity['entity-id'].'/medium.png'; ?>" />
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
			</tr>
        </table>
    </blockquote>
    <h2>Email Addresses and Instant messaging</h2>
    <p>This is the email addresses, display name for email address and instant messaging data on the entity contact!</p>
    <blockquote>
        <table width="100%">
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="email-address-one-address">Email + Name One:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['email-address-one']['data']['display-name'])?$entityarray['email-address-one']['data']['display-name']:$editlink); ?>
        			&nbsp;<<?php echo (isset($entityarray['email-address-one']['data']['email'])?$entityarray['email-address-one']['data']['email']:$editlink); ?>>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="email-address-two-address">Email + Name Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['email-address-two']['data']['display-name'])?$entityarray['email-address-two']['data']['display-name']:$editlink); ?>
        			&nbsp;<<?php echo (isset($entityarray['email-address-two']['data']['email'])?$entityarray['email-address-two']['data']['email']:$editlink); ?>>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="email-address-three-address">Email + Name Three:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['email-address-three']['data']['display-name'])?$entityarray['email-address-three']['data']['display-name']:$editlink); ?>
        			&nbsp;<<?php echo (isset($entityarray['email-address-three']['data']['email'])?$entityarray['email-address-three']['data']['email']:$editlink); ?>>
        			
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="instant-messaging-one">Instant Messaging One:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['instant-messaging-one']['data']['data'])?$entityarray['instant-messaging-one']['data']['data']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="instant-messaging-two">Instant Messaging Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['instant-messaging-two']['data']['data'])?$entityarray['instant-messaging-two']['data']['data']:$editlink); ?>
        		</td>
        	</tr>
        </table>
    </blockquote>
    <h2>Phone Numbers</h2>
    <p>This is the current phone numbers on the entity contact!</p>
    <blockquote>
        <table width="100%">
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="assistants-phone">Assistants Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['assistants-phone']['data']['number'])?$entityarray['assistants-phone']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-switch">Business Switch:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['business-switch']['data']['number'])?$entityarray['business-switch']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-fax">Business Fax:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['business-fax']['data']['number'])?$entityarray['business-fax']['data']['number']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-phone-one">Business Phone One:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['business-phone-one']['data']['number'])?$entityarray['business-phone-one']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-phone-two">Business Phone Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['business-phone-two']['data']['number'])?$entityarray['business-phone-two']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="mobile-phone">Mobile Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['mobile-phone']['data']['number'])?$entityarray['mobile-phone']['data']['number']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-phone-one">Home Phone One:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['home-phone-one']['data']['number'])?$entityarray['home-phone-one']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-phone-two">Home Phone Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['home-phone-two']['data']['number'])?$entityarray['home-phone-two']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-fax">Home Fax:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['home-fax']['data']['number'])?$entityarray['home-fax']['data']['number']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="primary-phone">Primary Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['primary-phone']['data']['number'])?$entityarray['primary-phone']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="pager">Pager:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['pager']['data']['number'])?$entityarray['pager']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="car-phone">Car Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['car-phone']['data']['number'])?$entityarray['car-phone']['data']['number']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-phone-one">Callback:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['callback']['data']['number'])?$entityarray['callback']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="radio-phone">Radio Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['radio-phone']['data']['number'])?$entityarray['radio-phone']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="tty-ttd-phone">TTY/TDD Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['tty-ttd-phone']['data']['number'])?$entityarray['tty-ttd-phone']['data']['number']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="isdn">ISDN:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['isdn']['data']['number'])?$entityarray['isdn']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="telex">Telex:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['telex'])?$entityarray['telex']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="other-fax">Other Fax:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['other-fax']['data']['number'])?$entityarray['other-fax']['data']['number']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="other-phone">Other Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['other-phone']['data']['number'])?$entityarray['other-phone']['data']['number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        	</tr>
        </table>
    </blockquote>
    <h2>Addresses</h2>
    <p>This is the addresses on the entity contact!</p>
    <blockquote>
        <table width="100%">
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			Business Address
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<table width="100%">
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-street-one">Street Line One:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['street-one'])?$entityarray['business-address']['data']['street-one']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-street-two">Street Line Two:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['street-two'])?$entityarray['business-address']['data']['street-two']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-street-three">Street Line Three:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['street-three'])?$entityarray['business-address']['data']['street-three']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-province">Province:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['province'])?$entityarray['business-address']['data']['province']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-city">City:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['city'])?$entityarray['business-address']['data']['city']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-state">State:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['state'])?$entityarray['business-address']['data']['state']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-postcode">Postcode:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['business-address']['data']['postcode'])?$entityarray['business-address']['data']['postcode']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-country">Country:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php foreach($countries as $country => $values) { if ($entityarray['business-address']['data']['country'] == $country) { echo $values; } }  ?>
			        		</td>
			        	</tr>
			        </table>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			Home Address
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<table width="100%">
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-street-one">Street Line One:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['street-one'])?$entityarray['home-address']['data']['street-one']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-street-two">Street Line Two:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['street-two'])?$entityarray['home-address']['data']['street-two']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-street-three">Street Line Three:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['street-three'])?$entityarray['home-address']['data']['street-three']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-province">Province:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['province'])?$entityarray['home-address']['data']['province']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-city">City:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['city'])?$entityarray['home-address']['data']['city']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-state">State:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['state'])?$entityarray['home-address']['data']['state']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-postcode">Postcode:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['home-address']['data']['postcode'])?$entityarray['home-address']['data']['postcode']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-country">Country:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php foreach($countries as $country => $values) { if ($entityarray['home-address']['data']['country'] == $country) { echo $values; } }  ?>
			        		</td>
			        	</tr>
			        </table>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			Other Address
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<table width="100%">
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-street-one">Street Line One:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['street-one'])?$entityarray['other-address']['data']['street-one']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-street-two">Street Line Two:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['street-two'])?$entityarray['other-address']['data']['street-two']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-street-three">Street Line Three:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['street-three'])?$entityarray['other-address']['data']['street-three']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-province">Province:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['province'])?$entityarray['other-address']['data']['province']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-city">City:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['city'])?$entityarray['other-address']['data']['city']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-state">State:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['state'])?$entityarray['other-address']['data']['state']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-postcode">Postcode:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php echo (isset($entityarray['other-address']['data']['postcode'])?$entityarray['other-address']['data']['postcode']:$editlink); ?>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-country">Country:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<?php foreach($countries as $country => $values) { if ($entityarray['other-address']['data']['country'] == $country) { echo $values; } }  ?>
			        		</td>
			        	</tr>
			        </table>
			       
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="2">
        			
        		</td>
        	</tr>
        </table>
    </blockquote>
    <h2>Internet URL/URI's</h2>
    <p>This is the internet addresses as URI/URL's for this entity contact!</p>
    <blockquote>
        <table width="100%">
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-primary">Primary URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-primary'])?getHTMLLink($entityarray['web-page-primary']):$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-blog">Blog URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-blog'])?getHTMLLink($entityarray['web-page-blog']):$editlink); ?>
        		</td>
        	</tr>
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-facebook">Facebook URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-facebook'])?getHTMLLink($entityarray['web-page-facebook']):$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-twitter">Twitter URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-twitter'])?getHTMLLink($entityarray['web-page-twitter']):$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-linkedin">Linked-in URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-linkedin'])?getHTMLLink($entityarray['web-page-linkedin']):$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-googleplus">Google+ URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-googleplus'])?getHTMLLink($entityarray['web-page-googleplus']):$editlink); ?>
        		</td>
        	</tr>  
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-other">Other URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['web-page-other'])?getHTMLLink($entityarray['web-page-other']):$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			&nbsp;
        		</td>
        	</tr>       	
        </table>
    </blockquote>
    <h2>Meta's Information</h2>
    <p>This is the meta's information and data for this entity contact!</p>
    <blockquote>
        <table width="100%">
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="category">Category:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo implode(', ', $categories); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="keywords">Keywords: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($keywords)&&is_array($keywords)?implode(",", $keywords):$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="government-id-number">Government ID Number:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['government-id-number'])?$entityarray['government-id-number']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-id-number">Business Registered Number: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['business-id-number'])?$entityarray['business-id-number']:$editlink); ?>
        		</td>
        	</tr>
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="languages">Languages: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['languages'])?$entityarray['languages']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="hobby">Hobbies: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['hobby'])?$entityarray['hobby']:$editlink); ?>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="Profession">Profession:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['profession']['data']['data'])?$entityarray['profession']['data']['data']:$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="milage">Milage's:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['milage'])?$entityarray['milage']:$editlink); ?>
        		</td>
        	</tr>  
        		<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="billing-information">Billing Information:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['billing-information'])?str_replace("\n", "<br/>", $entityarray['billing-information']):$editlink); ?>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="notes">Notes: </label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<?php echo (isset($entityarray['notes'])?str_replace("\n", "<br/>", $entityarray['notes']):$editlink); ?>
        		</td>
        	</tr>  	
        </table>
    </blockquote>
    </center>
</div>
</form>
</html>
