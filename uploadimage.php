<?php
//For uploading images into the database

//Much of this code is from http://www.w3schools.com/php/php_file_upload.asp
include("PHPconnectionDB.php");

if(!(isset($_COOKIE['Person'])) || !($_COOKIE['Status'] == "LoggedIn") || !($_COOKIE['Role'] == 'd'))
{
	header("Location:index.php");	
}
else if(isset($_POST['sensor_id']) && isset($_POST['image_id'])) //&& isset($_POST['imageFile']))
{
	$image = file_get_contents($_FILES['imageFile']['tmp_name']);
	$image = base64_encode($image);
	$image_dir =$_FILES["imageFile"]["tmp_name"];
	$uploadOk = 1;
	$imageFileType = pathinfo(basename($_FILES["imageFile"]["name"]),PATHINFO_EXTENSION);
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["imageSubmit"])) {
		$check = getimagesize($_FILES["imageFile"]["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}
	/*
	// Check file size
	if ($_FILES["imageFile"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	*/
	// Allow certain file formats
	if($imageFileType != "jpg" && $uploadOk == 1){
		echo "Sorry, only JPG files are allowed. ";
		echo $imageFileType;
		echo " is not jpg.";
		$uploadOk = 0;
	}
	
	//Check if image_id taken
	$conn=connect();
	$sql = 'SELECT image_id FROM images
			WHERE image_id = '.$_POST['image_id'].'';
	$stid = oci_parse($conn, $sql );
	//Execute a statement returned from oci_parse()
	$res=oci_execute($stid, OCI_DEFAULT); 		   
	//if error, retrieve the error using the oci_error() function & output an error
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
	} 
	$row = oci_fetch_array($stid, OCI_ASSOC);
	if($row['IMAGE_ID']){
		echo 'An image with the image id: '.$_POST['image_id'].' already exists. <br/>';
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
		//Create Thumbnail
		$thumb = createThumbnail($image_dir, $image_dir . '_thumb.jpg', 50, 50);
		if(!$thumb){
			echo "Sorry, an error has occurred while creating thumbnail";
			$uploadOk = 0;
		}
		//Attempt to put image into database
		//Code stolen and adapted from https://stackoverflow.com/questions/11970258/upload-images-as-blobs-in-oracle-using-php
		$conn=connect();
		//RECOREDED DATA VS. RECOREDED DATA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$sql = 
			'INSERT INTO images(image_id, sensor_id, date_created, description, thumbnail, recorded_data)
			VALUES (\''.$_POST['image_id'].'\', \''.$_POST['sensor_id'].'\', to_date(\''.$_POST['date_created'].'\',
			\'dd/mm/yyyy HH24:Mi:SS\'), \''.$_POST['description'].'\', empty_blob(), empty_blob()) 
			RETURNING thumbnail, recorded_data INTO :thumbnail, :recorded_data';

		$stid = oci_parse($conn, $sql);
		
		$tblob = oci_new_descriptor($conn, OCI_D_LOB);
		$iblob = oci_new_descriptor($conn, OCI_D_LOB);
		oci_bind_by_name($stid, ':thumbnail', $tblob, -1, OCI_B_BLOB);
		oci_bind_by_name($stid, ':recorded_data', $iblob, -1, OCI_B_BLOB);
		oci_execute($stid, OCI_DEFAULT) or die ("Unable to execute query");

		if(!$tblob->save($thumb) || !$iblob->save($image)){
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
				echo "Image File Successfully Uploaded.";	
				//Show view of the image.
				//Stolen from Gordon♦'s comment at https://stackoverflow.com/questions/3385982/the-image-cannot-be-displayed-because-it-contains-errors
				echo '<br/>';
				printf('<img src="data:image/jog;base64,%s"/>', base64_encode($thumb));
				echo '<br/><br/>';
				printf('<img src="data:image/jog;base64,%s"/>', $image);
			}
		}
		oci_free_statement($stid);
		$tblob->free();
		$iblob->free();
	}
}
else{
	echo "Please input proper data.";
	echo $_POST['sensor_id'];
	echo $_POST['image_id'];
	echo $_POST['imageFile'];
}

?>
<form name = "return" method = "post" action ="index.php">
	<input type = "submit" name="breturn" value = "Return to User Page" />
</form>
<?php 
//Copied and Pasted from https://stackoverflow.com/questions/11376315/creating-a-thumbnail-from-an-uploaded-image
function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height) {
    list($original_width, $original_height, $original_type) = getimagesize($filepath);
    if ($original_width > $original_height) {
        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);
    } else {
        $new_height = $thumbnail_height;
        $new_width = intval($original_width * $new_height / $original_height);
    }
    $dest_x = intval(($thumbnail_width - $new_width) / 2);
    $dest_y = intval(($thumbnail_height - $new_height) / 2);

    if ($original_type === 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    } else if ($original_type === 2) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    } else if ($original_type === 3) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    } else {
        return false;
    }

    $old_image = $imgcreatefrom($filepath);
    $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);

	//Stolen from comment by gre_gor at https://stackoverflow.com/questions/33601108/get-stream-from-imagecopyresampled
	ob_start();
    imagejpeg($new_image);
    $imageData = ob_get_clean();
    return $imageData;
} 