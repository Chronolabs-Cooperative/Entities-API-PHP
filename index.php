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
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         entities
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Entitiesing Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */
	

	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	$odds = $inner = array();
	foreach($_GET as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach($_POST as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	$help=true;
	if (isset($inner['output']) || !empty($inner['output'])) {
		$version = isset($inner['version'])?(string)$inner['version']:'v2';
		$output = isset($inner['output'])?(string)$inner['output']:'';
		$name = isset($inner['name'])?(string)$inner['name']:'';
		$clause = isset($inner['clause'])?(string)$inner['clause']:'';
		$callback = isset($_REQUEST['callback'])?(string)$_REQUEST['callback']:'';
		$mode = isset($inner['mode'])?(string)$inner['mode']:'';
		$state = isset($inner['state'])?(string)$inner['state']:'';
		switch($output)
		{
			default:
			    if (!in_array($output, file(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'entities-supported.diz')))
					$help=true;
				elseif (in_array($mode, array('entities')) && strlen($clause) == 32)
					$help=false;
				break;
			case "raw":
			case "html":
			case "serial":
			case "json":
			case "xml":
				if (in_array($mode, array('nodes', 'entities')))
					$help=false;
				break;
			case "forms":
				if (in_array($mode, array('uploads','edit')))
				{
					$help=false;
					if (empty($clause) && isset($_POST['return']))
						$clause = $_POST['return'];
				}
				break;
			case "profile":
				if (in_array($mode, array('sites')) && in_array($clause, array('create', 'forgotten', 'edit')))
					$help=false;
				break;
			case "css":
				if (in_array($mode, array('entities', 'entities', 'random')) && !empty($clause))
				{
					$help=false;
					if ($mode == 'random' && empty($state))
						$help=true;
				}
				break;	
			case "preview":
				if (in_array($mode, array('entities', 'entities', 'random')) && !empty($clause))
				{
					$help=false;
					if ($mode == 'random' && empty($state))
						$help=true;
				}
				break;		
		}
	} else {
		$help=true;
	}
	
	if ($help==true) {
		if (function_exists('http_response_code'))
			http_response_code(201);
		include dirname(__FILE__).'/help.php';
		exit;
	}
	/*
	session_start();
	if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) {
		if (isset($_SESSION['reset']) && $_SESSION['reset']<microtime(true))
			$_SESSION['hits'] = 0;
		if ($_SESSION['hits']<=MAXIMUM_QUERIES) {
			if (!isset($_SESSION['hits']) || $_SESSION['hits'] = 0)
				$_SESSION['reset'] = microtime(true) + 3600;
			$_SESSION['hits']++;
		} else {
			header("HTTP/1.0 404 Not Found");
			exit;
		}
	}
	*/
	
	switch($output)
	{
		default:
			$data = getEntitiesRawData($mode, $clause, $output, $version);
			break;
		case "raw":
		case "html":
		case "serial":
		case "json":
		case "xml":
			switch ($mode)
			{
				case "nodes":
					$data = getNodesListArray($clause, $output);
					break;
				case "entities":
					$data = getEntitiesListArray($clause, $output);
					break;
			}
			break;
		case "profile":
			$data = '';
			break;
		case "css":
			$data = getCSSListArray($mode, $clause, $state, $name, $output, $version);
			break;	
		case "preview":
			if (function_exists('http_response_code'))
				http_response_code(400);
			$data = getPreviewHTML($mode, $clause, $state, $name, $output, $version);
			break;	
		case "forms":
			if (function_exists('http_response_code'))
				http_response_code(201);
			die(getHTMLForm($mode, $clause, $callback, $output, $version));
			break;
	}
	
	if (function_exists('http_response_code'))
		http_response_code(200);
	
	switch ($output) {
		default:
			echo $data;
			break;
		case 'html':
			echo '<h1>' . $country . ' - ' . $place . ' (Places data)</h1>';
			echo '<pre style="entities-family: \'Courier New\', Courier, Terminal; entities-size: 0.77em;">';
			echo implode("\n", $data);
			echo '</pre>';
			break;
		case 'raw':
		    header('Content-type: application/x-httpd-php');
		    echo ('<?php'."\n\n".'return ' . var_export($data, true) . ";\n\n?>");
		    break;
		case 'json':
			header('Content-type: application/json');
			echo json_encode($data);
			break;
		case 'serial':
			header('Content-type: text/text');
			echo serialize($data);
			break;
		case 'xml':
			header('Content-type: application/xml');
			$dom = new XmlDomConstruct('1.0', 'utf-8');
			$dom->fromMixed(array('root'=>$data));
 			echo $dom->saveXML();
			break;
		case "css":
			header('Content-type: text/css');
			echo implode("\n\n", $data);
			break;
		case "preview":
			header('Content-type: text/html');
			echo $data;
			break;
			break;
	}
	
	// Checks Cache for Cleaning
	@cleanResourcesCache();
?>		