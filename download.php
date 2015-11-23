 <?php
	include("PHPconnectionDB.php");
	if(isset($_POST['id']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn"
	&& $_COOKIE['Role'] == 's'){
		$id = $_POST['id'];
		$type = $_POST['type'];
		$ext = $_POST['ext'];
		$conn=connect();
		$sql = NULL;
		if($ext == 'jpg'){
			$sql = 'SELECT recorded_data FROM images WHERE image_id = '.$id.'';
		}
		else if($ext === 'wav'){
			$sql = 'SELECT recorded_data FROM audio_recordings WHERE recording_id = '.$id.'';
		}
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		} 
		$row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_LOBS+OCI_RETURN_NULLS);
		// Free the statement identifier when closing the connection
		oci_free_statement($stid);
		oci_close($conn);
		/*
		//If no blob data is found 
		if(!$row){
			echo 'Alert("No recorded data found in the database")';
			echo 'made it in?';
			header("Location:searchsensors.php");
			exit();
		}
		*/
		//place the file contents into data to be sent to the user
		if($ext == 'jpg'){
			$data = base64_decode($row[0]);
    	}
    	else if($ext == 'wav'){
    		$data = $row[0];
    	}
    	ob_clean(); //make sure there is nothing in front of where the file content will be written
		//send the download to the user
		header('Content-Description: File Transfer');
    	header('Content-Type: octet-stream');
    	header('Content-Disposition: attachment; filename="'.$id.'.'.$ext.'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header("Content-Transfer-Encoding: binary");

		print($data);
		
		header("Location:searchsensors.php");
		exit();	
	}
	else{
		header("Location:index.php");		 
	} 