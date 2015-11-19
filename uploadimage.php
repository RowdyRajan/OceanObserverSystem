<?php
//Uploads image then put the image into the 

/*
CHECKLIST TO BE DELETED --------------------------------------------------------------------------------------
- Check for proper input/duplicates
- Fix echos and other chekcs
- Make thumbnail
- redirect and allow to view image? figure out how to view image?

*/
//Much of this code is from http://www.w3schools.com/php/php_file_upload.asp
include("PHPconnectionDB.php");
if(isset($_POST['sensor_id']) && isset($_POST['image_id'])) //&& isset($_POST['imageFile']))
{
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["imageFile"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["imageSubmit"])) {
		$check = getimagesize($_FILES["imageFile"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	/*
	// Check file size
	if ($_FILES["imageFile"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	*/
	// Allow certain file formats
	if($imageFileType != "jpg"){
		echo "Sorry, only JPG files are allowed. ";
		echo $imageFileType;
		echo " is not jpg.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
		/*
	} else {
		if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["imageFile"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
		*/
	}
	
	
	//Attempt to put image into database
	//Code stolen and adapted from https://stackoverflow.com/questions/11970258/upload-images-as-blobs-in-oracle-using-php
	$image = file_get_contents($_FILES['imageFile']['tmp_name']);
	$conn=connect();
	//RECOREDED DATA VS. RECOREDED DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$sql = 
		'
		INSERT INTO images(image_id, sensor_id, date_created, description, recorded_data)
		VALUES (\''.$_POST['image_id'].'\', \''.$_POST['sensor_id'].'\', to_date(\''.$_POST['date_created'].'\',
		\'dd/mm/yyyy\'), \''.$_POST['description'].'\', empty_blob()) RETURNING recorded_data INTO :recorded_data';

	$stid = oci_parse($conn, $sql);
	
	$blob = oci_new_descriptor($conn, OCI_D_LOB);
	oci_bind_by_name($stid, ':recorded_data', $blob, -1, OCI_B_BLOB);
	oci_execute($stid, OCI_DEFAULT) or die ("Unable to execute query");

	if(!$blob->save($image)) {
		oci_rollback($connection);
	}
	else {
		oci_commit($conn);
	}

	oci_free_statement($stid);
	$blob->free();
	
	//commit the change
	$res = oci_commit($conn);
	if (!$res) {
		$err = oci_error($conn);
    trigger_error(htmlentities($err['message']), E_USER_ERROR);
	
	}
}
else{
	echo "Please input proper data.";
	echo $_POST['sensor_id'];
	echo $_POST['image_id'];
	echo $_POST['imageFile'];
}
?> 