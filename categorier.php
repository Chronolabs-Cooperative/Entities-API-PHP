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

	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE md5(concat(`maps-id`, `import-id`)) LIKE '%s'",$clause);
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$import = $GLOBALS['APIDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE `records` = 0 AND `maps-id` LIKE '%s'",$import['maps-id']);
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	$imports = array();
	while ($imp = $GLOBALS['APIDB']->fetchArray($results))
		$imports[$column['import-id']] = $imp;
	if (!count($imports))
		die('Recordset Failed: ' . $sql);
	$columns = array();
	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports_columns') . "` WHERE `maps-id` LIKE '".$import['maps-id']."' ORDER BY `position` ASC";
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($column = $GLOBALS['APIDB']->fetchArray($results))
		$columns[$column['field']] = $column['title'];
	if (!count($columns))
		die('Recordset Failed: ' . $sql);
	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` WHERE `maps-id` LIKE '".$import['maps-id']."'";
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$maps = $GLOBALS['APIDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$csv = array();
	foreach($imports as $importid => $imp)
		if (file_exists($imp['path'].DIRECTORY_SEPARATOR.$imp['filename']))
			$csv[$importid] = csv_to_array(file($imp['path'].DIRECTORY_SEPARATOR.$imp['filename']),  stripslashes($imp['fields-eol']),  stripslashes($imp['fields-seperated']),  stripslashes($imp['fields-strings']),  stripslashes($imp['fields-escapes']));
	$codes = array();
	foreach($csv as $importid => $values)
		foreach($values as $value)
		{
			if (!empty($value[$columns['Category']]) && !strpos($value[$columns['Category']], ','))
				$codes[$value[$columns['Category']]] = ucwords(trim($value[$columns['Category']]));
			elseif (!empty($value[$columns['Category']]))
				foreach(explode(",", str_replace(array(", ", " ,"), ",", $value[$columns['Category']])) as $code)
					$codes[$code] = ucwords(trim($code));
		}
	sort($codes);
	$categories = array();
	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` WHERE `maps-id` LIKE '".$import['maps-id']."' AND `code` IN ('" . implode("', '", $codes) . "') ORDER BY `code` ASC";
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($category = $GLOBALS['APIDB']->fetchArray($results))
		$categories[$category['code']] = $category;
	$allcodes = array();
	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` WHERE `code` IN ('" . implode("', '", $codes) . "') ORDER BY `code` ASC";
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($category = $GLOBALS['APIDB']->fetchArray($results))
		$allcodes[$category['code']][$category['category-id']] = $category;
	$allcats = array();
	$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories') . "` ORDER BY `category` ASC";
	if (!$results = $GLOBALS['APIDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($category = $GLOBALS['APIDB']->fetchArray($results))
		$allcats[$category['category-id']] = $category;
	foreach($codes as $id => $code)
		if (empty($code))
			unset($codes[$id]);
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
	<title><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Category Mapper/Importer || Chronolabs Cooperative\</title>
	<meta property="og:title" content="<?php echo $servicecode; ?> API"/>
	<meta property="og:type" content="<?php echo strtolower($servicecode); ?>-api"/>
</head>
<body>
<form id="column-mapper" name="column-mapper" action="<?php echo API_URL . $_SERVER["REQUEST_URI"]; ?>" method="post">
<div class="main">
    <h1><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Category Mapper/Importer || Chronolabs Cooperative</h1>
    <p>You have imported a CSV that has the 'Category' property for a column, we have encounted some category codes we do not have a category defined for; could you possibly for all the category codes we have not got defined then define the category as displayed!</p>
    <h2>Categories to be mapped to corresponding fields!</h2>
    <p>This is the categories to be mapped to the header code; You have either enter a "(user defined type)" and enter something in the text field; or select one of the existing categories from the list for the header code type!</p>
    <blockquote>
        <table width="100%">
        	<?php 
        		$col = 0; 
        		foreach ($codes as $key => $code) { 
        			$col++;
        			if ($col==1) { ?>
        		<tr>
        		<?php 
        			}
        		?>
        			<td style="margin: 5px; vertical-align: top; width: 50%; align: center; text-align: center;">
        					<table border="2" style="margin: 6px;">
        						<thead>
        							<tr>
        								<td colspan="2" style="padding: 4px; background-color: #efefe1; color: #000; font-size: 133.33334%; font-weight: bold; text-align: center;"><?php echo $code; ?></td>
        							</tr>
        						</thead>
        						<tbody>
        							<tr>
        								<td style="background-color: #aa5e11; color: #20ffe0; font-weight: 400; text-align: center;"><select name="category[<?php $selected = false; echo $code; ?>]" id="field-category"><?php foreach( $allcats as $catid => $cat) { ?><option value="<?php echo $catid; ?>"<?php if (in_array($catid, array_keys($allcodes[$code])) || $catid == $categories[$code]['category-id']) { ?> selected="selected"<?php $selected = true; } ?>><?php echo $cat['category']; ?></option><?php } ?><option value="--user--"<?php if ($selected != true) { ?> selected="selected"<?php ; } ?>>(user defined text)</option></select></td>
        								<td style="background-color: #aa5e11; color: #20ffe0; font-weight: 400; text-align: center;"><label for="user-category" style="font-weight: 800;">Define:</label><input type="text" size="24" maxlength="200" name="user[<?php echo $code; ?>]" id="user-category" <?php if (strlen($code)>=3 && $selected != true ) { ?> value="<?php echo $code; ?>" <?php } ?>/></td>
        							</tr>
        						</tbody>
        					</table>
        			</td>
        		<?php if ($col==2) {
        			$col = 0;
        		?>
        		</tr>
        		<?php
				 } 
        	}
        	if ($col<2 && $col>0) {
        			$col = 0; ?>
        		<tr><td colspan="<?php echo 2 - $col; ?>"></td></tr>
        	<?php } ?>
        </table>
    </blockquote>
    <center>
    <input type="submit" value="Save Category Type Definition" style="font-size: 175%; font-weight: 700;" />
    </center>
</div>
<input type="hidden" id="op" name="op" value="categories"/>
</form>
</html>

<?php 
