<?php
$nb_cols = 1;
$bs_dir = 12 / $nb_cols;
?>
<center>
    <div class="container">
<?php
$img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';
if(file_exists($img_dir . '/' . $pdf_id .'.png')) {
        echo '<img style="max-width: 500px;" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '.png' . '" alt="" border= "0" />';
} else {
    $img_numb = 0;
    $col = 1;
    while(file_exists($img_dir . '/' . $pdf_id . '-' . $img_numb . '.png')) {
        if($col == 1) {
            echo '<div class="row">';
        }
        echo '<div class="col col-lg-' . $bs_dir . ' col-md-' . $bs_dir . ' col-sm-' . $bs_dir . ' col-xs-' . $bs_dir . '"><img style="max-width: 100%;" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '-' . $img_numb . '.png' . '" alt="" border= "0" /></div>';
        $col++;
        if($col > $nb_cols) {
            echo '</div>';
            $col = 1;
        }
        $img_numb++;
    }
    if($col <= $nb_cols) {
        echo '</div>';
    }
}

?>
    </div>
</center>