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
<title>User Role</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="jquery-1.9.1.min.js" type="text/javascript"></script>

<script>
$(document).ready(Main);
	
	function Main(){
	///////////////////////////////////////////////////////////////
			//Start of AJAX hit to get All users
			var obj = {"Action":"ShowAll","Table":"users"};
				var settings={
					type: "POST",
					dataType: "json",
					url: "api.php",
					data: obj,
					success: function(res){console.log(res);
					for(var i=0;i<res.length;i++){
					var opt=$("<option>").attr("value",res[i].userid).text(res[i].name);
					$("#cmbUser").append(opt);
				};//end of for loop.	
				},				
				error: function(){console.log("Error to get Users's list");}
				};//end of settings. Action:ShowAll
				$.ajax(settings);
				console.log("request send to get roles");
			//End of AJAX hit for users.
			
			//Start of AJAX hit to get All Roles.
			var obj = {"Action":"ShowAll","Table":"roles"};
				var settings={
					type: "POST",
					dataType: "json",
					url: "api.php",
					data: obj,
					success: function(res){console.log(res);
					for(var i=0;i<res.length;i++){
					var opt=$("<option>").attr("value",res[i].roleid).text(res[i].name);
					$("#cmbRole").append(opt);
				}//end of for loop.	
				},				
				//	success: function(r){console.log(r);},
					error: function(){console.log("Error to get Role's list");}
				};//end of settings. Action:ShowAll
				$.ajax(settings);
				console.log("request send to get roles");
			//End of AJAX hit for roles
			
			//Show all UserRole AJAX hit
			var obj = {"Action":"ShowAll","Table":"userrole"};
				var settings={
					type: "POST",
					dataType: "json",
					url: "api.php",
					data: obj,
					success: successOfShowAll,				
				//	success: function(r){console.log(r);},
					error: function(){console.log("Error to get UserfRole's list");}
				};//end of settings. Action":"ShowAllUser
				$.ajax(settings);
				console.log("request send to get roles");
				//End of AJAX hit to show all rolepermission
/**********************************************************************/
////////////////////////////////////////////////////////////////////////
/**********************************************************************/				
			//This function is called when users list is received successfully  
			function successOfShowAll(response){
				console.log(response);	
									
				var table=$("#grid");
				for(var i=0;i<response.length;i++)
				{
					//Displaying ID in table.
					var tr1=$("<tr>");
					var td1=$("<td>").text(response[i].id);
					tr1.append(td1);
		/************************************************************************/
		//Displaying UserName after converting from userid.
					var usrName;
					var actionObj={"Action":"GetObj","Table":"users","PK":"userid","tId":response[i].userid};
					var settings={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){console.log(res);
						usrName=res.name; //name of role.		
					},
					error: function(){alert("Error in getting UserName");}
					
				};//end of AJAX hit to get roleObj
				$.ajaxSetup({async: false}); //TO make synchronous.
				$.ajax(settings);
				$.ajaxSetup({async: true});//Again Setting to asynchronous
				//This line will be executed when AJAX hit response will return.
					td1=$("<td>").text(usrName);
					tr1.append(td1);
	/**************************************************************************/			
			//Displaying Role Name after converting from roleid.
					var roleName;
					var actionObj={"Action":"GetObj","Table":"roles","PK":"roleid","tId":response[i].roleid};
					var settings={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){console.log(res);
						roleName=res.name; //name of role.		
					},
					error: function(){alert("Error in getting Role Name");}
					
				};//end of AJAX hit to get roleObj
				$.ajaxSetup({async: false}); //TO make synchronous.
				$.ajax(settings);
				$.ajaxSetup({async: true});//Again Setting to asynchronous
				//this line will be executed when AJAX hit response will return.
					td1=$("<td>").text(roleName);
					tr1.append(td1);
	/***************************************************************************/		
					td1=$("<td>");
					$editBtn=$("<button>").text("Edit");
					$editBtn.click(clickOnEditUserRole);
					td1.append($editBtn);
					tr1.append(td1);
					td1=$("<td>");
					$deleteBtn=$("<button>").text("Delete");
					//event of deleteBtn click event.
					$deleteBtn.click(clickOnDelUserRole);
					td1.append($deleteBtn);
					tr1.append(td1);
					table.append(tr1);
				
					}
				}//end of success function of Action:ShowAll

/**********************************************************************/
////////////////////////////////////////////////////////////////////////
/**********************************************************************/				
		
				//This function is called on delete event 
						function clickOnDelUserRole(){
							var $isConfirm = confirm("Record will be deleted. Click Ok to continue and Cancel to Ignore");
							if ($isConfirm == true) {
								var tId=$(this).closest("tr").find("td:first").text();
								 var ref=this; //ref will store pointer to specific delete button that is clicked.
								 var obj={"Action":"Delete","Table":"userrole","PK":"id","id":tId};
								 var deleteObj={
									type: "POST",
									dataType: "json",
									url: "api.php",
									data: obj,
									success: function(r){
										$(ref).closest("tr").remove();
										alert("Role is deleted successfully");
										},
									error:  function(){alert("Error! in deleting userRole");}
								};//end of deleteUSerRole AJAX hit.
								$.ajax(deleteObj);
								console.log("delete request send");
							}
							return false;
						}//end of clickOnDelUser

