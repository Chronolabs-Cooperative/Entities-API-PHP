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


if (!function_exists("setNewPassword")) {
    /**
     *
     * @param string $url
     * @param string $version
     * @param string $callback
     * @param string $polinating
     * @param string $root
     */
    function setNewPassword( $entityid,  $mode = 'view' ) {
        
        
        require_once __DIR__ . '/class/entitiesmailer.php';
        $fingers = $emails = array();
        $sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `entity-id` LIKE '%s'",$entityid);
        if (!$results = $GLOBALS['APIDB']->queryF($sql))
            die('SQL Failed: ' . $sql);
        if (!$entity = $GLOBALS['APIDB']->fetchArray($results))
            die('Recordset Failed: ' . $sql);
                $entityarray = getEntityArray($entity);
                if (!empty($entity['email-address-one-id']))
                    $fingers[$entity['email-address-one-id']] = $entity['email-address-one-id'];
                    if (!empty($entity['email-address-two-id']))
                        $fingers[$entity['email-address-two-id']] = $entity['email-address-two-id'];
                        if (!empty($entity['email-address-three-id']))
                            $fingers[$entity['email-address-three-id']] = $entity['email-address-three-id'];
                            
                            $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('emails') . "` WHERE `email-id` IN ('".implode("','", array_keys($fingers))."') AND `offlined` = 0";
                            if ($GLOBALS['APIDB']->getRowsNum($result = $GLOBALS['APIDB']->queryF($sql = sprintf($sql, time() - (3600*24*7*mt_rand(4.765, 7.876)), time() - (3600*24*7*mt_rand(4.765, 7.876)))))>=1)
                            {
                                while($row = $GLOBALS['APIDB']->fetchArray($result))
                                {
                                    $emails[$entityid][$row['email-id']] = $row;
                                }
                            } else
                                die ("SQL Failed: $sql ::: " . $GLOBALS['APIDB']->error());
                                
                                foreach($emails as $entityid => $values)
                                {
                                    foreach($values as $emailid => $email)
                                    {
                                        $mailer = new EntitiesMailer("wishcraft@users.sourceforge.net", "Entities Repository API");
                                        if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "SMTPAuth.diz"))
                                            $smtpauths = explode("\n", str_replace(array("\r\n", "\n\n", "\n\r"), "\n", file_get_contents($file)));
                                            if (count($smtpauths)>=1)
                                                $auth = explode("||", $smtpauths[mt_rand(0, count($smtpauths)-1)]);
                                                if (!empty($auth[0]) && !empty($auth[1]) && !empty($auth[2]))
                                                    $mailer->multimailer->setSMTPAuth($auth[0], $auth[1], $auth[2]);
                                                    $html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'email-request-new-password.html');
                                                    $html = str_replace("{X_API_URL}", API_URL, $html);
                                                    $html = str_replace("{X_API_SHORT_URL}", sprintf(API_URL_SHORT, $entity['hash-referer']), $html);
                                                    $html = str_replace("{X_ENTITY_AVATAR}", sprintf(API_URL_SHORT, $entity['hash-referer']) . "/avatar.png", $html);
                                                    foreach($entity as $key => $value)
                                                        $html = str_replace("{X_ENTITY_".strtoupper($key)."}", $value, $html);
                                                        foreach($entityarray as $key => $value)
                                                        {
                                                            if (is_array($values) && isset($values['data']) && is_array($values['data']))
                                                            {
                                                                foreach($values['data'] as $keyb => $data)
                                                                    $html = str_replace("{X_ENTITY_".strtoupper($key)."_".strtoupper($keyb)."}", $data, $html);
                                                            } elseif (is_array($values) && isset($values['data']) && is_string($values['data']))
                                                            {
                                                                $html = str_replace("{X_ENTITY_".strtoupper($key)."}", $values['data'], $html);
                                                            } elseif (is_array($values) && !isset($values['data']))
                                                            {
                                                                $html = str_replace("{X_ENTITY_".strtoupper($key)."}", implode(", ", $values), $html);
                                                            } elseif (is_string($values))
                                                            {
                                                                $html = str_replace("{X_ENTITY_".strtoupper($key)."}", $values, $html);
                                                            }
                                                        }
                                                        $html = str_replace("{X_MODE}", $mode, $html);
                                                        $html = str_replace("{X_NEWPASSWORD_LINK}", API_URL . '/v2/' . $entityid . '/' . $mode . '/new-password.api?token='.md5($entityid.$emailid.$GLOBALS['peerid']), $html);
                                                        $html = str_replace("{X_VERIFYLOGO}", API_URL . '/v2/' . $emailid . '/logo.png', $html);
                                                        $to = array($email['email'] => $email['display-name']);
                                                        $html = str_replace("{X_TONAME}", $email['display-name'], $html);
                                                        
                                                        if ($mailer->sendMail($to, array(),  array(), "New $mode password requested please utilise this email to recieve one!", $html, array(), NULL, true))
                                                        {
                                                            
                                                            $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `verified` = -100000 WHERE `email-id` LIKE '" . $emailid . "'";
                                                            if (!$GLOBALS['APIDB']->queryF($sql))
                                                                die('SQL Failed: ' . $sql);
                                                                $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `contacted` = '" . time() . "' WHERE `email-id` = '" . $emailid . "'";
                                                                if (!$GLOBALS['APIDB']->queryF($sql))
                                                                    die('SQL Failed: : ' . $sql . " :: " . $GLOBALS['APIDB']->error());
                                                        }
                                                        
                                    }
                                }
                                return true;
    }
}

if (!function_exists("generateNewPassword")) {
    /**
     *
     * @param number $seed
     */
    function generateNewPassword( $seed = 0 ) {
        mt_srand($seed);
        $len = mt_rand(7,14);
        $pass = '';
        while(strlen($pass)<$len)
        {
            switch (mt_rand(0,13))
            {
                case 0:
                default:
                    $pass .= chr(mt_rand(ord("a"), ord("z")));
                    break;
                case 1:
                case 8:
                case 9:
                    $pass .= chr(mt_rand(ord("A"), ord("Z")));
                    break;
                case 2:
                case 3:
                case 4:
                    $pass .= chr(mt_rand(ord("0"), ord("9")));
                    break;
                case 7:
                    $pass .= chr(mt_rand(ord("!"), ord("|")));
                    break;
            }
        }
        return (string)$pass;
    }
}
if (!function_exists("getPeerIdentity")) {
    /**
     *
     * @param string $url
     * @param string $version
     * @param string $callback
     * @param string $polinating
     * @param string $root
     */
    function getPeerIdentity( $url,  $short, $version, $callback, $polinating = true, $root = "http://entities.labs.coop" ) {
        
        $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `api-url` LIKE '%s'";
        if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($url))))==1)
        {
            $peer = $GLOBALS['APIDB']->fetchArray($results);
            return $peer['peer-id'];
        } else {
            if (strpos($url, 'localhost')>0)
                $polinating = false;
                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('peers') . "` (`peer-id`, `api-url`, `api-short-url`, `version`, `callback`, `created`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')";
                $peerid = md5($url.$version.$callback.$polinating.$root.microtime(true));
                if ($GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($peerid), mysql_escape_string($url), mysql_escape_string($shorturl), mysql_escape_string($version), mysql_escape_string($callback), time())))
                {
                    @getURIData($root . "/v2/register/callback.api", 145, 145, array('peer-id'=>$peerid, 'api-url' => $url, 'api-short-url' => $short, 'version' => $version, 'callback' => $callback, 'polinating' => $polinating));
                }
                return $peerid;
        }
        
    }
}

if (!function_exists("getHTMLLink")) {
    /**
     *
     * @param string $url
     */
    function getHTMLLink( $url = "") {
        if (empty($url))
            return "";
            if (substr($url,0,3)!='htt' || substr($url,0,3)!='ftp' )
                $url = "http://$url";
                $text = str_replace(array("http://", "https://", "ftp://", "ftps://", "www."), "", $url);
                if (strlen($text)>36)
                {
                    $path = parse_url($url, PHP_URL_PATH);
                    $query = parse_url($url, PHP_URL_QUERY);
                    if (strlen($path)>2)
                        $text = str_replace($path, substr($path,0,3).'...'.substr($path,strlen($path)-3,3), $text);
                        if (strlen($query)>2 && strlen($text)>36)
                            $text = str_replace($query, substr($query,0,3).'...'.substr($query,strlen($query)-3,3), $text);
                }
                return "<a target='_blank' href='$url'>" . $text . "</a>";
    }
}


if (!function_exists("formatField")) {
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    function get_gravatar( $email, $s = 2048, $d = 'mm', $r = 'x', $img = false, $atts = array() ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
                $url .= ' />';
        }
        return $url;
    }
}

if (!function_exists("formatField")) {
    /**
     *
     * @param array $array
     */
    function formatField($type = '', $value = "")
    {
        switch ($type)
        {
            default:
            case 'Email':
            case "String":
                return (string)$value;
                break;
            case "Integer":
            case 'Unix Time Stamp':
                return (integer)$value;
                break;
            case 'Phone Number':
                return str_replace(array("+", "-", ":", ")", "(", " "), "", (string)$value);
                break;
            case 'URL':
                return (!strpos((string)$value, "://")?"http://":"").(string)$value;
                break;
            case 'Date':
            case 'Time':
            case 'Date/time':
                return strtotime($value);
                break;
        }
        return (string)$value;
    }
}


