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

$maps = array();
$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` WHERE `state` LIKE 'Defined'";
if (!$results = $GLOBALS['APIDB']->queryF($sql))
	die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
while ($map = $GLOBALS['APIDB']->fetchArray($results))
	$maps[$map['maps-id']] = $map;
if (!count($maps))
	die('Recordset Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
	
print_r($files = getCompleteCsvListAsArray(ENTITIES_RESOURCES_MAPPING));
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
					if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))>=1)
					{
						$uploader = $GLOBALS['APIDB']->fetchArray($result);
						$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports') . "` WHERE `maps-id` IN ('".implode("', '", array_keys($maps))."') AND  `uploader-email-id` LIKE '".$uploader['email-id']."' AND `filename` LIKE '".mysql_escape_string(basename($file))."' AND `path` LIKE '".mysql_escape_string(dirname($file))."'"; 
						if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql))==1)
						{
							$data = array();
							$import = $GLOBALS['APIDB']->fetchArray($result);
							$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
							if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
							{
								while($other = $GLOBALS['APIDB']->fetchArray($results))
								{
									@getURIData(sprintf($other['callback'], 'import-import'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'import-id' => $import['import-id'], 'data' => $import));
								}
							}
							$eol = $import['fields-eol'];
							$seperated = $import['fields-seperated'];
							$strings = $import['fields-strings'];
							$escapes = $import['fields-escapes'];
							if ($eol=='\\n')
								$eol = PHP_EOL;
							$row = 1;
							$csv = csv_to_array(file($file), stripslashes($eol), stripslashes($seperated), stripslashes($strings), stripslashes($escapes));
							$types = array();
							$columnsb = $columns = array();
							$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports_columns') . "` WHERE `maps-id` LIKE '".$import['maps-id']."' ORDER BY `position` ASC";
							if (!$results = $GLOBALS['APIDB']->queryF($sql))
								die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
							while ($column = $GLOBALS['APIDB']->fetchArray($results))
							{
								$columns[$column['title']] = $column['field'];
								$columnsb[$column['field']] = $column['title'];
								$types[$column['title']] = $column['type'];
							}
							if (!count($columns))
								die('Recordset Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
							$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('imports_maps') . "` WHERE `maps-id` LIKE '".$import['maps-id']."'";
							if (!$results = $GLOBALS['APIDB']->queryF($sql))
								die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
							if (!$maps = $GLOBALS['APIDB']->fetchArray($results))
								die('Recordset Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
							$codesql = $codes = array();
							foreach($csv as $value)
							{
								if (!empty($value[$columnsb['Category']]) && !strpos($value[$columnsb['Category']], ','))
									$codes[$value[$columnsb['Category']]] = ucwords(trim($value[$columnsb['Category']]));
								elseif (!empty($value[$columnsb['Category']]))
									foreach(explode(",", str_replace(array(", ", " ,"), ",", $value[$columnsb['Category']])) as $code)
										$codes[$code] = ucwords(trim($code));
							}
							sort($codes);
							foreach($codes as $id => $code)
								if (empty($code))
									unset($codes[$id]);
								else
									$codesql[$id] = mysql_escape_string($code);
							$cats = array();
							$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('categories_codes') . "` WHERE `maps-id` LIKE '".$import['maps-id']."' AND `code` IN ('" . implode("', '", $codesql) . "') ORDER BY `code` ASC";
							if (!$results = $GLOBALS['APIDB']->queryF($sql))
								die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
							while ($category = $GLOBALS['APIDB']->fetchArray($results))
								$cats[$category['code']] = $category;
							if (count($cats) == 0 && count($codes) > 0)
								die('Recordset Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
							if (count($cats) != count($codes))
							{
								if ($uploader['contacted'] < time() - (3600 * 24 * mt_rand(2.75, 4.5)))
								{
									$mailer = new EntitiesMailer("chronolabscoop@users.sourceforge.net", "Entities Repository API");
									if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "SMTPAuth.diz"))
										$smtpauths = explode("\n", str_replace(array("\r\n", "\n\n", "\n\r"), "\n", file_get_contents($file)));
									if (count($smtpauths)>=1)
										$auth = explode("||", $smtpauths[mt_rand(0, count($smtpauths)-1)]);
									if (!empty($auth[0]) && !empty($auth[1]) && !empty($auth[2]))
										$mailer->multimailer->setSMTPAuth($auth[0], $auth[1], $auth[2]);
									$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'import-categories.html');
									$html = str_replace("{X_CATEGORIESLINK}", $link = API_URL . '/v2/categories/'.md5($import['maps-id'].$import['import-id']).'/html.api', $html);
									$html = str_replace("{X_TONAME}", $uploader['display-name'], $html);
									$html = str_replace("{X_VERIFYLOGO}", API_URL . '/v2/' . $uploader['email-id'] . '/logo.png', $html);
									foreach(array_keys($categories) as $code)
										unset($codes[$code]);
									$html = str_replace("{X_CATEGORIES}", count($codes), $html);
									$html = str_replace("{X_CODES}", implode(", ", $codes), $html);
									if ($mailer->sendMail(array($email=>$uploader['display-name']), array(),  array(), "You need to map the CSV Categories for this file type ". $uploader['display-name'], $html, array(), NULL, true))
									{
										$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `verified` = -100 WHERE `email-id` LIKE '" . $uploader['email-id'] . "'";
										if (!$GLOBALS['APIDB']->queryF($sql))
											die('SQL Failed: ' . $sql);
										echo "Email Sent to: " . $uploader['display-name'] . " {".$email . "}<br/>";
										if (isset($import['callback']) && !empty($import['callback']))
											@getURIData($import['callback'], 45, 45, array('action'=>'categories-mapping', 'import-id' => $import['import-id'], 'maps-id' => $import['maps-id'], 'codes' => $codes, 'email-id' => $uploader['email-id'], 'email' => $uploader['email'], 'link' => $link, 'peer-id'=>$GLOBALS['peerid']));
										
									}
									$sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `contacted` = '" . time() . "' WHERE `email-id` = '" . $uploader['email-id'] . "'";
									if (!$GLOBALS['APIDB']->queryF($sql))
										die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());	
								}
							} else {
								$sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
								if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
								{
									while($other = $GLOBALS['APIDB']->fetchArray($results))
									{
										@getURIData(sprintf($other['callback'], 'import-category-codes'), 145, 145, array('peer-id'=>$GLOBALS['peerid'], 'import-id' => $import['import-id'], 'data' => $cats));
									}
								}
								$start = microtime(true);
								$data = array();
								foreach($csv as $values)
								{		
									$row++;
									$entityid = md5(microtime(true).json_encode($values).$GLOBALS['peerid']);
									$data[$entityid]['entity']['row'] = $row;
									foreach($values as $field => $value)
									{
										if (trim($values[$field])!='')
										{
											switch($columns[$field])
											{
												case 'Category':
													if (!empty($value) && !strpos($value, ','))
														if (!empty($cats[ucwords(trim($value))]['category-id']))
															$data[$entityid]['categories'][] = $cats[ucwords(trim($value))]['category-id'];
													else
														foreach(explode(",", str_replace(array(", ", " ,"), ",", $value)) as $code)
															if (!isset($cats[ucwords(trim($code))]['category-id']))
																$data[$entityid]['categories'][] = $cats[ucwords(trim($code))]['category-id'];		
													break;
												case 'Title':
													$data[$entityid]['entity']['title'] = formatField($type[$field], trim($value));
													break;
												case 'Full Name':
													if (!isset($data[$entityid]['entity']['first-name']))
													{
														$value = formatField($type[$field], ucwords(trim($value)));
														$parts = explode(" ", $value);
														if (count($parts)>1)
															$data[$entityid]['entity']['first-name'] = $parts[0];
													}
													if (!isset($data[$entityid]['entity']['last-name']))
													{
														$value = formatField($type[$field], ucwords(trim($value)));
														$parts = explode(" ", $value);
														if (count($parts)>1)
															$data[$entityid]['entity']['last-name'] = $parts[count($parts)-1];
													}
													if (!isset($data[$entityid]['entity']['middle-name']))
													{
														$value = formatField($type[$field], ucwords(trim($value)));
														$parts = explode(" ", $value);
														unset($parts[count($parts)-1]);
														unset($parts[0]);
														if (count($parts))
															$data[$entityid]['entity']['middle-name'] = implode(" ", $parts);
													}
												case 'First Name':
													$value = formatField($type[$field], trim($value));
													if (strpos($value, " "))
													{
														if (!isset($data[$entityid]['entity']['first-name']))
														{
															$parts = explode(" ", $value);
															if (count($parts)>1)
																$data[$entityid]['entity']['first-name'] = $parts[0];
														}
														if (!isset($data[$entityid]['entity']['last-name']))
														{
															$parts = explode(" ", $value);
															if (count($parts)>1)
																$data[$entityid]['entity']['last-name'] = $parts[count($parts)-1];
														}
														if (!isset($data[$entityid]['entity']['middle-name']))
														{
															$parts = explode(" ", $value);
															unset($parts[count($parts)-1]);
															unset($parts[0]);
															if (count($parts))
																$data[$entityid]['entity']['middle-name'] = implode(" ", $parts);
														}
													} else 
														$data[$entityid]['entity']['first-name'] = $value;
													break;
												case 'Middle Name':
													$value = formatField($type[$field], trim($value));
													if (strpos($value, " "))
													{
														if (!isset($data[$entityid]['entity']['first-name']))
														{
															$parts = explode(" ", $value);
															if (count($parts)>1)
																$data[$entityid]['entity']['first-name'] = $parts[0];
														}
														if (!isset($data[$entityid]['entity']['last-name']))
														{
															$parts = explode(" ", $value);
															if (count($parts)>1)
																$data[$entityid]['entity']['last-name'] = $parts[count($parts)-1];
														}
														if (!isset($data[$entityid]['entity']['middle-name']))
														{
															$parts = explode(" ", $value);
															unset($parts[count($parts)-1]);
															unset($parts[0]);
															if (count($parts))
																$data[$entityid]['entity']['middle-name'] = implode(" ", $parts);
														}
													} else
														$data[$entityid]['entity']['middle-name'] = $value;
													break;
												case 'Last Name':
													$value = formatField($type[$field], trim($value));
													if (strpos($value, " "))
													{
														if (!isset($data[$entityid]['entity']['first-name']))
														{
															$parts = explode(" ", $value);
															if (count($parts)>1)
																$data[$entityid]['entity']['first-name'] = $parts[0];
														}
														if (!isset($data[$entityid]['entity']['last-name']))
														{
															$parts = explode(" ", $value);
															if (count($parts)>1)
																$data[$entityid]['entity']['last-name'] = $parts[count($parts)-1];
														}
														if (!isset($data[$entityid]['entity']['middle-name']))
														{
															$parts = explode(" ", $value);
															unset($parts[count($parts)-1]);
															unset($parts[0]);
															if (count($parts))
																$data[$entityid]['entity']['middle-name'] = implode(" ", $parts);
														}
													} else
														$data[$entityid]['entity']['last-name'] = $value;
													break;
												case 'Suffix':
													$data[$entityid]['entity']['suffix'] = formatField($type[$field], trim($value));
													break;
												case 'Company':
													$data[$entityid]['entity']['company'] = formatField($type[$field], trim($value));
													$data[$entityid]['entity']['type'] = "Organisation";
													break;
												case 'Department':
													$data[$entityid]['keywords'][] = $data[$entityid]['entity']['department'] = formatField($type[$field], trim($value));
													break;
												case 'Job Title':
													$data[$entityid]['keywords'][] = $data[$entityid]['entity']['job-title'] = formatField($type[$field], trim($value));
													break;
												case 'Instant Messaging One':
													$data[$entityid]['strings']['instant-messaging-one'] = formatField($type[$field], trim($value));
													break;
												case 'Instant Messaging Two':
													$data[$entityid]['strings']['instant-messaging-two'] = formatField($type[$field], trim($value));
													break;
												case 'Business Street One':
													$data[$entityid]['addresses']['Business']['street-one'] = formatField($type[$field], trim($value));
													break;
												case 'Business Street Two':
													$data[$entityid]['addresses']['Business']['street-two'] = formatField($type[$field], trim($value));
													break;
												case 'Business Street Three':
													$data[$entityid]['addresses']['Business']['street-three'] = formatField($type[$field], trim($value));
													break;
												case 'Business Province':
													$data[$entityid]['addresses']['Business']['province'] = formatField($type[$field], trim($value));
													break;
												case 'Business City':
													$data[$entityid]['addresses']['Business']['city'] = formatField($type[$field], trim($value));
													break;
												case 'Business State':
													$data[$entityid]['addresses']['Business']['state'] = formatField($type[$field], trim($value));
													break;
												case 'Business Country':
													$data[$entityid]['addresses']['Business']['country'] = formatField($type[$field], trim($value));
													break;
												case 'Business Postcode':
													$data[$entityid]['addresses']['Business']['postcode'] = formatField($type[$field], trim($value));
													break;
												case 'Business Longitude':
													$data[$entityid]['addresses']['Business']['longitude'] = formatField($type[$field], trim($value));
													break;
												case 'Business Latitude':
													$data[$entityid]['addresses']['Business']['latitude'] = formatField($type[$field], trim($value));
													break;
												case 'Business Serial Number Postage':
													$data[$entityid]['addresses']['Business']['serial-postal'] = formatField($type[$field], trim($value));
													break;
												case 'Home Street One':
													$data[$entityid]['addresses']['Home']['street-one'] = formatField($type[$field], trim($value));
													break;
												case 'Home Street Two':
													$data[$entityid]['addresses']['Home']['street-two'] = formatField($type[$field], trim($value));
													break;
												case 'Home Street Three':
													$data[$entityid]['addresses']['Home']['street-three'] = formatField($type[$field], trim($value));
													break;
												case 'Home Province':
													$data[$entityid]['addresses']['Home']['province'] = formatField($type[$field], trim($value));
													break;
												case 'Home City':
													$data[$entityid]['addresses']['Home']['city'] = formatField($type[$field], trim($value));
													break;
												case 'Home State':
													$data[$entityid]['addresses']['Home']['state'] = formatField($type[$field], trim($value));
													break;
												case 'Home Country':
													$data[$entityid]['addresses']['Home']['country'] = formatField($type[$field], trim($value));
													break;
												case 'Home Postcode':
													$data[$entityid]['addresses']['Home']['postcode'] = formatField($type[$field], trim($value));
													break;
												case 'Home Longitude':
													$data[$entityid]['addresses']['Home']['longitude'] = formatField($type[$field], trim($value));
													break;
												case 'Home Latitude':
													$data[$entityid]['addresses']['Home']['latitude'] = formatField($type[$field], trim($value));
													break;
												case 'Home Serial Number Postage':
													$data[$entityid]['addresses']['Home']['serial-postal'] = formatField($type[$field], trim($value));
													break;
												case 'Other Street One':
													$data[$entityid]['addresses']['Other']['street-one'] = formatField($type[$field], trim($value));
													break;
												case 'Other Street Two':
													$data[$entityid]['addresses']['Other']['street-two'] = formatField($type[$field], trim($value));
													break;
												case 'Other Street Three':
													$data[$entityid]['addresses']['Other']['street-three'] = formatField($type[$field], trim($value));
													break;
												case 'Other Province':
													$data[$entityid]['addresses']['Other']['province'] = formatField($type[$field], trim($value));
													break;
												case 'Other City':
													$data[$entityid]['addresses']['Other']['city'] = formatField($type[$field], trim($value));
													break;
												case 'Other State':
													$data[$entityid]['addresses']['Other']['state'] = formatField($type[$field], trim($value));
													break;
												case 'Other Country':
													$data[$entityid]['addresses']['Other']['country'] = formatField($type[$field], trim($value));
													break;
												case 'Other Postcode':
													$data[$entityid]['addresses']['Other']['postcode'] = formatField($type[$field], trim($value));
													break;
												case 'Other Longitude':
													$data[$entityid]['addresses']['Other']['longitude'] = formatField($type[$field], trim($value));
													break;
												case 'Other Latitude':
													$data[$entityid]['addresses']['Other']['latitude'] = formatField($type[$field], trim($value));
													break;
												case 'Other Serial Number Postage':
													$data[$entityid]['addresses']['Other']['serial-postal'] = formatField($type[$field], trim($value));
													break;
												case 'Assistant Phone Number':
													$data[$entityid]['phones']['assistant-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Business Fax Number':
													$data[$entityid]['phones']['business-fax'] = formatField($type[$field], trim($value));
													break;
												case 'Business Phone Number One':
													$data[$entityid]['phones']['business-phone-one'] = formatField($type[$field], trim($value));
													break;
												case 'Business Phone Number Two':
													$data[$entityid]['phones']['business-phone-two'] = formatField($type[$field], trim($value));
													break;
												case 'Callback Phone Number':
													$data[$entityid]['phones']['callback'] = formatField($type[$field], trim($value));
													break;
												case 'Car Phone Number':
													$data[$entityid]['phones']['car-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Business Switch Phone Number':
													$data[$entityid]['phones']['business-switch-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Home Fax Number':
													$data[$entityid]['phones']['home-fax'] = formatField($type[$field], trim($value));
													break;
												case 'Home Phone Number One':
													$data[$entityid]['phones']['home-phone-one'] = formatField($type[$field], trim($value));
													break;
												case 'Home Phone Number Two':
													$data[$entityid]['phones']['home-phone-two'] = formatField($type[$field], trim($value));
													break;
												case 'ISDN Phone Number':
													$data[$entityid]['phones']['isdn'] = formatField($type[$field], trim($value));
													break;
												case 'Mobile Phone Number':
													$data[$entityid]['phones']['mobile-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Other Phone Number':
													$data[$entityid]['phones']['other-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Other Fax Number':
													$data[$entityid]['phones']['other-fax'] = formatField($type[$field], trim($value));
													break;
												case 'Pager Phone Number':
													$data[$entityid]['phones']['pager'] = formatField($type[$field], trim($value));
													break;
												case 'Primary Phone Number':
													$data[$entityid]['phones']['primary-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Radio Phone Number':
													$data[$entityid]['phones']['radio-phone'] = formatField($type[$field], trim($value));
													break;
												case 'TTY/TDD Phone Number':
													$data[$entityid]['phones']['tty-tdd-phone'] = formatField($type[$field], trim($value));
													break;
												case 'Telex':
													$data[$entityid]['entity']['telex'] = formatField($type[$field], trim($value));
													break;
												case 'Account':
													$data[$entityid]['entity']['account'] = formatField($type[$field], trim($value));
													break;
												case 'Anniversary':
													$data[$entityid]['entity']['anniversary'] = formatField($type[$field], trim($value));
													break;
												case 'Assistants Name':
													$data[$entityid]['entity']['assistants-name'] = formatField($type[$field], trim($value));
													break;
												case 'Billing Information':
													$data[$entityid]['entity']['billing-information'] = formatField($type[$field], trim($value));
													break;
												case 'Birthday':
													$data[$entityid]['entity']['birthday'] = formatField($type[$field], trim($value));
													break;
												case 'Children':
													$data[$entityid]['entity']['children'] = formatField($type[$field], trim($value));
													break;
												case 'Email One Address':
													$data[$entityid]['email']['email-address-one']['address'] = formatField($type[$field], trim($value));
													break;
												case 'Email One Display Name':
													$data[$entityid]['email']['email-address-one']['display'] = formatField($type[$field], trim($value));
													break;
												case 'Email Two Address':
													$data[$entityid]['email']['email-address-two']['address'] = formatField($type[$field], trim($value));
													break;
												case 'Email Two Display Name':
													$data[$entityid]['email']['email-address-two']['display'] = formatField($type[$field], trim($value));
													break;
												case 'Email Three Address':
													$data[$entityid]['email']['email-address-three']['address'] = formatField($type[$field], trim($value));
													break;
												case 'Email Three Display Name':
													$data[$entityid]['email']['email-address-three']['display'] = formatField($type[$field], trim($value));
													break;
												case 'Gender':
													$value = formatField($type[$field], trim($value));
													switch (substr(strtolower($value),0,1))
													{
														case "m":
															$data[$entityid]['entity']['gender'] = "Male";
															break;
														case "f":
															$data[$entityid]['entity']['gender'] = "Female";
															break;
														case "t":
															$data[$entityid]['entity']['gender'] = "Transsexual";
															break;
														case "o":
															$data[$entityid]['entity']['gender'] = "Other";
															break;
														default:
															$data[$entityid]['entity']['gender'] = "Unknown";
															break;
													}
												case 'Government ID Number':
													$data[$entityid]['entity']['government-id-number'] = formatField($type[$field], trim($value));
													break;
												case 'Hobby':
													$data[$entityid]['entity']['hobby'] = formatField($type[$field], trim($value));
													break;
												case 'Intials':
													$data[$entityid]['entity']['intials'] = formatField($type[$field], trim($value));
													break;
												case 'Keywords':
													if (!empty($value) && !strpos($value, ','))
														$data[$entityid]['keywords'][] = ucwords(trim($value));
													elseif (!empty($value))
														foreach(explode(',', str_replace(array(", ", " ,"), ",", $value)) as $code)
															$data[$entityid]['keywords'][] = ucwords(trim($code));
													break;					
												case 'Languages':
													$data[$entityid]['entity']['languages'] = formatField($type[$field], trim($value));
													break;
												case 'Country':
													$data[$entityid]['entity']['country-id'] = getCountryID(formatField($type[$field], trim($value)));
													break;
												case 'Location Place':
													$data[$entityid]['entity']['place-id'] = getPlaceID($data[$entityid]['entity']['country-id'], formatField($type[$field], trim($value)));
													break;
												case 'Milage':
													$data[$entityid]['entity']['milage'] = formatField($type[$field], trim($value));
													break;
												case 'Notes':
													$data[$entityid]['entity']['notes'] = formatField($type[$field], trim($value));
													break;
												case 'Office Country':
													$data[$entityid]['entity']['office-country-id'] = getCountryID(formatField($type[$field], trim($value)));
													break;
												case 'Office Location Place':
													$data[$entityid]['entity']['office-place-id'] = getPlaceID($data[$entityid]['entity']['office-country-id'], formatField($type[$field], trim($value)));
													break;
												case 'Registered Business Number':
													$data[$entityid]['entity']['business-id-number'] = formatField($type[$field], trim($value));
													break;
												case 'Profession':
													$data[$entityid]['keywords'][] = $data[$entityid]['entity']['profession'] = formatField($type[$field], trim($value));
													break;
												case 'Refereed By':
													$data[$entityid]['entity']['refereed-by'] = formatField($type[$field], trim($value));
													break;
												case 'Spouse':
													$data[$entityid]['entity']['spouse'] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Primary':
													$data[$entityid]['entity'][webField('web-page-primary', trim($value))] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Blog':
													$data[$entityid]['entity'][webField('web-page-blog', trim($value))] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Facebook':
													$data[$entityid]['entity'][webField('web-page-facebook', trim($value))] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Twitter':
													$data[$entityid]['entity'][webField('web-page-twitter', trim($value))] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Linked-in':
													$data[$entityid]['entity'][webField('web-page-linkedin', trim($value))] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Google+':
													$data[$entityid]['entity'][webField('web-page-googleplus', trim($value))] = formatField($type[$field], trim($value));
													break;
												case 'Web Page Other': 
													$data[$entityid]['entity'][webField('web-page-other', trim($value))] = formatField($type[$field], trim($value));
													break;
												default:
												case 'Skip (Unknown)':
													break;
											}
										}
										
									}
									if (!isset($data[$entityid]['entity']['type']))
										$data[$entityid]['entity']['type'] = "Individual";
									$data[$entityid]['entity']['hash-referer'] = substr($entityid, mt_rand(0,31-($len=mt_rand(4,8))), $len); 	
								}
								if ($entities = importDataArray($data, $import['import-id'], json_decode($import['country-ids'], true), $GLOBALS['peerid']))
									if (isset($import['callback']) && !empty($import['callback']))
										@getURIData($import['callback'], 90, 90, array('action'=>'imported', 'import-id' => $import['import-id'], 'entities' => $entities, 'seconds-taken' => microtime(true) - $start, 'started' => date("Y-m-d H:i:s", $start), 'peer-id'=>$GLOBALS['peerid']));
						
								unlink($file);
								if (count(getCompleteCsvListAsArray($path = dirname(dirname($file))))==0)
									shell_exec("rm -Rfv \"$path\"");
							}
						}
					} else {
						if (unlink($file))
							echo "Deleted: $file<br/>";
						if (count(getCompleteCsvListAsArray($path = dirname(dirname($file))))==0)
							shell_exec("rm -Rfv \"$path\"");
					}
				}
			}
		}
	}
}
?>