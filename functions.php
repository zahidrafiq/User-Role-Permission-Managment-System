<?php// require('conn.php');?>
<script>

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

//used in home.php in regularUserPart.
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
