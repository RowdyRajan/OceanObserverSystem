<?php
//Uploads audio then put the audio into the 


//Much of this code is from http://www.w3schools.com/php/php_file_upload.asp
include("PHPconnectionDB.php");

if(!(isset($_COOKIE['Person'])) || !($_COOKIE['Status'] == "LoggedIn") || !($_COOKIE['Role'] == 'd'))
{
	header("Location:index.php");	
}
else if(isset($_POST['sensor_id']) && isset($_POST['recording_id'])) //&& isset($_POST['audioFile']))
{
	//$target_dir = "uploads/";
	//$target_file = $target_dir . basename($_FILES["audioFile"]["name"]);
	$audio = file_get_contents($_FILES['audioFile']['tmp_name']);
	$audio_dir =$_FILES["audioFile"]["tmp_name"];
	$uploadOk = 1;
	$audioFileType = pathinfo(basename($_FILES["audioFile"]["name"]),PATHINFO_EXTENSION);
	
	/*
	// Check file size
	if ($_FILES["audioFile"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	*/
	// Allow certain file formats
	if($audioFileType != "wav" && $uploadOk == 1){
		echo "Sorry, only WAV files are allowed. ";
		echo $audioFileType;
		echo " is not WAV.";
		$uploadOk = 0;
	}
	
	//Check recording_id taken
	$conn=connect();
	$sql = 'SELECT recording_id FROM audio_recordings
			WHERE recording_id = '.$_POST['recording_id'].'';
	$stid = oci_parse($conn, $sql );
	//Execute a statement returned from oci_parse()
	$res=oci_execute($stid, OCI_DEFAULT); 		   
	//if error, retrieve the error using the oci_error() function & output an error
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
	} 
	$row = oci_fetch_array($stid, OCI_ASSOC);
	if($row['recording_id']){
		echo 'An audio with the audio id: '.$_POST['recording_id'].' already exists. <br/>';
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
		//Code stolen and adapted from https://stackoverflow.com/questions/11970258/upload-audio_recordings-as-blobs-in-oracle-using-php
		
		$conn=connect();
		//RECOREDED DATA VS. RECOREDED DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$sql = 
			'INSERT INTO audio_recordings(recording_id, sensor_id, date_created, description, recorded_data)
			VALUES (\''.$_POST['recording_id'].'\', \''.$_POST['sensor_id'].'\', to_date(\''.$_POST['date_created'].'\',
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
				printf('<img src="data:audio/jog;base64,%s"/>', base64_encode($audio));
			}
		}
		oci_free_statement($stid);
		$blob->free();
		
	}
}
else{
	echo "Please input proper data.";
	echo $_POST['sensor_id'];
	echo $_POST['recording_id'];
	echo $_POST['audioFile'];
}
?>

<?php 
