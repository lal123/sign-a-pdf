<?php

?>

<div class="container">
    <div class="col-lg-6 ms-0 mb-2">
        <h2><?php echo $tr['MENU.CONTACT']; ?></h2>
    </div>
<?php
switch($action) {
    case 'contact':
        unset($_SESSION['mail_sent']);
?>    
    <form method="POST" action="">
        <input type="hidden" name="action" value="contact" />
        <div class="form-row">
            <div class="col-lg-6 ms-0 mt-3 mb-3">
                <?php echo $tr['CONTACT.INTRO']; ?>
            </div>
            <div class="col-lg-2 ms-0 mb-1">
                <div class="form-group">
                    <label for="userName"><?php echo $tr['CONTACT.NAME.LABEL']; ?></label>
                    <input type="text" maxlength="24" class="form-control" name="user_name" id="userName" placeholder="<?php echo $tr['CONTACT.NAME.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_name'])) { echo $values['user_name']; } ?>" required="required" />
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['user_name'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-4 ms-0 mb-1">
                <div class="form-group">
                    <label for="userEmail"><?php echo $tr['CONTACT.MAIL.LABEL']; ?></label>
                    <input type="email" maxlength="256" class="form-control" name="user_email" id="userEmail" placeholder="<?php echo $tr['CONTACT.MAIL.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_email'])) { echo $values['user_email']; } ?>" required="required" />
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['user_email'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-4 ms-0 mb-3">
                <div class="form-group">
                    <label for="contactText"><?php echo $tr['CONTACT.TEXT.LABEL']; ?></label>
                    <textarea maxlength="2048" rows="8" class="form-control" name="contact_text" id="contactText" placeholder="<?php echo $tr['CONTACT.TEXT.PLACEHOLDER']; ?>" required="required"><?php if(isset($values['contact_text'])) { echo $values['contact_text']; } ?></textarea>
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['contact_text'] . '</div>'; } ?>
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
    <div class="col-lg-6 ms-0 mt-3 mb-3">
        <?php echo $tr['CONTACT.THANKS_MSG']; ?>
    </div>
<?php
        break;
}
?>
</div>
