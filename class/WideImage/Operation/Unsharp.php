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
	 * Unsharp filter
	 * 
	 * This filter was taken from http://vikjavev.no/computing/ump.php,
	 * the original author Torstein Hønsi. Adapted to fit better within
	 * the Wideimage package.
	 * 
	 * @package Internal/Operations
	 */
	class WideImage_Operation_Unsharp {
		/**
		 * Returns sharpened image
		 *
		 * @param WideImage_Image $image
		 * @param float $amount
		 * @param int $radius
		 * @param float $threshold
		 * @return WideImage_Image
		 */
		function execute($image, $amount, $radius, $threshold) {
			
			// Attempt to calibrate the parameters to Photoshop:
			if ($amount > 500)    $amount = 500;
			$amount = $amount * 0.016;
			if ($radius > 50)    $radius = 50;
			$radius = $radius * 2;
			if ($threshold > 255)    $threshold = 255;
			
			$radius = abs(round($radius));     // Only integers make sense.
			if ($radius == 0) {
				return $image;
			}
					
			// Gaussian blur matrix
	
			$matrix = array(
				array(1, 2, 1),
				array(2, 4, 2),
				array(1, 2, 1)
			);
			
			$blurred = $image->applyConvolution($matrix, 16, 0);
			
			if($threshold > 0) {
				// Calculate the difference between the blurred pixels and the original
				// and set the pixels
				for ($x = 0; $x < $image->getWidth(); $x++) {
					for ($y = 0; $y < $image->getHeight(); $y++) {
						$rgbOrig = $image->getRGBAt($x, $y);
						$rOrig = $rgbOrig["red"];
						$gOrig = $rgbOrig["green"];
						$bOrig = $rgbOrig["blue"];
						 
						$rgbBlur = $blurred->getRGBAt($x, $y);
						$rBlur = $rgbBlur["red"];
						$gBlur = $rgbBlur["green"];
						$bBlur = $rgbBlur["blue"];
						
						// When the masked pixels differ less from the original
						// than the threshold specifies, they are set to their original value.
						$rNew = (abs($rOrig - $rBlur) >= $threshold)  
							? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))  
							: $rOrig;
						$gNew = (abs($gOrig - $gBlur) >= $threshold)  
							? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))  
							: $gOrig;
						$bNew = (abs($bOrig - $bBlur) >= $threshold)  
							? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))  
							: $bOrig;
						$rgbNew = array("red" => $rNew, "green" => $gNew, "blue" => $bNew, "alpha" => 0);
							
						if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
							$image->setRGBAt($x, $y, $rgbNew);
						}
					}
				}
			}
			else {
				$w = $image->getWidth();
				$h = $image->getHeight();
				for ($x = 0; $x < $w; $x++) {
					for ($y = 0; $y < $h; $y++) {
						$rgbOrig = $image->getRGBAt($x, $y);
						$rOrig = $rgbOrig["red"];
						$gOrig = $rgbOrig["green"];
						$bOrig = $rgbOrig["blue"];
						
						$rgbBlur = $blurred->getRGBAt($x, $y);
						$rBlur = $rgbBlur["red"];
						$gBlur = $rgbBlur["green"];
						$bBlur = $rgbBlur["blue"];
						
						$rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;
							if($rNew>255){$rNew=255;}
							elseif($rNew<0){$rNew=0;}
						$gNew = ($amount * ($gOrig - $gBlur)) + $gOrig;
							if($gNew>255){$gNew=255;}
							elseif($gNew<0){$gNew=0;}
						$bNew = ($amount * ($bOrig - $bBlur)) + $bOrig;
							if($bNew>255){$bNew=255;}
							elseif($bNew<0){$bNew=0;}
						$rgbNew = array("red" => $rNew, "green" => $gNew, "blue" => $bNew, "alpha" => 0);
						
						$image->setRGBAt($x, $y, $rgbNew);
					}
				}
			}
			
			return $image;
		}
	}
