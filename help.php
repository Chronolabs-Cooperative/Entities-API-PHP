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

	$emailmd5 = md5(NULL);
	$width = mt_rand(280, 750);
	$height = mt_rand(280, 750);
	$pixels = mt_rand(880, 2750);
	$nodes = getExampleNodes();
	$random = getExampleNodes();
	$entities = getExampleFingerprint();
	$entitiesmd5 = $entities['entity-id'];
	$emailmd5 = (!isset($entities['email-address-three-id']) && !empty($entities['email-address-three-id'])?(!isset($entities['email-address-two-id']) && !empty($entities['email-address-two-id'])?(!isset($entities['email-address-one-id']) && !empty($entities['email-address-one-id'])?md5(NULL):$entities['email-address-one-id']):$entities['email-address-two-id']):$entities['email-address-three-id']);
	$entitieshash = $entities['hash-referer'];
	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings, $source;

	if ($emailmd5 != md5(NULL) && !empty($emailmd5))
	{
		$sql = "SELECT * FROM `emails` WHERE `email-id` LIKE '%s'";
		$results = $GLOBALS['EntitiesDB']->queryF($sql = sprintf($sql, $emailmd5));
		if (!$emailrec = $GLOBALS['EntitiesDB']->fetchArray($results))
			die('Recordset Failed: ' . $sql);
		$emailmd5 = md5($emailrec['email']);
		$email = $emailrec['email'];
		
	} else {
		$sql = "SELECT * FROM `emails` WHERE `email-id` NOT LIKE '' ORDER BY RAND() LIMIT 1";
		$results = $GLOBALS['EntitiesDB']->queryF($sql);
		if (!$emailrec = $GLOBALS['EntitiesDB']->fetchArray($results))
			die('Recordset Failed: ' . $sql);
		$emailmd5 = md5($emailrec['email']);
		$email = $emailrec['email'];
	}
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
	<title><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Open API || Chronolabs Cooperative (Under Development)</title>
	<meta property="og:title" content="<?php echo $servicecode; ?> API"/>
	<meta property="og:type" content="<?php echo strtolower($servicecode); ?>-api"/>
	<!-- AddThis Smart Layers BEGIN -->
	<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
	<script type="text/javascript">
	  addthis.layers({
		'theme' : 'transparent',
		'share' : {
		  'position' : 'right',
		  'numPreferredServices' : 6
		}, 
		'follow' : {
		  'services' : [
			{'service': 'twitter', 'id': 'ChronolabsCoop'},
			{'service': 'twitter', 'id': 'Cipherhouse'},
			{'service': 'twitter', 'id': 'OpenRend'},
			{'service': 'facebook', 'id': 'Chronolabs'},
			{'service': 'linkedin', 'id': 'founderandprinciple'},
			{'service': 'google_follow', 'id': '105256588269767640343'},
			{'service': 'google_follow', 'id': '116789643858806436996'}
		  ]
		},  
		'whatsnext' : {},  
		'recommended' : {
		  'title': 'Recommended for you:'
		} 
	  });
	</script>
	<!-- AddThis Smart Layers END -->
</head>
<body>
<div class="main">
    <h1><?php echo $servicename; ?> (<?php echo $servicecode; ?>) Open API || Chronolabs Cooperative (Under Development)</h1>
    <p>This is an API Service for providing entities, individuals, companies and people to your application or website. It provides the the entities through either fingerprinting checksums for the entities or keywords from the nodes list when access the API inclusing JSON, XML, Serialisation, HTML, RAW, CSS and raw file outputs.</p>
    <h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a href="<?php echo API_URL; ?>/docs/" target="_blank"><?php echo API_URL; ?>/docs/</a>. These should outline the source code core functions and classes for the API to function!</p>
    <h2>AVATAR Document Output</h2>
    <p>This is done with the <em>view.api</em> extension at the end of the url, this is for the functions for entities on the API!</p>
    <blockquote>
        <font color="#001201">This is for a png image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/avatar.gif</a></strong></em><br /><br />
        <font color="#001201">This is for a png image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/small/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/small/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/small/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/small/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/small/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/small/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the entities in the fingerprint listed in the URI and will produce it small (80x80) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/medium/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/medium/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the entities in the fingerprint listed in the URI and will produce it medium (160x160) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/medium/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/medium/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the entities in the fingerprint listed in the URI and will produce it medium (160x160) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/medium/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/medium/avatar.gif</a></strong></em><br /><br />  
        <font color="#001201">This is for a png image output for a view of the entities in the fingerprint listed in the URI and will produce it large (200x200) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/large/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/large/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the entities in the fingerprint listed in the URI and will produce it large (200x200) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/large/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/large/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the entities in the fingerprint listed in the URI and will produce it large (200x200) avatar!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/large/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $entitiesmd5; ?>/large/avatar.gif</a></strong></em><br /><br />    
