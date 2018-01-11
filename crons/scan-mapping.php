<?php
/**
 * Chronolabs Entitiesages API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         entities
 * @since           1.0.2
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		cronjobs
 * @description		Screening API Service REST
 */
$sql = array();
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '400M');
require_once dirname(__DIR__).'/apiconfig.php';
require_once dirname(__DIR__).'/class/entitiesmailer.php';
error_reporting(E_ERROR);
set_time_limit(7200);

$files = getCompleteCsvListAsArray(ENTITIES_RESOURCES_MAPPING);
print_r($files);
foreach($files as $typal => $filesets)
{
	foreach($filesets as $finger => $fileset)
	{
		foreach($fileset as $fileid => $file)
		{
			foreach(explode(DIRECTORY_SEPARATOR, $file) as $key => $email)
			{
				if (checkEmail($email))
				{
					$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('emails') . "` WHERE `email` LIKE '$email' AND `type` = 'Uploader'";
					if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))==1)
					{
						$uploader = $GLOBALS['APIDB']->fetchArray($result);
						$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE `maps-id` LIKE '' AND  `uploader-email-id` LIKE '".$uploader['email-id']."' AND  `peer-id` LIKE '".$GLOBALS['peerid']."' AND `filename` LIKE '".mysql_escape_string(basename($file))."' AND `path` LIKE '".mysql_escape_string(dirname($file))."'"; 
						if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))==1)
						{
							$import = $GLOBALS['APIDB']->fetchArray($result);
							$eol = $import['fields-eol'];
							$seperated = $import['fields-seperated'];
							$strings = $import['fields-strings'];
							$escapes = $import['fields-escapes'];
							if ($eol=='\\n')
								$eol = PHP_EOL;
							$fieldsql = $fields = csv_fields(file($file), stripslashes($eol), stripslashes($seperated), stripslashes($strings), stripslashes($escapes));
							$pass = true;
							foreach($fields as $kfield => $field)
								if (empty($field)||trim($field)==='')
									$pass = false;
								else {
									$fields[$kfield] = trim($field);
									$fieldsql[$kfield] = mysql_escape_string(trim($field));
								}
							if ($pass == true)
							{
								$found = false;
								$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` WHERE `columns` = '".count($fields)."'";
								if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))>0)
								{
									while($map = $GLOBALS['APIDB']->fetchArray($result))
									{
										$sql = "SELECT count(*) as rcount FROM `" . $GLOBALS['APIDB']->prefix('imports_columns') . "` WHERE `maps-id` = '".$map['maps-id']."' AND `title` IN ('" . implode("', '", $fieldsql) . "')";
										list($rc) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
										if ($rc == count($fields))
										{
											$found = true;
											$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('imports') . "` SET `maps-id` = '".$map['maps-id']."', columns = '$rc' WHERE `import-id` LIKE '". $import['import-id'] . "'";
											if (!$GLOBALS['APIDB']->queryF($sql))
												die('SQL Failed: ' . $sql);
											$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` SET `" . $GLOBALS['APIDB']->prefix('imports') . "` = `" . $GLOBALS['APIDB']->prefix('imports') . "` + 1 WHERE `maps-id` = '".$map['maps-id']."'";
											if (!$GLOBALS['APIDB']->queryF($sql))
												die('SQL Failed: ' . $sql);
											$mapping = $map;
										}
									}
								}
								if ($found === false)
								{
									if ($uploader['contacted'] < time() - (3600 * 24 * mt_rand(2.75, 4.5)))
									{
										$mapid = md5(microtime(true).json_encode($import).json_encode($uploader).$GLOBALS['peerid']);
										$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` (`maps-id`, `state`, `columns`) VALUES ('$mapid', 'Waiting', '" . ($rc = count($fields)) . "')";
										if (!$GLOBALS['APIDB']->queryF($sql))
											die('SQL Failed: ' . $sql);

										$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
										if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
										{
											while($other = $GLOBALS['APIDB']->fetchArray($results))
											{
												@getURIData(sprintf($other['callback'], 'mapping-map-add'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'maps-id'=> $mapid, 'state' => 'Waiting', 'columns' => $rc));
											}
										}
										$position = 0;
										foreach($fields as $fkey => $field)
										{
											$position++;
											$sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('imports_columns') . "` (`maps-id`, `position`, `title`) VALUES ('$mapid', '$position', '" . mysql_escape_string($field) . "')";
											if (!$GLOBALS['APIDB']->queryF($sql))
												die('SQL Failed: ' . $sql);

											$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
											if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
											{
												while($other = $GLOBALS['APIDB']->fetchArray($results))
												{
													@getURIData(sprintf($other['callback'], 'mapping-columns-add'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'maps-id'=> $mapid, 'position' => $position, 'title' => $field));
												}
											}
										}
										$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('imports') . "` SET `maps-id` = '".$mapid."', `columns` = '$rc' WHERE `import-id` LIKE '". $import['import-id'] . "'";
										if (!$GLOBALS['APIDB']->queryF($sql))
											die('SQL Failed: ' . $sql);
										$mailer = new EntitiesMailer("chronolabscoop@users.sourceforge.net", "Entities Repository API");
										if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "SMTPAuth.diz"))
											$smtpauths = explode("\n", str_replace(array("\r\n", "\n\n", "\n\r"), "\n", file_get_contents($file)));
										if (count($smtpauths)>=1)
											$auth = explode("||", $smtpauths[mt_rand(0, count($smtpauths)-1)]);
										if (!empty($auth[0]) && !empty($auth[1]) && !empty($auth[2]))
											$mailer->multimailer->setSMTPAuth($auth[0], $auth[1], $auth[2]);
										$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'scan-mapping.html');
										$html = str_replace("{X_MAPPINGLINK}", $link = API_URL . '/v2/mapping/'.md5($mapid.$import['import-id']).'/html.api', $html);
										$html = str_replace("{X_TONAME}", $uploader['display-name'], $html);
										$html = str_replace("{X_VERIFYLOGO}", API_URL . '/v2/' . $uploader['email-id'] . '/logo.png', $html);
										$html = str_replace("{X_COLUMNS}", $rc, $html);
										if ($mailer->sendMail(array($email=>$uploader['display-name']), array(),  array(), "You need to map the CSV Fields for this file type ". $uploader['display-name'], $html, array(), NULL, true))
										{
											$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `verified` = -1000 WHERE `email-id` LIKE '" . $uploader['email-id'] . "'";
											if (!$GLOBALS['APIDB']->queryF($sql))
												die('SQL Failed: ' . $sql);
											echo "Email Sent to: " . $uploader['display-name'] . " {".$email . "}<br/>";
											if (isset($import['callback']) && !empty($import['callback']))
												@getURIData($import['callback'], 45, 45, array('action'=>'field-mapping', 'import-id' => $import['import-id'], 'maps-id' => $mapid, 'fields' => $fields, 'email-id' => $uploader['email-id'], 'email' => $uploader['email'], 'link' => $link, 'peer-id'=>$GLOBALS['peerid']));
										}
										$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `contacted` = '" . time() . "' WHERE `email-id` = '" . $uploader['email-id'] . "'";
										if (!$GLOBALS['APIDB']->queryF($sql))
											die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
										}
								}
							} else {
								if (unlink($file))
									echo "Deleted: $file<br/>";
								if (count(getCompleteCsvListAsArray($path = dirname(dirname($file))))==0)
									shell_exec("rm -Rfv $path");
							}
						} else {
							if (unlink($file))
								echo "Deleted: $file<br/>";
							if (count(getCompleteCsvListAsArray($path = dirname(dirname($file))))==0)
								shell_exec("rm -Rfv $path");
						}
					} else {
						if (unlink($file))
							echo "Deleted: $file<br/>";
						if (count(getCompleteCsvListAsArray($path = dirname(dirname($file))))==0)
							shell_exec("rm -Rfv $path");
					}
				}
			}
		}
	}
}
?>