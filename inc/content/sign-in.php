<?php

?>

<div class="container">
<?php
switch($action) {
    case 'sign-in':
?>    
    <form method="POST" action="">
        <input type="hidden" name="action" value="sign-in" />
        <div class="form-row">
            <div class="col-lg-4 ms-0 mb-2">
                <h2><?php echo $tr['MENU.SIGN_IN']; ?></h2>
                <?php echo $tr['ACCOUNT.SIGN_IN_INTRO']; ?>
            </div>
            <div class="col-lg-2 ms-0 mb-1">
                <div class="form-group">
                    <label for="userName"><?php echo $tr['ACCOUNT.USER_NAME']; ?></label>
                    <input type="text" class="form-control" name="user_name" id="userName" placeholder="<?php echo $tr['ACCOUNT.USER_NAME.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_name'])) { echo $values['user_name']; } ?>" required="required" />
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['user_name'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-3">
                <div class="form-group">
                    <label for="userPassword"><?php echo $tr['ACCOUNT.USER_PASS']; ?></label>
                    <input type="password" class="form-control" name="user_pass" id="userPassword" placeholder="<?php echo $tr['ACCOUNT.USER_PASS.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_pass'])) { echo $values['user_pass']; } ?>" required="required" />
                    <?php if(isset($errors['user_pass'])) { echo '<div class="invalid-feedback">' . $errors['user_pass'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-2">
                <button type="submit" class="btn btn-primary dark-cyan"><?php echo $tr['SUBMIT']; ?></button>
                <?php if(isset($errors['general'])) { echo '<div style="color: red; margin: 10px 0px 10px 0px;">' . $errors['general'] . '</div>'; } ?>
            </div>
    </form>
<?php
        break;
}
?>
</div>