if (!function_exists("webField")) {
    /**
     *
     * @param array $array
     */
    function webField($field = '', $value = "")
    {
        if (strpos(strtolower($value), "facebook.com"))
            $field = 'web-page-facebook';
            elseif (strpos(strtolower($value), "twitter.com"))
            $field = 'web-page-twitter';
            elseif (strpos(strtolower($value), "linkedin.com"))
            $field = 'web-page-linkedin';
            elseif (strpos(strtolower($value), "plus.google.com"))
            $field = 'web-page-googleplus';
            elseif (strpos(strtolower($value), "blog"))
            $field = 'web-page-blog';
            elseif (strpos(strtolower($value), "wordpress"))
            $field = 'web-page-blog';
            elseif (strpos(strtolower($value), "tumblr.com"))
            $field = 'web-page-blog';
            elseif (strpos(strtolower($value), "youtube.com"))
            $field = 'web-page-other';
            elseif (strpos(strtolower($value), "soundcloud.com"))
            $field = 'web-page-other';
            return (string)$field;
    }
}


if (!function_exists("getEntityArray")) {
    /**
     *
     * @param array $array
     */
    function getEntityArray($entity = array())
    {
        $ret = array();
        foreach($entity as $field => $value)
        {
            if (!empty($value))
                if (strlen($value) == 32 && strtolower($value)==$value && $value != md5(NULL) && strpos($field, "-id"))
                {
                    $sql = sprintf("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` WHERE `entity-id` LIKE '%s' AND `fingerprint` LIKE '%s'", $entity['entity-id'], $value);
                    if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF($sql))==1)
                    {
                        $finger = $GLOBALS['APIDB']->fetchArray($results);
                        $ret[str_replace("-id", "", $field)] = getFingeredArray($finger['type'], $value);
                    }
                } elseif ($value != md5(NULL))
                $ret[$field] = $value;
        }
        return $ret;
    }
}



if (!function_exists("getFingeredArray")) {
    /**
     *
     * @param array $array
     */
    function getFingeredArray($table = 'unknown', $hash = '')
    {
        $ret = array('record'=>array('ident'=>md5(NULL), 'table'=>$table), 'data'=>array());
        if (empty($table))
            return $ret;
            $key = "";
            switch ($table)
            {
                case "addresses":
                    $key = "address-id";
                    break;
                case "emails":
                    $key = "email-id";
                    break;
                case "phones":
                    $key = "phone-id";
                    break;
                case "strings":
                    $key = "string-id";
                    break;
                case "networking":
                    $key = "ip-id";
                    break;
                case "categories":
                    $key = "category-id";
                    break;
                case "entities":
                    $key = "entity-id";
                    break;
                case "imports":
                    $key = "import-id";
                    break;
                default:
                    $key = "";
                    break;
            }
            $sql = "SELECT * FROM `%s` WHERE `%s` LIKE '%s'";
            if (!empty($key))
            {
                switch ($table)
                {
                    case "addresses":
                    case "emails":
                    case "phones":
                    case "strings":
                    case "networking":
                    case "categories":
                    case "entities":
                    case "imports":
                        if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, $table, $key, $hash)))>0)
                        {
                            $ret = array('data' => $GLOBALS['APIDB']->fetchArray($results));
                            $ret['record']['ident'] = $ret['data'][$key];
                            $ret['record']['prime'] = $key;
                            $ret['record']['table'] = $table;
                            unset($ret['data'][$key]);
                            $ret['record']['fields'] = array_keys($ret['data']);
                            if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, 'fingerprints', 'fingerprint', $hash)))>0)
                            {
                                while($finger = $GLOBALS['APIDB']->fetchArray($results))
                                {
                                    $ret['record']['import-ids'][$finger['import-id']] = $finger['import-id'];
                                    $ret['record']['entity-ids'][$finger['entity-id']] = $finger['entity-id'];
                                }
                            }
                            foreach($ret['data'] as $key => $value)
                                if (empty($value) || trim($value) == '')
                                    unset($ret['data'][$key]);
                        }
                        break;
                }
            }
            return $ret;
    }
}

if (!function_exists("getCountryID")) {
    /**
     *
     * @param array $array
     */
    function getCountryID($country = '')
    {
        static $countries = array();
        if (empty($countries))
            $countries = json_decode(getURIData(getPlacesAPIURI() . '/list/list/json.api', 120, 120), true);
            foreach($countries['countries'] as $name => $values)
                if (strtolower($name)==strtolower($country))
                    return $values['key'];
                    return $country;
    }
}


if (!function_exists("getPlaceID")) {
    /**
     *
     * @param array $array
     */
    function getPlaceID($countryid = '', $name = '', $countriessupported = array())
    {
        if (empty($countryid))
            return '';
            static $countries = array();
            if (empty($countries))
                $countries = json_decode(getURIData(getPlacesAPIURI() . '/list/list/json.api', 120, 120), true);
                if (count($countriessupported))
                    foreach($countries['countries'] as $country => $values)
                        if (!in_array($values['key'], $countriessupported))
                            unset($countries['countries'][$country]);
                            foreach($countries['countries'] as $country => $values)
                                if (strtolower($countryid)==strtolower($values['key']) || strtolower($name)==strtolower($countryid))
                                    $count = strtolower(str_replace(" ", "", $name));
                                    if (!empty($count))
                                        $place = json_decode(getURIData(getPlacesAPIURI() . '/$count/$name/json.api', 120, 120), true);
                                        else
                                            foreach($countries['countries'] as $country => $values)
                                            {
                                                $country = strtolower(str_replace(" ", "", $country));
                                                $place = json_decode(getURIData(getPlacesAPIURI() . '/$country/$name/json.api', 120, 120), true);
                                                if (isset($place['country']['place']['RegionName']) && !empty($place['country']['place']['RegionName']))
                                                    return $place['country']['place']['key'];
                                            }
                                        if (isset($place['country']['place']['RegionName']) && !empty($place['country']['place']['RegionName']))
                                            return $place['country']['place']['key'];
                                            return $name;
    }
}

if (!function_exists("getPlaceArray")) {
    /**
     *
     * @param array $array
     */
    function getPlaceArray($countryid = '', $name = '', $countriessupported = array())
    {
        if (empty($countryid))
            return array();
            static $countries = array();
            if (empty($countries))
                $countries = json_decode(getURIData(getPlacesAPIURI() . '/list/list/json.api', 120, 120), true);
                if (count($countriessupported))
                    foreach($countries['countries'] as $country => $values)
                        if (!in_array($values['key'], $countriessupported))
                            unset($countries['countries'][$country]);
                            foreach($countries['countries'] as $country => $values)
                                if (strtolower($countryid)==strtolower($values['key']) || strtolower($name)==strtolower($countryid))
                                    $count = strtolower(str_replace(" ", "", $country));
                                    if (!empty($count))
                                        $place = json_decode(getURIData(getPlacesAPIURI() . '/$count/$name/json.api', 120, 120), true);
                                        else
                                            foreach($countries['countries'] as $country => $values)
                                            {
                                                $country = strtolower(str_replace(" ", "", $country));
                                                $place = json_decode(getURIData(getPlacesAPIURI() . '/$country/$name/json.api', 120, 120), true);
                                                if (isset($place['country']['place']['RegionName']) && !empty($place['country']['place']['RegionName']))
                                                    return $place['country']['place']['key'];
                                            }
                                        if (isset($place['country']['place']['RegionName']) && !empty($place['country']['place']['RegionName']))
                                            return $place['country']['place'];
                                            return array();
    }
}

