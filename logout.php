<?php
include("config.php");
include("restrito.php");
session_destroy();
header("Location:./");
exit();
?>