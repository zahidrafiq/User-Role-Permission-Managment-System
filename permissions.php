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
<script src="jquery-1.9.1.min.js" type="text/javascript"></script>

<script>
$(document).ready(Main);
	
	function Main(){ 
	alert("ready");
			
			//Show all Roles
			var obj = {"Action":"ShowAll","Table":"permissions"};
				var settings={
					type: "POST",
					dataType: "json",
					url: "api.php",
					data: obj,
					success: successOfShowAll,				
				//	success: function(r){console.log(r);},
					error: function(){console.log("Error to get Role's list");}
				};//end of settings. Action":"ShowAllUser
				$.ajax(settings);
				console.log("request send to get permissions");
	//This function is called when users list is received successfully  
	function successOfShowAll(response){
					console.log(response);
	
					var table=$("#grid");
					for(var i=0;i<response.length;i++)
					{
						var tr1=$("<tr>");
						var td1=$("<td>").text(response[i].permissionid);
						tr1.append(td1);
			
						td1=$("<td>").text(response[i].name);
						tr1.append(td1);
						
						td1=$("<td>").text(response[i].description);
						tr1.append(td1);
						
						td1=$("<td>");
						$editBtn=$("<button>").text("Edit");
						$editBtn.click(clickOnEditPerm);
						td1.append($editBtn);
						tr1.append(td1);
						
						td1=$("<td>");
						$deleteBtn=$("<button>").text("Delete");
						//event of deleteBtn click event.
						$deleteBtn.click(clickOnDelPerm);
						td1.append($deleteBtn);
						tr1.append(td1);
						table.append(tr1);
				
					}
				}//end of success function of Action:ShowAll.
	//This function is called on delete event 
						function clickOnDelPerm(){
							var $isConfirm = confirm("Record will be deleted. Click Ok to continue and Cancel to Ignore");
							if ($isConfirm == true) {
								var tid=$(this).closest("tr").find("td:first").text();
								 var ref=this; //ref will store pointer to specific delete button that is clicked.
								 var obj={"Action":"Delete","Table":"permissions","PK":"permissionid","id":tid};
								 var deleteObj={
									type: "POST",
									dataType: "json",
									url: "api.php",
									data: obj,
									success: function(r){
										$(ref).closest("tr").remove();
										alert("Role is deleted successfully");
										},
									error:  function(){alert("Error! in deleting user");}
								};//end of deleteUSer AJAX hit.
								$.ajax(deleteObj);
								console.log("delete request send");
							}
							return false;
						}//end of clickOnDelUser
	///////////////////////////////////////////////////////////////
			function clickOnEditPerm()
			{//debugger;
				var tID=$(this).closest("tr").find("td:first").text();
				var rowNum=$(this).closest("tr").index();
				localStorage.setItem("editRowIndex",rowNum);
				var actionObj={"Action":"GetObjToEdit","Table":"permissions","PK":"permissionid","tId":tID};
				var editUsr={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){console.log("success"+res.email);
						//Filling form to edit.
						$("#txtPermission").val(res.name);//name of role.
						$("#txtDescription").val(res.description);
							
					},
					error: function(){alert("Error in Editing Role");}
					
				};//end of editUser AJAX hit settings obj 
				$.ajax(editUsr);
				console.log("edit request send");
				return false;
			}
	
	// ///////////////////////////////////////////////////////////////
							
	
			$("#btnSave").click(function(){
			var per=$("#txtPermission").val();
			var desc=$("#txtDescription").val();
			if(per=="" || desc=="" ){
				alert ("Please fill all fields!");
				return false;
			}
			else{
			//	
			var ActionObj = {"Action":"SavePerm","Perm":per,"Desc":desc};
				var settings={
				type: "POST",
				dataType: "json",
				url: "api.php",
				data: ActionObj,
				success: Mysuccfunction,
				error: OnError
				};//end of settings.
				$.ajax(settings);
				console.log('request to save sent');
			} //end of else			
		
		function Mysuccfunction(r){
			console.log(r);
				if(r.act=="edit")
				{
					var i=localStorage.getItem("editRowIndex");
					document.getElementById("grid").deleteRow(i);
				}
				addRowInTable(r['ID'],per,desc);
		}
		
		function OnError(){
			alert('Error occured in saving Role');
		}
	});//end of btnSave function	
		// //////////////////////////////////
		function addRowInTable(id,d1,d2)
		{//debugger;
			var table=$("#grid");
			var tr1=$("<tr>");
			
			var td1=$("<td>").text(id);
			tr1.append(td1);
			
			td1=$("<td>").text(d1);
			tr1.append(td1);
			
			td1=$("<td>").text(d2);
			tr1.append(td1);
			
			td1=$("<td>");
			//$editBtn=$("<button>").text("Edit");
			$editBtn=$("<button>").text("Edit");
			$editBtn.click(clickOnEditPerm);
			td1.append($editBtn);
			tr1.append(td1);
			
			td1=$("<td>");
			$deleteBtn=$("<button>").text("Delete");
			//deleteBtn click event.
			$deleteBtn.click(clickOnDelPerm);
						
			td1.append($deleteBtn);
			tr1.append(td1);
			table.append(tr1);
		
			}//end of addRowInTable.
	

	
	}//end of Main.
		
		
</script>


</head>

<body>
<form method="GET" action="" id="permform">
<div class="formPos" style="width:400px;height:600px;border:5px solid black;background-color:white;" >
	<h3 class="chngBackgrnd" style="padding-left:60px;font-size:35">Role Managment</h3><br>
		<div style="padding-left:80px">
		<span><b>Role:<b></span><br>
		<input type="text" name="txtPermission" id="txtPermission" autofocus><br><br>
		<span><b>Description:<b></span><br>
		<input type="text" name="txtDescription" id="txtDescription"><br><br>
		<br><br><br>
		<br><input class="btn" type="button" name="btnSave" id="btnSave" value="Save">
		<input class="btn" type="button" onclick="Clear('permform');" id="btnClear"  value="Clear">
		</div>
	</div>
</form>		
			
<div>
<h1 align="center" style="font-size:350%" >Users</h1>
<table id="grid" class ="gridPos">
<tr>
	<th>ID</th>
	<th>Permission</th>
	<th>Description</th>
	<th>Edit</th>
	<th>Delete</th>
</tr>
</table>
</div>

			
	</body>

</html>


<?php } ?>