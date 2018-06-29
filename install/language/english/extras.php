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
//
// _LANGCODE: en
// _CHARSET : UTF-8
// Translator: API Translation Team


define('API_EXTRAS',"API Constants & Extra Definitions");


define('API_WIDTH_LABEL','Mimimum original source for icon pixels in width x height!');
define('API_IDENTIFY_LABEL','ImageMagick Identifing executable');
define('API_IDENTIFY_HELP','You need to install imagemagick ie. $ sudo apt-get install imagemagick* -y');
define('API_CONVERT_LABEL','ImageMagick image conversion executable');
define('API_CONVERT_HELP','You need to install imagemagick ie. $ sudo apt-get install imagemagick* -y');

// Email Constants
define('API_IMAP_IMAP_LABEL','IMAP Service for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_IMAP_HELP','This is the service host netbios path name for the IMAP Services');
define('API_IMAP_SMTP_LABEL','SMTP Service for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_SMTP_HELP','This is the service host netbios path name for the SMTP Services');
define('API_IMAP_IMAPPORT_LABEL','IMAP Service Port for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_IMAPPORT_HELP','This is the service host netbios path name for the IMAP Services Port ');
define('API_IMAP_SMTPPORT_LABEL','SMTP Service Port for Catch All for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_SMTPPORT_HELP','This is the service host netbios path name for the SMTP Services Port ');
define('API_IMAP_CATCHALL_LABEL','This is the email address for the catch all for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_CATCHALL_HELP','This is the service host netbios path name for the Email Catch All');
define('API_IMAP_USERNAME_LABEL','IMAP, SMTP Service for Catch All Username for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_USERNAME_HELP','This is the service host netbios path name for the IMAP, SMTP Services Username');
define('API_IMAP_PASSWORD_LABEL','IMAP, SMTP Service for Catch All Password for this domain: ' . parse_url($_SESSION['settings']['URL'], PHP_URL_HOST));
define('API_IMAP_PASSWORD_HELP','This is the service host netbios path name for the IMAP, SMTP Services Password');
define('API_URLS_STRATA_URL_LABEL', 'Internet/Transnet Strata REST API (See: <a href="https://github.com/Chronolabs-Cooperative/Strata-API-PHP" target="_blank">github.com...</a>)');
define('API_URLS_STRATA_URL_HELP', 'You will need to install the Internet/Transnet Strata REST API or use a public version of it!');
define('API_URLS_LOOKUPS_URL_LABEL', 'IP Lookups REST API (See: <a href="https://github.com/Chronolabs-Cooperative/Lookups-API-PHP" target="_blank">github.com...</a>)');
define('API_URLS_LOOKUPS_URL_HELP', 'You will need to install the IP Lookups REST API or use a public version of it!');
define('API_URLS_WHOIS_URL_LABEL', 'IPv4/IPv6/Domain WhoIS Query REST API (See: <a href="https://github.com/Chronolabs-Cooperative/WhoIS-API-PHP" target="_blank">github.com...</a>)');
define('API_URLS_WHOIS_URL_HELP', 'You will need to install the IPv4/IPv6/Domain WhoIS Query REST API or use a public version of it!');
define('API_URLS_PLACES_URL_LABEL', 'GeoSpatial Places REST API (See: <a href="https://github.com/Chronolabs-Cooperative/Places-API-PHP" target="_blank">github.com...</a>)');
define('API_URLS_PLACES_URL_HELP', 'You will need to install the GeoSpatial Places REST API or use a public version of it!');
define('API_TWITTER_CONSUMER_KEY_LABEL', 'Twitter Consumer Key');
define('API_TWITTER_CONSUMER_KEY_HELP', 'This is the twitter consumer key found in your twitter application!');
define('API_TWITTER_CONSUMER_SECRET_LABEL', 'Twitter Consumer Secret');
define('API_TWITTER_CONSUMER_SECRET_HELP', 'This is the twitter consumer secret found in your twitter application!');
define('API_TWITTER_ACCESS_TOKEN_KEY_LABEL', 'Twitter Access Token Key');
define('API_TWITTER_ACCESS_TOKEN_KEY_HELP', 'This is the twitter access token key found in your twitter application!');
define('API_TWITTER_ACCESS_TOKEN_SECRET_LABEL', 'Twitter Access Token Secret');
define('API_TWITTER_ACCESS_TOKEN_SECRET_HELP', 'This is the twitter access token secret found in your twitter application!');
define('API_FACEBOOK_APP_ID_LABEL', 'Facebook App ID');
define('API_FACEBOOK_APP_ID_HELP', 'This is the facebook app id which is found in the basic setting of you facebook.com application!');
define('API_FACEBOOK_APP_SECRET_LABEL', 'Facebook App Secret');
define('API_FACEBOOK_APP_SECRET_HELP', 'This is the facebook app secret which is found in the basic setting of you facebook.com application!');
define('API_FACEBOOK_APP_CLIENT_TOKEN_LABEL', 'Facebook App Client Token');
define('API_FACEBOOK_APP_CLIENT_TOKEN_HELP', 'This is the facebook app client token which is found in the advanced setting of you facebook.com application!');
define('API_LINKEDIN_CLIENT_ID_LABEL', 'Linked-in Application Client ID');
define('API_LINKEDIN_CLIENT_ID_HELP', 'This is your linkedin.com client id for your application');
define('API_LINKEDIN_CLIENT_SECRET_LABEL', 'Linked-in Application Client Secret');
define('API_LINKEDIN_CLIENT_SECRET_HELP', 'This is your linkedin.com client secret for your application');

// Extra Paragraphs
define('API_IMAP_PARAGRAPH', 'This is all the mail settings for your entities api, it requires an email address to log onto and check automatically!');
define('API_URLS_PARAGRAPH', 'This is all the URL\'s settings for your secondary resources as rest api\'s, You will have to install them or use a 3rd party you will find there libraries here: <a href="https://github.com/Chronolabs-Cooperative" target="_blank">https://github.com/Chronolabs-Cooperative/</a>.');
define('API_TWITTER_PARAGRAPH', 'This is all the Twitter.com application settings for your entities api, you can create these at: <a href="https://apps.twitter.com" target="_blank">https://apps.twitter.com</a>!');
define('API_FACEBOOK_PARAGRAPH', 'This is all the Facebook.com application settings for your entities api, you can create these at: <a href="https://developers.facebook.com/docs/apps/register/" target="_blank">https://developers.facebook.com/docs/apps/register/</a>!');
define('API_LINKEDIN_PARAGRAPH', 'This is all the LinkedIn.com application settings for your entities api, you can create these at: <a href="https://www.linkedin.com/secure/developer?newapp=" target="_blank">https://www.linkedin.com/secure/developer?newapp=</a>!');
