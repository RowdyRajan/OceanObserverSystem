<html>
<?php
include	("PHPconnectionDB.php");
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
  </div>
</div>
</div> <!-- end of container-->
</body>
</html>