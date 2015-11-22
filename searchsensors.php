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
		
		if(isset($_POST['search'])){
		
			$person 		= $_COOKIE['Person'];
			$keywords 	= $_POST['fields'];
			$type			= $_POST['type'];
			$location	= $_POST['location'];
			$startDate	= $_POST['startdate'];
			$endDate		= $_POST['enddate'];
			/*
			if ($type == "all"){
				$TypeParam = "audio_recordings A, images I, scalar_data SD";
				$typeKeywords = "(A.description = '".$keywords."' OR I.description = '".$keywords."')";			
			} else if ($type == "audio"){
				$TypeParam = "audio_recordings A";
				$typeKeywords = "(A.description = '".$keywords."')";
			} else if ($type == "images"){
				$TypeParam = "images ID";
				$typeKeywords = "I.description = '".$keywords."')";
			}else if ($type == "scalar"){
				$TypeParam = "scalar_data SD";
				$typeKeywords = "";
			}*/
			if($keywords == ""){
				$keywordParam = ""; 
				$TypeKeywords = "";
			}else {
				$keywordParam = "AND (S.description = '".$keywords."' OR ".$typeKeywords.")";  			
			}
			
			if($location == ""){
				$locationParam = "";			
			}else{
				$locationParam = " AND S.location = '".$location."'";						
			}
			
			if($type == "audio"){
				if($keywords == ""){
					$keywordParam = ""; 
					$TypeKeywords = "";
				}else {
					$typeKeywords = "(A.description = '".$keywords."')";					
					$keywordParam = " AND (S.description = '".$keywords."' OR ".$typeKeywords.")";  			
				}
				
			 $sql = "Select S.sensor_id, S.location, S.description, A.recording_id, A.description, A.length, A.date_created, A.recorded_data
			 			FROM sensors S, audio_recordings A, subscriptions U 
			 			WHERE	U.person_id =".$_COOKIE['Person']." 
			 			AND S.sensor_type = 'a'
			 			AND U.sensor_id = S.sensor_id
						AND A.sensor_id = S.sensor_id
						AND (A.date_created BETWEEN to_date('".$startDate."', 'DD/MM/YYYY HH24:MI:SS') AND to_date('".$endDate."', 'DD/MM/YYYY HH24:MI:SS'))
						".$locationParam.$keywordParam;
				
				
				$conn = connect();
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				if (!$res) {
					$err = oci_error($stid);
					//header('scientist.php?error=general&place=audio');
		 			//exit;
		 		}
		 	
		 ?>
		<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Audio ID</TH>
		<TH>Audio Description</TH>
		<TH>Audio Length</TH>
		<TH>Audio Date Created</TH>
		<TH>Audio Data</TH>
		</TR>
		<?php
			while ($row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_NULLS+OCI_RETURN_LOBS)){ ?>
			<TD> <?php echo $row[0]; ?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>
			<TD> <?php echo $row[6];?> </TD>
			<TD> <?php 
				//print $lob->load();
				//browser promts to save or open the file
			showDownload($row[7], $row[3], 'wav'); 
			?> </TD></TR>
			<?php } ?>
		</TR>
		</TABLE>	
		 		
		 	
<?php
			} elseif($type == "scalar"){
				
			 $sql = "Select S.sensor_id, S.location, S.description, SC.id, SC.date_created, SC.value
			 			FROM sensors S, scalar_data SC, subscriptions U 
			 			WHERE	U.person_id =".$_COOKIE['Person']." 
			 			AND S.sensor_type = 's'
			 			AND U.sensor_id = S.sensor_id
						AND SC.sensor_id = S.sensor_id
						AND (SC.date_created BETWEEN to_date('".$startDate."', 'DD/MM/YYYY HH24:MI:SS') AND to_date('".$endDate."', 'DD/MM/YYYY HH24:MI:SS'))
						".$locationParam;
				echo $sql;
				
				$conn = connect();
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				if (!$res) {
					$err = oci_error($stid);
					//header('scientist.php?error=general&place=audio');
		 			//exit;
		 		}
		 	
		 ?>
		<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Scalar Data ID</TH>
		<TH>Date Scalar Data Created</TH>
		<TH>Value</TH>
		</TR>
		<?php
			while ($row = oci_fetch_array($stid, OCI_NUM)){ ?>
			<TD> <?php echo $row[0]; ?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>	
			<?php } ?>
		</TR>
		</TABLE>	
		 		
		 	
<?php
			} elseif($type == "images"){
				if($keywords == ""){
					$keywordParam = ""; 
					$TypeKeywords = "";
				}else {
					$typeKeywords = "(I.description = '".$keywords."')";					
					$keywordParam = " AND (S.description = '".$keywords."' OR ".$typeKeywords.")";  			
				}
				
			 $sql = "Select S.sensor_id, S.location, S.description, I.image_id, I.description, I.date_created, I.thumbnail, I.recorded_data
			 			FROM sensors S, images I, subscriptions U 
			 			WHERE	U.person_id =".$_COOKIE['Person']." 
			 			AND S.sensor_type = 'i'
			 			AND U.sensor_id = S.sensor_id
						AND I.sensor_id = S.sensor_id
						AND (I.date_created BETWEEN to_date('".$startDate."', 'DD/MM/YYYY HH24:MI:SS') AND to_date('".$endDate."', 'DD/MM/YYYY HH24:MI:SS'))
						".$locationParam.$keywordParam;
				
				
				$conn = connect();
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				if (!$res) {
					$err = oci_error($stid);
					//header('scientist.php?error=general&place=audio');
		 			//exit;
		 		}
		 	
		 ?>
		<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Image ID</TH>
		<TH>Image Description</TH>
		<TH>Date Image Created</TH>
		<TH>Image thumbnail</TH>
		<TH>Recorded data</TH>
		</TR>
		<?php
			while ($row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_LOBS)){ ?>
			<TD> <?php echo $row[0]; ?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>
			<TD> <?php 
			//printf('<img src="data:image/jog;base64,%s"/>', base64_encode(file_get_contents($row[6])));echo 'THUMBNAIL!'; echo $row[6]; echo 'this is the thumbnail';
			//header('Content-disposition: attachment;filename='.$_GET['name']);

          $lob = $row[6];
          //$row[6]->free();
          printf('<img src="data:image/jog;base64,%s"/>', base64_encode($lob));		
			?> </TD>

			<
			<TD> <?php 
			//header('Content-disposition: attachment;filename='.$_GET['name']);		
			//$lob = $row[7]->load();
         //$row[7]->free();	
			//echo $row[7];
			showDownload($row[7], $row[3], 'jpg'); 
			?> </TD></TR>
			<?php } ?>
		</TR>
		</TABLE>	
		 		
		 	
<?php
			} 
		}
		
function showDownload($data, $id, $ext){
	if($data != NULL){
     	echo '<form name = "download" method ="post" action="download.php">
				<input type="hidden" name="id" value="'.(int)$id.'" />
				<input type="hidden" name="data" value="'.$data.'" />
				<input type="hidden" name="ext" value="'.$ext.'" />
				<input type = "submit" value="Downaload"/>
				</form>';
	}
}
?>		
		
		
		
			
			




</body>
</html>
