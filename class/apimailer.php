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

/**
 * The new Multimailer class that will carry out the actual sending and will later replace this class.
 * If you're writing new code, please use that class instead.
 */
include_once (dirname(__FILE__).'/mail/apimultimailer.php');

/**
 * Class for sending mail.
 *
 * Changed to use the facilities of  {@link APIMultiMailer}
 *
 * @package class
 * @subpackage mail
 * @author Kazumi Ono <onokazu@xoops.org>
 */
class APIMailer
{
    /**
     * reference to a {@link APIMultiMailer}
     *
     * @var APIMultiMailer
     * @access private
     * @since 21.02.2003 14:14:13
     */
    var $multimailer;
    // sender email address
    // private
    var $fromEmail;
    // sender name
    // private
    var $fromName;
    // RMV-NOTIFY
    // sender UID
    // private
    var $fromUser;
    // array of user class objects
    // private
    var $toUsers;
    // array of email addresses
    // private
    var $toEmails;
    // custom headers
    // private
    var $headers;
    // subjet of mail
    // private
    var $subject;
    // body of mail
    // private
    var $body;
    // error messages
    // private
    var $errors;
    // messages upon success
    // private
    var $success;
    // private
    var $isMail;
    // private
    var $isPM;
    // private
    var $assignedTags;
    // private
    var $template;
    // private
    var $templatedir;
    // protected
    var $charSet = 'iso-8859-1';
    // protected
    var $encoding = '8bit';
    
    /**
     * Constructor
     *
     * @return APIMailer
     */
    function __construct($from, $fromname, $method = 'SMTPAuth')
    {
        $this->multimailer = new APIMultiMailer($from, $fromname, $method);
        $this->reset();
        $this->from = $from;
        $this->fromname = $fromname;
        
    }

    // public     // reset all properties to default    
	function setHTML($value = true)
    {
        $this->multimailer->isHTML($value);
    }

    // public
    // reset all properties to default
    function reset()
    {
        $this->fromEmail = "";
        $this->fromName = "";
        $this->fromUser = null; // RMV-NOTIFY
        $this->priority = '';
        $this->toUsers = array();
        $this->toEmails = array();
        $this->headers = array();
        $this->subject = "";
        $this->body = "";
        $this->errors = array();
        $this->success = array();
        $this->isMail = false;
        $this->isPM = false;
        $this->assignedTags = array();
        $this->template = "";
        $this->templatedir = "";
        // Change below to \r\n if you have problem sending mail
        $this->LE = "\n";
    }
    
    // public
    function setTemplate($value)
    {
        $this->template = $value;
    }
    
    // pupblic
    function setFromEmail($value)
    {
        $this->fromEmail = trim($value);
    }
    
    // public
    function setFromName($value)
    {
        $this->fromName = trim($value);
    }
    
    // RMV-NOTIFY
    // public
    function setFromUser(&$user)
    {
        if (strtolower(get_class($user)) == "xoopsuser") {
            $this->fromUser = &$user;
        }
    }
    
    // public
    function setPriority($value)
    {
        $this->priority = trim($value);
    }
    
    // public
    function setSubject($value)
    {
        $this->subject = trim($value);
    }
    
    // public
    function setBody($value)
    {
        $this->body = trim($value);
    }
 
    /**
     * Send email
     *
     * Uses the new APIMultiMailer
     *
     * @param string $
     * @param string $
     * @param string $
     * @return boolean FALSE on error.
     */
    
    function sendMail($email = array(), $cc = array(), $bcc = array(), $subject = '', $body = '', $attachments = array(), $headers, $ishtml = false)
    {
        
        $this->multimailer->isHTML($ishtml);
        $this->multimailer->ClearAllRecipients();
        foreach($email as $addy)
        	$this->multimailer->AddAddress($addy, $addy);
        foreach($cc as $addy)
        	$this->multimailer->AddCC($addy, $addy);
        foreach($bcc as $addy)
        	$this->multimailer->AddBCC($addy, $addy);
        $this->multimailer->Subject = $subject;
        $this->multimailer->Body = $body;
        $this->multimailer->CharSet = $this->charSet;
        $this->multimailer->Encoding = $this->encoding;

        foreach($attachments as $file)
        {
        	$this->multimailer->AddAttachment(file_get_contents($file), basename($file));
        }
        $this->multimailer->ClearCustomHeaders();
        foreach($this->headers as $header) {
            $this->multimailer->AddCustomHeader($header);
        }
        if (! $this->multimailer->Send()) {
            $this->errors[] = $this->multimailer->ErrorInfo;
            return false;
        }
        return true;
    }
    
    // public
    function getErrors($ashtml = true)
    {
        if (! $ashtml) {
            return $this->errors;
        } else {
            if (! empty($this->errors)) {
                $ret = "<h4>" . _ERRORS . "</h4>";
                foreach($this->errors as $error) {
                    $ret .= $error . "<br />";
                }
            } else {
                $ret = "";
            }
            return $ret;
        }
    }
    
    // public
    function getSuccess($ashtml = true)
    {
        if (! $ashtml) {
            return $this->success;
        } else {
            $ret = "";
            if (! empty($this->success)) {
                foreach($this->success as $suc) {
                    $ret .= $suc . "<br />";
                }
            }
            return $ret;
        }
    }
    
    // public
    function assign($tag, $value = null)
    {
        if (is_array($tag)) {
            foreach($tag as $k => $v) {
                $this->assign($k, $v);
            }
        } else {
            if (! empty($tag) && isset($value)) {
                $tag = strtoupper(trim($tag));
                // RMV-NOTIFY
                // TEMPORARY FIXME: until the X_tags are all in here
                // if ( substr($tag, 0, 2) != "X_" ) {
                $this->assignedTags[$tag] = $value;
                // }
            }
        }
    }
    
    // public
    function addHeaders($value)
    {
        $this->headers[] = trim($value) . $this->LE;
    }
    
    // public
    function setToEmails($email)
    {
        if (! is_array($email)) {
            if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $email)) {
                array_push($this->toEmails, $email);
            }
        } else {
            foreach($email as $e) {
                $this->setToEmails($e);
            }
        }
    }
   
    // abstract
    // to be overidden by lang specific mail class, if needed
    function encodeFromName($text)
    {
        return $text;
    }
    
    // abstract
    // to be overidden by lang specific mail class, if needed
    function encodeSubject($text)
    {
        return $text;
    }
    
    // abstract
    // to be overidden by lang specific mail class, if needed
    function encodeBody(&$text)
    {
    }
}

?>
