<?php

//$users = model_get_user_list();

$errors = [];
$values = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    switch($action) {
        case 'create':
            $values['user_name'] = $_POST['user_name'];
            if((strlen($values['user_name']) < 4) || (strlen($values['user_name']) > 24)) {
                $errors['user_name'] = $tr['ACCOUNT.USER_NAME.ERROR'];
            }
            $values['user_email'] = $_POST['user_email'];
            if(!utils_is_valid_email_address($values['user_email'])) {
                $errors['user_email'] = $tr['ACCOUNT.USER_MAIL.ERROR'];
            }
            $values['user_pass'] = $_POST['user_pass'];
            if((strlen($values['user_pass']) < 4) || (strlen($values['user_pass']) > 24)) {
                $errors['user_pass'] = $tr['ACCOUNT.USER_PASS.ERROR'];
            }
            $values['confirm'] = $_POST['confirm'];
            if($values['confirm'] != $values['user_pass']) {
                $errors['confirm'] = $tr['ACCOUNT.CONFIRM.ERROR'];
            }
            $values['user_optin'] = (isset($_POST['user_optin']) && ($_POST['user_optin'] == 'on') ? 1 : 0);
            $values['user_accept'] = (isset($_POST['user_accept']) && ($_POST['user_accept'] == 'on') ? 1 : 0);
            if(!isset($values['user_accept']) || ($values['user_accept'] != 1)) {
                $errors['user_accept'] =  $tr['ACCOUNT.USER_ACCEPT.ERROR'];
            }
            if(utils_create_user($values, $errors)) {
                $action = 'confirm';
            }
            break;
    }
}

?>

<div class="container">
    <h2><?php echo $tr['MENU.CREATE_ACCOUNT']; ?></h2>
<?php
switch($action) {
    case 'create':
    var_dump($errors);
?>    
    <div class="ms-0 mb-2">
        <?php echo $tr['ACCOUNT.CREATE_INTRO']; ?>
    </div>
    <form method="POST" action="">
        <input type="hidden" name="action" value="create" />
        <div class="form-row">
            <div class="col-lg-2 ms-0 mb-1">
                <div class="form-group">
                    <label for="userName"><?php echo $tr['ACCOUNT.USER_NAME']; ?></label>
                    <input type="text" class="form-control<?php if(isset($values['user_name'])) { echo (isset($errors['user_name']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_name" id="userName" aria-describedby="nameHelp" placeholder="<?php echo $tr['ACCOUNT.USER_NAME.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_name'])) { echo $values['user_name']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['user_name'])) { echo '<div class="invalid-feedback">' . $errors['user_name'] . '</div>'; } ?>
                    <div id="nameHelp" class="form-text">
                        <?php echo $tr['ACCOUNT.USER_NAME.HELP']; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ms-0 mb-1">
                <div class="form-group">
                    <label for="userEmail"><?php echo $tr['ACCOUNT.USER_MAIL']; ?></label>
                    <input type="email" class="form-control<?php if(isset($values['user_email'])) { echo (isset($errors['user_email']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_email" id="userEmail" aria-describedby="emailHelp" placeholder="<?php echo $tr['ACCOUNT.USER_MAIL.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_email'])) { echo $values['user_email']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['user_email'])) { echo '<div class="invalid-feedback">' . $errors['user_email'] . '</div>'; } ?>
                    <div id="nameHelp" class="form-text">
                        <?php echo $tr['ACCOUNT.USER_MAIL.HELP']; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-1">
                <div class="form-group">
                    <label for="userPassword"><?php echo $tr['ACCOUNT.USER_PASS']; ?></label>
                    <input type="password" class="form-control<?php if(isset($values['user_pass'])) { echo (isset($errors['user_pass']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_pass" id="userPassword" placeholder="<?php echo $tr['ACCOUNT.USER_PASS.PLACEHOLDER']; ?>" value="<?php if(isset($values['user_pass'])) { echo $values['user_pass']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['user_pass'])) { echo '<div class="invalid-feedback">' . $errors['user_pass'] . '</div>'; } ?>
                    <div id="nameHelp" class="form-text">
                        <?php echo $tr['ACCOUNT.USER_PASS.HELP']; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-3">
                <div class="form-group">
                    <label for="confirm"><?php echo $tr['ACCOUNT.CONFIRM']; ?></label>
                    <input type="password" class="form-control<?php if(isset($values['confirm'])) { echo (isset($errors['confirm']) ? ' is-invalid' : (!isset($errors['user_pass']) ? ' is-valid' : '')); } ?>" name="confirm" id="userConfirm" placeholder="<?php echo $tr['ACCOUNT.CONFIRM.PLACEHOLDER']; ?>" value="<?php if(isset($values['confirm'])) { echo $values['confirm']; } ?>" required="required" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <?php if(isset($errors['confirm'])) { echo '<div class="invalid-feedback">' . $errors['confirm'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-4 ms-0 mb-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input<?php if(isset($values['user_accept'])) { echo ' is-valid'; } ?>" name="user_optin" id="userOptin"<?php if(isset($values['user_optin']) && ($values['user_optin'] == 1)) { echo ' checked="checked"'; } ?> onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <label class="form-check-label" for="userOptin"><?php echo $tr['ACCOUNT.USER_OPTIN']; ?></label>
                </div>
            </div>
            <div class="col-lg-4 ms-0 mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input<?php if(isset($values['user_accept'])) { echo (isset($errors['user_accept']) ? ' is-invalid' : ' is-valid'); } ?>" name="user_accept" id="userAccept"<?php if(isset($values['user_accept']) && ($values['user_accept'] == 1)) { echo ' checked="checked"'; } ?> requred="requred" onfocus="$(this).removeClass('is-valid').removeClass('is-invalid');" />
                    <label class="form-check-label" for="userAccept"><?php echo $tr['ACCOUNT.USER_ACCEPT']; ?></label>
                    <?php if(isset($errors['user_accept'])) { echo '<div class="invalid-feedback">' . $errors['user_accept'] . '</div>'; } ?>
                </div>
            </div>
            <div class="col-lg-2 ms-0 mb-2">
                <button type="submit" class="btn btn-primary dark-cyan">Submit</button>
            </div>
    </form>
<?php
        break;
    case 'confirm':
?>
    <div class="ms-0 mb-2">
        Welcome!
    </div>
<?php
        break;
}
?>
</div>
