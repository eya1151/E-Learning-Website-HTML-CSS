<?php

include('../connexion/conx.php');

session_start();
session_unset();
session_destroy();

header('location:login.php');

?>