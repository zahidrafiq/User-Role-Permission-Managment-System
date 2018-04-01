
<?php
session_start();
 $_SESSION['loginUserID']=null;
 $_SESSION['loginUserIDNonAdmin']=null;
 $_SESSION['editUserId']=-1; //used in api.php
 echo "<center><h2 style='color:red;'>Logout successfully!</h2><center>";
 header('Refresh: 1; URL=login.php');
?>

