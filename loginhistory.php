<?php 
include('header.php'); 
require('conn.php');
require('functions.php');
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
<style>
<link rel="stylesheet" type="text/css" href="style.css">
</style>
<title>Login History</title>
</head>
<body>
<div>
<h1 align="center "style="font-size:750%" >Login History</h1>
<table id="grid" class ="center">
<tr>
	<th>ID</th>
	<th>userid</th>
	<th>login</th>
	<th>Login Time</th>
	<th>Machine ip</th>
</tr>
<?php
	$sql="SELECT * FROM loginhistory";
	$result=mysqli_query($conn,$sql);
	$records=mysqli_num_rows($result);
	if($records>0)
	{
		while($row=mysqli_fetch_assoc($result))
		{
			$ID=$row["id"];
			$uid=$row["userid"];
			$loginName=$row["login"];
			$loginTime=$row["logintime"];
			$ip=$row["machineip"];
?>
	<tr>	
	<td> <?php echo $ID; ?> </td>
	<td><?php echo $uid; ?> </td>
	<td> <?php echo $loginName; ?> </td>
	<td><?php echo $loginTime; ?> </td>
	<td> <?php echo $ip; ?> </td>
	</tr>
		<?php } ?>
	<?php } ?>

</table>
</div>

</body>

</html>

<?php } ?>