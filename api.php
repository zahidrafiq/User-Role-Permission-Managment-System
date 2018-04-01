<?php
require('conn.php');
session_start();
//var userObj = {"Action":"Save","Login":lgnme,"Password":psd,\
//"Name":unme,"Email":eml,"Country":cntry};

if(isset($_REQUEST['Action']) && !empty($_REQUEST['Action']))
{
		$action=$_REQUEST['Action'];
		if($action=="ShowAllUser")
		{	
			$sql="SELECT userid,name,email FROM users";
			$result=mysqli_query($conn,$sql);
			$records=mysqli_num_rows($result);
			if($records>0)
			{	$users=array();
				$i=0;
				while($row=mysqli_fetch_assoc($result))
				{
					$id=$row["userid"];
					$name=$row["name"];
					$emal=$row["email"];
					$users[$i]=array("userid"=>$id,"name"=>$name,"email"=>$emal);
					$i++;
				}
				$output["data"]=$users;
				echo json_encode($output);
			}
		}// end of Show all user's action.
		else if($action=="ShowAll"){//It will return any table.
			//format of data {"Action":"ShowAll","Table":"table_name"};
			$table=$_REQUEST['Table'];
			$sql="SELECT * FROM $table";
			$result=mysqli_query($conn,$sql);
			$records=mysqli_num_rows($result);
			if($records>0)
			{	$roles=array();
				$i=0;
				while($row=mysqli_fetch_assoc($result))
				{
					$objList[$i]=$row;
					$i++;
				}
				
				echo json_encode($objList);
			}
		}
		else if($action=="cityList"){
			//data format {"Action":"cityList","id":cntryId};
			$cid=$_REQUEST['id'];
			$sql="SELECT * FROM city WHERE countryid='$cid'";
			$rs=mysqli_query($conn,$sql);
			$numOfRecord=mysqli_num_rows($rs);
			if($numOfRecord>0){
				$city=array();
				$i=0;
				while($row=mysqli_fetch_assoc($rs))
				{
					$ctyId=$row['id'];
					$nme=$row['name'];
					$city[$i]=array("cityId"=>$ctyId,"name"=>$nme);
					$i++;
				}
				//$output["data"]=$city;
				echo json_encode($city);
			}else 
				echo json_encode(false); 
		}
		else if($action=="Edit"){
			$usid=$_REQUEST['id'];
			$_SESSION['editUserId']=$usid;
			$sql="SELECT * FROM users WHERE userid='$usid'";
			$rs=mysqli_query($conn,$sql);
			if(mysqli_num_rows($rs)>0)
			{
				$usrObj=mysqli_fetch_assoc($rs);
				echo json_encode($usrObj);
			}
			else
			{
				echo json_encode(false);
			}
		}
		else if($action=="GetObjToEdit"){//It will return any required object. 
			//{"Action":"GetObjToEdit","Table":"roles","PK":"roleid","tId":rId}
			$table=$_REQUEST['Table'];
			$pk=$_REQUEST['PK'];
			$targetId=$_REQUEST['tId'];
			$_SESSION['editId']=$targetId;
			$sql="SELECT * FROM $table WHERE $pk='$targetId'";
			$rs=mysqli_query($conn,$sql);
			if(mysqli_num_rows($rs)>0)
			{
				$obj=mysqli_fetch_assoc($rs);
				echo json_encode($obj);
			}
			else
			{
				echo json_encode(false);
			}
		}
		else if($action=="Delete"){//It will delete any record from any table.
			//data fromat {"Action":"Delete","Table":"table_name","PK":"pk_of_table","id":id_of_record_To-delete }
			$targetId=$_REQUEST['id'];
			$table=$_REQUEST['Table'];
			$pk=$_REQUEST['PK'];
			$sql="DELETE FROM $table WHERE $pk='$targetId'";
			$rs=mysqli_query($conn,$sql);
			if($rs==true)
				echo json_encode(true);
			else
				echo json_encode(false);
		}
		else if($action=="Save"){
			$log=$_REQUEST['Login'];
			$paswd=$_REQUEST['Password'];
			$nme=$_REQUEST['Name'];
			$emal=$_REQUEST['Email'];
			$cntry=$_REQUEST['Country'];
			$role=$_REQUEST['Role'];
			$editId=$_SESSION['editUserId'];
			//if save button is clicked to edit existing record .
			if($editId>0)
			{
				$query="UPDATE users SET login='$log' , password='$paswd' , name='$nme' ,email='$emal' ,countryid='$cntry' ,isadmin='$role' WHERE userid='$editId'";
				$_SESSION['editUserId']=-1;
				$obj=array("ID"=>$editId,"act"=>"edit");
			}
			else  //If button is clicked for new record.
			{
				$loginUsrId=$_SESSION['loginUserID'];
				 $query="INSERT INTO users( login , password , name, email , countryid ,createdon,createdby,isadmin) 
				VALUES ('$log','$paswd','$nme','$emal','$cntry',current_timestamp,'$loginUsrId','$role')";
			}
				$b=mysqli_query($conn,$query);
				if($b==true && $editId<0)
				{
					$sql="SELECT * FROM users WHERE login='$log'";
					$result=mysqli_query($conn,$sql);
					$row=mysqli_fetch_assoc($result);
					//$row=mysqli_fetch_assoc($rs);
					$uId=$row["userid"]; //id of user whose record is recently saved or edited.
					$obj=array("ID"=>$uId); //create obj in php???
					//alert('Added Succesfully');
				}
				//print_r($obj);
			$jsonObj=json_encode($obj); //To Convert in JSON in PHP.
			echo $jsonObj;
		}//end of Action==Save
		
		else if($action=="SaveRole"){
			//{"Action":"SaveRole","Role":role,"Desc":desc}
			$rol=$_REQUEST['Role'];
			$desc=$_REQUEST['Desc'];
			$editID=$_SESSION['editId'];
			//$_SESSION['editId']=-1;
			//echo json_encode($edtId);
			//if save button is clicked to edit existing record .
			 if($editID>0)
			 {
				$query="UPDATE roles SET name='$rol' , description='$desc' WHERE roleid='$editID'";
				$_SESSION['editId']=-1;
				$obj=array("ID"=>$editID,"act"=>"edit");
			}
			else  //If button is clicked for new record.
			{	//echo json_encode("INSERTING");
				$loginUsrId=$_SESSION['loginUserID'];
				$query="INSERT INTO roles(name,description,createdon,createdby) 
				VALUES('$rol','$desc',current_timestamp,'$loginUsrId')";
			}
				$b=mysqli_query($conn,$query);
				if($b==true && $editID<0)
				{
					$sql="SELECT * FROM roles WHERE name='$rol'";
					$result=mysqli_query($conn,$sql);
					$row=mysqli_fetch_assoc($result);
					//$row=mysqli_fetch_assoc($rs);
					$rId=$row["roleid"]; //id of role which record is recently saved or edited.
					$obj=array("ID"=>$rId); //create obj in php
					//alert('Added Succesfully');
				}
				//print_r($obj);
			$jsonObj=json_encode($obj); //To Convert in JSON in PHP.
			echo $jsonObj;

		}//End of SaveRole
		else if($action=="SavePerm"){
			//data format {"Action":"SavePerm","Perm":per,"Desc":desc};
			$perm=$_REQUEST['Perm'];
			$desc=$_REQUEST['Desc'];
			$editID=$_SESSION['editId']; //ID of user which you	want to edit
			//if save button is clicked to edit existing record .
			 if($editID>0)
			 {
				$query="UPDATE permissions SET name='$perm' , description='$desc' WHERE permissionid='$editID'";
				$_SESSION['editId']=-1;
				$obj=array("ID"=>$editID,"act"=>"edit");
			}
			else  //If button is clicked for new record.
			{	
				$loginUsrId=$_SESSION['loginUserID'];
				$query="INSERT INTO permissions(name,description,createdon,createdby) 
				VALUES('$perm','$desc',current_timestamp,'$loginUsrId')";
			}
				$b=mysqli_query($conn,$query);
				if($b==true && $editID<0)
				{
					$sql="SELECT * FROM permissions WHERE name='$perm'";
					$result=mysqli_query($conn,$sql);
					$row=mysqli_fetch_assoc($result);
					$pId=$row["permissionid"]; //id of permission which record is recently saved or edited.
					$obj=array("ID"=>$pId); //create obj in php
					//alert('Added Succesfully');
				}
				//print_r($obj);
			$jsonObj=json_encode($obj); //To Convert in JSON in PHP.
			echo $jsonObj;

		}//End of SavePerm
		else if($action=="SaveRolePerm"){
			//data format {"Action":"SaveRolePerm","Role":role,"Perm":per};
			$role=$_REQUEST['Role'];
			$perm=$_REQUEST['Perm'];
			$editID=$_SESSION['editId']; //ID of user which you	want to edit
	//		echo json_encode($perm."  ".$editID);
			//if save button is clicked to edit existing record .
			 if($editID>0)
			 {
				$query="UPDATE rolepermission SET roleid='$role' , permissionid='$perm' WHERE id='$editID'";
				$_SESSION['editId']=-1;
				$obj=array("ID"=>$editID,"act"=>"edit");
			}
			else  //If button is clicked for new record.
			{	
	//			$loginUsrId=$_SESSION['loginUserID'];
				$query="INSERT INTO rolepermission(roleid,permissionid) 
				VALUES('$role','$perm')";
			}
				$b=mysqli_query($conn,$query);
				if($b==true )
				{
					$sql="SELECT * FROM rolepermission WHERE roleid='$role' AND permissionid='$perm'";
					$result=mysqli_query($conn,$sql);
					$row=mysqli_fetch_assoc($result);
					$pId=$row["id"]; //id of permission which record is recently saved or edited.
					$obj=array("ID"=>$pId); //create obj in php
					//alert('Added Succesfully');
				}
				//print_r($obj);
			$jsonObj=json_encode($obj); //To Convert in JSON in PHP.
			echo $jsonObj;

		
		}//End of Save rolePermission.
	}//end of outermost if.

	



?>