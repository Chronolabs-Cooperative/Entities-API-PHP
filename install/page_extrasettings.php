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

require_once './include/common.inc.php';
defined('API_INSTALL') || die('API Installation wizard die');

$wizard->loadLangFile('extras');

include_once './include/functions.php';

$pageHasForm = true;
$pageHasHelp = true;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && @$_GET['var'] && @$_GET['action'] === 'checkfile') {
    $file                   = $_GET['var'];
    echo genPathCheckHtml($file, is_file($file));
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enabled = array();
    foreach(array('imap','twitter','facebook','linkedin') as $mode)
        foreach($wizard->configs[$mode] as $setting => $values)
            $_SESSION['constants'][$mode][$setting] = $_POST[$setting];
    $wizard->redirectToPage('+1');
    return 302;
}
ob_start();
?>
	<div class="panel panel-info">
    <div class="panel-heading"><?php echo API_EXTRAS; ?></div>
        <div class="panel-body">
        <?php foreach(array('imap','twitter','facebook','linkedin') as $mode) { ?>
        <div class="form-group">
        	<div class="xoform-help alert-info"><?php echo constant("API_".strtoupper($mode)."_PARAGRAPH"); ?></div>
            <?php 
            foreach($wizard->configs[$mode] as $setting => $default)
            {?>
            <div class="form-group">
                <label for="<?php echo $setting; ?>"><?php echo constant("API_".strtoupper($mode).'_'.strtoupper($setting) . "_LABEL"); ?></label>
                <div class="xoform-help alert alert-info"><?php echo constant("API_".strtoupper($mode).'_'.strtoupper($setting) . "_HELP"); ?></div>
                <input type="text" class="form-control" name="<?php echo $setting; ?>" id="<?php echo $setting; ?>" value="<?php echo $default; ?>"/>
            </div>
            <?php } ?>
       </div>
       <?php } ?>
   </div>

<?php
$content = ob_get_contents();
ob_end_clean();

include './include/install_tpl.php';
