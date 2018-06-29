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
	 * TTF font support class
	 * 
	 * @package WideImage
	 */
	class WideImage_Font_TTF
	{
		public $face;
		public $size;
		public $color;
		
		function __construct($face, $size, $color)
		{
			$this->face = $face;
			$this->size = $size;
			$this->color = $color;
		}
		
		/**
		 * Writes text onto an image
		 * 
		 * @param WideImage_Image $image
		 * @param mixed $x smart coordinate
		 * @param mixed $y smart coordinate
		 * @param string $text
		 * @param int $angle Angle in degrees clockwise
		 */
		function writeText($image, $x, $y, $text, $angle = 0)
		{
			if ($image->isTrueColor())
				$image->alphaBlending(true);
			
			$box = imageftbbox($this->size, $angle, $this->face, $text);
			$obox = array(
				'left' => min($box[0], $box[2], $box[4], $box[6]),
				'top' => min($box[1], $box[3], $box[5], $box[7]),
				'right' => max($box[0], $box[2], $box[4], $box[6]) - 1,
				'bottom' => max($box[1], $box[3], $box[5], $box[7]) - 1
			);
			$obox['width'] = abs($obox['left']) + abs($obox['right']);
			$obox['height'] = abs($obox['top']) + abs($obox['bottom']);
			
			$x = WideImage_Coordinate::fix($x, $image->getWidth(), $obox['width']);
			$y = WideImage_Coordinate::fix($y, $image->getHeight(), $obox['height']);
			
			$fixed_x = $x - $obox['left'];
			$fixed_y = $y - $obox['top'];
			
			imagettftext($image->getHandle(), $this->size, $angle, $fixed_x, $fixed_y, $this->color, $this->face, $text);
		}
	}
