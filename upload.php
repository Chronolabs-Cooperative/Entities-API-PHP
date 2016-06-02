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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         entities
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Entitiesing Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */

	//die("Closed until release opening routines!!<br/><br/>No Uploaded Currently Permitted!!");
	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings, $source, $ipid;
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

	set_time_limit(3600*36*9*14);
	
	$error = array();
	if (isset($_GET['field']) || !empty($_GET['field'])) {
		if (empty($_FILES[$_GET['field']]))
			$error[] = 'No file uploaded in the correct field name of: "' . $_GET['field'] . '"';
		else {
			$formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'entities-converted.diz')); 
			$packs = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'));
			$extensions = array_unique(array_merge($formats, $packs));
			sort($extensions);
			$pass = false;
			foreach($extensions as $xtension)
			{
				if (strtolower(substr($_FILES[$_GET['field']]['name'], strlen($_FILES[$_GET['field']]['name'])- strlen($xtension))) == strtolower($xtension))
					if (in_array($xtension, $formats))
						$filetype = 'entities';
					else {
						$filetype = 'pack';
						$packtype = $xtension;
					}
					$pass=true;
					continue;
			}
			if ($pass == false)
				$error[] = 'The file extension type of <strong>'.$_FILES[$_GET['field']]['name'].'</strong> is not valid you can only upload the following file types: <em>'.implode("</em>&nbsp;<em>*.", $extensions).'</em>!';
		}
	} else 
		$error[] = 'File uploaded field name not specified in the URL!';
	
	if (isset($_REQUEST['email']) || !empty($_REQUEST['email'])) {
		if (!checkEmail($_REQUEST['email']))
			$error[] = 'Email is invalid!';
	} else
		$error[] = 'No Uploaders Email Address for Notification + Downloads specified!';
	
	if (((!isset($_REQUEST['name']) || empty($_REQUEST['name'])))) {
		$error[] = 'No Uploaders Individual name or organisation not specified in survey scope when selected!';
	}
	
	if ((!isset($_REQUEST['seperated']) || empty($_REQUEST['seperated']))) {
		$error[] = 'No Field Delimiter for Field Seperation specified with the upload!';
	}

	if ((!isset($_REQUEST['strings']) || empty($_REQUEST['strings']))) {
		$error[] = 'No Field Delimiter for Field Textual String specified with the upload!';
	}
	
	if ((!isset($_REQUEST['escapes']) || empty($_REQUEST['escapes']))) {
		$error[] = 'No Field Delimiter for Field Escaping specified with the upload!';
	}
	
	if ((!isset($_REQUEST['eol']) || empty($_REQUEST['eol']))) {
		$error[] = 'No Field End-of-line Termination specified with the upload!';
	}
	
	if (!empty($error))
	{
		redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
		exit(0);
	}
	
	$file = array();
	switch ($filetype)
	{
		case "entities":
			$uploadpath = DIRECTORY_SEPARATOR . $_REQUEST['email'] . DIRECTORY_SEPARATOR . microtime(true);
			if (!is_dir(constant("ENTITIES_UPLOAD_PATH") . $uploadpath))
				mkdir(constant("ENTITIES_UPLOAD_PATH") . $uploadpath, 0777, true);
			if (!move_uploaded_file($_FILES[$_GET['field']]['tmp_name'], $file[] = constant("ENTITIES_UPLOAD_PATH") . $uploadpath . DIRECTORY_SEPARATOR . sef($_FILES[$_GET['field']]['name'])).".csv") {
				redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Uploading Error Has Occured</h1><br/><p>Entities API was unable to recieve and store: <strong>".$_FILES[$_GET['field']]['name']."</strong>!</p></center>");
				exit(0);
			}
		case "pack":
			$uploadpath = DIRECTORY_SEPARATOR . $_REQUEST['email'] . DIRECTORY_SEPARATOR . microtime(true);
			if (!is_dir(constant("ENTITIES_UPLOAD_PATH") . $uploadpath))
				mkdir(constant("ENTITIES_UPLOAD_PATH") . $uploadpath, 0777, true);
			if (!is_dir(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath))
				mkdir(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath, 0777, true);
			if (!move_uploaded_file($_FILES[$_GET['field']]['tmp_name'], $zipe = constant("ENTITIES_UPLOAD_PATH") . $uploadpath . DIRECTORY_SEPARATOR . sef($_FILES[$_GET['field']]['name']) . ".$packtype")) {
				redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Uploading Error Has Occured</h1><br/><p>Entities API was unable to recieve and store: <strong>".$_FILES[$_GET['field']]['name']."</strong>!</p></center>");
				exit(0);
			}
			$cmds = getExtractionShellExec();
			@shell_exec($dd = __DIR__ . DIRECTORY_SEPARATOR . str_replace('%path', constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR, str_replace('%pack', $zipe, $cmds[$packtype])));
			unlink($zipe);
			
			$packs = true;
			while($packs == true)
			{
				$packs = false;
				foreach(getCompletePacksListAsArray(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath) as $packtype => $packs)
				{
					foreach($packs as $hashinfo => $packfile)
					{
						if (!is_dir(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo))
							mkdir(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo, 0777, true);
						@shell_exec($cmd = __DIR__ . DIRECTORY_SEPARATOR . str_replace('%path', constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo, str_replace('%pack', $packfile, $cmds[$packtype])));
						$packs=true;
						unlink($packfile);
					}
				}
			}
			@shell_exec($cmd = __DIR__ . DIRECTORY_SEPARATOR . "fdupes -R -N -D " . constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo);
			foreach($cullist = getCsvCullList(getCompleteCsvListAsArray(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath)) as $finger => $culls)
				foreach($culls as $finged => $fingering)
					foreach($fingering as $file)
					{
						unlink($file);
						if (isset($_REQUEST['callback']) && !empty($_REQUEST['callback']))
							@getURIData($_REQUEST['callback'], 27, 31, array('action'=>'ignored', 'file-md5' => $finger, 'allocated' => true, 'email' => $_REQUEST['email'], 'name' => $_REQUEST['name'], 'bizo' => $_REQUEST['bizo'], 'filename' => basename($file), 'culled' => true));
					}
			break;
		default:
			$error[] = 'The file extension type of <strong>*.'.$fileext.'</strong> is not valid you can only upload the following: <em>*.otf</em>, <em>*.ttf</em> & <em>*.zip</em>!';
			break;
	}
	if (!empty($error))
	{
		redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
		exit(0);
	}
	$GLOBALS["EntitiesDB"]->queryF('UPDATE `networking` SET `uploads` = `uploads` + 1 WHERE `ip-id` = "'.$ipid.'"');
	$files = getCompleteCsvListAsArray(constant("ENTITIES_RESOURCES_UNPACKING") . $uploadpath);
	$culled = array();
	
	$copypath = ENTITIES_RESOURCES_MAPPING . DIRECTORY_SEPARATOR . $_REQUEST['email'] . DIRECTORY_SEPARATOR . microtime(true) . DIRECTORY_SEPARATOR;
	if (!is_dir($copypath))
		mkdir($copypath, 0777, true);
	$size = 0;
	foreach($files as $type => $entitiesfiles)
	{
		foreach($entitiesfiles as $finger => $entitiesfile)
		{
			$size += count($entitiesfile);
		}
	}
	$filez = array();
	foreach($files as $type => $entitiesfiles)
	{
		foreach($entitiesfiles as $finger => $entitiesfilez)
		{
			foreach($entitiesfilez as $entitiesfile)
			{
				$uploademailid = '';
				$sql = "SELECT `email-id` FROM `emails` WHERE `type` = 'Uploader' AND `email` LIKE '".mysql_escape_string($_REQUEST['email']) . "'  AND `display-name` LIKE '".mysql_escape_string($_REQUEST['name']) . "'";
				if ($result = $GLOBALS['EntitiesDB']->queryF($sql))
				{
					list($uploademailid) = $GLOBALS['EntitiesDB']->fetchRow($result);
				} 
				if (empty($uploademailid)) {
					$uploademailid = md5(microtime(true).$_REQUEST['email'].$_REQUEST['name']);
					$sql = "INSERT INTO `emails` (`email-id`, `type`, `email`, `display-name`) VALUES ('$uploademailid', 'Uploader', '".mysql_escape_string($_REQUEST['email']) . "', '".mysql_escape_string($_REQUEST['name']) . "')";
					if (!$GLOBALS['EntitiesDB']->queryF($sql))
							die('SQL Failed: ' . $sql);;
				}
				if (!file_exists($copypath . DIRECTORY_SEPARATOR . sha1_file($entitiesfile) . '.csv')&&filesize($entitiesfile)>199)
				{
					if (copy($entitiesfile, $csvfile = $copypath . DIRECTORY_SEPARATOR .  sha1_file($entitiesfile) . '.csv'))
					{
						$importid = md5($sql = "INSERT INTO `imports` (`import-id`, `peer-id`, `uploader-email-id`, `country-ids`, `ip-id`, `referee`, `subject`, `filename`, `path`, `bytes`, `uploaded`, `fields-seperated`, `fields-strings`, `fields-escapes`, `fields-eol`, `callback`) VALUES ('%s', '".$GLOBALS['peerid']."', '$uploademailid', '".mysql_escape_string(json_encode($_REQUEST['country']))."', '$ipid','" . sha1_file($csvfile) . "','" . mysql_escape_string(basename($entitiesfile)) . "','" . mysql_escape_string(basename($csvfile)) . "','" . mysql_escape_string(dirname($csvfile)) . "','" . mysql_escape_string(filesize($csvfile)) . "','" . mysql_escape_string(time()) . "','" . mysql_escape_string($_REQUEST['seperated']). "','" . mysql_escape_string($_REQUEST['strings']) . "','" . mysql_escape_string($_REQUEST['escapes']) . "','" . mysql_escape_string($_REQUEST['eol']) . "','" . mysql_escape_string($_REQUEST['callback']) . "')");
						$filez[basename($entitiesfile)] = basename($entitiesfile);
						if ($GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $importid)))
						{
							if (isset($_REQUEST['callback']) && !empty($_REQUEST['callback']))
								@getURIData($_REQUEST['callback'], 45, 45, array('action'=>'uploaded', 'filename' => basename($entitiesfile), 'file-md5' => md5($csvfile), 'import-id' => $importid, 'email-id' => $uploademailid, 'email' => $_REQUEST['email'], 'name' => $_REQUEST['name'], 'peer-id'=>$GLOBALS['peerid']));
						}
						else 
							die('SQL Failed: ' . $sql);
					}
				}
			}
		}
	}
	shell_exec(sprintf(__DIR__ . DIRECTORY_SEPARATOR . "rm -Rf " . constant("ENTITIES_UPLOAD_PATH") . $uploadpath . DIRECTORY_SEPARATOR . '*'));
	redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:API_URL, 18, "<center><h1 style='color:rgb(0,198,0);'>Uploading Partially or Completely Successful</h1><br/><div>The following files where uploaded and queued for conversion on the API Successfully:</div><div style='height: auto; clear: both; width: 100%;'><ul style='height: auto; clear: both; width: 100%;'><li style='width: 24%; float: left;'>".implode("</li><li style='width: 24%; float: left;'>", $filez)."</li></ul></div><br/><div style='clear: both; height: 11px; width: 100%'>&nbsp;</div><p>You need to wait for the conversion maintenance to run in the next 30 minutes, you will recieve an email when done per each file!</p></center>");
	exit(0);
	
?>