<?php
require 'config/config.php';
require 'config/database.php';
require 'models/User.php';

$user = new User();
$result = $user->login('mesero1', '123456');

if ($result) {
    echo "SUCCESS: Logged in as " . $result['usuario'] . " with role " . $result['rol'];
} else {
    echo "FAILED: Could not log in";
}
?>