<?php 	if ($emailmd5 != md5(NULL))
	{?>
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in  the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in  the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in  the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/avatar.gif</a></strong></em><br /><br />
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in  the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/small/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/small/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in  the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/small/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/small/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/small/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/small/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/medium/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/medium/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/medium/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/medium/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it medium (160x160) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/medium/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/medium/avatar.gif</a></strong></em><br /><br />  
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/large/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/large/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/large/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/large/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/large/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/large/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $pixels; ?>x<?php echo $pixels; ?> pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $pixels; ?>/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $pixels; ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $pixels; ?>x<?php echo $pixels; ?> pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $pixels; ?>/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $pixels; ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $pixels; ?>x<?php echo $pixels; ?> pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $pixels; ?>/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $pixels; ?>/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $width; ?>x<?php echo $height; ?> width by height in pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.png" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $width; ?>x<?php echo $height; ?> width by height in pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.jpg" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the <strong><em>md5</em>("<em><?php echo $email; ?></em>");</strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $width; ?>x<?php echo $height; ?> width by height in pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.gif" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $emailmd5; ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.gif</a></strong></em><br /><br />
      <?php } ?>
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/avatar.png" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/avatar.jpg" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/avatar.gif" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/avatar.gif</a></strong></em><br /><br />
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/small/avatar.png" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/small/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/small/avatar.jpg" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/small/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it small (80x80) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/small/avatar.gif" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/small/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/medium/avatar.png" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/medium/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/medium/avatar.jpg" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/medium/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it medium (160x160) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/medium/avatar.gif" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/medium/avatar.gif</a></strong></em><br /><br />  
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/large/avatar.png" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/large/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/large/avatar.jpg" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/large/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produce it large (200x200) avatar; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/large/avatar.gif" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/large/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $pixels; ?>x<?php echo $pixels; ?> pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $pixels; ?>/avatar.png" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $pixels; ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $pixels; ?>x<?php echo $pixels; ?> pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $pixels; ?>/avatar.jpg" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $pixels; ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $pixels; ?>x<?php echo $pixels; ?> pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $pixels; ?>/avatar.gif" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $pixels; ?>/avatar.gif</a></strong></em><br /><br />    
        <font color="#001201">This is for a png image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $width; ?>x<?php echo $height; ?> width by height in pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.png" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.png</a></strong></em><br /><br />
        <font color="#001201">This is for a jpeg image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $width; ?>x<?php echo $height; ?> width by height in pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.jpg" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.jpg</a></strong></em><br /><br />
        <font color="#001201">This is for a gif image output for a view of the of the entities avatar using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI and will produces resizes maximum size fitted to scale of <?php echo $width; ?>x<?php echo $height; ?> width by height in pixels; this is the entities avatar which can be different from email when uploaded!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.gif" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/<?php echo $width; ?>x<?php echo $height; ?>/avatar.gif</a></strong></em><br /><br />
      
     </blockquote>
    <h2>VIEW Document Output</h2>
    <p>This is done with the <em>view.api</em> extension at the end of the url, this is for the functions for entities on the API!</p>
    <blockquote>
        <font color="#001201">This is for a html output for a view of the entities in the fingerprint listed in the URI!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entity/<?php echo $entitiesmd5; ?>/view.api" target="_blank"><?php echo API_URL; ?>/v2/entity/<?php echo $entitiesmd5; ?>/view.api</a></strong></em><br /><br />
        <font color="#001201">This is for a html output for a view of the entities in the referee short hash listed in the URI!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entity/<?php echo $entitieshash; ?>/view.api" target="_blank"><?php echo API_URL; ?>/v2/entity/<?php echo $entitiesmd5; ?>/view.api</a></strong></em><br /><br />
        <font color="#001201">This is for a html output for a view of the entities using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/view.api" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/view.api</a></strong></em><br /><br />
        <font color="#001201">This is for a html output for a view of the entities in using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/view.api" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/view.api</a></strong></em><br /><br />
        
    </blockquote>
    <h2>EDIT Document Output</h2>
    <p>This is done with the <em>edit.api</em> extension at the end of the url, this is for the functions for entities on the API!</p>
    <blockquote>
        <font color="#001201">This is for a html output for a editing of the entities in the fingerprint listed in the URI!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entity/<?php echo $entitiesmd5; ?>/edit.api" target="_blank"><?php echo API_URL; ?>/v2/entity/<?php echo $entitiesmd5; ?>/edit.api</a></strong></em><br /><br />
        <font color="#001201">This is for a html output for a editing of the entities in the referee short hash listed in the URI!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entity/<?php echo $entitieshash; ?>/edit.api" target="_blank"><?php echo API_URL; ?>/v2/entity/<?php echo $entitiesmd5; ?>/edit.api</a></strong></em><br /><br />
        <font color="#001201">This is for a html output for a editing the entities using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/view.api" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/edit.api</a></strong></em><br /><br />
        <font color="#001201">This is for a html output for a editing  entities in using the short referee <strong><?php echo $entitieshash; ?></strong> in the URI!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/view.api" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/edit.api</a></strong></em><br /><br />
    </blockquote>
    <h2>DOWNLOAD Document Output</h2>
    <p>This is done with the <em>download.api</em> extension at the end of the url, this is for the functions for entities on the API!</p>
    <blockquote>
        <font color="#001201">This is for a single entity output for a VCF of the entity in the fingerprint listed in the URI!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/vcf/<?php echo $entitiesmd5; ?>/download.api" target="_blank"><?php echo API_URL; ?>/v2/vcf/<?php echo $entitiesmd5; ?>/download.api</a></strong></em><br /><br />
        <font color="#001201">This is for a single entity output for a VCF of the entity in the short hash referee listed in the start of the URI!</font><br/>
        <em><strong><a href="<?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/vcf" target="_blank"><?php echo sprintf(API_URL_SHORT, $entitieshash); ?>/vcf</a></strong></em><br /><br />
        <font color="#001201">This is for a csv output for entities of the entities in the node list or keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/csv/<?php echo $nodes; ?>/download.api" target="_blank"><?php echo API_URL; ?>/v2/csv/<?php echo $nodes; ?>/download.api</a></strong></em><br /><br />
        <font color="#001201">This is for a csv output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis, also includes the country basis of listing;</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/csv/<?php echo $nodes; ?>/<?php echo $country; ?>/download.api" target="_blank"><?php echo API_URL; ?>/v2/csv/<?php echo $nodes; ?>/<?php echo $country; ?>/download.api</a></strong></em><br /><br />
        <font color="#001201">This is for a csv output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis by longitude+latitude plus surrounding kilometers!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/csv/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/download.api" target="_blank"><?php echo API_URL; ?>/v2/csv/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/download.api</a></strong></em><br /><br />
		<font color="#001201">This is for a csv output for entities of the entities in a random list of how many to return in this example, 200 records!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/csv/random/200/download.api" target="_blank"><?php echo API_URL; ?>/v2/csv/random/200/download.api</a></strong></em><br /><br />
    </blockquote>
    <h2>UPLOAD Document Output</h2>
    <p>This is done with the <em>upload.api</em> extension at the end of the url, you can upload and stage entities on the API permanently and upload them in the file formats of either each one by one or in an archive ZIP file the entities formats we will convert and use <strong style="text-shadow: 0px 0px 0px !important;">( *.<?php $formats = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'entities-converted.diz'); sort($formats); echo implode("  *.", array_unique($formats)); ?> )</strong> ~~ simply put them in a compressed archive if you want in any of these formats <strong style="text-shadow: 0px 0px 0px !important;">( *.<?php $packs = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'); sort($packs); echo implode("  *.", array_unique($packs)); ?> )</strong> containing any of these file formats any other will be ignored, you will be notified and sent a copy of the web entities when they are converted with example CSS via the email address!</p>
    <blockquote>
        <?php echo $upldform = getURIData(API_URL."/v2/uploads/forms.api", 560, 560, 
				array('return' => API_URL, 
				'callback' => '')); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($upldform); ?>
			</pre>
		</div>
    </blockquote>
    <h2>EDIT Document Output</h2>
    <p>This is done with the <em>edit.api</em> extension at the end of the url, this form will subscribe/unsubscribe you from recieving releases of entities after they have been catelogued by the survey! The API will email you a editing URL you can use to update as well as protect your information!</p>
    <blockquote>
        <?php echo $rlesform = getURIData(API_URL."/v2/edit/forms.api", 560, 560, 
				array('return' => API_URL, 
				'callback' => '')); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($rlesform); ?>
			</pre>
		</div>
    </blockquote>
    <h2>FORMS Document Output</h2>
    <p>This is done with the <em>forms.api</em> extension at the end of the urland will provide a HTML Submission form for the API in options the only modal for this at the moment is an Upload form!</p>
    <blockquote>
    <font color="#001201">The following examples for <em>forms.api</em> uses the cURL function <strong>getURIData()</strong> in PHP to use the example below in PHP!</font><br/><br/>
    <pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	if (!function_exists("getURIData")) {
	
		/* function getURIData() cURL Routine
		 * 
		 * @author 		Simon Roberts (labs.coop) wishcraft@users.sourceforge.net
		 * @return 		string
		 */
		function getURIData($uri = '', $timeout = 25, $connectout = 25, $post_data = array())
		{
			if (!function_exists("curl_init"))
			{
				return file_get_contents($uri);
			}
			if (!$btt = curl_init($uri)) {
				return false;
			}
			curl_setopt($btt, CURLOPT_HEADER, 0);
			curl_setopt($btt, CURLOPT_POST, (count($posts)==0?false:true));
			if (count($posts)!=0)
				curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post_data));
			curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
			curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($btt, CURLOPT_VERBOSE, false);
			curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($btt);
			curl_close($btt);
			return $data;
		}
	}
