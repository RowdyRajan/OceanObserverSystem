<?php
//Uploads audio then put the audio into the 


//Much of this code is from http://www.w3schools.com/php/php_file_upload.asp
include("PHPconnectionDB.php");

if(!(isset($_COOKIE['Person'])) || !($_COOKIE['Status'] == "LoggedIn") || !($_COOKIE['Role'] == 'd'))
{
	header("Location:index.php");	
}
//$target_dir = "uploads/";
//$target_file = $target_dir . basename($_FILES["csvFile"]["name"]);
//explode stolen from comment by zzzzBov at http://stackoverflow.com/questions/4103287/read-a-plain-text-file-with-php

$audio_dir =$_FILES["csvFile"]["tmp_name"];
$uploadOk = 1;
$csvFileType = pathinfo(basename($_FILES["csvFile"]["name"]),PATHINFO_EXTENSION);

/*
// Check file size
if ($_FILES["csvFile"]["size"] > 500000) {
	echo "Sorry, your file is too large.";
	$uploadOk = 0;
}
*/
// Allow certain file formats
if($csvFileType != "csv" && $uploadOk == 1){
	echo "Sorry, only CSV files are allowed. ";
	echo $csvFileType;
	echo " is not CSV.";
	$uploadOk = 0;
}
$scalars = explode("\n", file_get_contents($_FILES['csvFile']['tmp_name']));	
foreach ($scalars as &$row) {
		$items = explode(",", $row);
		$sensor_id = $items[0];
		$date_created = $items[1];
		$value = $items[2];		
		if(isset($sensor_id) && isset($date_created) && isset($value))
		{
			//Check if the sensor_id exists
			$conn=connect();
			$sql = 'SELECT sensor_id FROM sensors
						WHERE sensor_id = '.$sensor_id.'';
			$stid = oci_parse($conn, $sql );
			//Execute a statement returned from oci_parse()
			$res=oci_execute($stid, OCI_DEFAULT); 		   
			//if error, retrieve the error using the oci_error() function & output an error
			if (!$res) {
				$err = oci_error($stid);
				echo htmlentities($err['message']);
			} 
			$row = oci_fetch_array($stid, OCI_ASSOC);
			oci_free_statement($stid);
			if(!$row['SENSOR_ID']){
				echo 'The sensor with the sensor id: '.$sensor_id.' does not exist. 
				The row: SENSOR_ID: '.$sensor_id.' DATE_CREATED: '.$date_created.' VALUE: '.$value.' 
				has not been uploaded<br/>';
				$uploadOk = 0;
			}
			else {
				//Increments the number in scalarid.txt each time it is run to generate unique ids for each scalar_data
				//adapted from http://www.developingwebs.net/phpclass/hitcounter.php
				$last_scalar_id = ("scalarid.txt");
				$id = file($last_scalar_id);
				$id[0] = $id[0] + 1;
				$fp = fopen($last_scalar_id, "w");
				fputs($fp , "$id[0]");
				fclose($fp);	
				$id = $id[0];
				
				echo 'Adding Row: ID: '.$id.' SENSOR_ID: '.$sensor_id.' DATE_CREATED: '.$date_created.' VALUE: '.$value.'<br/>';
				//Try to put the data into the database
				$sql = 'INSERT INTO scalar_data VALUES(\''.$id.'\', \''.$sensor_id.'\', to_date(\''.$date_created.'\',
						\'dd/mm/yyyy HH24:Mi:SS\'), \''.$value.'\')';
				$stid = oci_parse($conn, $sql);
				$res=oci_execute($stid);
				if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
				}

				//commit the change
				$res = oci_commit($conn);
				if (!$res) {
					$err = oci_error($conn);
  				  trigger_error(htmlentities($err['message']), E_USER_ERROR);
				} else {
  					echo 'Row successfully added to database <br/>';	
			}
		}
	}
}
$conn=connect();
$sql = 			
'DROP TABLE fact';
$stid = oci_parse($conn, $sql);
$res=oci_execute($stid);
$res = oci_commit($conn);

