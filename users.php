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
			//show all cities of selected country
			$("#cmbCountries").change(function(){
				cntryId=$(this).val();
				$("#cmbcity").empty();
				var ActionObj={"Action":"cityList","id":cntryId};
				
				var getCity={ type: "POST", dataType: "json", url: "api.php",
				data:ActionObj, success: function(res){console.log(res);
				var opt=$("<option>").attr("value",0).text("--Select--");
					$("#cmbcity").append(opt);
				for(var i=0;i<res.length;i++){
					var opt=$("<option>").attr("value",res[i].cityId).text(res[i].name);
					$("#cmbcity").append(opt);
				}//end of for loop.	
				},
				error: function(){alert("Error in getting cities");}
				
				};//end of ActionObj.
				$.ajax(getCity);//hit To server using AJAX
				console.log("getCity Request send");
				
			});//End of cmbCountries change event.
	
	
			//Show all Users 
			var obj = {"Action":"ShowAllUser"};
				var settings={
					type: "POST",
					dataType: "json",
					url: "api.php",
					data: obj,
					success: successOfShowUser,				
					error: function(){console.log("Error to get user's list");}
				};//end of settings. Action":"ShowAllUser
				$.ajax(settings);
				
	//This function is called when users list is received successfully  
	function successOfShowUser(response){
					console.log(response);
					var table=$("#grid");
					for(var i=0;i<response.data.length;i++)
					{
						var tr1=$("<tr>");
						var td1=$("<td>").text(response.data[i].userid);
						tr1.append(td1);
			
						td1=$("<td>").text(response.data[i].name);
						tr1.append(td1);
						
						td1=$("<td>").text(response.data[i].email);
						tr1.append(td1);
						
						td1=$("<td>");
						$editBtn=$("<button>").text("Edit");
						$editBtn.click(clickOnEditUser);
						td1.append($editBtn);
						tr1.append(td1);
						
						td1=$("<td>");
						$deleteBtn=$("<button>").text("Delete");
						//event of deleteBtn click event.
						$deleteBtn.click(clickOnDelUser);
						td1.append($deleteBtn);
						tr1.append(td1);
						table.append(tr1);
				
					}
				}//end of success function of Action:ShowAllUser.
	//This function is called on delete event 
						function clickOnDelUser(){
							var $isConfirm = confirm("Record will be deleted. Click Ok to continue and Cancel to Ignore");
							if ($isConfirm == true) {
								var uid=$(this).closest("tr").find("td:first").text();
								var ref=this; //ref will store pointer to specific delete button that is clicked.
								var obj={"Action":"Delete","id":uid};
								var deleteUSer={
									type: "POST",
									dataType: "json",
									url: "api.php",
									data: obj,
									success: function(r){
										$(ref).closest("tr").remove();
										alert("USer is deleted successfully");
										},
									error:  function(){alert("Error! in deleting user");}
								};//end of deleteUSer AJAX hit.
								$.ajax(deleteUSer);
								console.log("delete request send");
							}
							return false;
						}//end of clickOnDelUser
	///////////////////////////////////////////////////////////////
			function clickOnEditUser()
			{//debugger;
				var usrId=$(this).closest("tr").find("td:first").text();
				alert(usrId);
				var rowNum=$(this).closest("tr").index();
				localStorage.setItem("editRowIndex",rowNum);
				var actionObj={"Action":"Edit","id":usrId};
				var editUsr={
					type: "POST",dataType: "json", url:"api.php",data: actionObj,
					success: function(res){console.log("success"+res.email);
						$("#txtLoginName").val(res.login);
						$("#txtUserPassword").val(res.password);
						$("#txtUserName").val(res.name);
						$("#txtUserEmail").val(res.email);
						alert(res.isadmin);
						if(res.isadmin==0){
							//alert("IF");
						$("#rad1").attr("checked",true);
						}else {
						//	alert("Else");
							$("#rad2").attr("checked",true);
						}		
							
					},
					error: function(){alert("ERROR");}
					
				};//end of editUser AJAX hit settings obj 
				$.ajax(editUsr);
				console.log("edit request send");
				return false;
			}
	
	///////////////////////////////////////////////////////////////
							
	
			$("#btnSave").click(function(){
			var lgnme=$("#txtLoginName").val();
			var psd=$("#txtUserPassword").val();
			var unme=$("#txtUserName").val();
			var eml=$("#txtUserEmail").val();
			var cntry=$("#cmbCountries").val();
			var role=$(".radrole:checked").val();
			if(lgnme=="" || psd=="" || eml=="" || cntry<1 || unme==""){
				alert ("Please fill all fields!");
				return false;
			}
			else{
			//	
			var userObj = {"Action":"Save","Login":lgnme,"Password":psd,"Name":unme,"Email":eml,"Country":cntry,"Role":role};
				var settings={
				type: "GET",
				dataType: "json",
				url: "api.php",
				data: userObj,
				success: Mysuccfunction,
				error: OnError
				};//end of settings.
				$.ajax(settings);
				console.log('request to save sent');
			} //end of else			
		
		function Mysuccfunction(r){
				if(r.act=="edit")
				{
					var i=localStorage.getItem("editRowIndex");
					document.getElementById("grid").deleteRow(i);
				}
				addRowInTable(r['ID'],unme,eml);
		}
		
		function OnError(){
			alert('error has occured');
		}
	});//end of btnSave function	
		//////////////////////////////////
		function addRowInTable(id,uname,email)
		{//debugger;
			var table=$("#grid");
			var tr1=$("<tr>");
			
			var td1=$("<td>").text(id);
			tr1.append(td1);
			
			td1=$("<td>").text(uname);
			tr1.append(td1);
			
			td1=$("<td>").text(email);
			tr1.append(td1);
			
			td1=$("<td>");
			//$editBtn=$("<button>").text("Edit");
			$editBtn=$("<button>").text("Edit");
			$editBtn.click(clickOnEditUser);
			td1.append($editBtn);
			tr1.append(td1);
			
			td1=$("<td>");
			$deleteBtn=$("<button>").text("Delete");
			//deleteBtn click event.
			$deleteBtn.click(clickOnDelUser);
						
			td1.append($deleteBtn);
			tr1.append(td1);
			table.append(tr1);
		
			}//end of addRowInTable.
	

	
	}//end of Main.
		
		
