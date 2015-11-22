 <?php
	if(isset($_POST['scalars']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE['Role'] == 's'){
		//$scalars = $_POST['scalars'];
		//echo $scalars;
		$scalars = explode("_",$_POST['scalars']);
		ob_clean();
		header('Content-Description: File Transfer');
    	header('Content-Type: octet-stream');
    	header('Content-Disposition: attachment; filename="scalar_data.csv"');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
    	header("Content-Transfer-Encoding: binary");
		$first = 0;
		foreach ($scalars as $scalar) {
			if($first != 1){
				$first = 1;
			} else{
				echo "\n";
			}
			echo $scalar;
		}
		//header("Location:searchsensors.php");
		//exit();	
		/*
		if($ext == 'jpg'){
			$im = imagecreatefromstring($row[0]);
			ob_start();
    		imagejpeg($im);
    		$data = ob_get_clean();
    	}
    	else if($ext == 'wav'){
    		$data = $row[0];
    	}
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
		print($data);
		
		header("Location:searchsensors.php");
		exit();	
		*/
	}
	else{
		header("Location:index.php");		 
	} 