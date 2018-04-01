<?php
			
?>


<html>
<style>	

*{
	margin:0px;
}
a.navbar{

    display: inline;
    padding: 8px;
	margin:10px;
    background-color: black;
	color:white;
	font-size:20px;
	text-decoration:none;
}

</style>

<script>
function confirmation()
{
	if(confirm("Are You Sure You want to logout!"))
		return true;
	else
		return false;
}
</script>


<head>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="style.css">


</head>

<body>
<div  style="background-color:black;color:white;height:40px">
   <a class="navbar" href="home.php">Home</a> 
   <a class="navbar" href="users.php">User Managment</a> 
   <a class="navbar" href="roles.php">Role Managment</a> 
   <a class="navbar" href="permissions.php">Permissions Managment</a> 
   <a class="navbar" href="rolePermission.php">Role-Permission Assignment</a> 
   <a class="navbar" href="userRole.php">User-Role Assignment</a> 
   <a class="navbar" href="loginhistory.php">Login History</a> 
   <a class="navbar" onclick="return confirmation();" href="logout.php">Logout</a> 
</div>


</body>

</html>
