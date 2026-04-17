<?php

$filename='sign-a-pdf';
$font_filename = 'fonts/HelveticaNeue.ttf';
$font_size = 11.5;
$angle = 0;
$image_width = 88;
$image_height = 31;
$margin_top = 3;

$lang_text = [
    "en" => "Sign a PDF for free",
    "fr" => "Signez un PDF gratuitement",
    "pt" => "Assine um PDF gratuitamente",
 ];

foreach($lang_text as $lang => $text) {

    $img_files = [];

    $x_off = 58;

    for($i = 0 ; $i < 800; $i++) {
        $img = imagecreatetruecolor(88, 31);

        $background = imagecolorallocate($img, 0x18, 0x30, 0x34);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);

        $logo = imagecreatefrompng('favicon-32x32.png');

        imagefill($img, 0, 0, $background);

        $dims = imagettfbbox($font_size, 0, $font_filename, $text);
        $ascent = abs($dims[7]);
        $descent = abs($dims[1]);
        $text_width = abs($dims[0]) + abs($dims[2]);
        $text_height = $ascent + $descent;
        $text_x = $x_off;
        $text_y = $margin_top + (($image_height / 2) - ($text_height / 2)) + $ascent;

        imagettftext($img, $font_size, $angle, $text_x, $text_y, $white, $font_filename, $text);

        imagefilledrectangle($img, 0, 0, 28, 31, $background);

        imagecopyresampled($img, $logo, 3, 5, 0, 0, 21, 21, 32, 32);

        $tmp_filename = './uploads/' . $filename . '-' . $lang . '-' .$image_width . 'x' . $image_height . '-' . $i . '.gif';

        $img_files[]= $tmp_filename;

        imagegif($img, $tmp_filename);

        imagedestroy($img);

        $x_off--;
        if($x_off < (28 - $text_width)) {
            $x_off = 88;
        } else if($x_off == 58) {
            break;
        }
    }

    $command = "/usr/bin/gifsicle -l -w -O3 --disposal=bg --colors 256 -o=" . getcwd() . "/img/{$filename}-{$lang}-{$image_width}x{$image_height}-a.gif";
    
    for($i = 0 ; $i < sizeof($img_files); $i++) {
        $command.= " -d=3 " . getcwd() . "/" . $img_files[$i];
    }
    
    exec($command);

    for($i = 0 ; $i < sizeof($img_files); $i++) {
        unlink($img_files[$i]);
    }
}

?>
<body>
<?php
foreach($lang_text as $lang => $text) {
    $img_src = "/img/{$filename}-{$lang}-{$image_width}x{$image_height}-a.gif";
    echo '<img src="' . $img_src . '" alt="" border="0" /><br /><br />';
}
?>
</body>
