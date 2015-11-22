 <?php
	include("PHPconnectionDB.php");
	if(isset($_POST['id']) && isset($_POST['data'])  && isset($_COOKIE['Person'])
	&& $_COOKIE['Status'] == "LoggedIn" && $_COOKIE['Role'] == 's'){
		$id = $_POST['id'];
		$data = $_POST['data'];
		$ext = $_POST['ext'];
		//echo $data;
		
		//file_put_contents('temp'."/h".$id, base64_encode($data));h
		
		$size = strlen($data);
		header('Content-Description: File Transfer');
    	header('Content-Type: octet-stream');
    	header('Content-Disposition: attachment; filename="'.$id.'.'.$ext.'"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header("Content-Transfer-Encoding: binary");
    	//header('Content-Length: ' . size);
    	//printf('<img src="data:image/jog;base64,%s"/>', base64_encode($data));
		
		//header("Location:searchsensors.php");
		//exit();
		
	}
	else{
		header("Location:index.php");		 
		
	} 
	?>