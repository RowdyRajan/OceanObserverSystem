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
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css">
	
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
		
		#userManual{
			float:right;		
		}
		
		#divAddNewUser{
			display: none;		
		}
		
		#divAddExistingUser{
			display: none;		
		}
		#divUpdateUser{
			display: none;		
		}
		#divUpdateRole{
			display: none;
		}
		#divUpdatePerson{
			display: none;		
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
		//Creates tabs
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
    <button id="userManual">User Manual</button>
  </ul>
  
  <!-- Sensor Management tab -->
  <div id="tabs-1">
  	<h3 class="subheaders">Add Sensors</h3> 
  		<?php
  		//Displaying errors returned from form submits
  		if(isset($_GET['sError']) && $_GET['sError'] == 'general'){
  			echo "<span class='error'> Error adding sensor </span>" ;
  		} elseif(isset($_GET['sError']) && $_GET['sError'] == 'takenSensorname'){
			  	echo "<span class='error'> Sensor Id already taken</span>" ;
  		}
  		//Displaying successes returned from form submits
  		if(isset($_GET['sSuccess']) && $_GET['sSuccess'] == 'addedSensor'){
  			echo "<span class='success'> Successfully added sensor </span>";
  		}
  		?>

	 <!-- Add Sensor Form -->
  	<div id="divAddSensor">
		<form id="formAddSensor" name="addSensor"method="post" action="sensorManagement.php">
			Enter Sensor Id:<br/>	
			<input type="text" name="sensorID" required/> <br/>
			Enter Location:<br/>	
			<input type="text" name="location" required/> <br/>
			Enter description:<br/>	
			<textarea form="formAddSensor" name="sensorDescription" ></textarea><br/>
			Type:<br/>	
			Audio<input type="radio" name="type" value="a"  required/>
			Image<input type="radio" name="type" value="i" required/>
			Scalar<input type="radio" name="type" value="s" required/><br/>
			<input type="submit" value="Add Sensor" name="submitAddSensor"/>						
		</form>
	</div>
	
	<h3 class="subheaders">Remove Sensors</h3>
	<?php
		//Displaying errors and successes returned from form submits
  		if(isset($_GET['sError']) && $_GET['sError'] == 'deleteGeneral'){
  			echo "<span class='error'> Error deleting sensor </span>" ;
  		} elseif(isset($_GET['sError']) && $_GET['sError'] == 'invalidSensorname'){
			  	echo "<span class='error'> Sensor Id does not exist</span>" ;
  		}
  		
  		if(isset($_GET['sSuccess']) && $_GET['sSuccess'] == 'removedSensor'){
  			echo "<span class='success'> Successfully removed sensor </span>";
  		}
  		?>
  	
  	<!-- Remove Sensor Form -->
  	<div id="divRemoveSensor">
		<form name="removeSensor"method="post" action="sensorManagement.php">
			Enter Sensor Id:<br/>	
			<input type="text" name="sensorID" required/> <br/>
			<input type="submit" value="Remove Sensor" name="submitRemoveSensor"/>						
		</form>
	</div>
	
  </div> <!-- end of tab one -->
  <div id="tabs-2">
  <h3 class="subheaders">Add Users</h3>
  	<!-- Buttons for adding new users for new and existing people -->
  	<button id="btnAddNewUser"> Add New User</button>
  	<button id="btnAddExistingUser"> Add Existing User </button>
  	<?php
  		//Displaying errors and successes returned from form submits
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
  	
  	<!-- Add New User Form -->
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
	
	<!-- Add Existing User Form -->
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
	<!-- Delete user code -->
	<h3 class="subheaders">Delete Users</h3>
	<button id="btnDeleteUser">Delete a User</button>
  	<button id="btnDeletePerson"> Delete a person</button>
  	
  	<?php
  		//Displaying errors and successes returned from form submits
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
  	
  	<!-- Delete User Form -->
  	<div id="divDeleteUser">
		<form name="DeleteUser"method="post" action="deleteUser.php">
			Enter Username you wish to delete:<br/>
			<input type="text" name="deleteUsername" required/> <br/>
			<input type="submit" value="Delete User" name="submitDeleteUser"/>	
		</form>
  	</div>
  	
  	<!-- Delete Person Form -->
  	<div id="divDeletePerson">
  		<form name="DeletePerson"method="post" action="deleteUser.php">
			Enter the email of person you wish to delete:<br/>
			<input type="text" name="deleteEmail" required/> <br/>
			<input type="submit" value="Delete Person" name="submitDeletePerson"/>	
		</form>
  	</div>
  	
  		<br/>
	<!-- Modify user code -->
	<h3 class="subheaders">Update User Information</h3>
	<button id="btnUpdateUser">Change User Password</button>
	<button id="btnUpdateRole">Change User's Role</button>
  	<button id="btnUpdatePerson">Change Person's Information</button>

  	<div id="divUpdateUser">
		<form name="UpdateUser"method="post" action="updateuser.php">
			Enter Username:<br/>
			<input type="text" name="username" required/> <br/>
			Enter New Password:<br/>
			<input type="password" name="password1" required/> <br/>
			Re-enter New Password:<br/>
			<input type="password" name="password2" required/> <br/>
			<input type="submit" name="updatepassword" value="Change Password" />	
		</form>
  	</div>
  	
	<div id="divUpdateRole">
		<form name="UpdateRole"method="post" action="changerole.php">
			Enter Username:<br/>
			<input type="text" name="username" required/> <br/>
			Enter New Role:
			<select name="role">
				<option value="a">Administrator</option>
				<option value="s">Scientist</option>
				<option value="d">Data Curator</option>			
			</select> <br/>
			<input type="submit" name="updaterole" value="Change Role" />	
		</form>
  	</div>
  	
  	<div id="divUpdatePerson">
		<form name="UpdatePerson"method="post" action="updateperson.php">
			Enter Email of Person: <br/>
			<input type="text" name="email" required/> <br/>
			<input type="submit" value="Change Personal Information" name="updateperson"/>	
		</form>
  	</div>
  	
	
  </div> <!-- end of second tab -->
  <div id="tabs-3">
  <!-- Changing Personal Password and Information -->
   <h3 class="subheaders">Change User Password/Personal Information</h3>
  	<button id="btnChangePassword"> Change Password</button>
  	<button id="btnChangePerson"> Modify Personal Information </button>
  	
  	<!-- Change Password -->
  	<div id="divChangePassword">
		<?php echo '<h3>Password Change for '.$_COOKIE['Username'].'</h3>';
		echo '<form name = "changepass" method = "post" action = "changepassword.php">';
		echo 'New Password: <input type="password" name="password1"/><br/>';
		echo 'Repeat New Password: <input type="password" name="password2"/><br/>';
		echo '<input type = "submit" name = "changepassword" value = "Change Password"/></form>'; ?>
	</div>
	
	<!-- Change Personal Information -->
	<div id="divChangePerson">
		<?php 
			//Pulls current person's credentials, and displays them in fields.
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
		
		//Display current personal data in all fields
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
  		
  	//Logout button on click
	$("#userManual").click(function(){
			window.location.href = "userManual.html";
  		});
  	
  	
  	//Code for fading in and out forms based on button clicks
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
  	
  	//Handling User and Person Deletion
  	
  	var deleteUserShowing = false;
  	var deletePersonShowing = false;
  		
  	$("#btnDeleteUser").click(function () {
 		if(deletePersonShowing == true){
 			$("#divDeletePerson").fadeOut('fast');
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
  	
  	//Handling User and Person Changes
  	
  	var updateUserShowing = false;
  	var updatePersonShowing = false;
  	var updateRoleShowing = false;
  	
  	$("#btnUpdateUser").click(function () {
		if(updatePersonShowing == true){
			$("#divUpdatePerson").fadeOut('fast');
			$("#divUpdatePerson").promise().done(function () {
				$("#divUpdateUser").fadeIn();			
			});
			updatePersonShowing = false;
			updateUserShowing = true;
		}
		else if(updateRoleShowing == true){
			$("#divUpdateRole").fadeOut('fast');
			$("#divUpdateRole").promise().done(function () {
				$("#divUpdateUser").fadeIn();			
			});
			updateRoleShowing = false;		
			updateUserShowing = true;
		}
		else{
			$("#divUpdateUser").fadeIn();
			updateUserShowing = true;		
		}
	});
	
	$("#btnUpdatePerson").click(function () {
		if(updateUserShowing == true){
			$("#divUpdateUser").fadeOut('fast');
			$("#divUpdateUser").promise().done(function () {
				$("#divUpdatePerson").fadeIn();			
			});
			updateUserShowing = false;
			updatePersonShowing = true;
		}else if (updateRoleShowing == true) {
			$("#divUpdateRole").fadeOut('fast');
			$("#divUpdateRole").promise().done(function () {
				$("#divUpdatePerson").fadeIn();			
			});
			updateRoleShowing = false;		
			updatePersonShowing = true;	
		}
		else{
			$("#divUpdatePerson").fadeIn();
			UpdatePersonShowing = true;		
		}
	});
	
	$("#btnUpdateRole").click(function () {
		if(updateUserShowing == true){
			$("#divUpdateUser").fadeOut('fast');
			$("#divUpdateUser").promise().done(function () {
				$("#divUpdateRole").fadeIn();			
			});
			updateUserShowing = false;
			updateRoleShowing = true;
		}else if (updatePersonShowing == true) {
			$("#divUpdatePerson").fadeOut('fast');
			$("#divUpdatePerson").promise().done(function () {
				$("#divUpdateRole").fadeIn();			
			});
			updatePersonShowing = false;		
			updateRoleShowing = true;	
		}
		else{
			$("#divUpdateRole").fadeIn();
			UpdateRoleShowing = true;		
		}
	});
  	
</script>
</body>
</html>