if (!function_exists("importDataArray")) {
    /**
     *
     * @param array $array
     */
    function importDataArray($data = array(), $importid = '', $countriessupported = array(), $peerid = '')
    {
        $ret = array();
        foreach( $data as $entityid => $values)
        {
            $entity = $values['entity'];
            if (!empty($values['addresses']))
            {
                foreach($values['addresses'] as $type => $address)
                {
                    $address['type'] = $type;
                    if (!isset($address['longitude']) && !isset($address['latitude']))
                    {
                        $place = array();
                        if (isset($address['province']))
                            $place = getPlaceArray($address['country'], $address['province'], $countriessupported);
                            if (isset($address['city']) && count($place)<2)
                                $place = getPlaceArray($address['country'], $address['city'], $countriessupported);
                                if (isset($place['Longitude_Float']))
                                    $address['longitude'] = $place['Longitude_Float'];
                                    if (isset($place['Latitude_Float']))
                                        $address['latitude'] = $place['Latitude_Float'];
                    }
                    if (!isset($address['country-id']) && !empty($address['country']))
                        $address['country-id'] = getCountryID($address['country']);
                        if (!isset($address['place-id']) && !empty($address['province']))
                            $address['place-id'] = getPlaceID($address['country-id'], $address['province'], $countriessupported);
                            if (!isset($address['place-id']) && !empty($address['city']))
                                $address['place-id'] = getPlaceID($address['country-id'], $address['city'], $countriessupported);
                                
                                $sql = "SELECT `address-id` FROM `" . $GLOBALS['APIDB']->prefix('addresses') . "` WHERE ";
                                foreach($address as $field => $value)
                                    $sql .= " `$field` = '" . mysql_escape_string($value) . "' AND";
                                    $sql = substr($sql, 0, strlen($sql)-4);
                                    if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF($sql))==0)
                                    {
                                        $address['created'] = time();
                                        $address['entities'] = 1;
                                        foreach($address as $field => $value)
                                            $address[$field] =  mysql_escape_string($value);
                                            $addressid = $address['address-id'] = md5(microtime(true) . json_encode($address) . $peerid);
                                            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('addresses') . "` (`" . implode("`, `", array_keys($address)) . "`) VALUES ('" . implode("', '", $address) . "')";
                                            if (!$GLOBALS['APIDB']->queryF($sql))
                                                die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                                
                                    } else
                                        if (list($addressid) = $GLOBALS['APIDB']->fetchRow($results))
                                        {
                                            $address['address-id'] = $addressid;
                                            $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('addresses') . "` SET `" . $GLOBALS['APIDB']->prefix('entities') . "` = `" . $GLOBALS['APIDB']->prefix('entities') . "` + 1 WHERE `address-id` = '$addressid'";
                                            if (!$GLOBALS['APIDB']->queryF($sql))
                                                die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                        }
                                    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                                    if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                                    {
                                        while($other = $GLOBALS['APIDB']->fetchArray($results))
                                        {
                                            @getURIData(sprintf($other['callback'], 'import-address'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $address));
                                        }
                                    }
                                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` (`entity-id`, `import-id`, `peer-id`, `type`, `fingerprint`, `created`) VALUES ('$entityid', '$importid', '$peerid',  'addresses','$addressid', '".time()."')";
                                    if (!$GLOBALS['APIDB']->queryF($sql))
                                        die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                        switch($type)
                                        {
                                            case "Business":
                                                $entity['business-address-id'] = $addressid;
                                                break;
                                            case "Home":
                                                $entity['home-address-id'] = $addressid;
                                                break;
                                            case "Other":
                                                $entity['other-address-id'] = $addressid;
                                                break;
                                        }
                }
            }
            if (!empty($values['phones']))
            {
                foreach($values['phones'] as $type => $number)
                {
                    $phone = array();
                    $phone['number'] = $number;
                    foreach(array('Business','Home','Callback','Fax','Car','Switch','ISDN','Mobile','Other','Pager','Radio','TTY/TDD','Primary','Unknown') as $typal)
                        if (strpos($type, strtolower(str_replace("/", "-", $typal))))
                            $phone['type'] = $typal;
                            if (!isset($phone['type']))
                                $phone['type'] = "Unknown";
                                
                                $sql = "SELECT `phone-id` FROM `" . $GLOBALS['APIDB']->prefix('phones') . "` WHERE ";
                                foreach($phone as $field => $value)
                                    $sql .= " `$field` = '" . mysql_escape_string($value) . "' AND";
                                    $sql = substr($sql, 0, strlen($sql)-4);
                                    if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF($sql))==0)
                                    {
                                        $phone['created'] = time();
                                        $phone['entities'] = 1;
                                        foreach($phone as $field => $value)
                                            $phone[$field] =  mysql_escape_string($value);
                                            $phoneid = $phone['phone-id'] = md5(microtime(true) . json_encode($phone) . $peerid);
                                            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('phones') . "` (`" . implode("`, `", array_keys($phone)) . "`) VALUES ('" . implode("', '", $phone) . "')";
                                            if (!$GLOBALS['APIDB']->queryF($sql))
                                                die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                    } else
                                        if (list($phoneid) = $GLOBALS['APIDB']->fetchRow($results))
                                        {
                                            $phone['phone-id'] = $phoneid;
                                            $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('phones') . "` SET `" . $GLOBALS['APIDB']->prefix('entities') . "` = `" . $GLOBALS['APIDB']->prefix('entities') . "` + 1 WHERE `phone-id` = '$phoneid'";
                                            if (!$GLOBALS['APIDB']->queryF($sql))
                                                die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                        }
                                    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                                    if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                                    {
                                        while($other = $GLOBALS['APIDB']->fetchArray($results))
                                        {
                                            @getURIData(sprintf($other['callback'], 'import-phone'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $phone));
                                        }
                                    }
                                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` (`entity-id`, `import-id`, `peer-id`, `type`, `fingerprint`, `created`) VALUES ('$entityid', '$importid', '$peerid',  'phones','$phoneid', '".time()."')";
                                    if (!$GLOBALS['APIDB']->queryF($sql))
                                        die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                        if (isset($phoneid))
                                            $entity["$type-id"] = $phoneid;
                                            unset($phoneid);
                }
            }
            if (!empty($values['email']))
            {
                foreach($values['email'] as $type => $dvalues)
                {
                    $email = array();
                    $email['email'] = $dvalues['address'];
                    $email['display-name'] = $dvalues['display'];
                    if (empty($email['display-name']))
                        $email['display-name'] = $entity['first-name'] . " " . $entity['last-name'];
                        $sql = "SELECT `email-id` FROM `" . $GLOBALS['APIDB']->prefix('emails') . "` WHERE ";
                        foreach($email as $field => $value)
                            $sql .= " `$field` = '" . mysql_escape_string($value) . "' AND";
                            $sql = substr($sql, 0, strlen($sql)-4);
                            if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF($sql))==0)
                            {
                                $email['created'] = time();
                                $email['entities'] = 1;
                                $email['type'] = "Email";
                                foreach($email as $field => $value)
                                    $email[$field] =  mysql_escape_string($value);
                                    $emailid = $email['email-id'] = md5(microtime(true) . json_encode($email) . $peerid);
                                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('emails') . "` (`" . implode("`, `", array_keys($email)) . "`) VALUES ('" . implode("', '", $email) . "')";
                                    if (!$GLOBALS['APIDB']->queryF($sql))
                                        die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                            } else
                                if (list($emailid) = $GLOBALS['APIDB']->fetchRow($results))
                                {
                                    $email['email-id'] = $emailid;
                                    $email['type'] = "Email";
                                    $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('emails') . "` SET `" . $GLOBALS['APIDB']->prefix('entities') . "` = `" . $GLOBALS['APIDB']->prefix('entities') . "` + 1 WHERE `email-id` = '$emailid'";
                                    if (!$GLOBALS['APIDB']->queryF($sql))
                                        die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                }
                            $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                            if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                            {
                                while($other = $GLOBALS['APIDB']->fetchArray($results))
                                {
                                    @getURIData(sprintf($other['callback'], 'import-email'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $email));
                                }
                            }
                            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` (`entity-id`, `import-id`, `peer-id`, `type`, `fingerprint`, `created`) VALUES ('$entityid', '$importid', '$peerid',  'emails','$emailid', '".time()."')";
                            if (!$GLOBALS['APIDB']->queryF($sql))
                                die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                if (isset($emailid))
                                    $entity["$type-id"] = $emailid;
                                    unset($emailid);
                }
            }
            if (!empty($values['strings']))
            {
                foreach($values['strings'] as $type => $dvalues)
                {
                    $string = array();
                    $string['data'] = $dvalues;
                    $sql = "SELECT `string-id` FROM `" . $GLOBALS['APIDB']->prefix('strings') . "` WHERE ";
                    foreach($email as $field => $value)
                        $sql .= " `$field` = '" . mysql_escape_string($value) . "' AND";
                        $sql = substr($sql, 0, strlen($sql)-4);
                        if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF($sql))==0)
                        {
                            foreach($string as $field => $value)
                                $string[$field] =  mysql_escape_string($value);
                                $stringid = $string['string-id'] = md5(microtime(true) . json_encode($string) . $peerid);
                                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('strings') . "` (`" . implode("`, `", array_keys($string)) . "`) VALUES ('" . implode("', '", $string) . "')";
                                if (!$GLOBALS['APIDB']->queryF($sql))
                                    die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                        } else
                            if (list($stringid) = $GLOBALS['APIDB']->fetchRow($results))
                            {
                                $string['string-id'] = $stringid;
                                if (empty($stringid))
                                    die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
                            }
                        $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                        if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                        {
                            while($other = $GLOBALS['APIDB']->fetchArray($results))
                            {
                                @getURIData(sprintf($other['callback'], 'import-string'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $string));
                            }
                        }
                        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` (`entity-id`, `import-id`, `peer-id`, `type`, `fingerprint`, `created`) VALUES ('$entityid', '$importid', '$peerid',  'strings','$stringid', '".time()."')";
                        if (!$GLOBALS['APIDB']->queryF($sql))
                            die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                            if (isset($stringid) && !empty($stringid))
                                $entity["$type-id"] = $stringid;
                                unset($stringidid);
                }
            }
            if (!empty($values['keywords']))
            {
                foreach($values['keywords'] as $id => $keyword)
                {
                    if (!empty($keyword) && trim($keyword)!='')
                    {
                        $sql = "SELECT `keyword-id` FROM `" . $GLOBALS['APIDB']->prefix('keywords') . "` WHERE `keyword` LIKE '" . mysql_escape_string($keyword) . "'";
                        if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF($sql))==0)
                        {
                            $key = array('keyword' => $keyword);
                            $key['created'] = time();
                            $key['entities'] = 1;
                            foreach($key as $field => $value)
                                $key[$field] =  mysql_escape_string($value);
                                if (!empty($key))
                                {
                                    $keywordid = $key['keyword-id'] = md5(microtime(true) . json_encode($key) . $peerid);
                                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('keywords') . "` (`" . implode("`, `", array_keys($key)) . "`) VALUES ('" . implode("', '", $key) . "')";
                                    if (!$GLOBALS['APIDB']->queryF($sql))
                                        die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                                }
                        } else
                            if (list($keywordid) = $GLOBALS['APIDB']->fetchRow($results))
                            {
                                $key['keyword-id'] = $keywordid;
                                $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('keywords') . "` SET `" . $GLOBALS['APIDB']->prefix('entities') . "` = `" . $GLOBALS['APIDB']->prefix('entities') . "` + 1 WHERE `keyword-id` = '$keywordid'";
                                if (!$GLOBALS['APIDB']->queryF($sql))
                                    die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
                            }
                        $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                        if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                        {
                            while($other = $GLOBALS['APIDB']->fetchArray($results))
                            {
                                @getURIData(sprintf($other['callback'], 'import-keyword'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $key));
                            }
                        }
                        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('keywords_entities') . "` (`entity-id`, `keyword-id`) VALUES ('$entityid', '$keywordid')";
                        if (!$GLOBALS['APIDB']->queryF($sql))
                            die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                            
                            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` (`entity-id`, `import-id`, `peer-id`, `type`, `fingerprint`, `created`) VALUES ('$entityid', '$importid', '$peerid',  'keywords','$keywordid', '".time()."')";
                            if (!$GLOBALS['APIDB']->queryF($sql))
                                die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                    }
                }
            }
            
            if (!empty($values['categories']))
            {
                foreach($values['categories'] as $id => $categoryid)
                {
                    $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('categories_entities') . "` (`entity-id`, `category-id`) VALUES ('$entityid', '$categoryid')";
                    if (!$GLOBALS['APIDB']->queryF($sql))
                        die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                        $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('categories') . "` SET `" . $GLOBALS['APIDB']->prefix('entities') . "` = `" . $GLOBALS['APIDB']->prefix('entities') . "` + 1 WHERE `category-id` = '$categoryid'";
                        if (!$GLOBALS['APIDB']->queryF($sql))
                            die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
                            
                }
                $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                {
                    while($other = $GLOBALS['APIDB']->fetchArray($results))
                    {
                        @getURIData(sprintf($other['callback'], 'import-categories'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $values['categories']));
                    }
                }
            }
            $entity['peer-id'] = $peerid;
            $entity['entity-id'] = $entityid;
            $entity['import-id'] = $importid;
            $entity['created'] = time();
            foreach($entity as $field => $value)
                $entity[$field] =  mysql_escape_string($value);
                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('entities') . "` (`" . implode("`, `", array_keys($entity)) . "`) VALUES ('" . implode("', '", $entity) . "')";
                if (!$GLOBALS['APIDB']->queryF($sql))
                    die("Insert SQL Failed: $sql :: " . $GLOBALS['APIDB']->error());
                    $sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('peers') . "` WHERE `peer-id` NOT LIKE '%s' AND `polinating` = 'Yes'";
                    if ($GLOBALS['APIDB']->getRowsNum($results = $GLOBALS['APIDB']->queryF(sprintf($sql, mysql_escape_string($GLOBALS['peerid']))))>=1)
                    {
                        while($other = $GLOBALS['APIDB']->fetchArray($results))
                        {
                            @getURIData(sprintf($other['callback'], 'import-entity'), 145, 145, array('peer-id'=>$peerid, 'import-id' => $importid, 'entity-id' => $entityid, 'data' => $entity));
                        }
                    }
                    $ret[$importid][$entityid] = $entity;
                    $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('imports') . "` SET `records` = `records` + 1 WHERE `import-id` = '$importid'";
                    if (!$GLOBALS['APIDB']->queryF($sql))
                        die("Update Failed: $sql :: " . $GLOBALS['APIDB']->error());
        }
        return $ret;
    }
}




