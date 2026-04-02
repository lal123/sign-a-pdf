<?php

function cmp($a, $b)
{
    global $docs;
    return strcasecmp($docs[$b]['time'], $docs[$a]['time']);
}

$img_dir = getcwd() . '/' . UPLOAD_DIR . '/img';

$nb_cols = 1;
$bs_dir = 12 / $nb_cols;

$pdf_id = '';

if(isset($_GET['pdf_id']) && ($_GET['pdf_id'] != '')) {
    $pdf_id = $_GET['pdf_id'];
}

?>

<center>
    <div class="container">

<?php
if($pdf_id != '') {
?>
    <div>Votre document: <?php echo $_SESSION['docs'][$pdf_id]['name'] . " (" . $_SESSION['docs'][$pdf_id]['size'] . ")"; ?> - <a href="/<?php echo $lang; ?>/docs">Voir tous les documents</a></div>
<?php
if(file_exists($img_dir . '/' . $pdf_id .'.png')) {
    echo '<img class="page-preview" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '.png' . '" alt="" border= "0" />';
} else {
    $img_numb = 0;
    $col = 1;
    while(file_exists($img_dir . '/' . $pdf_id . '-' . $img_numb . '.png')) {
        if($col == 1) {
            echo '<div class="row">';
        }
        echo '<div class="col col-lg-' . $bs_dir . ' col-md-' . $bs_dir . ' col-sm-' . $bs_dir . ' col-xs-' . $bs_dir . '"><img class="page-preview" src="/' . UPLOAD_DIR . '/img/' . $pdf_id . '-' . $img_numb . '.png' . '" alt="" border= "0" /></div>';
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
        <div>
            <a href="/<?php echo $lang; ?>/"class="btn btn-primary btn-lg dark-cyan"><?php echo $tr['BACK']; ?></a>
        </div>
<?php
} else {
    $docs = $_SESSION['docs'];
    uksort($docs, "cmp");
    echo '<div class="row">';
    foreach($docs as $pdf_id => $details) {
        echo '<div class="col col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="/' . $lang . '/docs/' . $pdf_id . '/">' . $details['name'] . '</a> (' . $details['size'] . ') - ' . date("Y-m-d H:i:s", $details['time']);
        if(file_exists($img_dir . '/' . $pdf_id .'.png')) {
            $img_src = '/' . UPLOAD_DIR . '/img/' . $pdf_id .'.png';
        } else if(file_exists($img_dir . '/' . $pdf_id . '-0.png')) {
            $img_src = '/' . UPLOAD_DIR . '/img/' . $pdf_id .'-0.png';
        }
        echo '<a href="/' . $lang . '/docs/' . $pdf_id . '/"><img class="page-preview" src="' . $img_src . '" alt="" border= "0" /></a>';
        echo '</div>';
    }
    echo '</div>';
}
?>
    </div>
</center>
