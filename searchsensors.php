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
			
			$scalars = array();
			
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
				
			 $sql = "Select S.sensor_id, S.location, S.description, A.recording_id, A.description, A.length, to_char(A.date_created, 'dd/mm/yyyy hh24:mi:ss') datetime, A.recorded_data
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
		<TH>Recorded Data</TH>
		</TR>
		<?php
			while ($row = oci_fetch_array($stid, OCI_NUM)){ ?>
			<tr>
			<TD> <?php echo $row[0]; ?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>
			<TD> <?php echo $row[6];?> </TD>
			<TD> <?php 
				showDownload($row[3], 'wav'); 
			?> </TD>
			</tr>
			<?php } ?>
		
		</TABLE>	
		 		
		 	
<?php
			} elseif($type == "scalar"){
			
			//code for formating date stolen form https://community.oracle.com/thread/312115?start=0&tstart=0
			 $sql = "Select S.sensor_id, S.location, S.description, SC.id, to_char(SC.date_created, 'dd/mm/yyyy hh24:mi:ss') datetime, SC.value
			 			FROM sensors S, scalar_data SC, subscriptions U 
			 			WHERE	U.person_id =".$_COOKIE['Person']." 
			 			AND S.sensor_type = 's'
			 			AND U.sensor_id = S.sensor_id
						AND SC.sensor_id = S.sensor_id
						AND (SC.date_created BETWEEN to_date('".$startDate."', 'DD/MM/YYYY HH24:MI:SS') AND to_date('".$endDate."', 'DD/MM/YYYY HH24:MI:SS'))
						".$locationParam;
				//echo $sql;
				
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
		<TH>CSV</TH>
		</TR>
		<?php
			while ($row = oci_fetch_array($stid, OCI_NUM)){ 
			$scalars[$row[3]] = ''.$row[0].','.$row[4].','.$row[5].'';
			?>
			<tr>
			<TD> <?php echo $row[0]; ?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>
			<TD> <?php 	echo '<form name = "download_scalar" method ="post" action="downloadscalars.php">
									<input type="hidden" name="scalars" value="'.$scalars[$row[3]].'" />	
									<input type = "submit" value="Download"/>
									</form>'; ?>
			</tr>
			<?php } ?>
		
		</TABLE>
		<?php 		
		echo '<form name = "download_scalar" method ="post" action="downloadscalars.php">
									<input type="hidden" name="scalars" value="'.implode("_", $scalars).'" />	
									<input type = "submit" value="Download All Scalars"/>
									</form>'; ?>		
		 	
<?php
			} elseif($type == "images"){
				if($keywords == ""){
					$keywordParam = ""; 
					$TypeKeywords = "";
				}else {
					$typeKeywords = "(I.description = '".$keywords."')";					
					$keywordParam = " AND (S.description = '".$keywords."' OR ".$typeKeywords.")";  			
				}
				
			 $sql = "Select S.sensor_id, S.location, S.description, I.image_id, I.description, to_char(I.date_created, 'dd/mm/yyyy hh24:mi:ss') datetime, I.thumbnail
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
		<TH>Thumbnail</TH>
		<TH>Recorded Data</TH>
		</TR>
		
		<?php
		
			while ($row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_NULLS+OCI_RETURN_LOBS)){ ?>
			<tr>
			<TD> <?php echo $row[0];?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>
			<TD> <?php printf('<img src="data:image/jog;base64,%s"/>', base64_encode($row[6]));?> </TD>
			<TD> <?php 
				if($row[6] != NULL){
					showDownload($row[3], 'jpg'); 
				}
			?> </TD>
			</tr>
			<?php } ?>
	
		</TABLE>	
		

		 		
		 	
