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

	$sql = sprintf("SELECT * FROM `imports` WHERE md5(concat(`maps-id`, `import-id`)) LIKE '%s'",$clause);
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$import = $GLOBALS['EntitiesDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$sql = "SELECT * FROM `imports_maps` WHERE `maps-id` LIKE '".$import['maps-id']."'";
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	if (!$maps = $GLOBALS['EntitiesDB']->fetchArray($results))
		die('Recordset Failed: ' . $sql);
	$columns = array();
	$sql = "SELECT * FROM `imports_columns` WHERE `maps-id` LIKE '".$import['maps-id']."' ORDER BY `position` ASC";
	if (!$results = $GLOBALS['EntitiesDB']->queryF($sql))
		die('SQL Failed: ' . $sql);
	while ($column = $GLOBALS['EntitiesDB']->fetchArray($results))
		$columns[$column['column-id']] = $column;
	if (!count($columns))
		die('Recordset Failed: ' . $sql);
	if (!file_exists($import['path'].DIRECTORY_SEPARATOR.$import['filename']))
		die('File Missing: ' . $import['filename']);
	$csv = csv_to_array(file($import['path'].DIRECTORY_SEPARATOR.$import['filename']),  stripslashes($import['fields-eol']),  stripslashes($import['fields-seperated']),  stripslashes($import['fields-strings']),  stripslashes($import['fields-escapes']));
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
	<title><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Column Mapper/Importer || Chronolabs Cooperative\</title>
	<meta property="og:title" content="<?php echo $servicecode; ?> API"/>
	<meta property="og:type" content="<?php echo strtolower($servicecode); ?>-api"/>
</head>
<body>
<form id="column-mapper" name="column-mapper" action="<?php echo API_URL . $_SERVER["REQUEST_URI"]; ?>" method="post">
<div class="main">
    <h1><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Column Mapper/Importer || Chronolabs Cooperative</h1>
    <p>You have imported a CSV that has the following properties for columns, we have not encounted this type of CSV Before and could be one of your customised one, you only have to fill this form out once per CSV Type; but we need you to <em>CAREFULLY!!</em> select the field values based on the examples as well as field name types; that is the value type!</p>
    <h2>Title for this CSV Type!</h2>
    <blockquote>
    	<label for="title" style="font-size: 137%; font-weight: 800;">CSV Type/Subject/Title:</label>
    	<input type="text" name="title" id="title" style="font-size: 137%; font-weight: 800;" maxlength="200" size="42" value="<?php echo $maps['title']; ?>" />
    </blockquote>
    <h2>Columns to be mapped to corresponding fields!</h2>
    <p>This is the columns to be mapped to the fields; please select as many options for the data possible; at the top of each section of the table is the field name as the CSV names it, below it is two select boxes, one for the field variable type and one below it for a value type; below that if it is exists is upto 5 columns of data examples or an indicator if the data is missing!</p>
    <blockquote>
        <table width="100%">
        	<?php 
        		$col = 0; 
        		foreach ($columns as $key => $column) { 
        			$col++;
        			if ($col==1) { ?>
        		<tr>
        		<?php 
        			}
        		?>
        			<td style="margin: 5px; vertical-align: top; width: 25%; align: center; text-align: center;">
        					<table border="2" style="margin: 6px;">
        						<thead>
        							<tr>
        								<td style="padding: 4px; background-color: #efefe1; color: #000; font-size: 133.33334%; font-weight: bold; text-align: center;"><?php echo $column['title']; ?></td>
        							</tr>
        						</thead>
        						<tbody>
        							<tr>
        								<td style="background-color: #aa5e11; color: #20ffe0; font-weight: 400; text-align: center;"><label for="field-column" style="font-weight: 800;">Type:</label><select name="field[<?php echo $key; ?>]" id="field-column"><?php foreach( getEnumerators('fields') as $field) { ?><option value="<?php echo $field; ?>"<?php if ($field == $_POST["field"][$key] || $field == $column['field']) { ?> selected="selected"<?php } ?>><?php echo $field; ?></option><?php } ?></select></td>
        							</tr>
        							<tr>
        								<td style="background-color: #aa5e11; color: #20ffe0; font-weight: 400; text-align: center;"><label for="type-column" style="font-weight: 800;">Typal:</label><select name="type[<?php echo $key; ?>]" id="type-column"><?php foreach( getEnumerators('types') as $type) { ?><option value="<?php echo $type; ?>"<?php if ($type == $_POST["type"][$key] || $type == $column['type']) { ?> selected="selected"<?php } ?>><?php echo $type; ?></option><?php } ?></select></td>
        							</tr>
									<tr>
        								<td style="background-color: #efefe1; color: #000; font-weight: 400; text-align: center;">Data Examples</td>
        							</tr>
        							<?php 
        							$rww = 0;
        							foreach ($csv as $key => $rows) {
        								if (!empty($rows[$column['title']]) && $rww < 5)
        								{
        									$rww++;?>
        							<tr>
        								<td style="background-color: #efefe1; color: #000; font-weight: 400; font-size: 65%; text-align: center;"><?php echo substr($rows[$column['title']], 0, 32) . (strlen($rows[$column['title']])>32?"...":''); ?></td>
        							</tr><?php 
        								}
        							}
        							if ($rww==0) {?>
        							<tr>
        								<td style="background-color: #efefe1; color: #000; font-weight: 400; font-size: 65%; text-align: center;"><em>No Data Examples Empty Column</em></td>
        							</tr>
        							<?php }?>
        						</tbody>
        					</table>
        			</td>
        		<?php if ($col==4) {
        			$col = 0;
        		?>
        		</tr>
        		<?php
				 } 
        	}
        	if ($col<4 && $col>0) {
        			$col = 0; ?>
        		<tr><td colspan="<?php echo 4 - $col; ?>"></td></tr>
        	<?php } ?>
        </table>
    </blockquote>
    <center>
    <input type="submit" value="Save CSV Type Definition" style="font-size: 175%; font-weight: 700;" />
    </center>
</div>
<input type="hidden" id="op" name="op" value="mapping"/>
</form>
</html>

<?php 
