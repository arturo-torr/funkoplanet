<?php
session_start();
session_unset();
session_destroy();
?>
<?php
header("Location: /funkoplanet/index.php");
exit();
?>