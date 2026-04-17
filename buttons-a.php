<?php

$filename='sign-a-pdf';
$font_filename = 'fonts/HelveticaNeue.ttf';
$font_size = 11.5;
$angle = 0;
$image_width = 88;
$image_height = 31;
$margin_top = 3;

$lang_text = [
    "en" => [
        "Sign",
        "a PDF",
        "for free",
    ],
    "fr" => [
        "Signez",
        "un PDF",
        "gratuit",
    ],
    "pt" => [
        "Assine",
        "um PDF",
        "gratuito",
    ],
 ];

foreach($lang_text as $lang => $text) {

    $img_files = [];

    $max_ascent = -1;
    $max_descent = -1;

    for($i = 0 ; $i < sizeof($text); $i++) {
        $dims = imagettfbbox($font_size, 0, $font_filename, $text[$i]);
        $ascent = abs($dims[7]);
        if(($ascent > $max_ascent) || ($max_ascent == -1)) {
            $max_ascent = $ascent;
        }
        $descent = abs($dims[1]);
        if(($descent > $max_descent) || ($max_descent == -1)) {
            $max_descent = $descent;
        }
    }

    for($i = 0 ; $i < sizeof($text); $i++) {

        $img = imagecreatetruecolor(88, 31);

        $background = imagecolorallocate($img, 0x18, 0x30, 0x34);
        $white = imagecolorallocate($img, 0xff, 0xff, 0xff);

        $logo = imagecreatefrompng('favicon-32x32.png');

        imagefill($img, 0, 0, $background);

        imagecopyresampled($img, $logo, 3, 5, 0, 0, 21, 21, 32, 32);

        $dims = imagettfbbox($font_size, 0, $font_filename, $text[$i]);
        //$ascent = abs($dims[7]);
        //$descent = abs($dims[1]);
        //$text_width = abs($dims[0]) + abs($dims[2]);
        $text_height = $max_ascent + $max_descent;
        $text_x = 28;
        $text_y = $margin_top + (($image_height / 2) - ($text_height / 2)) + $ascent;

        imagettftext($img, $font_size, $angle, $text_x, $text_y, $white, $font_filename, $text[$i]);

        $tmp_filename = './uploads/' . $filename . '-' . $lang . '-' .$image_width . 'x' . $image_height . '-' . $i . '.gif';

        $img_files[]= $tmp_filename;

        imagegif($img, $tmp_filename);

        imagedestroy($img);
    }

    $command = "/usr/bin/gifsicle -l -w -O3 --disposal=bg --colors 256 -o=" . getcwd() . "/img/{$filename}-{$lang}-{$image_width}x{$image_height}.gif";
    for($i = 0 ; $i < sizeof($img_files); $i++) {
        $command.= " -d=150 " . getcwd() . "/" . $img_files[$i];
    }
    exec($command);
    //echo $command . "<br />\n";

    for($i = 0 ; $i < sizeof($text); $i++) {
        unlink($img_files[$i]);
    }
}

?>
<body>
<?php
foreach($lang_text as $lang => $text) {
    $img_src = "/img/{$filename}-{$lang}-{$image_width}x{$image_height}.gif";
    echo '<img src="' . $img_src . '" alt="" border="0" /><br /><br />';
}
?>
</body>
