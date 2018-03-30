<?php 
include('header.php');
include('functions.php');
include('conn.php');
session_start();
?>

<?php
//FOR sessions.Session is started in header.php, 
//that is include in this file 
	if(isset($_SESSION['loginUserID'])==false)
	{
		echo "<center><h2 style='color:red;'>Please First Login!</h2><center>";
		header('Refresh: 1; URL=login.php');
	}
	else
	{
			
?>

<html>
<head>
<title>Add User</title>
<link rel="stylesheet" type="text/css" href="style.css">

<?php
//These variables are used to set values of fields.
//if form is loaded for edit these variables will be 
//assigned value of respective user record.
$logname="";
$passwd="";
$email="";
$uname="";
$country="";
$msg="";
$usrid=-1;
$role=0;
/////////////////////////////
//$usrid=$_REQUEST['i'];
/*if($usrid) // If from is loaded to edit record.
{
	//values are being set with user's data to set in fields to edit.
	$usr=getObjById($conn,"users","userid",$usrid);
	$cntry=getCountryById($conn,$usr['countryid']);
	$logname=$usr['login'];
	$passwd=$usr['password'];
	$uname=$usr['name'];
	$email=$usr['email'];
	$role=$usr['isadmin'];
	$country=$cntry['name'];
}*/
?>


<?php
	if(isset($_REQUEST['btnSave'])==true)
	{	
		$uid=$_REQUEST['i'];
		$log=$_REQUEST['txtLoginName'];
		$paswd=$_REQUEST['txtUserPassword'];
		$nme=$_REQUEST['txtUserName'];
		$emal=$_REQUEST['txtUserEmail'];
		$cntry=$_REQUEST['cmbCountries'];
		$role=$_REQUEST['radrole'];
		//if save button is clicked to edit existing record .
		//if($_REQUEST['i']>0)
		//{	
			//$query="UPDATE users SET login='$log' , password='$paswd' , name='$nme' ,email='$emal' ,countryid='$cntry' ,isadmin='$role' WHERE userid='$uid'";
		//}
		//else  //If button is clicked for new record.
		//{
			$loginUsrId=$_SESSION['loginUserID'];
			 $query="INSERT INTO users( login , password , name, email , countryid ,createdon,createdby,isadmin) 
			VALUES ('$log','$paswd','$nme','$emal','$cntry',current_timestamp,'$loginUsrId','$role')";
	//	}
			$b=mysqli_query($conn,$query);
			if($b==true)
			{
				header('Location: usersList.php?i');
			}
			else
			{
				echo "Error in database query! " . $query . "<br>" . mysqli_error($conn);
			}
	}//end of outermost if.
?>

<script>
function validate()
{
	var lgnme=document.getElementById("txtLoginName").value;
	var psd=document.getElementById("txtUserPassword").value;
	var unme=document.getElementById("txtUserName").value;
	var eml=document.getElementById("txtUserEmail").value;
	var cntry=document.getElementById("cmbCountries").value;
	if(lgnme=="" || psd=="" || eml=="" || cntry<1 || unme=="")
	{
		alert ("Please fill all fields!");
		return false;
	}
	else
	{	
		return true;
	}
				
}


</script>
</head>

<body>
<style>

div.formPos {
    position: absolute;
    left: 5%;
	top:15%;
}
.gridPos{
	position: absolute;
    left: 45%;
	top:35%;
	
}


</style>
<span style='background-color:red'><?php echo $msg ?></span>
<form method="GET" action="" id="adduserform">
<div class="formPos" style="width:400px;height:600px;border:5px solid black;background-color:white;" >
	<h3 class="chngBackgrnd" style="padding-left:60px;font-size:35">Users Managment</h3><br>
		<div style="padding-left:80px">
		<span><b>Login:<b></span><br>
		<input type="text" name="txtLoginName" id="txtLoginName" value="<?php echo $logname; ?>" ><br><br>
		<span><b>password:<b></span><br>
		<input type="password" name="txtUserPassword" id="txtUserPassword" value="<?php echo $passwd; ?>"><br><br>
		<span><b>Name:<b></span><br>
		<input type="text" name="txtUserName" id="txtUserName" value="<?php echo $uname; ?>"><br><br>
		<span><b>E-mail:<b></span><br>
		<input type="email" name="txtUserEmail" id="txtUserEmail" value="<?php echo $email; ?>"><br><br>
		<input type="radio" name="radrole" value=0 <?php if($role==0)echo 'checked' ?>>&nbsp Regular User<br><br>
		<input type="radio" name="radrole" value=1 <?php if($role==1)echo 'checked' ?>>&nbsp Admin<br>
		<input type="text" name="i" hidden value=<?php echo $usrid; ?> >
		<br><br>
		<span><b>Country<b></span><br>
		
		<select name="cmbCountries" id="cmbCountries">
		<option value="0">--Select--</option>
		<?php
			//getAllCountries($conn,$country );
		?>
		</select>
		<br><br><br>
		<br><input class="btn" type="submit" name="btnSave" onclick="return validate();" value="Save">
		<input class="btn" type="button" onclick="Clear('adduserform');" id="btnClear"  value="Clear">
		</div>
	</div>
</form>		
			
<div>
<h1 align="center" style="font-size:350%" >Users</h1>
<table id="grid" class ="gridPos">
<tr>
	<th>ID</th>
	<th>Name</th>
	<th>E-mail</th>
	<th>Edit</th>
	<th>Delete</th>
</tr>
<?php getAllUsers($conn); ?>

</table>
</div>

			
	</body>

</html>


<?php } ?>