$sql = 'CREATE TABLE fact(
		sensor_id	int,
		id		int,
		time_id		date,
		svalue		float,
		PRIMARY KEY (sensor_id, id, time_id),
		FOREIGN KEY (sensor_id) REFERENCES sensors,
		FOREIGN KEY (id) REFERENCES scalar_data,
		FOREIGN KEY (time_id) REFERENCES time)';
$stid = oci_parse($conn, $sql);
$res=oci_execute($stid);
$res = oci_commit($conn);

$sql = 			
'INSERT INTO fact ( sensor_id, id, time_id, svalue)
SELECT	s.sensor_id, c.id, t.time_id, c.value
FROM	sensors s, scalar_data c, time t
WHERE	s.sensor_id = c.sensor_id
AND	t.time_id LIKE c.date_created';
$stid = oci_parse($conn, $sql);
$res=oci_execute($stid);
$res = oci_commit($conn);

	/*
	//Check id taken
	$conn=connect();
	$sql = 'SELECT id FROM scalar_data
			WHERE id = '.$_POST['id'].'';
	$stid = oci_parse($conn, $sql );
	//Execute a statement returned from oci_parse()
	$res=oci_execute($stid, OCI_DEFAULT); 		   
	//if error, retrieve the error using the oci_error() function & output an error
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
	} 
	$row = oci_fetch_array($stid, OCI_ASSOC);
	if($row['id']){
		echo 'An audio with the audio id: '.$_POST['id'].' already exists. <br/>';
		$uploadOk = 0;
	}
	oci_free_statement($stid);
	
	$conn=connect();
	$sql = 'SELECT sensor_id FROM sensors
			WHERE sensor_id = '.$_POST['sensor_id'].'';
	$stid = oci_parse($conn, $sql );
	//Execute a statement returned from oci_parse()
	$res=oci_execute($stid, OCI_DEFAULT); 		   
	//if error, retrieve the error using the oci_error() function & output an error
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
	} 
	$row = oci_fetch_array($stid, OCI_ASSOC);
	if(!$row['SENSOR_ID']){
		echo 'The sensor with the sensor id: '.$_POST['sensor_id'].' does not exist. <br/>';
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	}
	else{
		
		//Attempt to put audio into database
		//Code stolen and adapted from https://stackoverflow.com/questions/11970258/upload-scalar_data-as-blobs-in-oracle-using-php
		
		$conn=connect();
		//RECOREDED DATA VS. RECOREDED DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$sql = 
			'INSERT INTO scalar_data(id, sensor_id, date_created, description, recorded_data)
			VALUES (\''.$_POST['id'].'\', \''.$_POST['sensor_id'].'\', to_date(\''.$_POST['date_created'].'\',
			\'dd/mm/yyyy HH24:Mi:SS\'), \''.$_POST['description'].'\', empty_blob()) 
			RETURNING recorded_data INTO :recorded_data';

		$stid = oci_parse($conn, $sql);
		
		$blob = oci_new_descriptor($conn, OCI_D_LOB);
		oci_bind_by_name($stid, ':recorded_data', $blob, -1, OCI_B_BLOB);
		oci_execute($stid, OCI_DEFAULT) or die ("Unable to execute query");

		if(!$blob->save($audio)){
			oci_rollback($conn);
			echo "Aborted.";
		}
		else {
			$res = oci_commit($conn);
			
			if (!$res) {
				$err = oci_error($conn);
				trigger_error(htmlentities($err['message']), E_USER_ERROR);
			}
			else{
				echo "audio File Successfully Uploaded.";
				
				//Show view of the audio.
				//Stolen from Gordon♦'s comment at https://stackoverflow.com/questions/3385982/the-audio-cannot-be-displayed-because-it-contains-errors
				//echo '<br/>';
				//printf('<img src="data:audio/jog;base64,%s"/>', base64_encode($thumb));
				//echo '<br/><br/>';
				//printf('<img src="data:audio/jog;base64,%s"/>', base64_encode($audio));
			}
		}
		oci_free_statement($stid);
		$blob->free();
		
	}
}

else{
	echo "Please input proper data.";
	echo $_POST['sensor_id'];
	echo $_POST['id'];
	echo $_POST['csvFile'];
}
*/
?>
<form name = "return" method = "post" action ="index.php">
	<input type = "submit" name="breturn" value = "Return to User Page" />
</form>