<html>
<?php
include	("PHPconnectionDB.php");
if(isset($_COOKIE['Status']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 's' ){	 }
else{
header("Location:index.php");		 
} 
?>
<head>
	<title>Scientist Dashboard</title>
	<script type="text/javascript" src="libraries/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="libraries/jquery-ui/jquery-ui.js"></script>
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css">
	
	<style type="text/css">
		#logout{
			float:right;		
		}
		
		#userManual{
			float:right;		
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
    <li><a href="#tabs-1">Subscribe</a></li>
    <li><a href="#tabs-2">Search</a></li>
    <li><a href="#tabs-3">Data Analysis Report</a></li>
    <li><a href="#tabs-4">Account Settings</a></li>
    <button id="logout">Log out</button>
    <button id="userManual">User Manual</button>
  </ul>
  <div id="tabs-1">
   	Subscriptions:
        <?php 
		$table = 0;
		while ($table < 2){
			$table = $table+1;      
		   //establish connection
			   $conn=connect();
			   $sql = null;	
			   //the first table shows the subscribed sensors
			   if ($table == 1){		   
					$sql = 'SELECT * FROM sensors WHERE sensor_id IN(SELECT sensor_id FROM subscriptions WHERE person_id = '.$_COOKIE['Person'].')';
				//the second table shows the unsubcribed sensors
			   } elseif ($table == 2){
					$sql = 'SELECT * FROM sensors WHERE sensor_id NOT IN(SELECT sensor_id FROM subscriptions WHERE person_id = '.$_COOKIE['Person'].')';
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
					//For unsubscribing
					echo '<form name = "unsubscribe" method ="post" action="subscribe.php">
					<input type="hidden" name="sensor_id" value="' . (int)$row['SENSOR_ID'] . '" />
					<input type="hidden" name="subscribing?" value="false" />
					<input type = "submit" value="Unsubscribe"/>
					</form>'; 
					} else if ($table = 2){ 
					//For subscibing
					echo '<form name = "subscribe" method ="post" action="subscribe.php">
					<input type="hidden" name="sensor_id" value="' . (int)$row['SENSOR_ID'] . '" />
					<input type="hidden" name="subscribing?" value="true" />
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
  
  <!-- Search tab -->
  <div id="tabs-2">
   <h3 class="subheaders"> Search Sensor Data</h3>
	
	<!-- Form for searching -->	   
   <div id="divSearchFields">
   <form name = "searchsensor" method = post action = "searchsensors.php">
  		Keywords: <input type="text" name="fields" /><br/>
 		Sensor types:<select name = "type">
   		<option value="all">All</option>
   		<option value="audio">Audio Recordings</option>
   		<option value="images">Images</option>
   		<option value="scalar">Scalar Data</option>
   	</select> <br/>
   	Location: <input type ="input" name="location"/> <br/>
   	
   	<!--Date range forms -->
  		<br/>
   	(Input dates in the form d/m/yyyy Hours:minutes:seconds) <br/>
   	Start Date of Records: <input type="date" name="startdate" value ="20/09/2014 22:10:26" /> <br/>
   	End Date of Records: <input type="date" name="enddate" value = '<?php echo date("d/m/Y H:i:s");  ?>' /> <br/>
   	<input type = "submit" name ="search" value = "Search Records" /></form>
  
   </div>
  </div>
  <div id="tabs-3">
  	<!-- Data Analysis Tab -->
	<?php 
		   //establish connection
			   $conn=connect();
			   $sql = null;
			   //Pull all scalar data sensors user is subscribed to	   
				$sql = '	SELECT 		s.sensor_id
							FROM 			sensors s, persons p, subscriptions u
							WHERE 		s.sensor_id = u.sensor_id
							AND 			p.person_id = u.person_id
							AND			s.sensor_type = \'s\'
							AND			p.person_id = '.$_COOKIE['Person'].'
							GROUP BY		s.sensor_id';
			   //Prepare sql using conn and returns the statement identifier
			   $stidd = oci_parse($conn, $sql );
			   
			   //Execute a statement returned from oci_parse()
			   $res=oci_execute($stidd, OCI_DEFAULT); 
			   
			   //if error, retrieve the error using the oci_error() function & output an error
			   if (!$res) {
					$err = oci_error($stidd);
					echo htmlentities($err['message']);
			   } 
		?>
			<!-- Button to pass off scalar sensr they would like to see -->
			<form name = "getYearly" method ="post" action="getYearly.php">
				<select name="sensor">
					<?php while ($row = oci_fetch_array($stidd, OCI_ASSOC)) { ?>
						<?php echo '<option value ="'.$row['SENSOR_ID'].'" > '.$row['SENSOR_ID'].' </option>'; }?>
				</select>
				<input type = "submit" name = "getYearly" value = "Get Sensors Reports" />
			</form>
			
  </div>
  <div id="tabs-4">
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
	
	<!-- Change Personal Information-->
	<div id="divChangePerson">
		<?php 
			$conn=connect();
			//Pull all personal data
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
</div> <!-- end of container-->

<script type="text/javascript">
	//Logout button on click
	$("#logout").click(function(){
			window.location.href = "login.php?status=logout";
  		});
  		
  	//user manual button on click
	$("#userManual").click(function(){
			window.location.href = "userManual.html";
  		});
  	
  	//Handing fade in and outs for forms with button clicks
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