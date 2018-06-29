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

	$sql = sprintf("SELECT * FROM `entities` WHERE `entity-id` LIKE '%s'",$clause);
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$entity = $GLOBALS['EntitiesDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$entityarray = getEntityArray($entity);
	$sql = sprintf("SELECT * FROM `imports` WHERE `import-id` LIKE '%s'", $entity['import-id']);
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$import = $GLOBALS['EntitiesDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$sql = sprintf("SELECT * FROM `imports` WHERE `records` = 0 AND `maps-id` LIKE '%s'",$import['maps-id']);
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	$categories = array();
	$sql = "SELECT * FROM `categories` ORDER BY `category` ASC";
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($category = $GLOBALS['EntitiesDB']->fetchArray($results))
		$categories[$category['category-id']] = $category['category'];
	$countries = json_decode(getURIData('http://places.labs.coop/v1/list/list/json.api', 120, 120), true);
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
	<title><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Entity Editor || Chronolabs Cooperative</title>
	<meta property="og:title" content="<?php echo $servicecode; ?> API"/>
	<meta property="og:type" content="<?php echo strtolower($servicecode); ?>-api"/>
</head>
<body>
<form id="entity-edit" name="entity-edit" action="<?php echo API_URL . $_SERVER["REQUEST_URI"]; ?>" method='post' enctype='multipart/form-data' >
<div class="main">
    <h1><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Entity Editor || Chronolabs Cooperative</h1>
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
        			<input type="text" name="entity[title]" id="title" value="<?php echo (isset($entityarray['title'])?$entityarray['title']:''); ?>" maximumlength="45" size="10"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="first-name">First name:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[first-name]" id="first-name" value="<?php echo (isset($entityarray['first-name'])?$entityarray['first-name']:''); ?>" maximumlength="100" size="24"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="middle-name">Middle name(s):</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<input type="text" name="entity[middle-name]" id="middle-name" value="<?php echo (isset($entityarray['middle-name'])?$entityarray['middle-name']:''); ?>" maximumlength="100" size="24"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="last-name">Last Name:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text"  name="entity[last-name]" id="last-name" value="<?php echo (isset($entityarray['last-name'])?$entityarray['last-name']:''); ?>" maximumlength="100" size="24"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<label for="suffix">Suffix:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<input type="text" name="entity[suffix]" id="suffix" value="<?php echo (isset($entityarray['suffix'])?$entityarray['suffix']:''); ?>" maximumlength="100" size="15"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="company">Company:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[company]" id="company" value="<?php echo (isset($entityarray['company'])?$entityarray['company']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<label for="department">Job Title:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[department]" id="department" value="<?php echo (isset($entityarray['department'])?$entityarray['department']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="job-title">Job Title:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[job-title]" id="job-title" value="<?php echo (isset($entityarray['job-title'])?$entityarray['job-title']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="assistants-name">Assistant's name:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[assistants-name]" id="assistants-name" value="<?php echo (isset($entityarray['assistants-name'])?$entityarray['assistants-name']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="spouse">Spouse:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[spouse]" id="spouse" value="<?php echo (isset($entityarray['spouse'])?$entityarray['spouse']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="children">Children:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[children]" id="children" value="<?php echo (isset($entityarray['children'])?$entityarray['children']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="refereed-by">Refereed By:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;" colspan="9">
        			<input type="text" name="entity[refereed-by]" id="refereed-by" value="<?php echo (isset($entityarray['refereed-by'])?$entityarray['refereed-by']:''); ?>" maximumlength="200" size="52"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="gender">Gender:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<select name="entity[gender]" id="gender">
        				<option value='Male'<?php echo ($entityarray['gender']=="Male"?' selected="selected"':''); ?>>Male</option>
        				<option value='Female'<?php echo ($entityarray['gender']=="Female"?' selected="selected"':''); ?>>Female</option>
        				<option value='Transexual'<?php echo ($entityarray['gender']=="Transexual"?' selected="selected"':''); ?>>Transexual</option>
        				<option value='Other'<?php echo ($entityarray['gender']=="Other"?' selected="selected"':''); ?>>Other</option>
        				<option value='Unknown'<?php echo ($entityarray['gender']=="Unknown"?' selected="selected"':''); ?>>Unknown</option>
        			</select>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="account">Username/Account Ref:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[account]" id="account" value="<?php echo (isset($entityarray['account'])?$entityarray['account']:''); ?>" maximumlength="64" size="29"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="intials">Intials:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<input type="text" name="entity[intials]" id="intials" value="<?php echo (isset($entityarray['intials'])?$entityarray['intials']:''); ?>" maximumlength="8" size="10"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="birthday">Birthday Date:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[birthday]" id="birthday" value="<?php echo (isset($entityarray['birthday'])?date("Y-m-d", $entityarray['birthday']):''); ?>" maximumlength="12" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<label for="anniversary">Anniversary Date:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top; align: center; text-align: center;">
        			<input type="text" name="entity[anniversary]" id="anniversary" value="<?php echo (isset($entityarray['anniversary'])?date("Y-m-d", $entityarray['anniversary']):''); ?>" maximumlength="12" size="14"/>
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
        			<label for="avatar">Image File <em style="font-size: 69%;">(Min. 401x401) [png] [jpg] [gif]</em>:</label>
        			<input type="file" name="avatar" id="avatar" />
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
        			<input type="text" name="emails[email-address-one][address]" id="email-address-one-address" value="<?php echo (isset($entityarray['email-address-one']['data']['email'])?$entityarray['email-address-one']['data']['email']:''); ?>" maximumlength="250" size="35"/>
        			<input type="text" name="emails[email-address-one][display]" id="email-address-one-display-name" value="<?php echo (isset($entityarray['email-address-one']['data']['display-name'])?$entityarray['email-address-one']['data']['display-name']:''); ?>" maximumlength="150" size="25"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="email-address-two-address">Email + Name Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="emails[email-address-two][address]" id="email-address-two-address" value="<?php echo (isset($entityarray['email-address-two']['data']['email'])?$entityarray['email-address-two']['data']['email']:''); ?>" maximumlength="250" size="35"/>
        			<input type="text" name="emails[email-address-two][display]" id="email-address-two-display-name" value="<?php echo (isset($entityarray['email-address-two']['data']['display-name'])?$entityarray['email-address-two']['data']['display-name']:''); ?>" maximumlength="150" size="25"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="email-address-three-address">Email + Name Three:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="emails[email-address-three][address]" id="email-address-three-address" value="<?php echo (isset($entityarray['email-address-three']['data']['email'])?$entityarray['email-address-three']['data']['email']:''); ?>" maximumlength="250" size="35"/>
        			<input type="text" name="emails[email-address-three][display]" id="email-address-three-display-name" value="<?php echo (isset($entityarray['email-address-three']['data']['display-name'])?$entityarray['email-address-three']['data']['display-name']:''); ?>" maximumlength="150" size="25"/>
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
        			<input type="text" name="strings[instant-messaging-one]" id="instant-messaging-one" value="<?php echo (isset($entityarray['instant-messaging-one']['data']['data'])?$entityarray['instant-messaging-one']['data']['data']:''); ?>" maximumlength="200" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="instant-messaging-two">Instant Messaging Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="strings[instant-messaging-two]" id="instant-messaging-two" value="<?php echo (isset($entityarray['instant-messaging-two']['data']['data'])?$entityarray['instant-messaging-two']['data']['data']:''); ?>" maximumlength="200" size="35"/>
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
        			<input type="text" name="phones[assistants-phone]" id="assistants-phone" value="<?php echo (isset($entityarray['assistants-phone']['data']['number'])?$entityarray['assistants-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-switch">Business Switch:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[business-switch]" id="business-switch" value="<?php echo (isset($entityarray['business-switch']['data']['number'])?$entityarray['business-switch']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-fax">Business Fax:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[business-fax]" id="business-fax" value="<?php echo (isset($entityarray['business-fax']['data']['number'])?$entityarray['business-fax']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-phone-one">Business Phone One:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[business-phone-one]" id="business-phone-one" value="<?php echo (isset($entityarray['business-phone-one']['data']['number'])?$entityarray['business-phone-one']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-phone-two">Business Phone Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[business-phone-two]" id="business-phone-two" value="<?php echo (isset($entityarray['business-phone-two']['data']['number'])?$entityarray['business-phone-two']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="mobile-phone">Mobile Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[mobile-phone]" id="mobile-phone" value="<?php echo (isset($entityarray['mobile-phone']['data']['number'])?$entityarray['mobile-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-phone-one">Home Phone One:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[home-phone-one]" id="home-phone-one" value="<?php echo (isset($entityarray['home-phone-one']['data']['number'])?$entityarray['home-phone-one']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-phone-two">Home Phone Two:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[home-phone-two]" id="home-phone-two" value="<?php echo (isset($entityarray['home-phone-two']['data']['number'])?$entityarray['home-phone-two']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-fax">Home Fax:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[home-fax]" id="home-fax" value="<?php echo (isset($entityarray['home-fax']['data']['number'])?$entityarray['home-fax']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="primary-phone">Primary Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[primary-phone]" id="primary-phone" value="<?php echo (isset($entityarray['primary-phone']['data']['number'])?$entityarray['primary-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="pager">Pager:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[pager]" id="pager" value="<?php echo (isset($entityarray['pager']['data']['number'])?$entityarray['pager']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="car-phone">Car Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[car-phone]" id="car-phone" value="<?php echo (isset($entityarray['car-phone']['data']['number'])?$entityarray['car-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="home-phone-one">Callback:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[callback]" id="callback" value="<?php echo (isset($entityarray['callback']['data']['number'])?$entityarray['callback']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="radio-phone">Radio Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[radio-phone]" id="radio-phone" value="<?php echo (isset($entityarray['radio-phone']['data']['number'])?$entityarray['radio-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="tty-ttd-phone">TTY/TDD Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[tty-ttd-phone]" id="tty-ttd-phone" value="<?php echo (isset($entityarray['tty-ttd-phone']['data']['number'])?$entityarray['tty-ttd-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="isdn">ISDN:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[isdn]" id="isdn" value="<?php echo (isset($entityarray['isdn']['data']['number'])?$entityarray['isdn']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="telex">Telex:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[telex]" id="telex" value="<?php echo (isset($entityarray['telex'])?$entityarray['telex']:''); ?>" maximumlength="64" size="14"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="other-fax">Other Fax:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[other-fax]" id="other-fax" value="<?php echo (isset($entityarray['other-fax']['data']['number'])?$entityarray['other-fax']['data']['number']:''); ?>" maximumlength="30" size="14"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="other-phone">Other Phone:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="phones[other-phone]" id="other-phone" value="<?php echo (isset($entityarray['other-phone']['data']['number'])?$entityarray['other-phone']['data']['number']:''); ?>" maximumlength="30" size="14"/>
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
			        			<input type="text" name="addresses[business][street-one]" id="business-street-one" value="<?php echo (isset($entityarray['business-address']['data']['street-one'])?$entityarray['business-address']['data']['street-one']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-street-two">Street Line Two:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[business][street-two]" id="business-street-two" value="<?php echo (isset($entityarray['business-address']['data']['street-two'])?$entityarray['business-address']['data']['street-two']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-street-three">Street Line Three:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[business][street-three]" id="business-street-three" value="<?php echo (isset($entityarray['business-address']['data']['street-three'])?$entityarray['business-address']['data']['street-three']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-province">Province:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[business][province]" id="business-province" value="<?php echo (isset($entityarray['business-address']['data']['province'])?$entityarray['business-address']['data']['province']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-city">City:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[business][city]" id="business-city" value="<?php echo (isset($entityarray['business-address']['data']['city'])?$entityarray['business-address']['data']['city']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-state">State:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[business][state]" id="business-state" value="<?php echo (isset($entityarray['business-address']['data']['state'])?$entityarray['business-address']['data']['state']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-postcode">Postcode:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[business][postcode]" id="business-postcode" value="<?php echo (isset($entityarray['business-address']['data']['postcode'])?$entityarray['business-address']['data']['postcode']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="business-country">Country:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<select name="addresses[business][country]" id="business-country"><?php foreach($countries as $country => $values) { ?><option value="<?php echo $country; ?>"<?php if ($entityarray['business-address']['data']['country'] == $country) {; ?> selected="selected"<?php } ?>><?php echo $country; ?></option><?php } ?></select>
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
			        			<input type="text" name="addresses[home][street-one]" id="home-street-one" value="<?php echo (isset($entityarray['home-address']['data']['street-one'])?$entityarray['home-address']['data']['street-one']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-street-two">Street Line Two:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[home][street-two]" id="home-street-two" value="<?php echo (isset($entityarray['home-address']['data']['street-two'])?$entityarray['home-address']['data']['street-two']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-street-three">Street Line Three:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[home][street-three]" id="home-street-three" value="<?php echo (isset($entityarray['home-address']['data']['street-three'])?$entityarray['home-address']['data']['street-three']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-province">Province:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[home][province]" id="home-province" value="<?php echo (isset($entityarray['home-address']['data']['province'])?$entityarray['home-address']['data']['province']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-city">City:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[home][city]" id="home-city" value="<?php echo (isset($entityarray['home-address']['data']['city'])?$entityarray['home-address']['data']['city']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-state">State:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[home][state]" id="home-state" value="<?php echo (isset($entityarray['home-address']['data']['state'])?$entityarray['home-address']['data']['state']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-postcode">Postcode:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[home][postcode]" id="home-postcode" value="<?php echo (isset($entityarray['home-address']['data']['postcode'])?$entityarray['home-address']['data']['postcode']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="home-country">Country:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<select name="addresses[home][country]" id="home-country"><?php foreach($countries as $country => $values) { ?><option value="<?php echo $country; ?>"<?php if ($entityarray['home-address']['data']['country'] == $country) {; ?> selected="selected"<?php } ?>><?php echo $country; ?></option><?php } ?></select>
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
			        			<input type="text" name="addresses[other][street-one]" id="other-street-one" value="<?php echo (isset($entityarray['other-address']['data']['street-one'])?$entityarray['other-address']['data']['street-one']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-street-two">Street Line Two:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[other][street-two]" id="other-street-two" value="<?php echo (isset($entityarray['other-address']['data']['street-two'])?$entityarray['other-address']['data']['street-two']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-street-three">Street Line Three:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[other][street-three]" id="other-street-three" value="<?php echo (isset($entityarray['other-address']['data']['street-three'])?$entityarray['other-address']['data']['street-three']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-province">Province:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[other][province]" id="other-province" value="<?php echo (isset($entityarray['other-address']['data']['province'])?$entityarray['other-address']['data']['province']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-city">City:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[other][city]" id="other-city" value="<?php echo (isset($entityarray['other-address']['data']['city'])?$entityarray['other-address']['data']['city']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-state">State:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" name="addresses[other][state]" id="other-state" value="<?php echo (isset($entityarray['other-address']['data']['state'])?$entityarray['other-address']['data']['state']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-postcode">Postcode:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<input type="text" addresses[other][postcode]" id="other-postcode" value="<?php echo (isset($entityarray['other-address']['data']['postcode'])?$entityarray['other-address']['data']['postcode']:''); ?>" maximumlength="100" size="30"/>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<label for="other-country">Country:</label>
			        		</td>
			        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
			        			<select name="addresses[other][country]" id="other-country"><?php foreach($countries as $country => $values) { ?><option value="<?php echo $country; ?>"<?php if ($entityarray['other-address']['data']['country'] == $country) {; ?> selected="selected"<?php } ?>><?php echo $country; ?></option><?php } ?></select>
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
        			<input type="text" name="entity[web-page-primary]" id="web-page-primary" value="<?php echo (isset($entityarray['web-page-primary'])?$entityarray['web-page-primary']:''); ?>" maximumlength="190" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-blog">Blog URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[web-page-blog]" id="web-page-blog" value="<?php echo (isset($entityarray['web-page-blog'])?$entityarray['web-page-blog']:''); ?>" maximumlength="190" size="35"/>
        		</td>
        	</tr>
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-facebook">Facebook URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[web-page-facebook]" id="web-page-facebook" value="<?php echo (isset($entityarray['web-page-facebook'])?$entityarray['web-page-facebook']:''); ?>" maximumlength="190" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-twitter">Twitter URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[web-page-twitter]" id="web-page-twitter" value="<?php echo (isset($entityarray['web-page-twitter'])?$entityarray['web-page-twitter']:''); ?>" maximumlength="190" size="35"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-linkedin">Linked-in URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[web-page-linkedin]" id="web-page-linkedin" value="<?php echo (isset($entityarray['web-page-linkedin'])?$entityarray['web-page-linkedin']:''); ?>" maximumlength="190" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-googleplus">Google+ URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[web-page-googleplus]" id="web-page-googleplus" value="<?php echo (isset($entityarray['web-page-googleplus'])?$entityarray['web-page-googleplus']:''); ?>" maximumlength="190" size="35"/>
        		</td>
        	</tr>  
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="web-page-other">Other URL:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[web-page-other]" id="web-page-other" value="<?php echo (isset($entityarray['web-page-other'])?$entityarray['web-page-other']:''); ?>" maximumlength="190" size="35"/>
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
        			<select name="entity[category]" id="category" size="8" multiple="multiple"><?php foreach($categories as $categoryid => $category) { ?><option value="<?php echo $categoryid; ?>"<?php if (in_array($categoryid, $entitycatids)) { echo ' selected="selected"'; } ?>><?php echo $category; ?><option/><?php } ?></select>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="keywords">Keywords: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<textarea name="entity[keywords]" id="keywords" cols="37" rows="8"><?php echo (isset($keywords)&&is_array($keywords)?implode(",", $keywords):''); ?><textarea/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="government-id-number">Government ID Number:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[government-id-number]" id="government-id-number" value="<?php echo (isset($entityarray['government-id-number'])?$entityarray['government-id-number']:''); ?>" maximumlength="35" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="business-id-number">Business Registered Number: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[business-id-number]" id="business-id-number" value="<?php echo (isset($entityarray['business-id-number'])?$entityarray['business-id-number']:''); ?>" maximumlength="45" size="35"/>
        		</td>
        	</tr>
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="languages">Languages: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[languages]" id="languages" value="<?php echo (isset($entityarray['languages'])?$entityarray['languages']:''); ?>" maximumlength="250" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="hobby">Hobbies: <em style="font-size: 57%;">Seperate with a comma [,]</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[hobby]" id="hobby" value="<?php echo (isset($entityarray['hobby'])?$entityarray['hobby']:''); ?>" maximumlength="150" size="35"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="Profession">Profession:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="string[profession]" id="profession" value="<?php echo (isset($entityarray['profession']['data']['data'])?$entityarray['profession']['data']['data']:''); ?>" maximumlength="200" size="35"/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="milage">Milage's:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="text" name="entity[milage]" id="milage" value="<?php echo (isset($entityarray['milage'])?$entityarray['milage']:''); ?>" maximumlength="11" size="15"/>
        		</td>
        	</tr>  
        		<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="billing-information">Billing Information:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<textarea name="entity[billing-information]" id="billing-information" cols="37" rows="18"><?php echo (isset($entityarray['billing-information'])?$entityarray['billing-information']:''); ?><textarea/>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="notes">Notes: </label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<textarea name="entity[notes]" id="notes" cols="37" rows="18"><?php echo (isset($entityarray['notes'])?$entityarray['notes']:''); ?><textarea/>
        		</td>
        	</tr>  	
        </table>
    </blockquote>
    <h2>View + Edit Passwords</h2>
    <p>This is the View + Edit Password setting's for this entity contact!</p>
    <blockquote>
        <table width="100%">
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="view-protected">Protect Viewing: <em style="font-size: 57%;">Removes from Public Mail-list's</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="radio" name="entity[view-protected]" id="view-protected-yes" value="Yes" <?php echo ($entityarray['view-protected']=='Yes')?'checked="checked"':''; ?> />
        			<label for="view-protected-yes"><strong>Yes</strong></label>&nbsp;&nbsp;
        			<input type="radio" name="entity[view-protected]" id="view-protected-no" value="No" <?php echo ($entityarray['view-protected']=='No')?'checked="checked"':''; ?> />
        			<label for="view-protected-no"><strong>No</strong></label> 
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="view-password">New View Password:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="password" name="entity[view-password]" id="view-password" value="" size="25"/>
        		</td>
        	</tr>
           	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="edit-protected">Protect Editing: <em style="font-size: 57%;">Removes from Public Editing</em></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="radio" name="entity[edit-protected]" id="edit-protected-yes" value="Yes" <?php echo ($entityarray['edit-protected']=='Yes')?'checked="checked"':''; ?> />
        			<label for="edit-protected-yes"><strong>Yes</strong></label>&nbsp;&nbsp;
        			<input type="radio" name="entity[edit-protected]" id="edit-protected-no" value="No" <?php echo ($entityarray['edit-protected']=='No')?'checked="checked"':''; ?> />
        			<label for="edit-protected-no"><strong>No</strong></label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="edit-password">New edit Password:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="password" name="entity[edit-password]" id="edit-password" value="" size="25"/>
        		</td>
        	</tr>
         </table>
    </blockquote>
<?php
    foreach($entityarray as $field => $values)
    {
		if (isset($values['record']) && !empty($values['record']) && is_array($values['record'])) 
		{
			foreach($values['record'] as $key => $value)
			{
				if (is_array($value))
			    {
			        foreach($value as $data) {
?>	<input type="hidden" name="record[<?php echo $field; ?>][<?php echo $key; ?>][<?php echo $data; ?>]" value="<?php echo $data; ?>" />
<?php				}
			    } else {
?>	<input type="hidden" name="record[<?php echo $field; ?>][<?php echo $key; ?>]" value="<?php echo $value; ?>" />
<?php			}  			
			}
		} 
    }
?>
    <center>
    <input type="submit" value="Save Entity Contact" style="font-size: 175%; font-weight: 700;" />
    </center>
</div>
<input type="hidden" id="op" name="op" value="entity"/>
</form>
</html>

<?php 
