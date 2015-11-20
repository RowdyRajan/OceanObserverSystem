<html>
<?php
include	("PHPconnectionDB.php");
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 's'){	 }
else{
header("Location:index.php");		 
} 
?>

<head>
	<title>Search Results</title>
</head>

<body>

	<?php
		if(isset($_POST['searchsensor'])){
			$person 		= $_COOKIE['Person'];
			$keywords 	= $_POST['fields'];
			$type			= $_POST['type'];
			$location	= $_POST['location'];
			$startdate	= $_POST['startdate'];
			$enddate		= $_POST['enddate'];
			
			$conn = connect();
			if($_POST['fields'] =="audio"){
				$sql = '	SELECT S.sensor_id, S.location, S.description, A.description, A.length, A.recorded_data
							FROM sensors S, audio_recordings A, subscriptions U
							WHERE	U.person_id = \''.$_COOKIE['Person'].'\'
							AND	S.sensor_type = a
							AND	U.sensor_id = S.sensor_id
							AND	A.sensor_id = S.sensor_id
							AND	A.date_created BETWEEN \''.$_POST['startdate'].'\' AND \''.$_POST['enddate'].'\'
							AND	REGEXP_LIKE(S.description, \''.$_POST['fields'].'\' )
							OR 	REGEXP_LIKE(S.description, \''.$_POST['fields'].'\' )';
			}
			$stid = oci_parse($conn, $sql);
			$res = oci_execute($stid, OCI_DEFAULT);
			if (!$res) {
					$err = oci_error($stid);
					header('Refresh: 3; url = index.php');
		 			echo '<h2>Error: Incorrect search input, returning to user page...</h2>';
		 			exit;
		 	}
		}
	?>
	<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Audio Description</TH>
		<TH>Audio Length</TH>
		<TH>Audio Data</TH>
		</TR>
		<?php
			while ($row = oci_fetch_array($stid, OCI_ASSOC)){ ?>
			<TD> <?php echo $row['SENSOR_ID']; ?> </TD>
			<TD> <?php echo $row['LOCATION'];?> </TD>
			<TD> <?php echo $row['DESCRIPTION'];?> </TD>
			<TD> <?php echo $row['DESCRIPTION'];?> </TD>
			<TD> <?php echo $row['LENGTH'];?> </TD>
			<TD> <?php echo $row['RECORDED_DATA'];?> </TD>
			<?php } ?>
		</TR>
		</TABLE>
			
	<form name = "return" method = "post" action ="index.php">
		<input type = "submit" name="breturn" value = "Return to User Page" />
	</form>
</body>
</html>