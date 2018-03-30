
<?php
session_start();
 $_SESSION['loginUserID']=null;
 echo "<center><h2 style='color:red;'>Logout successfully!</h2><center>";
 header('Refresh: 1; URL=login.php');
?>

