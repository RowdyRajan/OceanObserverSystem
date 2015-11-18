<html>
<?php
include	("PHPconnectionDB.php");
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" ){	 }
else{
header("Location:index.php");		 
} 
?>
<head>
	<title>Scientist Dashboard</title>
	<script type="text/javascript" src="libraries/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="libraries/jquery-ui/jquery-ui.js"></script>
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css"></script>
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
    <li><a href="#tabs-1">Subscribe</a></li>
    <li><a href="#tabs-2">Search</a></li>
    <li><a href="#tabs-3">Data Analysis Report</a></li>
    <li><a href="#tabs-4">Account Settings</a></li>
 	 <button id="logout">Log out</button>
  </ul>
  <div id="tabs-1">
   	Subscribtions:
	</body>
        <?php 
		$table = 0;
		while ($table < 2){
			$table = $table+1;      
		   //establish connection
			   $conn=connect();

			   $sql = null;	
			   if ($table == 1){
				   //NEED TO USE THE PERSON_ID OF THE LOGGED IN USER
					$sql = 'SELECT * FROM sensors WHERE sensor_id IN(SELECT sensor_id FROM subscriptions WHERE person_id = 2)';
				//the second table shows the unscribed sensors
			   } elseif ($table == 2){
			   //NEED TO USE THE PERSON_ID OF THE LOGGED IN USER
					$sql = 'SELECT * FROM sensors WHERE sensor_id NOT IN(SELECT sensor_id FROM subscriptions WHERE person_id = 2)';
			   }
			   
			   //Prepare sql using conn and returns the statement identifier
			   $stid = oci_parse($conn, $sql );
			   
			   //Execute a statement returned from oci_parse()
			   $res=oci_execute($stid, OCI_DEFAULT); 
			   
			   //if error, retrieve the error using the oci_error() function & output an error
			   if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
			   } 
		?>
		<TABLE BORDER=2>
			<TR>
			<TH>ID</TH>
			<TH>Location</TH>
			<TH>Type</TH>
			<TH>Description</TH>
			</TR>
		<?php 
			//Display extracted rows
			while ($row = oci_fetch_array($stid, OCI_ASSOC)) { 
				?>
				<TD> <?php echo $row['SENSOR_ID'];  ?> </TD>
				<TD> <?php echo $row['LOCATION']; ?> </TD>
				<TD> <?php if ($row['SENSOR_TYPE'] == 'a') {
								echo 'Audio'; 
							} elseif ($row['SENSOR_TYPE'] == 'i') {
								echo 'Image';
							} elseif ($row['SENSOR_TYPE'] == 's') {
								echo 'Scalar';
							}
							?> </TD>
				<TD> <?php echo $row['DESCRIPTION']; ?> </TD>
				<TD> <?php if ($table == 1){
					echo '<form name = "unsubscribe" method ="post" action="unsubscribe.php">
					<input type="hidden" name="sensor_id" value="' . (int)$row['SENSOR_ID'] . '" />
					<input type = "submit" value="Unsubscribe"/>
					</form>'; 
					} else if ($table = 2){ 
					echo '<form name = "subscribe" method ="post" action="subscribe.php">
					<input type="hidden" name="sensor_id" value="' . (int)$row['SENSOR_ID'] . '" />
					<input type = "submit" value="Subscribe"/>
					</form>';
					}
					?></TD>
				</TR>

		 <?php } 
		 if($table == 2){
			echo 'Other Sensors:';
		 }
		}
	 	// Free the statement identifier when closing the connection
	    oci_free_statement($stid);
	    oci_close($conn);?>
	</TABLE>  

  </div>
  <div id="tabs-2">
   	Search goes here!
  </div>
  <div id="tabs-3">
    Data analysis report generating go here!
  </div>
  <div id="tabs-4">
   	Account Settings go here!
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
</div> <!-- end of container-->
<script type="text/javascript">
	//Logout button on click
	$("#logout").click(function(){
			window.location.href = "login.php?status=logout";
  		});
  	//Handing the add users
  	
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
  	
</body>
</html>