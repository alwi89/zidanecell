<?php
session_start();
unset($_SESSION['usrzdncl']);
unset($_SESSION['lvlusrzdncl']);
unset($_SESSION['cbg']);
header("location:login.php");
?>