if (!function_exists("csv_fields")) {
    /**
     *
     * @param string $string
     * @param string $row_delimiter
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    function csv_fields($farray = array(), $row_delimiter = PHP_EOL, $delimiter = ",", $enclosure = '"', $escape = "\\" )
    {
        $header = NULL;
        foreach($farray as $row)
        {
            if (substr($row, strlen($row) - strlen($row_delimiter), strlen($row_delimiter))==$row_delimiter)
                $row = substr($row, 0, strlen($row) - strlen($row_delimiter));
            $row = str_getcsv ($row, $delimiter, $enclosure, $escape);
            if(!$header)
                return $row;
        }
        return array();
    }
}

if (!function_exists("csv_to_array")) {
    /**
     *
     * @param string $string
     * @param string $row_delimiter
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    function csv_to_array($farray = array(), $row_delimiter = PHP_EOL, $delimiter = ",", $enclosure = '"', $escape = "\\" )
    {
        $header = NULL;
        $data = array();
        
        foreach($farray as $row)
        {
            if (substr($row, strlen($row) - strlen($row_delimiter), strlen($row_delimiter))==$row_delimiter)
                $row = substr($row, 0, strlen($row) - strlen($row_delimiter));
            $row = str_getcsv ($row, $delimiter, $enclosure, $escape);
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        
        return $data;
    }
}

if (!function_exists("getEnumerators")) {
    /**
     *
     * @param unknown_type $mode
     * @return array
     */
    function getEnumerators($mode = '')
    {
        switch ($mode)
        {
            case "fields":
            case "field":
                return array('Category','Title','Full Name','First Name','Middle Name','Last Name','Suffix','Company','Department','Job Title','Instant Messaging One','Instant Messaging Two','Business Street One','Business Street Two','Business Street Three','Business Province','Business City','Business State','Business Country','Business Postcode','Business Longitude','Business Latitude','Business Serial Number Postage','Home Street One','Home Street Two','Home Street Three','Home Province','Home City','Home State','Home Country','Home Postcode','Home Longitude','Home Latitude','Home Serial Number Postage','Other Street One','Other Street Two','Other Street Three','Other Province','Other City','Other State','Other Country','Other Postcode','Other Longitude','Other Latitude','Other Serial Number Postage','Assistant Phone Number','Business Fax Number','Business Phone Number One','Business Phone Number Two','Callback Phone Number','Car Phone Number','Business Switch Phone Number','Home Fax Number','Home Phone Number One','Home Phone Number Two','ISDN Phone Number','Mobile Phone Number','Other Phone Number','Other Fax Number','Pager Phone Number','Primary Phone Number','Radio Phone Number','TTY/TDD Phone Number','Telex','Account','Anniversary','Assistants Name','Billing Information','Birthday','Children','Email One Address','Email One Display Name','Email Two Address','Email Two Display Name','Email Three Address','Email Three Display Name','Gender','Government ID Number','Hobby','Intials','Keywords','Languages','Country','Location Place','Milage','Notes','Office Country','Office Location Place','Registered Business Number','Profession','Refereed By','Spouse','Web Page Primary','Web Page Blog','Web Page Facebook','Web Page Twitter','Web Page Linked-in','Web Page Google+','Web Page Other','Skip (Unknown)');
                break;
            case "types":
            case "type":
                return array('String','Integer','Unix Time Stamp','URL','Email','Phone Number','Date','Time','Date/time','Unknown');
                break;
        }
        return array();
    }
}