?&gt;

		</pre><br/><br/>
        <font color="#001201">You basically import and output to the buffer the HTML Submission form for the form to be emailed new releases of a entities at the following URI: <strong><?php echo API_URL; ?>/v2/edit/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	// output the table & form
	echo getURIData("<?php echo API_URL; ?>/v2/edit/forms.api", 560, 560, 
				 
				 /* URL Upload return after submission (required) */
				array('return' => '<?php echo $source; ?>', 
				
				/* URL for API Callback for progress and archive with data  (optional) */
				'callback' => '<?php echo API_URL; ?>/v2/releases/callback.api'));
?&gt;
		</pre><br/><br/>
		<font color="#001201">You basically import and output to the buffer the HTML Submission form for uploading a entities at the following URI: <strong><?php echo API_URL; ?>/v2/uploads/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	// output the table & form
	echo getURIData("<?php echo API_URL; ?>/v2/uploads/forms.api", 560, 560, 
				 
				 /* URL Upload return after submission (required) */
				array('return' => '<?php echo $source; ?>', 
				
				/* URL for API Callback for progress and archive with data  (optional) */
				'callback' => '<?php echo API_URL; ?>/v2/uploads/callback.api'));
?&gt;
		</pre>
		 <font color="#2e31c1; entities-size: 134%; entities-weight: 900;">An example of the callback routines the variables are outlined in this file you click and download the PHP Routines examples: <a href="/callback-example.php" target="_blank">callback-example.php</a></font>
    </blockquote>    
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>serial.api</em> extension at the end of the url, this is for the functions for entities on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categories for the entities on the API</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/categories/serial.api" target="_blank"><?php echo API_URL; ?>/v2/categories/serial.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for keywords for the entities on the API</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/keywords/serial.api" target="_blank"><?php echo API_URL; ?>/v2/keywords/serial.api</a></strong></em><br /><br />
		<font color="#001201">This is for a serialisation output for entities of the entities in the node list or keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/serial.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/serial.api</a></strong></em><br /><br />
        <font color="#001201">This is for a serialisation output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis, also includes the country basis of listing;</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $country; ?>/serial.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $country; ?>/serial.api</a></strong></em><br /><br />
        <font color="#001201">This is for a csv output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis by longitude+latitude plus surrounding kilometers!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/serial.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/serial.api</a></strong></em><br /><br />
		<font color="#001201">This is for a csv output for entities of the entities in a random list of how many to return in this example, 200 records!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/random/200/serial.api" target="_blank"><?php echo API_URL; ?>/v2/entities/random/200/serial.api</a></strong></em><br /><br />
    </blockquote>
    <h2>XML Document Output</h2>
    <p>This is done with the <em>xml.api</em> extension at the end of the url, this is for the functions for entities on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categories for the entities on the API</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/categories/xml.api" target="_blank"><?php echo API_URL; ?>/v2/categories/xml.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for keywords for the entities on the API</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/keywords/xml.api" target="_blank"><?php echo API_URL; ?>/v2/keywords/xml.api</a></strong></em><br /><br />
		<font color="#001201">This is for a XML output for entities of the entities in the node list or keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/xml.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/xml.api</a></strong></em><br /><br />
        <font color="#001201">This is for a XML output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis, also includes the country basis of listing;</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $country; ?>/xml.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $country; ?>/xml.api</a></strong></em><br /><br />
        <font color="#001201">This is for a csv output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis by longitude+latitude plus surrounding kilometers!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/xml.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/xml.api</a></strong></em><br /><br />
		<font color="#001201">This is for a csv output for entities of the entities in a random list of how many to return in this example, 200 records!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/random/200/xml.api" target="_blank"><?php echo API_URL; ?>/v2/entities/random/200/xml.api</a></strong></em><br /><br />
    </blockquote>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>json.api</em> extension at the end of the url, you replace the address with either a domain, an IPv4 or IPv6 address the following example is of calls to the api</p>
    <blockquote>
         <font color="#001201">This is for a list of all categories for the entities on the API</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/categories/json.api" target="_blank"><?php echo API_URL; ?>/v2/categories/json.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for keywords for the entities on the API</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/keywords/json.api" target="_blank"><?php echo API_URL; ?>/v2/keywords/json.api</a></strong></em><br /><br />
		<font color="#001201">This is for a JSON output for entities of the entities in the node list or keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/json.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/json.api</a></strong></em><br /><br />
        <font color="#001201">This is for a JSON output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis, also includes the country basis of listing;</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $country; ?>/json.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $country; ?>/json.api</a></strong></em><br /><br />
        <font color="#001201">This is for a csv output for entities for a selection of one of any of the entities keywords and/or categories you can also include the reserved keyword 'all' to retrieve all basis by longitude+latitude plus surrounding kilometers!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/json.api" target="_blank"><?php echo API_URL; ?>/v2/entities/<?php echo $nodes; ?>/<?php echo $longitude; ?>/<?php echo $latitude; ?>/<?php echo $kms; ?>/json.api</a></strong></em><br /><br />
		<font color="#001201">This is for a csv output for entities of the entities in a random list of how many to return in this example, 200 records!</font><br/>
        <em><strong><a href="<?php echo API_URL; ?>/v2/entities/random/200/json.api" target="_blank"><?php echo API_URL; ?>/v2/entities/random/200/json.api</a></strong></em><br /><br />
    </blockquote>
  <?php if (file_exists($fionf = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apis-labs.coop.html')) {
    	readfile($fionf);
    }?>	
    <?php if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) { ?>
    <h2>Limits</h2>
    <p>There is a limit of <?php echo MAXIMUM_QUERIES; ?> queries per hour. You can add yourself to the whitelist by using the following form API <a href="http://whitelist.<?php echo domain; ?>/">Whitelisting form (whitelist.<?php echo domain; ?>)</a>. This is only so this service isn't abused!!</p>
    <?php } ?>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2013 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>
<?php 
