<?php// require('conn.php');?>
<script>
function editUSer(link)
{
	var id=link.parentNode.parentNode.cells[0].innerText;
	window.location.href="users.php?i="+id;
	//alert(id);
	return false;
}

function deleteUser(link)
{
	var id=link.parentNode.parentNode.cells[0].innerText;
	if(confirm("Are You sure you want to delete user"))
	{
		window.location.href="usersList.php?i="+id;
	}
		return false;
}

/////////////////////////////////////////////
// This function redirects to target page  //
////////////////////////////////////////////
function editObj(link,targetPageURL)
{
	var id=link.parentNode.parentNode.cells[0].innerText;
	window.location.href=""+targetPageURL+"?i="+id;
	return false;
}
////////////////////////////////////////////
function deleteObj(link)
{
	var id=link.parentNode.parentNode.cells[0].innerText;
	if(confirm("Are You sure you want to delete user"))
	{
		window.location.href=""+"?i="+id;
	}
		return false;
}
////////////////////////////////////////////
function Clear(form)
{
		document.getElementById(form).reset();		
}
</script>
	 <?php
	 	function getAllCountries($conn,$select)
		{		
			$sql = "SELECT id,name FROM country";		
			$result = mysqli_query($conn, $sql);
			$recordsFound = mysqli_num_rows($result);			
			
			if ($recordsFound > 0){ 
				while($row = mysqli_fetch_assoc($result)) {
				
					$id = $row["id"];
					$name = $row["name"];
					if($name==$select)
						echo "<option value='$id' selected>$name</option>";
					else
						echo "<option value='$id'>$name</option>";
					
				
				}
			}	
		}
		/////////////////////////////////////////////////
		
		function getAllUsers($conn)
		{
			$sql="SELECT userid,name,email FROM users";
			$result=mysqli_query($conn,$sql);
			$records=mysqli_num_rows($result);
			if($records>0)
			{
				while($row=mysqli_fetch_assoc($result))
				{
					$id=$row["userid"];
					$name=$row["name"];
					$emal=$row["email"];
					echo "<tr>";
					echo "<td>$id</td>";
					echo "<td>$name</td>";
					echo "<td>$emal</td>";
				//	echo "<td><a href='' onclick='return editUSer(this);'>Edit</a></td>";
					//echo "<td><a href='' onclick='return deleteUser(this);' '$id' name ='deleteLink'>Delete</a></td>";
					echo "<td><input type='button' value='Edit'/></td>";
					echo "<td><input type='button' value='Delete'/></td>";
		
					echo "</tr>";
					
				}
			}
			
		}
		
		
////////////////////////////////////////////////////////

	
	
/////////////////////////////////////////////////////////
	function getCountryById($conn,$d)
	{
		$query="SELECT name FROM country WHERE id=".$d;
		$result=mysqli_query($conn,$query);
		$records=mysqli_num_rows($result);
		if($records>0)
		{
			$cntry=mysqli_fetch_assoc($result);
		 return $cntry;
		}
		else
		{
			echo "Error: in function.php " . $query . "<br>" . mysqli_error($conn);
		}
	}
	
////////////////////////////////////////////////////////////
//    This function is to get any type of object by ID    //
///////////////////////////////////////////////////////////
function getObjById($conn,$tableName,$tablePK,$searchId)
{
		$query="SELECT * FROM $tableName WHERE $tablePK=".$searchId;
		$result=mysqli_query($conn,$query);
		$records=mysqli_num_rows($result);
		if($records>0)
		{
			$row=mysqli_fetch_assoc($result);
			return $row;
		}
		else
		{
			echo "Error: in getObjById(functions.php) " . $query . "<br>" . mysqli_error($conn);
		}
}
	?>
<?php
	function showPermissions($conn,$rolID)
		{
			//echo "ROLE ID : ".$rolID;
			$sql="SELECT permissionid FROM rolepermission WHERE roleid=".$rolID;
			$result=mysqli_query($conn,$sql);
			$numOfRecord=mysqli_num_rows($result);
			if($numOfRecord>0)
			{
				 while($usrPerm=mysqli_fetch_assoc($result))
				 {//echo "PID : ".$usrPerm['permissionid']."<br>";
						$permId=$usrPerm['permissionid'];
					 $permObj=getObjById($conn,"permissions","permissionid",$permId);
					// //$role is obj of permissions table[permissionid,name,description,createdon,createdby ]
					 echo "<li>".$permObj['name']."</li>";
				 }
			}
		}
	
?>
