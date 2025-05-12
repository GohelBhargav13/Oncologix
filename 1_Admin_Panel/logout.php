<?php
session_start();
session_destroy();
header("Location: Admin_login.php");
exit;
