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
	 * @package WideImage
	 */
	class WideImage_PaletteImage extends WideImage_Image
	{
		/**
		 * Create a palette image
		 *
		 * @param int $width
		 * @param int $height
		 * @return WideImage_PaletteImage
		 */
		static function create($width, $height)
		{
			if ($width * $height <= 0 || $width < 0)
				throw new WideImage_InvalidImageDimensionException("Can't create an image with dimensions [$width, $height].");
			
			return new WideImage_PaletteImage(imagecreate($width, $height));
		}
		
		function doCreate($width, $height)
		{
			return self::create($width, $height);
		}
		
		/**
		 * (non-PHPdoc)
		 * @see WideImage_Image#isTrueColor()
		 */
		function isTrueColor()
		{
			return false;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see WideImage_Image#asPalette($nColors, $dither, $matchPalette)
		 */
		function asPalette($nColors = 255, $dither = null, $matchPalette = true)
		{
			return $this->copy();
		}
		
		/**
		 * Returns a copy of the image
		 * 
		 * @param $trueColor True if the new image should be truecolor
		 * @return WideImage_Image
		 */
		protected function copyAsNew($trueColor = false)
		{
			$width = $this->getWidth();
			$height = $this->getHeight();
			
			if ($trueColor)
				$new = WideImage_TrueColorImage::create($width, $height);
			else
				$new = WideImage_PaletteImage::create($width, $height);
			
			// copy transparency of source to target
			if ($this->isTransparent())
			{
				$rgb = $this->getTransparentColorRGB();
				if (is_array($rgb))
				{
					$tci = $new->allocateColor($rgb['red'], $rgb['green'], $rgb['blue']);
					$new->fill(0, 0, $tci);
					$new->setTransparentColor($tci);
				}
			}
			
			imageCopy($new->getHandle(), $this->handle, 0, 0, 0, 0, $width, $height);
			return $new;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see WideImage_Image#asTrueColor()
		 */
		function asTrueColor()
		{
			$width = $this->getWidth();
			$height = $this->getHeight();
			$new = WideImage::createTrueColorImage($width, $height);
			if ($this->isTransparent())
				$new->copyTransparencyFrom($this);
			if (!imageCopy($new->getHandle(), $this->handle, 0, 0, 0, 0, $width, $height))
				throw new WideImage_GDFunctionResultException("imagecopy() returned false");
			return $new;
		}
		
		/**
		 * (non-PHPdoc)
		 * @see WideImage_Image#getChannels()
		 */
		function getChannels()
		{
			$args = func_get_args();
			if (count($args) == 1 && is_array($args[0]))
				$args = $args[0];
			return WideImage_OperationFactory::get('CopyChannelsPalette')->execute($this, $args);
		}
		
		/**
		 * (non-PHPdoc)
		 * @see WideImage_Image#copyNoAlpha()
		 */
		function copyNoAlpha()
		{
			return WideImage_Image::loadFromString($this->asString('png'));
		}
	}
