<?php

require_once '../inc/utils.php';

db_connect();

$users = model_get_user_list();

print_r($users);

?>