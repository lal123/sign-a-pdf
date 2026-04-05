<?php

?>
<div class="container">
<?php
switch($action) {
    case 'create':
    case 'update':
        unset($_SESSION['mail_sent']);
?>    
    <h2><?php echo ($action == 'create' ? $tr['MENU.CREATE_ACCOUNT']: $tr['MENU.UPDATE_ACCOUNT']); ?></h2>
    <div class="ms-0 mt-3 mb-3">
        <?php echo ($action == 'create' ? $tr['ACCOUNT.CREATE_INTRO'] : $tr['ACCOUNT.UPDATE_INTRO']); ?>
    </div>
    <form method="POST" action="">
        <input type="hidden" name="action" value="<?php echo $action; ?>" />
        <div class="form-row">
            <div class="col-lg-2 ms-0 mb-1">
                <div class="form-group">
                    <label for="userName"><?php echo $tr['ACCOUNT.USER_NAME']; ?></label>
                    <input type="text" maxlength="24" class="form-control<?php if(isset($values['user_name'])) { echo (isset($errors['user_name']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_name" id="userName" aria-describedby="nameHelp" placeholder="<?php echo $tr['ACCOUNT.USER_NAME.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_name'])) { echo $values['user_name']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['user_name'] . '</div>'; } ?>
                    <div id="nameHelp" class="form-text">
                        <?php echo $tr['ACCOUNT.USER_NAME.HELP']; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 ms-0 mb-1">
                <div class="form-group">
                    <label for="userEmail"><?php echo $tr['ACCOUNT.USER_MAIL']; ?></label>
                    <input type="email" maxlength="256" class="form-control<?php if(isset($values['user_email'])) { echo (isset($errors['user_email']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_email" id="userEmail" aria-describedby="emailHelp" placeholder="<?php echo $tr['ACCOUNT.USER_MAIL.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_email'])) { echo $values['user_email']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['user_email'])) { echo '<div class="invalid-feedback">' . $errors['user_email'] . '</div>'; } ?>
                    <div id="nameHelp" class="form-text">
                        <?php echo $tr['ACCOUNT.USER_MAIL.HELP']; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-1">
                <div class="form-group">
                    <label for="userPassword"><?php echo $tr['ACCOUNT.USER_PASS']; ?></label>
                    <input type="password" maxlength="24" class="form-control<?php if(isset($values['user_pass'])) { echo (isset($errors['user_pass']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_pass" id="userPassword" aria-describedby="passwordHelp" placeholder="<?php echo $tr['ACCOUNT.USER_PASS.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_pass'])) { echo $values['user_pass']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['user_pass'])) { echo '<div class="invalid-feedback">' . $errors['user_pass'] . '</div>'; } ?>
                    <div id="passwordHelp" class="form-text">
                        <?php echo $tr['ACCOUNT.USER_PASS.HELP']; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-3">
                <div class="form-group">
                    <label for="confirm"><?php echo $tr['ACCOUNT.CONFIRM']; ?></label>
                    <input type="password" maxlength="24" class="form-control<?php if(isset($values['confirm'])) { echo (isset($errors['confirm']) ? ' is-invalid' : (!isset($errors['user_pass']) ? ' is-valid' : '')); } ?>" name="confirm" id="userConfirm" placeholder="<?php echo $tr['ACCOUNT.CONFIRM.PLACEHOLDER']; ?>" value="<?php if(isset($values['confirm'])) { echo $values['confirm']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['confirm'])) { echo '<div class="invalid-feedback">' . $errors['confirm'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-5 ms-0 mb-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input<?php if(isset($values['user_optin'])) { echo ' is-valid'; } ?>" name="user_optin" id="userOptin"<?php if(isset($values['user_optin']) && ($values['user_optin'] == 1)) { echo ' checked="checked"'; } ?> onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <label class="form-check-label" for="userOptin"><?php echo $tr['ACCOUNT.USER_OPTIN']; ?></label>
                </div>
            </div>
            <div class="col-lg-4 ms-0 mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input<?php if(isset($values['user_accept'])) { echo (isset($errors['user_accept']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_accept" id="userAccept"<?php if(isset($values['user_accept']) && ($values['user_accept'] == 1)) { echo ' checked="checked"'; } ?> required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <label class="form-check-label" for="userAccept"><?php echo $tr['ACCOUNT.USER_ACCEPT']; ?></label><?php if(isset($errors['user_accept'])) { echo '<div class="invalid-feedback">' . $errors['user_accept'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-2">
                <button type="submit" class="btn btn-primary dark-cyan"><?php echo $tr['SUBMIT']; ?></button>
                <?php if(isset($errors['general'])) { echo '<div style="color: red; margin: 10px 0px 10px 0px;">' . $tr['ACCOUNT.UNEXPECTED_ERROR'] . '</div>'; } ?>
            </div>
<?php
if(($action == 'update') && isset($_SESSION['user_id'])) {
?>
            <div class="col-lg-2 ms-0 mt-4 mb-2">
                <a class="common" href="javascript:void(0)" onclick="return account.confirm('<?php echo $_SESSION['user_id']; ?>'); return false;"><?php echo $tr['ACCOUNT.DELETE_ACCOUNT']; ?></a>
            </div>
<?php
}
?>
        </div>
    </form>
<?php
        break;
    case 'confirm':
?>
    <h2><?php echo $tr['ACCOUNT.CONFIRM_TITLE']; ?></h2>
    <div class="ms-0 mt-4 mb-2">
<?php
        echo strtr($tr['ACCOUNT.CONFIRM_WELCOME'], ['%%user_name%%' => $values['user_name'], '%%user_email%%' => $values['user_email']]);
?>
    </div>
<?php
        break;
    case 'confirm-update':
?>
    <h2><?php echo $tr['MENU.UPDATE_ACCOUNT']; ?></h2>
    <div class="ms-0 mt-4 mb-2">
<?php
        echo strtr($tr['ACCOUNT.UPDATE_WELCOME'], ['%%user_name%%' => $values['user_name'], '%%user_email%%' => $values['user_email']]);
?>
    </div>
<?php
        break;
    case 'validate':
?>
    <h2><?php echo $tr['ACCOUNT.VALIDATION_TITLE']; ?></h2>
    <div class="ms-0 mt-4 mb-2">
<?php
if(isset($errors['general']) && ($errors['general'] != '')) {
    echo $errors['general'];
} else {
    echo $tr['ACCOUNT.VALIDATION_WELCOME'];
}
?>
    </div>
<?php
        break;
}
?>
</div>

<div class="modal" id="confirmModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $tr['CONFIRMATION']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><?php echo $tr['ACCOUNT.DELETE.CONFIRM']; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $tr['CANCEL']; ?></button>
        <button id="actionConfirm" type="button" class="btn btn-primary dark-cyan"><?php echo $tr['CONFIRM']; ?></button>
      </div>
    </div>
  </div>
</div>