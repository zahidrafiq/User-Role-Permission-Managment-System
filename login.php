<html>
<head>
<title>Login Page</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="jquery-1.9.1.min.js" type="text/javascript">
</script>
<?php require('conn.php');
session_start();
$msg="";
$usr="";

?>

<?php
function storeLoginHistory($conn,$userObj)
{
	$uid=$userObj['userid'];
	$ulogin=$userObj['login'];
	$ip = getHostByName(getHostName());
	//$sql="INSERT INTO loginhistory(userid,login,logintime,machineip) VALUES('$uid','$ulogin',current_timestamp,'$ip')";
	mysqli_query($conn,$sql);
	}

?>
<script>

$(document).ready(function(){});
function validate()
{
	var uname=document.getElementById("txtUserName").val;
	var psd=document.getElementById("txtUserPassword").val;
	if(uname=="" || psd=="")
	{
		alert("Please fill all fields!");
		return false;
	}
	return true;
}
</script>
</head>
<?php

if(isset($_REQUEST['btnUserLogin'])==true)
{
	$uname=$_REQUEST['txtUserName'];
	$upasswd=$_REQUEST['txtUserPassword'];
	$sql="SELECT * FROM users WHERE login='$uname' AND password='$upasswd'";
	$result = mysqli_query($conn, $sql);
	$records=mysqli_num_rows($result);
	if($records >0)
	{	
		$usr = mysqli_fetch_assoc($result);
		/////////////////////////////////////
		//storeLoginHistory($conn,$usr);
		/////////////////////////////////////
		if(isset($_SESSION['loginUserID'])==false)
		{	
			$_SESSION['loginUserID']=$usr['userid'];
		}
		$admin=$usr["isadmin"];
		if($admin==1)
		{
		//	echo "admin";
			header('location:home.php');
		}
		else
		{
			echo "Non admin";
			$_SESSION['loginUserID']=null;
			$_SESSION['loginUserIDNonAdmin']=$usr['userid'];
			header('location:home.php');
		}
		
	}
	else 
	{
		$msg="incorrect username or password";
	}	
		
}	
?>

<body>
<h1>SECURITY MANAGER</h1>
<br>
	<div  class="absolute" style="width:400px;height:500px;border:3px solid black;background-color:white;">
<form method="GET" action="">
	<h3 class="chngBackgrnd" style="padding-left:60px;font-size:35">User Login</h3><br>
		<div style="padding-left:20%;">
		<span><b>username</b></span><br>
		<input type="text" name="txtUserName" name="username" autofocus><br><br>
		<span><b>password</b></span><br>
		<input type="password" name="txtUserPassword" name="password"><br><br>
		<input type="submit" onclick="return validate();" name="btnUserLogin" value="Login">
	</div>
</form>

</div>
<h4><?php echo $msg;?></h4>
</body>


</html>