<?php
			} elseif($type == "all"){
				if($keywords == ""){
					$keywordParam = ""; 
					$TypeKeywords = "";
				}else {
					$typeKeywords = "(I.description = '".$keywords."')";					
					$keywordParam = " AND (S.description = '".$keywords."' OR ".$typeKeywords.")";  			
				}
				
			 $sql = "Select S.sensor_id, S.location, S.description, I.image_id, I.description, to_char(I.date_created, 'dd/mm/yyyy hh24:mi:ss') datetime, I.thumbnail, I.recorded_data
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
		 	$sql2 = "Select S.sensor_id, S.location, S.description, SC.id, to_char(SC.date_created, 'dd/mm/yyyy hh24:mi:ss') datetime, SC.value
			 			FROM sensors S, scalar_data SC, subscriptions U 
			 			WHERE	U.person_id =".$_COOKIE['Person']." 
			 			AND S.sensor_type = 's'
			 			AND U.sensor_id = S.sensor_id
						AND SC.sensor_id = S.sensor_id
						AND (SC.date_created BETWEEN to_date('".$startDate."', 'DD/MM/YYYY HH24:MI:SS') AND to_date('".$endDate."', 'DD/MM/YYYY HH24:MI:SS'))
						".$locationParam;
				
				
				
				$stid2 = oci_parse($conn, $sql2);
				$res2 = oci_execute($stid2, OCI_DEFAULT);
				if (!$res2) {
					$err2 = oci_error($stid2);
					//header('scientist.php?error=general&place=audio');
		 			//exit;
		 		}
		 		
		 	if($keywords == ""){
					$keywordParam = ""; 
					$TypeKeywords = "";
				}else {
					$typeKeywords = "(A.description = '".$keywords."')";					
					$keywordParam = " AND (S.description = '".$keywords."' OR ".$typeKeywords.")";  			
				}
				
			 $sql3 = "Select S.sensor_id, S.location, S.description, A.recording_id, A.description, A.length, to_char(A.date_created, 'dd/mm/yyyy hh24:mi:ss') datetime
			 			FROM sensors S, audio_recordings A, subscriptions U 
			 			WHERE	U.person_id =".$_COOKIE['Person']." 
			 			AND S.sensor_type = 'a'
			 			AND U.sensor_id = S.sensor_id
						AND A.sensor_id = S.sensor_id
						AND (A.date_created BETWEEN to_date('".$startDate."', 'DD/MM/YYYY HH24:MI:SS') AND to_date('".$endDate."', 'DD/MM/YYYY HH24:MI:SS'))
						".$locationParam.$keywordParam;
				
				
			
				$stid3 = oci_parse($conn, $sql3);
				$res3 = oci_execute($stid3, OCI_DEFAULT);
				if (!$res3) {
					$err3 = oci_error($stid3);
					//header('scientist.php?error=general&place=audio');
		 			//exit;
		 		}
		 		
		 	
		 	
		 ?>
		
		 <h3> Images </h3>
		<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Image ID</TH>
		<TH>Image Description</TH>
		<TH>Date Image Created</TH>
		<TH>Thumbnail</TH>
		<TH>Recorded Data</TH>
		
		</TR>
		
		<?php
			while ($row = oci_fetch_array($stid, OCI_NUM+OCI_RETURN_NULLS+OCI_RETURN_LOBS)){ ?>
			<tr>
			<TD> <?php echo $row[0]; ?> </TD>
			<TD> <?php echo $row[1];?> </TD>
			<TD> <?php echo $row[2];?> </TD>
			<TD> <?php echo $row[3];?> </TD>
			<TD> <?php echo $row[4];?> </TD>
			<TD> <?php echo $row[5];?> </TD>
			<TD> <?php printf('<img src="data:image/jog;base64,%s"/>', base64_encode($row[6]));?> </TD>
			<TD> <?php 
				if($row[6] != NULL){
					showDownload($row[3], 'jpg'); 
				}
			?> </TD>
			</tr>
			<?php } ?>
			
		
		</TABLE>
		
		<h3>Scalar Data</h3>
		<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Scalar Data ID</TH>
		<TH>Date Scalar Data Created</TH>
		<TH>Value</TH>
		<TH>CSV</TH>
		</TR>
		<?php
			while ($row2 = oci_fetch_array($stid2, OCI_NUM)){ 
			$scalars[$row2[3]] = ''.$row2[0].','.$row2[4].','.$row2[5].'';
			?>
			<tr>
			<TD> <?php echo $row2[0]; ?> </TD>
			<TD> <?php echo $row2[1];?> </TD>
			<TD> <?php echo $row2[2];?> </TD>
			<TD> <?php echo $row2[3];?> </TD>
			<TD> <?php echo $row2[4];?> </TD>
			<TD> <?php echo $row2[5];?> </TD>
			<TD> <?php 	echo '<form name = "download_scalar" method ="post" action="downloadscalars.php">
									<input type="hidden" name="scalars" value="'.$scalars[$row2[3]].'" />	
									<input type = "submit" value="Downaload"/>
									</form>'; ?>
			</tr>
			<?php } ?>
		
		</TABLE>
		<?php 		
		echo '<form name = "download_scalar" method ="post" action="downloadscalars.php">
									<input type="hidden" name="scalars" value="'.implode("_", $scalars).'" />	
									<input type = "submit" value="Downaload All Scalars"/>
									</form>'; ?>		
		 	
	
	</TABLE>
	
	<h3> Audio Data </h3>
		<TABLE BORDER = 2>
		<TR>
		<TH>Sensor ID</TH>
		<TH>Location</TH>
		<TH>Sensor Description</TH>
		<TH>Audio ID</TH>
		<TH>Audio Description</TH>
		<TH>Audio Length</TH>
		<TH>Audio Date Created</TH>
		<TH>Recorded Data</TH>
		</TR>
		<?php
			while ($row3 = oci_fetch_array($stid3, OCI_NUM)){ ?>
			<tr>
			<TD> <?php echo $row3[0];?> </TD>
			<TD> <?php echo $row3[1];?> </TD>
			<TD> <?php echo $row3[2];?> </TD>
			<TD> <?php echo $row3[3];?> </TD>
			<TD> <?php echo $row3[4];?> </TD>
			<TD> <?php echo $row3[5];?> </TD>
			<TD> <?php echo $row3[6];?> </TD>
			<TD> <?php 
				showDownload($row3[3], 'wav'); 
			?> </TD>
			</tr>
			<?php } ?>
		
		</TABLE>		


		 		


 	
<?php
			} 
		} 
function showDownload($id, $ext){
	echo '<form name = "download" method ="post" action="download.php">
			<input type="hidden" name="id" value="'.(int)$id.'" />	
			<input type="hidden" name="data" value="'.$data.'" />
			<input type="hidden" name="ext" value="'.$ext.'" />
			<input type = "submit" value="Download"/>
			</form>';
}		
?>		
		
</body>
</html>
