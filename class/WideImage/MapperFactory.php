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
 * @author Gasper Kozak
 * @copyright 2007-2011

    This file is part of WideImage.
		
    WideImage is free software; you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.
		
    WideImage is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.
		
    You should have received a copy of the GNU Lesser General Public License
    along with WideImage; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    * @package WideImage
  **/
	
	/**
	 * Thrown when image format isn't supported
	 * 
	 * @package Exceptions
	 */
	class WideImage_UnsupportedFormatException extends WideImage_Exception {}
	
	/**
	 * Mapper factory
	 * 
	 * @package Internals
	 **/
	abstract class WideImage_MapperFactory
	{
		static protected $mappers = array();
		static protected $customMappers = array();
		
		static protected $mimeTable = array(
			'image/jpg' => 'JPEG', 
			'image/jpeg' => 'JPEG', 
			'image/pjpeg' => 'JPEG', 
			'image/gif' => 'GIF', 
			'image/png' => 'PNG'
			);
		
		/**
		 * Returns a mapper, based on the $uri and $format
		 * 
		 * @param string $uri File URI
		 * @param string $format File format (extension or mime-type) or null
		 * @return WideImage_Mapper
		 **/
		static function selectMapper($uri, $format = null)
		{
			$format = self::determineFormat($uri, $format);
			
			if (array_key_exists($format, self::$mappers))
				return self::$mappers[$format];
			
			$mapperClassName = 'WideImage_Mapper_' . $format;
			
			if (!class_exists($mapperClassName, false))
			{
				$mapperFileName = WideImage::path() . 'Mapper/' . $format . '.php';
				if (file_exists($mapperFileName))
					require_once $mapperFileName;
			}
			
			if (class_exists($mapperClassName))
			{
				self::$mappers[$format] = new $mapperClassName();
				return self::$mappers[$format];
			}
			
			throw new WideImage_UnsupportedFormatException("Format '{$format}' is not supported.");
		}
		
		static function registerMapper($mapper_class_name, $mime_type, $extension)
		{
			self::$customMappers[$mime_type] = $mapper_class_name;
			self::$mimeTable[$mime_type] = $extension;
		}
		
		static function getCustomMappers()
		{
			return self::$customMappers;
		}
		
		static function determineFormat($uri, $format = null)
		{
			if ($format == null)
				$format = self::extractExtension($uri);
			
			// mime-type match
			if (preg_match('~[a-z]*/[a-z-]*~i', $format))
				if (isset(self::$mimeTable[strtolower($format)]))
				{
					return self::$mimeTable[strtolower($format)];
				}
			
			// clean the string
			$format = strtoupper(preg_replace('/[^a-z0-9_-]/i', '', $format));
			if ($format == 'JPG')
				$format = 'JPEG';
			
			return $format;
		}
		
		static function mimeType($format)
		{
			return array_search(strtoupper($format), self::$mimeTable);
		}
		
		static function extractExtension($uri)
		{
			$p = strrpos($uri, '.');
			if ($p === false)
				return '';
			else
				return substr($uri, $p + 1);
		}
	}
