<html>
<?php
include	("PHPconnectionDB.php");
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" ){	 }
else{
header("Location:Login.html");		 
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
	<form name="addUser"method="post" action="admin.php">
		Enter new username: <br/>
		<input type="text" name="newUsername"/> <br/>
		Enter new users password:<br/>	
		<input type="text" name="newPassword"/> <br/>
		Enter first name:<br/>	
		<input type="text" name="newFirstName"/> <br/>
		Enter last name:<br/>	
		<input type="text" name="newLastName"/> <br/>
		Enter address:<br/>	
		<input type="text" name="newAdress"/> <br/>
		Enter email:<br/>	
		<input type="text" name="newEmail"/> <br/>
		Enter phone number:<br/>	
		<input type="text" name="newPhoneNumber"/> <br/>
		Select role:<br/>	
		Admin<input type="radio" name="role"/>
		Scientist<input type="radio" name="role"/>
		Data Curator<input type="radio" name="role"/><br/>
		<input id="submitAddNewUser" type="submit" value="Add New User" name="submitNewUser"/>
		
	</form>
	
	<?php
		echo $_COOKIE["ROLE"];
		echo "YES";
	?>
  </div>
  <div id="tabs-3">
   	Account Settings go here!
  </div>
</div>
</div> <!-- end of container-->
<script type="text/javascript">
	$("#logout").click(function(){
			window.location.href = "login.php?status=logout";
  		});
</script>
</body>
</html>
