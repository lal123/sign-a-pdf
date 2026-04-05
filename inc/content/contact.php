<?php
$action = 'contact';
?>

<div class="container">
<?php
switch($action) {
    case 'contact':
        unset($_SESSION['mail_sent']);
?>    
    <form method="POST" action="">
        <input type="hidden" name="action" value="sign-in" />
        <div class="form-row">
            <div class="col-lg-5 ms-0 mb-2">
                <h2><?php echo $tr['MENU.CONTACT']; ?></h2>
            </div>
            <div class="col-lg-5 ms-0 mt-3 mb-3">
                <?php echo $tr['CONTACT.INTRO']; ?>
            </div>
        </div>
    </form>
<?php
        break;
}
?>
</div>
