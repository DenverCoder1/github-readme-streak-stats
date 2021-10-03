<?php declare (strict_types = 1);
$_POST = json_decode(file_get_contents("php://input"), true);
var_export($_POST['json'],false);
?>