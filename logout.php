<?php
session_start();
session_unset();
session_destroy();
header("Location: home.php"); // Redirige a la página principal
exit();
