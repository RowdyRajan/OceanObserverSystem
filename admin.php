
<html>

<?php
include	("PHPconnectionDB.php");
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 'a' ){	 }
else{
header("Location:index.php");		 
} 
?>
<head>
	<title>Admin Dashboard </title>
	<script type="text/javascript" src="libraries/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="libraries/jquery-ui/jquery-ui.js"></script>
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css"></script>
	
	<style type="text/css">
		#submitAddNewUser{
			margin-top:10px;
		}
		.subheaders{
			font-weight:bold;
			text-decoration:underline;
		}
		
		#logout{
			float:right;		
		}
		
		#divAddNewUser{
			display: None;		
		}
		
		#divAddExistingUser{
			display: None;		
		}
		
		.ui-tabs {
			height:100%;			
		}
		
			
	</style>
		
	<script>
		$(function() {
    		$("#tabs").tabs();
  		});
  		
  		
	</script>
</head>

<body>
<div id="container">
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Sensor Management</a></li>
    <li><a href="#tabs-2">User Management</a></li>
    <li><a href="#tabs-3">Account Settings</a></li>
    <button id="logout">Log out</button>
  </ul>
  <div id="tabs-1">
	Sensor management systems goes here!!
  </div>
  <div id="tabs-2">
  <h3 class="subheaders">Add Users</h3>
  	
  	<button id="btnAddNewUser"> Add New User</button>
  	<button id="btnAddExistingUser"> Add Existing User </button>
  	
  	<div id="divAddNewUser">
	<form name="addNewUser"method="post" action="addUser.php">
		Enter new username: <br/>
		<input type="text" name="newUsername" required/> <br/>
		Enter new users password:<br/>	
		<input type="password" name="newPassword" required/> <br/>
		Enter new personID: <br/>
		<input type="text" name="newPersonID" required/> <br/>
		Enter first name:<br/>	
		<input type="text" name="newFirstName" required/> <br/>
		Enter last name:<br/>	
		<input type="text" name="newLastName" required/> <br/>
		Enter address:<br/>	
		<input type="text" name="newAdress" required/> <br/>
		Enter email:<br/>	
		<input type="text" name="newEmail" required/> <br/>
		Enter phone number:<br/>	
		<input type="text" name="newPhoneNumber" required/> <br/>
		Select role:<br/>	
		Admin<input type="radio" name="role" value="a"  required/>
		Scientist<input type="radio" name="role" value="s" required/>
		Data Curator<input type="radio" name="role" value="d" required/><br/>
		<input id="submitAddNewUser" type="submit" value="Add New User" name="submitNewUser"/>
	
	</form>
	</div>
	
	<div id="divAddExistingUser">
	<form name="addExistingUser"method="post" action="admin.php">
		Enter email:<br/>	
		<input type="text" name="newEmail" required/> <br/>
		Enter new username: <br/>
		<input type="text" name="newUsername" required/> <br/>
		Enter new users password:<br/>	
		<input type="password" name="newPassword" required/> <br/>
		Select role:<br/>	
		Admin<input type="radio" name="role" required/>
		Scientist<input type="radio" name="role" required/>
		Data Curator<input type="radio" name="role" required/><br/>
		<input id="submitExistingUser" type="submit" value="Add User" name="submitExistingNewUser"/>
	
	</form>
	</div>
	
  </div> <!-- end of second tab -->
  <div id="tabs-3">
   	Account Settings go here!
  </div>
</div>
</div> <!-- end of container-->
<script type="text/javascript">
	//Logout button on click
	$("#logout").click(function(){
			window.location.href = "login.php?status=logout";
  		});
  	//Handing the add users
  	
  	var newUserShowing = false;
  	var existingUserShowing = false;	
  	$("#btnAddNewUser").click(function () {
 		if(existingUserShowing == true){
 			$("#divAddExistingUser").fadeOut();
 			$("#divAddExistingUser").promise().done(function () {
 					$("#divAddNewUser").fadeIn();
 			});
  			
  			existingUserShowing = false;	
  			newUserShowing = true;
  		}else{
			$("#divAddNewUser").fadeIn();
			newUserShowing = true; 		
  		}
  	});
  	
  	$("#btnAddExistingUser").click(function () {
 		if(newUserShowing == true){
 			$("#divAddNewUser").fadeOut('fast');
 			$("#divAddNewUser").promise().done(function(){
 				$("#divAddExistingUser").fadeIn();
 				});
  			
  			existingUserShowing = true;	
  			newUserShowing = false;
  		}else{
			$("#divAddExistingUser").fadeIn();
			existingUserShowing = true; 		
  		}
  	});
  	
  	
</script>
</body>
</html>
