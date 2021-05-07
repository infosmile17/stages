<?php
session_start();
unset($_SESSION["users"]);
session_destroy();

header("Location:login.php?msg=Merci d'avoir utiliser notre application");
