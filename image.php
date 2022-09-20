<?php
include('common.php');
function get_image_mime_type($image_path, $mimes)
{
    if (($image_type = exif_imagetype($image_path)) && (array_key_exists($image_type, $mimes)))
    {
        return $mimes[$image_type];
    }
    else
    {
        return FALSE;
    }
}

//print_r($img_mimes);
$source = base64url_decode($_SERVER['QUERY_STRING']);
//echo "source: " . $source;
//echo "<br>";
//echo "mime: " . get_image_mime_type($source, $img_mimes);
header('Content-Type '. get_image_mime_type($source, $img_mimes));
$fp = fopen($source, 'rb');
fpassthru($fp);
?>