if (!function_exists("getHTMLForm")) {
    /**
     *
     * @param unknown_type $mode
     * @param unknown_type $clause
     * @param unknown_type $output
     * @param unknown_type $version
     * @return string
     */
    function getHTMLForm($mode = '', $clause = '', $callback = '', $output = '', $version = 'v2')
    {
        $ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
        $form = array();
        switch ($mode)
        {
            case "uploads":
                $form[] = "<form name='" . $ua . "' method='POST' enctype='multipart/form-data' action='" . API_URL . '/v2/' .$ua . "/upload.api'>";
                $form[] = "\t<table class='entities-uploader' id='entities-uploader' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='email'>Uploader's Email:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='textbox' name='email' id='email' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='name'>Uploader's Name:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' maxlen='198' size='41' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;' valign='top'>";
                $form[] = "\t\t\t\t<label for='country'>CSV's Cover countries:&nbsp;</label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<table width='100%' style='font-size: 65% !important;'>";
                $form[] = "\t\t\t\t\t<tr>";
                $i=0;
                foreach(getCountries() as $name => $country)
                {
                    $i++;
                    $form[] = "\t\t\t\t\t\t<div style='font-size: 55.5556% !important; font-weight: 900 !important; float: left; width: 30% !important; border-bottom: 2px solid #000; clear: none; margin: 4px; padding: 3px'><label for='country-".$country['key']."'>".$name.(!empty($country['TLD'])?" (".$country['TLD'].')':"")."</label>&nbsp;<input type='checkbox' name='country[".$country['key']."]' id='country-".$country['key']."' value='".$country['key']."' /></div>";
                }
                $form[] = "\t\t\t\t\t</tr>";
                $form[] = "\t\t\t\t</table>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                
                
                $form[] = "\t\t<tr style='padding: 9px;'>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='seperated'>Field Seperating Delimiter:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='textbox' name='seperated' id='seperated' maxlen='6' size='8' value=','/><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr style='padding: 9px;'>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='strings'>Field String Defined Delimiter:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='textbox' name='strings' id='strings' maxlen='6' size='8' value='\"'/><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr style='padding: 9px;'>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='escapes'>Field Escaped Delimiter:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='textbox' name='escapes' id='escapes' maxlen='6' size='8' value='\\'/><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr style='padding: 9px;'>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='eol'>Field End-of-Line Delimiter:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='textbox' name='eol' id='eol' maxlen='6' size='8' value='\\n'/><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px;'>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr style='padding: 9px;'>";
                $form[] = "\t\t\t<td valign='top'>";
                $form[] = "\t\t\t\t<label for='".$ua."'>Entities/Pack to upload:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2' style='padding: 9px;'>";
                $form[] = "\t\t\t\t<input type='file' name='" . $ua . "' id='" . $ua ."'><br/>";
                $form[] = "\t\t\t\t<div style='margin-left:12px; font-size: 72%; entities-size: 71.99%; margin-top: 7px; padding: 11px;'>";
                $form[] = "\t\t\t\t\t ~~ <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); entities-weight: bold; entities-size: 132.6502%;'>" . ini_get('upload_max_filesize') . "!!!</em></strong><br/>";
                $formats = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'entities-converted.diz'); sort($formats);
                $form[] = "\t\t\t\t\t ~~ <strong>Entities File Formats Supported: <em style='color:rgb(15,70 43); entities-weight: bold; entities-size: 81.6502%;'>*." . str_replace("\n" , "", implode(" *.", array_unique($formats))) . "</em></strong>!<br/>";
                $packs = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'); sort($packs);
                $form[] = "\t\t\t\t\t ~~ <strong>Compressed File Pack Supported: <em style='color:rgb(55,10,33); entities-weight: bold; entities-size: 81.6502%;'>*." . str_replace("\n" , "", implode("  *.", array_unique($packs))) . "</em></strong>!<br/>";
                $form[] = "\t\t\t\t</div>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr style='padding: 9px;'>";
                $form[] = "\t\t\t<td colspan='1' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='return' value='" . (empty($clause)?$GLOBALS['protocol'] . $_SERVER["HTTP_HOST"]:$clause) ."'>";
                $form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . (empty($callback)?'':$callback) ."'>";
                $form[] = "\t\t\t\t<input type='submit' value='Upload File' name='submit' style='padding:11px; entities-size:122%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
            case "edit":
                $form[] = "<form name='" . $ua . "' method='POST' enctype='multipart/form-data' action='" . $GLOBALS['protocol'] . $_SERVER["HTTP_HOST"] . '/v2/' .$ua . "/edit.api'>";
                $form[] = "\t<table class='entities-releases' id='entities-releases' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='email'>Entity Email:&nbsp;<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='email[$ua]' id='email' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='password'>Entity Password (If Protected):</label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='password' name='password[$ua]' id='password' size='41' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='return[$ua]' value='" . (empty($clause)?$GLOBALS['protocol'] . $_SERVER["HTTP_HOST"]:$clause) ."'>";
                $form[] = "\t\t\t\t<input type='submit' value='Get Entity Editing Email' name='submit' style='padding:11px; entities-size:111%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
                $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); entities-size: 139%; entities-weight: bold;'>* </font><font  style='color: rgb(10,10,10); entities-size: 99%; entities-weight: bold'><em style='entities-size: 76%'>~ Required Field for Form Submission</em></font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
        }
        return implode("\n", $form);
    }
}

if (!function_exists("getCountries")) {
    /**
     * getBaseDomain
     *
     * @param string $url
     * @return string|unknown
     */
    function getCountries($search = '', $field = '')
    {
        if (!empty($field) && !empty($search))
            $sql = "SELECT md5(concat(`CountryID`,`Country`)) as `key`, `" . $GLOBALS['APIDB']->prefix('countries') . "`.* FROM `" . $GLOBALS['APIDB']->prefix('countries') . "` WHERE `$field` LIKE '$search%' ORDER BY `Country`";
        else 
            $sql = "SELECT md5(concat(`CountryID`,`Country`)) as `key`, `" . $GLOBALS['APIDB']->prefix('countries') . "`.* FROM `" . $GLOBALS['APIDB']->prefix('countries') . "` ORDER BY `Country`";
        $return = array();
        if (!$result = $GLOBALS['APIDB']->queryF($sql))
            die("SQL Error: $sql;");
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            $return[$row['Country']] = $row;
        return $return;
    }
}

