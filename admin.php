
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

		
		.error{
			color: red;		
		}
		.success{
			color:green;		
		}
		
		#divDeleteUser{
			display:none;		
		}
		
		#divDeletePerson{
			display:none;		
		}
		#divChangePassword{
			display: none;
		}
		#divChangePerson{
			display: none;
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
  	<?php
  		if(isset($_GET['error']) && $_GET['error'] == 'general'){
			echo "<span class='error'> Error adding user </span>" ;		
  		} elseif(isset($_GET['error']) && $_GET['error'] == 'invalidEmail') {
			echo "<span class='error'> Error: invalid email </span>"; 	
  		} elseif(isset($_GET['error']) && $_GET['error'] == 'takenUsername') {
			echo "<span class='error'> Error: taken Username </span>"; 	
  		} elseif(isset($_GET['error']) && $_GET['error'] == 'roleTaken') {
			echo "<span class='error'> Error: Person already has this role </span>"; 	
  		}
  		
  		if(isset($_GET['success']) && $_GET['success'] == 'generalAdd'){
  			echo "<span class='success'> Successfully added User </span>";
  		}
  			
  	?>
  	
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
	<form name="addExistingUser"method="post" action="addUser.php">
		Enter email:<br/>	
		<input type="text" name="newEmail" required/> <br/>
		Enter new username: <br/>
		<input type="text" name="newUsername" required/> <br/>
		Enter new users password:<br/>	
		<input type="password" name="newPassword" required/> <br/>
		Select role:<br/>	
		Admin<input type="radio" name="role" value="a" required />
		Scientist<input type="radio" name="role" value="s" required/>
		Data Curator<input type="radio" name="role" value="d" required/><br/>
		<input id="submitExistingUser" type="submit" value="Add User" name="submitExistingNewUser"/>
	
	</form>
	</div>

	<br/>
	<br/>
	<!-- Delete user code -->
	<button id="btnDeleteUser">Delete a User</button>
  	<button id="btnDeletePerson"> Delete a person</button>
  	
  	<?php
  		if(isset($_GET['dError']) && $_GET['dError'] == 'general'){
  			echo "<span class='error'> Error deleting target </span>" ;
  		} elseif(isset($_GET['dError']) && $_GET['dError'] == 'invalidUsername'){
			echo "<span class='error'> Invalid username </span>"; 		
  		} elseif(isset($_GET['dError']) && $_GET['dError'] == 'invalidEmail'){
			echo "<span class='error'> Invalid email </span>"; 		
  		}
  		
  		if(isset($_GET['dSuccess']) && $_GET['dSuccess'] == 'user'){
  			echo "<span class='success'> Successfully deleted User </span>";
  		} elseif(isset($_GET['dSuccess']) && $_GET['dSuccess'] == 'person'){
  			echo "<span class='success'> Successfully deleted a Person and all users associated with them </span>";
  		}
  			
  	?>
  	
  	<div id="divDeleteUser">
		<form name="DeleteUser"method="post" action="deleteUser.php">
			Enter Username you wish to delete:<br/>
			<input type="text" name="deleteUsername" required/> <br/>
			<input type="submit" value="Delete User" name="submitDeleteUser"/>	
		</form>
  	</div>
  	
  	<div id="divDeletePerson">
  		<form name="DeletePerson"method="post" action="deleteUser.php">
			Enter the email of person you wish to delete:<br/>
			<input type="text" name="deleteEmail" required/> <br/>
			<input type="submit" value="Delete Person" name="submitDeletePerson"/>	
		</form>
  	</div>
  	
	
  </div> <!-- end of second tab -->
  <div id="tabs-3">
   	<h3 class="subheaders">Change User Password/Personal Information</h3>
  	<button id="btnChangePassword"> Change Password</button>
  	<button id="btnChangePerson"> Modify Personal Information </button>
  	<div id="divChangePassword">
		<?php echo '<h3>Password Change for '.$_COOKIE['Username'].'</h3>';
		echo '<form name = "changepass" method = "post" action = "changepassword.php">';
		echo 'New Password: <input type="password" name="password1"/><br/>';
		echo 'Repeat New Password: <input type="password" name="password2"/><br/>';
		echo '<input type = "submit" name = "changepassword" value = "Change Password"/></form>'; ?>
	</div>
	<div id="divChangePerson">
		<?php 
			$conn=connect();
			$sqlp = '  	SELECT *
							FROM persons p
							WHERE p.person_id = \''.$_COOKIE['Person'].'\'';
			$stidp = oci_parse($conn, $sqlp);
			$res = oci_execute($stidp, OCI_DEFAULT);
			if (!$res) {
				$err = oci_error($stidp);
				echo htmlentities($err['message']);
	 			}		
		$persons = oci_fetch_row($stidp);
		echo '<h3>Personal Information change for '.$persons[1].' '.$persons[2].' </h3>';
		echo '<form name = "changeperson" method = "post" action = "changeperson.php">';
		echo 'First Name: <input type = "text" name="fname" value = '.$persons[1].' /> <br/>';
		echo 'Last Name:	<input type = "text" name="lname" value = '.$persons[2].' /> <br/>';
		echo 'Address:		<input type = "text" name="addr" value = '.$persons[3].' /> <br/>';
		echo 'Email:		<input type = "text" name="email" value = '.$persons[4].' /> <br/>';
		echo 'Phone:		<input type = "text" name="phone" value = '.$persons[5].' /> <br/>';
		echo '<input type = "submit" name = "changeperson" value = "Change Personal Info" /></form>'; ?>
	</div>
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
  	
  	//Handling Personal Info Changes
  	var Password = false;
  	var Personal = false;	
  	$("#btnChangePassword").click(function () {
 		if(Personal == true){
 			$("#divChangePerson").fadeOut('fast');
 			$("#divChangePerson").promise().done(function () {
 					$("#divChangePassword").fadeIn();
 			});
  			Personal = false;	
  			Password = true;
  		}else{
			$("#divChangePassword").fadeIn();
			Password = true; 		
  		}
  	});
  	
  	$("#btnChangePerson").click(function () {
 		if(Password == true){
 			$("#divChangePassword").fadeOut('fast');
 			$("#divChangePassword").promise().done(function(){
 				$("#divChangePerson").fadeIn();
 				});
  			
  			Personal = true;	
  			Password = false;
  		}else{
			$("#divChangePerson").fadeIn();
			Personal = true; 		
  		}
  	});
  	
  	
  	var deleteUserShowing = false;
  	var deletePersonShowing = false;
  		
  	$("#btnDeleteUser").click(function () {
 		if(deletePersonShowing == true){
 			$("#divDeletePerson").fadeOut();
 			$("#divDeletePerson").promise().done(function () {
 					$("#divDeleteUser").fadeIn();
 			});
  			
  			deletePersonShowing = false;	
  			deleteUserShowing = true;
  		}else{
			$("#divDeleteUser").fadeIn();
			deleteUserShowing = true; 		
  		}
  	});
  	
  	$("#btnDeletePerson").click(function () {
 		if(deleteUserShowing == true){
 			$("#divDeleteUser").fadeOut('fast');
 			$("#divDeleteUser").promise().done(function(){
 				$("#divDeletePerson").fadeIn();
 				});
  			
  			deleteUserShowing = false;	
  			deletePersonShowing = true;
  		}else{
			$("#divDeletePerson").fadeIn();
			deletePersonShowing = true; 		
  		}
  	});
  	
</script>
</body>
</html>
