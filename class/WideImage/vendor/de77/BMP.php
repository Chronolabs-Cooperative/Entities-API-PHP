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

    * @package Internal/Mappers
  **/

	/**
	 * External code for BMP
	 * 
	 * Adapted for use in WideImage. Code used with permission from the original author de77.
	 * http://de77.com/php/read-and-write-bmp-in-php-imagecreatefrombmp-imagebmp
	 * 
	 * @author de77
	 * @license MIT
	 * @url de77.com
	 * @version 21.08.2010
	 * 
	 * @package Internal/Mappers
	 */
class WideImage_vendor_de77_BMP
{
	public static function imagebmp(&$img, $filename = false)
	{
		$wid = imagesx($img);
		$hei = imagesy($img);
		$wid_pad = str_pad('', $wid % 4, "\0");
		
		$size = 54 + ($wid + $wid_pad) * $hei * 3; //fixed
		
		//prepare & save header
		$header['identifier']		= 'BM';
		$header['file_size']		= self::dword($size);
		$header['reserved']			= self::dword(0);
		$header['bitmap_data']		= self::dword(54);
		$header['header_size']		= self::dword(40);
		$header['width']			= self::dword($wid);
		$header['height']			= self::dword($hei);
		$header['planes']			= self::word(1);
		$header['bits_per_pixel']	= self::word(24);
		$header['compression']		= self::dword(0);
		$header['data_size']		= self::dword(0);
		$header['h_resolution']		= self::dword(0);
		$header['v_resolution']		= self::dword(0);
		$header['colors']			= self::dword(0);
		$header['important_colors']	= self::dword(0);
		
		if ($filename)
		{
		    $f = fopen($filename, "wb");
		    foreach ($header AS $h)
		    {
		    	fwrite($f, $h);
		    }
		    
			//save pixels
			for ($y=$hei-1; $y>=0; $y--)
			{
				for ($x=0; $x<$wid; $x++)
				{
					$rgb = imagecolorat($img, $x, $y);
					fwrite($f, self::byte3($rgb));
				}
				fwrite($f, $wid_pad);
			}
			fclose($f);
		}
		else
		{
		    foreach ($header AS $h)
		    {
		    	echo $h;
		    }
		    
			//save pixels
			for ($y=$hei-1; $y>=0; $y--)
			{
				for ($x=0; $x<$wid; $x++)
				{
					$rgb = imagecolorat($img, $x, $y);
					echo self::byte3($rgb);
				}
				echo $wid_pad;
			}
		}
		return true;
	}
	
	public static function imagecreatefromstring($data)
	{
		//read header    
		$pos = 0;
		$header = substr($data, 0, 54);
		$pos = 54;
		
		if (strlen($header) < 54)
			return false;
		
	    $header = unpack(	'c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/' .
							'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
							'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);
	
	    if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
	    {
	    	return false;
	    	//die('Not a valid bmp file');
	    }
	    
	    if (!in_array($header['bits_per_pixel'], array(24, 32, 8, 4, 1)))
	    {
	    	return false;
	    	//die('Only 1, 4, 8, 24 and 32 bit BMP images are supported');
	    }
	    
		$bps = $header['bits_per_pixel']; //bits per pixel 
	    $wid2 = ceil(($bps/8 * $header['width']) / 4) * 4;
		$colors = pow(2, $bps);
	
	    $wid = $header['width'];
	    $hei = $header['height'];
	
	    $img = imagecreatetruecolor($header['width'], $header['height']);
	
		//read palette
		if ($bps < 9)
		{
			for ($i=0; $i<$colors; $i++)
			{
				$palette[] = self::undword(substr($data, $pos, 4));
				$pos += 4;
			}
		}
		else
		{
			if ($bps == 32)
			{
				imagealphablending($img, false);
				imagesavealpha($img, true);			
			}
			$palette = array();
		}	
	
		//read pixels    
	    for ($y=$hei-1; $y>=0; $y--)
	    {
			$row = substr($data, $pos, $wid2);
			$pos += $wid2;		
			$pixels = self::str_split2($row, $bps, $palette);
	    	for ($x=0; $x<$wid; $x++)
	    	{
	    		self::makepixel($img, $x, $y, $pixels[$x], $bps);
	    	}
	    }
		
		return $img;
	}
	
	public static function imagecreatefrombmp($filename)
	{
		return self::imagecreatefromstring(file_get_contents($filename));
	}
	
	private static function str_split2($row, $bps, $palette)
	{
		switch ($bps)
		{
			case 32:
			case 24:	return str_split($row, $bps/8);
			case  8:	$out = array();
						$count = strlen($row);				
						for ($i=0; $i<$count; $i++)
						{					
							$out[] = $palette[	ord($row[$i])		];
						}				
						return $out;		
			case  4:	$out = array();
						$count = strlen($row);				
						for ($i=0; $i<$count; $i++)
						{
							$roww = ord($row[$i]);						
							$out[] = $palette[	($roww & 240) >> 4	];
							$out[] = $palette[	($roww & 15) 		];
						}				
						return $out;
			case  1:	$out = array();
						$count = strlen($row);				
						for ($i=0; $i<$count; $i++)
						{
							$roww = ord($row[$i]);						
							$out[] = $palette[	($roww & 128) >> 7	];
							$out[] = $palette[	($roww & 64) >> 6	];
							$out[] = $palette[	($roww & 32) >> 5	];
							$out[] = $palette[	($roww & 16) >> 4	];
							$out[] = $palette[	($roww & 8) >> 3	];
							$out[] = $palette[	($roww & 4) >> 2	];
							$out[] = $palette[	($roww & 2) >> 1	];
							$out[] = $palette[	($roww & 1)			];
						}				
						return $out;					
		}
	}
	
	private static function makepixel($img, $x, $y, $str, $bps)
	{
		switch ($bps)
		{
			case 32 :	$a = ord($str[0]);
						$b = ord($str[1]);
						$c = ord($str[2]);
						$d = 256 - ord($str[3]); //TODO: gives imperfect results
						$pixel = $d*256*256*256 + $c*256*256 + $b*256 + $a;
						imagesetpixel($img, $x, $y, $pixel);
						break;
			case 24 :	$a = ord($str[0]);
						$b = ord($str[1]);
						$c = ord($str[2]);
						$pixel = $c*256*256 + $b*256 + $a;
						imagesetpixel($img, $x, $y, $pixel);
						break;					
			case 8 :
			case 4 :
			case 1 :	imagesetpixel($img, $x, $y, $str);
						break;
		}
	}
	
	private static function byte3($n)
	{
		return chr($n & 255) . chr(($n >> 8) & 255) . chr(($n >> 16) & 255);	
	}
	
	private static function undword($n)
	{
		$r = unpack("V", $n);
		return $r[1];
	}
	
	private static function dword($n)
	{
		return pack("V", $n);
	}
	
	private static function word($n)
	{
		return pack("v", $n);
	}
}
