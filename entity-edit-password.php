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
	<title><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Entity Editor Password Required || Chronolabs Cooperative</title>
	<meta property="og:title" content="<?php echo $servicecode; ?> API"/>
	<meta property="og:type" content="<?php echo strtolower($servicecode); ?>-api"/>
</head>
<body>
<form id="password-edit" name="password-edit" action="<?php echo API_URL . $_SERVER["REQUEST_URI"]; ?>" method='post' enctype='multipart/form-data' >
<div class="main">
    <h1><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Entity Editor Password Required || Chronolabs Cooperative</h1>
    <p>You have enter the password for this entity to edit it!</p>
    <h2>Password</h2>
    <blockquote>
        <table width="100%">
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="title">Password:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="password" name="password" id="password" value="" size="40"/>
        		</td>
        	</tr>
        	<tr>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<label for="request">Request New Password:</label>
        		</td>
        		<td style="margin: 5px; vertical-align: top;  align: center; text-align: center;">
        			<input type="checkbox" name="request" id="request-yes" value="Yes" />&nbsp;
        			<label for="request-yes">I want a new password sent to entity emails</label>
        		</td>
        	</tr>
        </table>
    </blockquote>
    <center>
    <input type="submit" value="Action Password" style="font-size: 175%; font-weight: 700;" />
    </center>
</div>
<input type="hidden" id="op" name="op" value="password"/>
</form>
</html>

<?php 