/**********************************************************************/
////////////////////////////////////////////////////////////////////////
/**********************************************************************/
			function clickOnEditUserRole()
			{//debugger;
				var rId=$(this).closest("tr").find("td:first").text();
				var rowNum=$(this).closest("tr").index();
				localStorage.setItem("editRowIndex",rowNum);
				var actionObj={"Action":"GetObjToEdit","Table":"userrole","PK":"id","tId":rId};
				var editUsr={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){
						//Filling form to edit.
						$("#cmbUser").val(res.userid);//name of userrole
						$("#cmbRole").val(res.roleid);
							
					},
					error: function(){alert("Error in Editing Role");}
					
				};//end of editUser AJAX hit settings obj 
				$.ajax(editUsr);
				console.log("edit request send");
				return false;
			}
	

/**********************************************************************/
////////////////////////////////////////////////////////////////////////
/**********************************************************************/							
	
			$("#btnSave").click(function(){
			var usr=$("#cmbUser").val();
			var role=$("#cmbRole").val();
			if(role=="" || usr=="" ){
				alert ("Please fill all fields!");
				return false;
			}
			else{
			//	
			var ActionObj = {"Action":"SaveUserRole","User":usr,"Role":role};
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

/**********************************************************************/
////////////////////////////////////////////////////////////////////////
/**********************************************************************/		
		function Mysuccfunction(r){
			//console.log("SAVE: " +r);
				if(r.act=="edit")
				{
					var i=localStorage.getItem("editRowIndex");
					document.getElementById("grid").deleteRow(i);
				}
				//alert(r['ID']);
				addRowInTable(r['ID'],usr,role);
		}
		
		function OnError(){
			alert('Error occured in saving RolePermission');
		}
	});//end of btnSave function	

/**********************************************************************/
////////////////////////////////////////////////////////////////////////
/**********************************************************************/		
function addRowInTable(id,d1,d2)
		{//debugger;
			var table=$("#grid");
			var tr1=$("<tr>");
			
			var td1=$("<td>").text(id);
			tr1.append(td1);

		/************************************************************************/					
		//Displaying Users Name after converting from userid.
					var usrName;
					var actionObj={"Action":"GetObj","Table":"users","PK":"userid","tId":d1};
					var settings={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){console.log(res);
						usrName=res.name; //name of role.		
					},
					error: function(){alert("Error in getting Permission Name");}
					
				};//end of AJAX hit to get userObj
				$.ajaxSetup({async: false}); //TO make synchronous.
				$.ajax(settings);
				$.ajaxSetup({async: true});//Again Setting to asynchronous
				//This line will be executed when AJAX hit response will return.
					td1=$("<td>").text(usrName);
					tr1.append(td1);
/**************************************************************************/			
			//Displaying Role Name after converting from roleid.
					var roleName;
					var actionObj={"Action":"GetObj","Table":"roles","PK":"roleid","tId":d2};
					var settings={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){console.log(res);
						roleName=res.name; //name of role.		
					},
					error: function(){alert("Error in getting Role Name");}
					
				};//end of AJAX hit to get roleObj
				$.ajaxSetup({async: false}); //TO make synchronous.
				$.ajax(settings);
				$.ajaxSetup({async: true});//Again Setting to asynchronous
				//this line will be executed when AJAX hit response will return.
					td1=$("<td>").text(roleName);
					tr1.append(td1);
	/***************************************************************************/	
			td1=$("<td>");
			//$editBtn=$("<button>").text("Edit");
			$editBtn=$("<button>").text("Edit");
			$editBtn.click(clickOnEditUserRole);
			td1.append($editBtn);
			tr1.append(td1);
			
			td1=$("<td>");
			$deleteBtn=$("<button>").text("Delete");
			//deleteBtn click event.
			$deleteBtn.click(clickOnDelUserRole);
						
			td1.append($deleteBtn);
			tr1.append(td1);
			table.append(tr1);
		
			}//end of addRowInTable.
	

	
	}//end of Main.
		
		
</script>


</head>

<body>
<form method="GET" action="" id="userroleform">
<div class="formPos" style="width:400px;height:600px;border:5px solid black;background-color:white;" >
	<h3 class="chngBackgrnd" style="padding-left:60px;font-size:35">Role Managment</h3><br>
		<div style="padding-left:80px">
		<span><b>User:<b></span><br>
		<select id="cmbUser">
		<option value=0>--Select--</option>
		</select>
		
		<br><span><b>Role:<b></span><br>
		<select id="cmbRole">
		<option value=0>--Select--</option>
		</select>
		<br><br><br>
		<br><input class="btn" type="button" name="btnSave" id="btnSave" value="Save">
		<input class="btn" type="button" onclick="Clear('userroleform');" id="btnClear"  value="Clear">
		</div>
	</div>
</form>		
			
<div>
<h1 align="center" style="font-size:350%" >User-Role</h1>
<table id="grid" class ="gridPos">
<tr>
	<th>ID</th>
	<th>Users</th>
	<th>Permissions</th>
	<th>Edit</th>
	<th>Delete</th>
</tr>
</table>
</div>

			
	</body>

</html>


<?php } ?>