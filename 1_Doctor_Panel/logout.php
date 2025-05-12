<?php
session_start();
session_destroy();
header("Location: Doctor_Login.php");
exit;
?>