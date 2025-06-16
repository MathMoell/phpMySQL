<?php
session_start();
session_unset();
session_destroy();
header("Location: /~mmoll/phpmysql/index.php");
exit();
?>
