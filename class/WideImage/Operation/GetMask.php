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
	
	* @package Internal/Operations
  **/
	
	/**
	 * GetMask operation class
	 * 
	 * @package Internal/Operations
	 */
	class WideImage_Operation_GetMask
	{
		/**
		 * Returns a mask
		 *
		 * @param WideImage_Image $image
		 * @return WideImage_Image
		 */
		function execute($image)
		{
			$width = $image->getWidth();
			$height = $image->getHeight();
			
			$mask = WideImage_TrueColorImage::create($width, $height);
			$mask->setTransparentColor(-1);
			$mask->alphaBlending(false);
			$mask->saveAlpha(false);
			
			for ($i = 0; $i <= 255; $i++)
				$greyscale[$i] = ImageColorAllocate($mask->getHandle(), $i, $i, $i);
			
			imagefilledrectangle($mask->getHandle(), 0, 0, $width, $height, $greyscale[255]);
			
			$transparentColor = $image->getTransparentColor();
			$alphaToGreyRatio = 255 / 127;
			for ($x = 0; $x < $width; $x++)
				for ($y = 0; $y < $height; $y++)
				{
					$color = $image->getColorAt($x, $y);
					if ($color == $transparentColor)
						$rgba['alpha'] = 127;
					else
						$rgba = $image->getColorRGB($color);
					imagesetpixel($mask->getHandle(), $x, $y, $greyscale[255 - round($rgba['alpha'] * $alphaToGreyRatio)]);
				}
			return $mask;
		}
	}
