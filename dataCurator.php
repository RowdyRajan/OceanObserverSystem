<html>
<?php
include	("PHPconnectionDB.php");
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 'd' ){	 }
else{
header("Location:index.php");		 
} 
?>
<head>
	<title>Data Curator Dashboard</title>
	<script type="text/javascript" src="libraries/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="libraries/jquery-ui/jquery-ui.js"></script>
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css"></script>
	
	<style type="text/css">
		#logout{
			float:right;		
		}	
		#divUploadImage{
			display: None;
		}
		#divChangePassword{
			display: None;
		}
		#divChangePerson{
			display: None;
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
    <li><a href="#tabs-1">Upload</a></li>
    <li><a href="#tabs-2">Account Settings</a></li>
    <button id="logout">Log out</button>
  </ul>
  <div id="tabs-1">
   	<h3 class="subheaders">Upload Data to the Server</h3>
  	<button id="btnUploadImage"> Upload Image</button>
	<div id="divUploadImage">
		<?php echo '<h3>Uploading Image:</h3>';
		echo '<form enctype="multipart/form-data" name = "uploadimage" method = "post" action = "uploadimage.php">';
		echo 'Image ID: <input type="number" name="image_id" pattern="[0-9]" required="true"/><br/>';
		echo 'Sensor ID: <input type="number" name="sensor_id" pattern="[0-9]" required="true"/><br/>';
		echo 'Date Created (dd/mm/yyyy hours(24):minutes:seconds): <input type="date" name="date_created" size="20" 
			value = "'.date('d/m/Y').' '.date('H:i:s').'"/><br/>';
		echo 'Description: <input type="text" name="description" maxlength="128" size="64"/><br/>';
		echo 'File: <input type="file" name="imageFile" accept=".jpg" id="imageFile"/><br/>';
		echo '<input type = "submit" name ="imageSubmit" value = "Submit Image"/></form>'; ?>
	</div>
  </div>
  <div id="tabs-2">
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
</body>
<script type="text/javascript">
	//Logout button on click
	$("#logout").click(function(){
			window.location.href = "login.php?status=logout";
  		});
		
	//Handling Upload
	var image = false;
	$("#btnUploadImage").click(function () {
		if(image == true){
			$("#divUploadImage").fadeOut('fast');
			image = false;
		}else{
			$("#divUploadImage").fadeIn();
			image = true;
		}
	});
	
  	//Handling Personal Info Change
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
  	</script>
</html>