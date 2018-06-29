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
	 * AutoCrop operation
	 * 
	 * @package Internal/Operations
	 */
	class WideImage_Operation_AutoCrop
	{
		/**
		 * Executes the auto-crop operation on the $img
		 * 
		 * @param WideImage_Image $img 
		 * @param int $rgb_threshold The difference in RGB from $base_color
		 * @param int $pixel_cutoff The number of pixels on each border that must be over $rgb_threshold
		 * @param int $base_color The color that will get cropped
		 * @return WideImage_Image resulting auto-cropped image
		 */
		function execute($img, $margin, $rgb_threshold, $pixel_cutoff, $base_color)
		{
			$margin = intval($margin);
			
			$rgb_threshold = intval($rgb_threshold);
			if ($rgb_threshold < 0)
				$rgb_threshold = 0;
			
			$pixel_cutoff = intval($pixel_cutoff);
			if ($pixel_cutoff <= 1)
				$pixel_cutoff = 1;
			
			if ($base_color === null)
				$rgb_base = $img->getRGBAt(0, 0);
			else
			{
				if ($base_color < 0)
					return $img->copy();
				
				$rgb_base = $img->getColorRGB($base_color);
			}
			
			$cut_rect = array('left' => 0, 'top' => 0, 'right' => $img->getWidth() - 1, 'bottom' => $img->getHeight() - 1);
			
			for ($y = 0; $y <= $cut_rect['bottom']; $y++)
			{
				$count = 0;
				for ($x = 0; $x <= $cut_rect['right']; $x++)
				{
					$rgb = $img->getRGBAt($x, $y);
					$diff = abs($rgb['red'] - $rgb_base['red']) + abs($rgb['green'] - $rgb_base['green']) + abs($rgb['blue'] - $rgb_base['blue']);
					if ($diff > $rgb_threshold)
					{
						$count++;
						if ($count >= $pixel_cutoff)
						{
							$cut_rect['top'] = $y;
							break 2;
						}
					}
				}
			}
			
			for ($y = $img->getHeight() - 1; $y >= $cut_rect['top']; $y--)
			{
				$count = 0;
				for ($x = 0; $x <= $cut_rect['right']; $x++)
				{
					$rgb = $img->getRGBAt($x, $y);
					$diff = abs($rgb['red'] - $rgb_base['red']) + abs($rgb['green'] - $rgb_base['green']) + abs($rgb['blue'] - $rgb_base['blue']);
					if ($diff > $rgb_threshold)
					{
						$count++;
						if ($count >= $pixel_cutoff)
						{
							$cut_rect['bottom'] = $y;
							break 2;
						}
					}
				}
			}
			
			for ($x = 0; $x <= $cut_rect['right']; $x++)
			{
				$count = 0;
				for ($y = $cut_rect['top']; $y <= $cut_rect['bottom']; $y++)
				{
					$rgb = $img->getRGBAt($x, $y);
					$diff = abs($rgb['red'] - $rgb_base['red']) + abs($rgb['green'] - $rgb_base['green']) + abs($rgb['blue'] - $rgb_base['blue']);
					if ($diff > $rgb_threshold)
					{
						$count++;
						if ($count >= $pixel_cutoff)
						{
							$cut_rect['left'] = $x;
							break 2;
						}
					}
				}
			}
			
			for ($x = $cut_rect['right']; $x >= $cut_rect['left']; $x--)
			{
				$count = 0;
				for ($y = $cut_rect['top']; $y <= $cut_rect['bottom']; $y++)
				{
					$rgb = $img->getRGBAt($x, $y);
					$diff = abs($rgb['red'] - $rgb_base['red']) + abs($rgb['green'] - $rgb_base['green']) + abs($rgb['blue'] - $rgb_base['blue']);
					if ($diff > $rgb_threshold)
					{
						$count++;
						if ($count >= $pixel_cutoff)
						{
							$cut_rect['right'] = $x;
							break 2;
						}
					}
				}
			}
			
			$cut_rect = array(
					'left' => $cut_rect['left'] - $margin,
					'top' => $cut_rect['top'] - $margin,
					'right' => $cut_rect['right'] + $margin,
					'bottom' => $cut_rect['bottom'] + $margin
				);
			
			if ($cut_rect['left'] < 0)
				$cut_rect['left'] = 0;
			
			if ($cut_rect['top'] < 0)
				$cut_rect['top'] = 0;
			
			if ($cut_rect['right'] >= $img->getWidth())
				$cut_rect['right'] = $img->getWidth() - 1;
			
			if ($cut_rect['bottom'] >= $img->getHeight())
				$cut_rect['bottom'] = $img->getHeight() - 1;
			
			return $img->crop($cut_rect['left'], $cut_rect['top'], $cut_rect['right'] - $cut_rect['left'] + 1, $cut_rect['bottom'] - $cut_rect['top'] + 1);
		}
	}
