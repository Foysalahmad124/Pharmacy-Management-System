<?php
session_start();
session_destroy();
header("Location:/JP/login.php");
exit();