if (!function_exists("getBaseDomain")) {
    /**
     * getBaseDomain
     *
     * @param string $url
     * @return string|unknown
     */
    function getBaseDomain($url)
    {
        
        static $fallout, $stratauris, $classes;
        
        if (empty($classes))
        {
            if (empty($stratauris)) {
                $stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
                shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
            }
            shuffle($stratauris);
            $attempts = 0;
            while(empty($classes) || $attempts <= (count($stratauris) * 1.65))
            {
                $attempts++;
                $classes = array_keys(unserialize(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/strata/serial.api", 120, 120)));
            }
        }
        if (empty($fallout))
        {
            if (empty($stratauris)) {
                $stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
                shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
            }
            shuffle($stratauris);
            $attempts = 0;
            while(empty($fallout) || $attempts <= (count($stratauris) * 1.65))
            {
                $attempts++;
                $fallout = array_keys(unserialize(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/fallout/serial.api", 120, 120)));
            }
        }
        
        // Get Full Hostname
        $url = strtolower($url);
        $hostname = parse_url($url, PHP_URL_HOST);
        if (!filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
            return $hostname;
        
        // break up domain, reverse
        $elements = explode('.', $hostname);
        $elements = array_reverse($elements);
        
        // Returns Base Domain
        if (in_array($elements[0], $classes))
            return $elements[1] . '.' . $elements[0];
        elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
            return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
        elseif (in_array($elements[0], $fallout))
            return  $elements[1] . '.' . $elements[0];
        else
            return  $elements[1] . '.' . $elements[0];
    }
}

if (!function_exists("getUploadHTML")) {
    
    /* function getPeersSupporting()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		array()
     */
    function getUploadHTML($return = '')
    {
        $forms = array();
        
        $forms[md5($return)]['html'] = getURIData(API_URL . "/v2/uploads/forms.api", 83, 83, array('return'=>$return));
        $forms[md5($return)]['timeout'] = time() + mt_rand(3600*3.5,3600*9.5) * mt_rand(4.5,11.511);
        
        return $forms[md5($return)]['html'];
    }
}

if (!function_exists("mkdirSecure")) {
    /**
     *
     * @param unknown_type $path
     * @param unknown_type $perm
     * @param unknown_type $secure
     */
    function mkdirSecure($path = '', $perm = 0777, $secure = true)
    {
        if (!is_dir($path))
        {
            mkdir($path, $perm, true);
            if ($secure == true)
            {
                writeRawFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
            }
            return true;
        }
        return false;
    }
}

if (!function_exists("cleanWhitespaces")) {
    /**
     *
     * @param array $array
     */
    function cleanWhitespaces($array = array())
    {
        foreach($array as $key => $value)
        {
            if (is_array($value))
                $array[$key] = cleanWhitespaces($value);
                else {
                    $array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
                }
        }
        return $array;
    }
}

if (!function_exists("getURIData")) {
    
    /* function getURIData()
     *
     * 	cURL Routine
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 65, $connectout = 65, $post_data = array())
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

if (!function_exists("writeRawFile")) {
    /**
     *
     * @param string $file
     * @param string $data
     */
    function writeRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            if (strpos(' '.$file, ENTITIES_CACHE))
                mkdirSecure(dirname($file), 0777, true);
            else
                mkdir(dirname($file), 0777, true);
        elseif (strpos(' '.$file, ENTITIES_CACHE) && !file_exists(ENTITIES_CACHE . DIRECTORY_SEPARATOR . '.htaccess'))
            writeRawFile(ENTITIES_CACHE . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
        if (is_file($file))
            unlink($file);
        $data = str_replace("\n", $lineBreak, $data);
        $ff = fopen($file, 'w');
        fwrite($ff, $data, strlen($data));
        fclose($ff);
    }
}

if (!function_exists("getMimetype")) {
    function getMimetype($type = '')
    {
        $result = $GLOBALS['APIDB']->queryF("SELECT * from `" . $GLOBALS['APIDB']->prefix('mimetypes') . "` WHERE `type` LIKE '$type'");
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            return $row['mimetype'];
        return 'text/html';
    }
}

if (!function_exists("getExampleNodes")) {
    function getExampleNodes()
    {
        $nodes = array();
        $result = $GLOBALS['APIDB']->queryF("SELECT * from `" . $GLOBALS['APIDB']->prefix('categories') . "` WHERE LENGTH(`category`) > 3 ORDER BY RAND() LIMIT " . mt_rand(1,3));
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            $nodes[] = str_replace(" ", "-", strtolower($row['category']));
            $result = $GLOBALS['APIDB']->queryF("SELECT * from `" . $GLOBALS['APIDB']->prefix('keywords') . "` WHERE LENGTH(`keyword`) > 3 ORDER BY RAND() LIMIT " . mt_rand(1,3));
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            $nodes[] = str_replace(" ", "-", strtolower($row['keyword']));
        sort($nodes);
        return str_replace("---", "--", implode('--', $nodes));
    }
}

if (!function_exists("getExampleFingerprint")) {
    function getExampleFingerprint()
    {
        $nodes = array();
        $result = $GLOBALS['APIDB']->queryF("SELECT * from `" . $GLOBALS['APIDB']->prefix('entities') . "` WHERE `view-protected` = 'No' ORDER BY RAND() LIMIT 1");
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            return $row;
    }
}

if (!function_exists("getExampleCsvFiles")) {
    function getExampleCsvFiles($md5 = '')
    {
        $entities = array();
        $result = $GLOBALS['APIDB']->queryF("SELECT * from `" . $GLOBALS['APIDB']->prefix('entities_archiving') . "` WHERE `entities_id` = '$md5'");
        while($row = $GLOBALS['APIDB']->fetchArray($result))
            if (!empty($row['type']))
                $entities[$row['type']] = $row;
                return $entities;
    }
}

if (!function_exists("getArchivedCsvFile")) {
    function getArchivedCsvFile($Csv_resource = '', $Csv_file = '')
    {
        $data = false;
        $Csv = Csv_open($Csv_resource);
        if ($Csv) {
            while ($Csv_entry = Csv_read($Csv)) {
                if (strpos('  '.strtolower(Csv_entry_name($Csv_entry)), strtolower($Csv_file)))
                    if (Csv_entry_open($Csv, $Csv_entry, "r")) {
                        $data = Csv_entry_read($Csv_entry, Csv_entry_filesize($Csv_entry));
                        Csv_entry_close($Csv_entry);
                    }
            }
            Csv_close($Csv);
        }
        return $data;
    }
}

if (!function_exists('sef'))
{
    
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     * @return string
     */
    function sef($value = '', $stripe ='-')
    {
        $value = str_replace('&', 'and', $value);
        $value = str_replace(array("'", '"', "`"), 'tick', $value);
        $replacement_chars = array();
        $accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
            "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
        for($i=0;$i<256;$i++){
            if (!in_array(strtolower(chr($i)),$accepted))
                $replacement_chars[] = chr($i);
        }
        $result = (str_replace($replacement_chars, $stripe, ($value)));
        while(substr($result, 0, strlen($stripe)) == $stripe)
            $result = substr($result, strlen($stripe), strlen($result) - strlen($stripe));
        while(substr($result, strlen($result) - strlen($stripe), strlen($stripe)) == $stripe)
            $result = substr($result, 0, strlen($result) - strlen($stripe));
        while(strpos($result, $stripe . $stripe))
            $result = str_replace($stripe . $stripe, $stripe, $result);
        return(strtolower($result));
    }
}

if (!function_exists("redirect")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @return bool|mixed
     */
    function redirect($url = '', $seconds = 9, $message = '')
    {
        $GLOBALS['url'] = $url;
        $GLOBALS['time'] = $seconds;
        $GLOBALS['message'] = $message;
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'redirect.php';
        exit(-1000);
    }
}


 if (!function_exists("getCompleteCsvListAsArray")) {
     function getCompleteCsvListAsArray($dirname, $result = array())
     {
         foreach(getDirListAsArray($dirname) as $path)
         {
             foreach(getCsvListAsArray($dirname . DIRECTORY_SEPARATOR . $path) as $file=>$values)
                 $result['csv'][md5_file($dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file)][] = $dirname . DIRECTORY_SEPARATOR . $path . $file;
                 $result = getCompleteCsvListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
         }
         foreach(getCsvListAsArray($dirname) as $file=>$values)
             $result['csv'][md5_file($dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file)][] = $dirname . DIRECTORY_SEPARATOR . $path  . $file;
             return $result;
     }
 }
 
 if (!function_exists("getCsvListAsArray")) {
     function getCsvListAsArray($dirname, $prefix = '')
     {
         $filelist = array();
         if ($handle = opendir($dirname)) {
             while (false !== ($file = readdir($handle))) {
                 if (preg_match('/(\.csv)$/i', $file)) {
                     $file = $prefix . $file;
                     $filelist[$file] = $file;
                 }
             }
             closedir($handle);
             asort($filelist);
             reset($filelist);
         }
         return $filelist;
     }
 }
 
 
 
 if (!function_exists("getPlacesAPIURI")) {
     function getPlacesAPIURI($asarray = false)
     {
         $places = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'places-uris.diz'));
         if ($asarray==true)
             return $places;
         return $places[mt_rand(0, count($places) -1)];
     }
 }
 
 if (!function_exists("getCsvListAsArray")) {
     function getCsvListAsArray($dirname, $prefix = '')
     {
         $formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'entities-converted.diz'));
         $filelist = array();
         
         if ($handle = opendir($dirname)) {
             while (false !== ($file = readdir($handle))) {
                 foreach($formats as $format)
                     if (substr(strtolower($file), strlen($file)-strlen($format)) == strtolower($format)) {
                         $file = $prefix . $file;
                         $filelist[$format] = array('file'=>$file, 'type'=>$format);
                     }
             }
             closedir($handle);
         }
         return $filelist;
     }
 }
 
 
 
 
 if (!function_exists("getCsvCullList")) {
     function getCsvCullList($files = array())
     {
         $handlers = $ret = array();
         foreach($files as $type => $entities)
         {
             foreach($entities as $hashinfo => $entities)
             {
                 $id = str_replace(array("_", '.', "-", " ", ",", "="), '', substr(strtolower(basename($entities)), 0, strlen($entities) - strlen($type)));
                 $handlers[$type][$id] = $hashinfo;
             }
         }
         $keys = array_keys($handlers);
         foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'entities-converted.diz')) as $prefered)
         {
             if (in_array($prefered, $keys))
             {
                 unset($keys[$prefered]);
                 foreach($keys as $key)
                 {
                     foreach($handlers[$prefered] as $idc => $finger)
                     {
                         foreach($handlers[$key] as $idd => $fingerb)
                         {
                             if ($idc == $idd)
                                 $ret[$finger][$key][$fingerb] = $files[$key][$finger];
                         }
                     }
                 }
             }
         }
         $sql = "SELECT COUNT(*) as RC from `" . $GLOBALS['APIDB']->prefix('entities_fingering') . "` where `fingerprint` LIKE '%s'";
         foreach($ret as $fingerprint => $filevars)
         {
             list($count) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF(sprintf($sql, $fingerprint)));
             if ($count<1)
                 unset($ret[$fingerprint]);
         }
         return $ret;
     }
 }
 
if (!function_exists("getHistoryListAsArray")) {
	function getHistoryListAsArray($dirname, $prefix = '')
	{
		$formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'history-formats.diz'));
		$filelist = array();

		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				foreach($formats as $format)
					if (substr(strtolower($file), strlen($file)-strlen(".".$format)) == strtolower(".".$format)) {
						$file = $prefix . $file;
						$filelist[$file] = array('file'=>$file, 'type'=>$format, 'sha1' => sha1_file($dirname . DIRECTORY_SEPARATOR . $file));
					}
			}
			closedir($handle);
		}
		return $filelist;
	}
}

if (!function_exists("setCallBackURI")) {
    /*
     * set's a callback to be called in the database reference for the cronjob
     *
     * @param string $uri
     * @param integer $timeout
     * @param integer $connectout
     * @param array $data
     * @param array $queries
     *
     * @return boolean
     */
    function setCallBackURI($uri = '', $timeout = 65, $connectout = 65, $data = array(), $queries = array())
    {
        list($when) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT `when` from `" . $GLOBALS['APIDB']->prefix('callbacks') . "` ORDER BY `when` DESC LIMIT 1"));
        if ($when<time())
            $when = $time();
            $when = $when + mt_rand(3, 14);
            return $GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('callbacks') . "` (`when`, `uri`, `timeout`, `connection`, `data`, `queries`) VALUES(\"$when\", \"$uri\", \"$timeout\", \"$connectout\", \"" . $GLOBALS['APIDB']->escape(json_encode($data)) . "\",\"" . $GLOBALS['APIDB']->escape(json_encode($queries)) . "\")");
    }
}

