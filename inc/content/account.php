<?php

$users = model_get_user_list();

?>

<div class="container">
    <h2><?php echo $tr['MENU.CREATE_ACCOUNT']; ?></h2>
    <div class="ms-0 mb-2">
        By creating an account you will retrieve easily your documents and your sisgnatures.
    </div>
        <form method="POST" action=""<?php if($_SERVER['REQUEST_METHOD'] == 'POST') { echo ' class="!!!was-validated"'; } ?>>
            <div class="form-row">
                <div class="col-lg-2 ms-0 mb-2">
                    <div class="form-group">
                        <label for="userName">Name (or login)</label>
                        <input type="text" class="form-control<?php echo (strlen($_POST['user_name']) >= 4 ? ' is-valid' : ' is-invalid'); ?>" name="user_name" id="userName" aria-describedby="nameHelp" placeholder="Enter name" value="<?php echo $_POST['user_name']; ?>" required>
                        <?php if(strlen($_POST['user_name']) >= 4) { echo '<div class="valid-feedback">Example valid name feedback</div>'; } ?>
                        <?php if(strlen($_POST['user_name']) < 4) { echo '<div class="invalid-feedback">Example invalid name feedback</div>'; } ?>
                    </div>
                </div>
                <div class="col-lg-4 ms-0 mb-2">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>
                        <div class="invalid-feedback">Example invalid email feedback</div>
                    </div>
                </div>
                <div class="col-lg-2 ms-0 mb-2">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="col-lg-2 ms-0 mb-3">
                    <div class="form-group">
                        <label for="confirmPassword">Confirm password</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password" required>
                    </div>
                </div>
                <div class="col-lg-4 ms-0 mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">I would like to be informed by this site</label>
                    </div>
                </div>
                <div class="col-lg-4 ms-0 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck2" required>
                        <label class="form-check-label" for="exampleCheck2">I accept the terms of use</label>
                    </div>
                </div>
                <div class="col-lg-2 ms-0 mb-2">
                    <button type="submit" class="btn btn-primary dark-cyan">Submit</button>
                </div>
        </form>
</div>
