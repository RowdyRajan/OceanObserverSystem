 <?php
	if(isset($_POST['scalars']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE['Role'] == 's'){
		//split scalars back into an array
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
		//iterate through the array and each scalar
		foreach ($scalars as $scalar) {
			if($first != 1){
				$first = 1;
			} else{
				echo "\n";
			}
			echo $scalar;
		}
	}
	else{
		header("Location:index.php");		 
	} 