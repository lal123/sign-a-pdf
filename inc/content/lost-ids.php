<?php

?>
<div class="container">
<?php
switch($action) {
    case 'lost-ids':
        unset($_SESSION['mail_sent']);
?>    
    <form method="POST" action="">
        <input type="hidden" name="action" value="lost-ids" />
        <div class="form-row">
            <div class="col-lg-4 ms-0 mb-2">
                <h2><?php echo $tr['ACCOUNT.LOST_IDS_TITLE']; ?></h2>
            </div>
            <div class="col-lg-12 ms-0 mt-3 mb-3">
                <?php echo $tr['ACCOUNT.LOST_IDS_INTRO']; ?>
            </div>
            <div class="col-lg-4 ms-0 mb-3">
                <div class="form-group">
                    <label for="userEmail"><?php echo $tr['ACCOUNT.USER_MAIL']; ?></label>
                    <input type="text" maxlength="256" class="form-control" name="user_email" id="userEmail" placeholder="<?php echo $tr['ACCOUNT.USER_MAIL.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_email'])) { echo $values['user_email']; } ?>" required="required" />
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['user_email'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-2">
                <button type="submit" class="btn btn-primary dark-cyan"><?php echo $tr['SUBMIT']; ?></button>
            </div>
            <div class="col-lg-6 ms-0 mb-2">
                <?php if(isset($errors['general'])) { echo '<div style="color: red; margin: 10px 0px 10px 0px;">' . $errors['general'] . '</div>'; } ?>
            </div>
        </div>
    </form>
<?php
        break;
    case 'mail-sent':
?>
    <div class="col-lg-4 ms-0 mb-2">
        <h2><?php echo $tr['ACCOUNT.LOST_IDS_TITLE']; ?></h2>
    </div>
    <div class="col-lg-12 ms-0 mt-3 mb-3">
        <?php echo strtr($tr['ACCOUNT.LOST_IDS_MAIL_SENT'], ['%%user_email%%' => $values['user_email']]); ?>
    </div>
<?php
        break;
}
?>
</div>
