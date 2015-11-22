 <?php
	include("PHPconnectionDB.php");
	if(isset($_POST['id']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE['Role'] == 's'){
		$id = $_POST['id'];
		$type = $_POST['type'];
		$ext = $_POST['ext'];
		//echo $data;
		//file_put_contents('temp'."/h".$id, base64_encode($data));
		//$data = base64_decode($data);
		//echo 'this is data'.$data.'';
		//printf('<img src="data:image/jog;base64,%s"/>', base64_encode($data));	
		//$image = imagecreatefromstring($data); 
		//file_put_contents('temp'."/h".$id.$ext, $im);
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
		$row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_LOBS);
		// Free the statement identifier when closing the connection
		oci_free_statement($stid);
		oci_close($conn);
		//$data = $row[0];
		$im = imagecreatefromstring($row[0]);
		//$im = $row[7];
		//imagejpeg($im, 'temp/COMPLEXTEST'.$row[3].'.jpg')
		ob_start();
    	imagejpeg($im);
    	$imageData = ob_get_clean();
    	ob_clean();
		//$size = strlen($im);
		header('Content-Description: File Transfer');
    	header('Content-Type: octet-stream');
    	header('Content-Disposition: attachment; filename="'.$id.'.'.$ext.'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header("Content-Transfer-Encoding: binary");
    	//header('Content-Length: ' . size);
    	//echo $data;
    	//imagejpeg($im);
		print($imageData);
		//header("Location:searchsensors.php");
		//exit();	
	}
	else{
		header("Location:index.php");		 
	} 