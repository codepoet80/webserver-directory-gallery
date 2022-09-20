<?php
function base64url_encode($data)
{
  // First of all you should encode $data to Base64 string
  $b64 = base64_encode($data);

  // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
  if ($b64 === false) {
    return false;
  }

  // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
  $url = strtr($b64, '+/', '-_');

  // Remove padding character from the end of line and return the Base64URL result
  return rtrim($url, '=');
}

function base64url_decode($data, $strict = false)
{
  // Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
  $b64 = strtr($data, '-_', '+/');

  // Decode Base64 string and return the original data
  return base64_decode($b64, $strict);
}

$img_mimes  = array(
    IMAGETYPE_GIF => "image/gif",
    IMAGETYPE_JPEG => "image/jpg",
    IMAGETYPE_PNG => "image/png",
    IMAGETYPE_SWF => "image/swf",
    IMAGETYPE_PSD => "image/psd",
    IMAGETYPE_BMP => "image/bmp",
    IMAGETYPE_TIFF_II => "image/tiff",
    IMAGETYPE_TIFF_MM => "image/tiff",
    IMAGETYPE_JPC => "image/jpc",
    IMAGETYPE_JP2 => "image/jp2",
    IMAGETYPE_JPX => "image/jpx",
    IMAGETYPE_JB2 => "image/jb2",
    IMAGETYPE_SWC => "image/swc",
    IMAGETYPE_IFF => "image/iff",
    IMAGETYPE_WBMP => "image/wbmp",
    IMAGETYPE_XBM => "image/xbm",
    IMAGETYPE_ICO => "image/ico"
);

?>