</script>


</head>

<body>
<form method="GET" action="" id="adduserform">
<div class="formPos" style="width:400px;height:600px;border:5px solid black;background-color:white;" >
	<h3 class="chngBackgrnd" style="padding-left:60px;font-size:35">Users Managment</h3><br>
		<div style="padding-left:80px">
		<span><b>Login:<b></span><br>
		<input type="text" name="txtLoginName" id="txtLoginName" autofocus><br><br>
		<span><b>password:<b></span><br>
		<input type="password" name="txtUserPassword" id="txtUserPassword"><br><br>
		<span><b>Name:<b></span><br>
		<input type="text" name="txtUserName" id="txtUserName"><br><br>
		<span><b>E-mail:<b></span><br>
		<input type="email" name="txtUserEmail" id="txtUserEmail" ><br><br>
		<input type="radio" class="radrole" name="radrole1" id="rad1" value=0 checked>&nbsp Regular User<br><br>
		<input type="radio" class="radrole" name="radrole1" id="rad2" value=1 >&nbsp Admin<br>
		<br><br>
		<span><b>Country<b></span><br>
		
		<select name="cmbCountries" id="cmbCountries">
		<option value="0">--Select--</option>
		<?php
		$country="";
			getAllCountries($conn,$country );
		?>
		</select>
		<select name="cmbcity" id="cmbcity">
		</select>
		<br><br><br>
		<br><input class="btn" type="button" name="btnSave" id="btnSave" value="Save">
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
</table>
</div>

			
	</body>

</html>


<?php } ?>