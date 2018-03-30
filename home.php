<?php 
//include('functions.php');
include('conn.php');
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">

<title>Home Page</title>
</head>
<body>

<?php
session_start();
//FOR sessions.Session is started in header.php, 
//that is include in this file 
	if(isset($_SESSION['loginUserID'])==true) //if admin is login
	{
	include('header.php');
		?>		
		<h1>Welcome Admin</h1>
<?php
	}
	else if(isset($_SESSION['loginUserIDNonAdmin'])==true)//if user is login
	{		
?>	
		<a class="btn" onclick="return confirmation();" href="logout.php">Logout</a> 

	<?php
			$uid=$_SESSION['loginUserIDNonAdmin'];
			echo "<h1>Welcome User Panel</h1>";	
			$sql="SELECT roleid from userrole WHERE userid=".$uid;
			$result=mysqli_query($conn,$sql);
			$numOfRecord=mysqli_num_rows($result);
			if($numOfRecord>0)
			{	echo "<ol>";
				while($userRole=mysqli_fetch_assoc($result))
				{
					//$userRole is obj of userrole table[roleid is extracted] 
					//getObjById($conn,$tableName,$tablePK,$searchId)
					$role=getObjById($conn,"roles","roleid",$userRole['roleid']);
					//$role is obj of roles table [roleid,name,description,createdby ]
					$roleName=$role['name']; //Name of role From roles table obj.
					echo "<li>Role : ".$roleName."</li>";
					$rolID=$userRole['roleid'];
					echo "<ul>";
					showPermissions($conn,$rolID);
					echo "</ul>";
				}
				echo "</ol>";
				
			}
			
		}			
		else//if user is not logined
		{
			echo "<center><h2 style='color:red;'>Please First Login!</h2><center>";
			header('Refresh:1 ; URL=login.php');
		}

	
			
?>

</body>
</html>
