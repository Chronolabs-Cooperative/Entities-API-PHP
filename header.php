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

	require_once __DIR__ . '/apiconfig.php';
	
	$parts = explode(".", microtime(true));
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	$salter = ((float)(mt_rand(0,1)==1?'':'-').$parts[1].'.'.$parts[0]) / sqrt((float)$parts[1].'.'.intval(cosh($parts[0])))*tanh($parts[1]) * mt_rand(1, intval($parts[0] / $parts[1]));
	header('Blowfish-salt: '. $salter);
	
	