if (!function_exists("setExecutionTimer")) {
    /**
     * get a font nodes by Font Identity Hash
     *
     * @param string $font_id
     *
     * @return array
     */
    function setExecutionTimer($what = 'header')
    {
        static $seconds = 0;
        if ($seconds == 0)
            $seconds = ini_get('max_execution_time');
            
            if (file_exists($cache = getCacheFilename(FONTS_CACHE , 'execution-timers--%s--%s.json',sha1($what), 'php')))
            {
                $timers = json_decode(file_get_contents($cache), true);
                foreach($timers as $id => $time)
                {
                    $ttl = $ttl + $time;
                }
                $seconds = $seconds + ($ttl / count($timers));
            } else {
                $seconds = $seconds + 9;
            }
            set_time_limit($seconds);
            ini_set('max_execution_time', $seconds);
    }
}

if (!function_exists("saveExecutionTimer")) {
    /**
     * get a font nodes by Font Identity Hash
     *
     * @param string $font_id
     *
     * @return array
     */
    function saveExecutionTimer()
    {
        static $seconds = 0;
        if ($seconds == 0)
            $seconds = ini_get('max_execution_time');
            
            $cache = getCacheFilename(FONTS_CACHE , 'execution-timers--%s--%s.json',sha1('header'), 'php');
            $timers = json_decode(file_get_contents($cache), true);
            $timers[$GLOBAL['header']['start']] = $GLOBAL['header']['end'] - $GLOBAL['header']['start'];
            if(($max = mt_rand(32,78))>count($timers))
            {
                $keys = array_keys($timers);
                $index = $max;
                foreach($keys as $key)
                {
                    if ($index>0)
                    {
                        unset($timers[$key]);
                        $index--;
                    }
                }
            }
            @writeRawFile($cache, json_encode($timers));
            if (isset($GLOBAL['apifuncs']) && !empty($GLOBAL['apifuncs']))
            {
                foreach($GLOBAL['apifuncs'] as $what => $values)
                {
                    $cache = getCacheFilename(FONTS_CACHE , 'execution-timers--%s--%s.json',sha1($what), 'php');
                    $timers = json_decode(file_get_contents($cache), true);
                    $timers[$values['start']] = $values['end'] - $values['start'];
                    if(($max = mt_rand(32,78))>count($timers))
                    {
                        $keys = array_keys($timers);
                        $index = $max;
                        foreach($keys as $key)
                        {
                            if ($index>0)
                            {
                                unset($timers[$key]);
                                $index--;
                            }
                        }
                    }
                    @writeRawFile($cache, json_encode($timers));
                }
                
            }
            
    }
}


if (!function_exists("whitelistGetIP")) {
    /**
     * Provides an associative array of whitelisted IP Addresses
     *
     * @return array
     */
    function whitelistGetIPAddy() {
        return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
    }
}

if (!function_exists("whitelistGetNetBIOSIP")) {
    /**
     * provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
     *
     * @return array
     */
    function whitelistGetNetBIOSIP() {
        $ret = array();
        foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
            $ip = gethostbyname($domain);
            $ret[$ip] = $ip;
        }
        return $ret;
    }
}

if (!function_exists("whitelistGetIP")) {
    /**
     * get the True IPv4/IPv6 address of the client using the API
     *
     * @param boolean $asString
     *
     * @return mixed
     */
    function whitelistGetIP($asString = true){
        
        // Gets the proxy ip sent by the user
        $proxy_ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else
            if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
                $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
            } else
                if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                    $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
                } else
                    if (!empty($_SERVER['HTTP_FORWARDED'])) {
                        $proxy_ip = $_SERVER['HTTP_FORWARDED'];
                    } else
                        if (!empty($_SERVER['HTTP_VIA'])) {
                            $proxy_ip = $_SERVER['HTTP_VIA'];
                        } else
                            if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
                                $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
                            } else
                                if (!empty($_SERVER['HTTP_COMING_FROM'])) {
                                    $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
                                }
        
        if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
            $the_IP = $regs[0];
        } else {
            $the_IP = $_SERVER['REMOTE_ADDR'];
        }
        
        if (isset($_REQUEST['ip']) && !empty($_REQUEST['ip']) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $_REQUEST['ip'], $regs) && count($regs) > 0)  {
            $ip = $regs[0];
        }
        
        return isset($ip) && !empty($ip)?(($asString) ? $ip : ip2long($ip)):(($asString) ? $the_IP : ip2long($the_IP));
    }
}

if (!function_exists("getBaseDomain")) {
    /**
     * Gets the base domain of a tld with subdomains, that is the root domain header for the network rout
     *
     * @param string $url
     *
     * @return string
     */
    function getBaseDomain($uri = '')
    {
        
        static $fallout, $stratauris, $classes;
        
        if (API_NETWORK_LOGISTICS==true)
        {
            if (empty($classes))
            {
                if (empty($stratauris)) {
                    $stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
                    shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
                }
                shuffle($stratauris);
                $attempts = 0;
                while(empty($classes) || $attempts <= (count($stratauris) * 1.65))
                {
                    $attempts++;
                    $classes = array_keys(json_decode(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/strata/serial.api", 15, 10), true));
                }
            }
            if (empty($fallout))
            {
                if (empty($stratauris)) {
                    $stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
                    shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
                }
                shuffle($stratauris);
                $attempts = 0;
                while(empty($fallout) || $attempts <= (count($stratauris) * 1.65))
                {
                    $attempts++;
                    $fallout = array_keys(json_decode(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/fallout/serial.api", 15, 10), true));
                }
            }
            
            // Get Full Hostname
            $uri = strtolower($uri);
            $hostname = parse_url($uri, PHP_URL_HOST);
            if (!filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
                return $hostname;
            
            // break up domain, reverse
            $elements = explode('.', $hostname);
            $elements = array_reverse($elements);
            
            // Returns Base Domain
            if (in_array($elements[0], $classes))
                return $elements[1] . '.' . $elements[0];
            elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
                return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
            elseif (in_array($elements[0], $fallout))
                return  $elements[1] . '.' . $elements[0];
            else
                return  $elements[1] . '.' . $elements[0];
        }
        
        return parse_url($uri, PHP_URL_HOST);
    }
}
if (!function_exists("getMimetype")) {
    /**
     * Get the mime type for a file extension
     *
     * @param string $extension
     *
     * @return string
     */
    function getMimetype($extension = '-=-')
    {
        $mimetypes = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mimetypes.diz'));
        foreach($mimetypes as $mimetype)
        {
            $parts = explode("||", $mimetype);
            if (strtolower($extension) == strtolower($parts[0]))
                return $parts[1];
                if (strtolower("-=-") == strtolower($parts[0]))
                    $final = $parts[1];
        }
        return $final;
    }
}

if (!function_exists("mkdirSecure")) {
    /**
     * Make a folder and secure's it with .htaccess mod-rewrite with apache2
     *
     * @param string $path
     * @param integer $perm
     * @param boolean $secure
     *
     * @return boolean
     */
    function mkdirSecure($path = '', $perm = 0777, $secure = true)
    {
        if (!is_dir($path))
        {
            mkdir($path, $perm, true);
            if ($secure == true)
            {
                SaveToFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
            }
            return true;
        }
        return false;
    }
}

if (!function_exists("cleanWhitespaces")) {
    /**
     * Clean's an array of \n, \r, \t when importing for example with file() and includes carriage returns in array
     *
     * @param array $array
     *
     * @return array
     */
    function cleanWhitespaces($array = array())
    {
        foreach($array as $key => $value)
        {
            if (is_array($value))
                $array[$key] = cleanWhitespaces($value);
                else {
                    $array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
                }
        }
        return $array;
    }
}

if (!function_exists("getURIData")) {
    /**
     * uses cURL to return data from the URL/URI with POST Data if required
     *
     * @param string $urt
     * @param integer $timeout
     * @param integer $connectout
     * @param array $post_data
     *
     * @return string
     */
    function getURIData($uri = '', $timeout = 65, $connectout = 65, $post_data = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Need to install php-curl: $ sudo apt-get install php-curl");
        }
        if (!$btt = curl_init($uri)) {
            return false;
        }
        curl_setopt($btt, CURLOPT_HEADER, 0);
        curl_setopt($btt, CURLOPT_POST, (count($post_data)==0?false:true));
        if (count($post_data)!=0)
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

if (!function_exists('sef'))
{
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     *
     * @return string
     */
    function sef($value = '', $stripe ='-')
    {
        return(strtolower(getOnlyAlpha($result, $stripe)));
    }
}


if (!function_exists('getOnlyAlpha'))
{
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     *
     * @return string
     */
    function getOnlyAlpha($value = '', $stripe ='-')
    {
        $value = str_replace('&', 'and', $value);
        $value = str_replace(array("'", '"', "`"), 'tick', $value);
        $replacement_chars = array();
        $accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
            "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
        for($i=0;$i<256;$i++){
            if (!in_array(strtolower(chr($i)),$accepted))
                $replacement_chars[] = chr($i);
        }
        $result = trim(str_replace($replacement_chars, $stripe, ($value)));
        while(strpos($result, $stripe.$stripe, 0))
            $result = (str_replace($stripe.$stripe, $stripe, $result));
        while(substr($result, 0, strlen($stripe)) == $stripe)
            $result = substr($result, strlen($stripe), strlen($result) - strlen($stripe));
        while(substr($result, strlen($result) - strlen($stripe), strlen($stripe)) == $stripe)
            $result = substr($result, 0, strlen($result) - strlen($stripe));
        return($result);
    }
}

if (!function_exists("spacerName")) {
    /**
     * Formats font name to correct definition textualisation without typed precisioning
     *
     * @param string $name
     *
     * @return string
     */
    function spacerName($name = '')
    {
        $name = getOnlyAlpha(str_replace(array('-', ':', ',', '<', '>', ';', '+', '_', '(', ')', '[', ']', '{', '}', '='), ' ', $name), ' ');
        $nname = '';
        $previous = $last = '';
        for($i=0; $i<strlen($name); $i++)
        {
            if (substr($name, $i, 1)==strtoupper(substr($name, $i, 1)) && $last==strtolower($last))
            {
                $nname .= ' ' . substr($name, $i, 1);
            } else
                $nname .= substr($name, $i, 1);
                $last=substr($name, $i, 1);
        }
        while(strpos($nname, '  ')>0)
            $nname = str_replace('  ', ' ', $nname);
            return trim(implode(' ', array_unique(explode(' ', $nname))));
    }
}

if (!function_exists("redirect")) {
    /**
     * Redirect HTML Display
     *
     * @param string $uri
     * @param integer $seconds
     * @param string $message
     *
     */
    function redirect($uri = '', $seconds = 9, $message = '')
    {
        $GLOBALS['url'] = $uri;
        $GLOBALS['time'] = $seconds;
        $GLOBALS['message'] = $message;
        require_once API_ROOT_PATH . DIRECTORY_SEPARATOR . 'redirect.php';
        exit(-1000);
    }
}

if (!function_exists("checkEmail")) {
    /**
     * checks if a data element is an email address
     *
     * @param mixed $email
     *
     * @return bool|mixed
     */
    function checkEmail($email)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return $email;
    }
}


if (!function_exists("deleteFilesNotListedByArray")) {
    /**
     * deletes all files and folders contained within the path passed which do not match the array for file skipping
     *
     * @param string $dirname
     * @param array $skipped
     *
     * @return array
     */
    function deleteFilesNotListedByArray($dirname, $skipped = array())
    {
        $deleted = array();
        foreach(array_reverse(getCompleteFilesListAsArray($dirname)) as $file)
        {
            $found = false;
            foreach($skipped as $skip)
                if (strtolower(substr($file, strlen($file)-strlen($skip)))==strtolower($skip))
                    $found = true;
                    if ($found == false)
                    {
                        if (unlink($file))
                        {
                            $deleted[str_replace($dirname, "", dirname($file))][] = basename($file);
                            rmdir(dirname($file));
                        }
                    }
        }
        return $deleted;
    }
    
}

if (!function_exists("getCompleteFilesListAsArray")) {
    /**
     * Get a complete file listing for a folder and sub-folder
     *
     * @param string $dirname
     * @param string $remove
     *
     * @return array
     */
    function getCompleteFilesListAsArray($dirname, $remove = '')
    {
        foreach(getCompleteDirListAsArray($dirname) as $path)
            foreach(getFileListAsArray($path) as $file)
                $result[str_replace($remove, '', $path.DIRECTORY_SEPARATOR.$file)] = str_replace($remove, '', $path.DIRECTORY_SEPARATOR.$file);
                return $result;
    }
    
}


if (!function_exists("getCompleteDirListAsArray")) {
    /**
     * Get a complete folder/directory listing for a folder and sub-folder
     *
     * @param string $dirname
     * @param array $result
     *
     * @return array
     */
    function getCompleteDirListAsArray($dirname, $result = array())
    {
        $result[$dirname] = $dirname;
        foreach(getDirListAsArray($dirname) as $path)
        {
            $result[$dirname . DIRECTORY_SEPARATOR . $path] = $dirname . DIRECTORY_SEPARATOR . $path;
            $result = getCompleteDirListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
        }
        return $result;
    }
    
}

if (!function_exists("getCompletePacksListAsArray")) {
    /**
     * Get a complete all packed archive supported for a folder and sub-folder
     *
     * @param string $dirname
     * @param array $result
     *
     * @return array
     */
    function getCompletePacksListAsArray($dirname, $result = array())
    {
        foreach(getCompleteDirListAsArray($dirname) as $path)
        {
            foreach(getPacksListAsArray($path) as $file=>$values)
                $result[$values['type']][md5_file( $path . DIRECTORY_SEPARATOR . $values['file'])] =  $path . DIRECTORY_SEPARATOR . $values['file'];
        }
        return $result;
    }
}

if (!function_exists("getDirListAsArray")) {
    /**
     * Get a folder listing for a single path no recursive
     *
     * @param string $dirname
     *
     * @return array
     */
    function getDirListAsArray($dirname)
    {
        $ignored = array(
            'cvs' ,
            '_darcs', '.git', '.svn');
        $list = array();
        if (substr($dirname, - 1) != '/') {
            $dirname .= '/';
        }
        if ($handle = opendir($dirname)) {
            while ($file = readdir($handle)) {
                if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
                    continue;
                    if (is_dir($dirname . $file)) {
                        $list[$file] = $file;
                    }
            }
            closedir($handle);
            asort($list);
            reset($list);
        }
        return $list;
    }
}

if (!function_exists("getFileListAsArray")) {
    /**
     * Get a file listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getFileListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if (substr($dirname, - 1) == '/') {
            $dirname = substr($dirname, 0, - 1);
        }
        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
                    $file = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }
        return $filelist;
    }
}

if (!function_exists("getPacksListAsArray")) {
    /**
     * Get a compressed archives file listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getPacksListAsArray($dirname, $prefix = '')
    {
        $packs = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'));
        $filelist = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                foreach($packs as $pack)
                    if (substr(strtolower($file), strlen($file)-strlen(".".$pack)) == strtolower(".".$pack)) {
                        $file = $prefix . $file;
                        $filelist[$file] = array('file'=>$file, 'type'=>$pack);
                    }
            }
            closedir($handle);
        }
        return $filelist;
    }
}

if (!function_exists("getStampingShellExec")) {
    /**
     * Get a bash shell execution command for stamping archives
     *
     * @return array
     */
    function getStampingShellExec()
    {
        $ret = array();
        foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-stamping.diz')) as $values)
        {
            $parts = explode("||", $values);
            $ret[$parts[0]] = $parts[1];
        }
        return $ret;
    }
}

if (!function_exists("getArchivingShellExec")) {
    /**
     * Get a bash shell execution command for creating archives
     *
     * @return array
     */
    function getArchivingShellExec()
    {
        $ret = array();
        foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-archiving.diz')) as $values)
        {
            $parts = explode("||", $values);
            $ret[$parts[0]] = $parts[1];
        }
        return $ret;
    }
}

if (!function_exists("getExtractionShellExec")) {
    /**
     * Get a bash shell execution command for extracting archives
     *
     * @return array
     */
    function getExtractionShellExec()
    {
        $ret = array();
        foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-extracting.diz')) as $values)
        {
            $parts = explode("||", $values);
            $ret[$parts[0]] = $parts[1];
        }
        return $ret;
    }
}

if (!class_exists("XmlDomConstruct")) {
    /**
     * class XmlDomConstruct
     *
     * 	Extends the DOMDocument to implement personal (utility) methods.
     *
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     */
    class XmlDomConstruct extends DOMDocument {
        
        /**
         * Constructs elements and texts from an array or string.
         * The array can contain an element's name in the index part
         * and an element's text in the value part.
         *
         * It can also creates an xml with the same element tagName on the same
         * level.
         *
         * ex:
         * <nodes>
         *   <node>text</node>
         *   <node>
         *     <field>hello</field>
         *     <field>world</field>
         *   </node>
         * </nodes>
         *
         * Array should then look like:
         *
         * Array (
         *   "nodes" => Array (
         *     "node" => Array (
         *       0 => "text"
         *       1 => Array (
         *         "field" => Array (
         *           0 => "hello"
         *           1 => "world"
         *         )
         *       )
         *     )
         *   )
         * )
         *
         * @param mixed $mixed An array or string.
         *
         * @param DOMElement[optional] $domElement Then element
         * from where the array will be construct to.
         *
         * @author 		Simon Roberts (Chronolabs) simon@labs.coop
         *
         */
        public function fromMixed($mixed, DOMElement $domElement = null) {
            
            $domElement = is_null($domElement) ? $this : $domElement;
            
            if (is_array($mixed)) {
                foreach( $mixed as $index => $mixedElement ) {
                    
                    if ( is_int($index) ) {
                        if ( $index == 0 ) {
                            $node = $domElement;
                        } else {
                            $node = $this->createElement($domElement->tagName);
                            $domElement->parentNode->appendChild($node);
                        }
                    }
                    
                    else {
                        $node = $this->createElement($index);
                        $domElement->appendChild($node);
                    }
                    
                    $this->fromMixed($mixedElement, $node);
                    
                }
            } else {
                $domElement->appendChild($this->createTextNode($mixed));
            }
            
        }
        
    }
}

