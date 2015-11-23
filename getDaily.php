<html>
	<?php
		include	("PHPconnectionDB.php");	
		if(isset($_COOKIE['Status']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 's' ){	 }
		else{
			header("Location:index.php");		 
		} 
	?>

	<div>
		<?php
			if(isset($_POST['getDaily'])){
				$conn= connect();		   
				$sql = '	SELECT 	f.sensor_id, s.location, t.year, t.quarter, t.month, t.week, t.time_id , AVG(f.svalue), MIN(f.svalue), MAX(f.svalue)
							FROM 		fact f, sensors s, time t, persons p, subscriptions u
							WHERE 	s.sensor_id = f.sensor_id
							AND 		f.time_id = t.time_id
							AND		p.person_id = u.person_id
							AND		u.sensor_id = s.sensor_id
							AND		p.person_id = '.$_COOKIE['Person'].'
							AND		s.sensor_id = '.$_POST['sensor'].'
							AND		t.year = '.$_POST['year'].'
							AND		t.quarter = '.$_POST['quarter'].'
							AND		t.month = '.$_POST['month'].'
							AND		t.week = '.$_POST['week'].'
							GROUP BY f.sensor_id, s.location, t.year, t.quarter, t.month, t.week, t.time_id
							ORDER BY f.sensor_id';
			   //Prepare sql using conn and returns the statement identifier
			   $stid = oci_parse($conn, $sql);
			   //Execute a statement returned from oci_parse()
			   $res=oci_execute($stid, OCI_DEFAULT); 
			   //if error, retrieve the error using the oci_error() function & output an error
			   if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
			   }
			   ?>
				<TABLE BORDER=2>
				<TR>
				<TH>Sensor ID</TH>
				<TH>Sensor Location</TH>
				<TH>Year</TH>
				<TH>Quarter</TH>
				<TH>Month</TH>
				<TH>Week</TH>
				<TH>Date</TH>
				<TH>Average Value</TH>
				<TH>Minimum Value</TH>
				<TH>Maximum Value</TH>
				</TR>
				<?php 
					//Display extracted rows
					while ($row = oci_fetch_array($stid, OCI_ASSOC)) { 
						?>
						<tr>
		 				<TD> <?php echo $row['SENSOR_ID'];  ?> </TD>
						<TD> <?php echo $row['LOCATION']; ?> </TD>
						<TD> <?php echo '<form name = "getQuarterly" method ="post" action="getQuarterly.php">
							<input type="hidden" name="sensor" value="' . $row['SENSOR_ID'] . '" />
							<input type="hidden" name="year" value="' . $row['YEAR'] . '" />
							<input type = "submit" name="getQuarterly" value="' . $row['YEAR'] . '"/>
							</form>'; ?> </TD>
						<TD> <?php echo '<form name = "getMonthly" method ="post" action="getMonthly.php">
							<input type="hidden" name="sensor" value="' . $row['SENSOR_ID'] . '" />
							<input type="hidden" name="year" value="' . $row['YEAR'] . '" />
							<input type="hidden" name="quarter" value="' . $row['QUARTER'] . '" />
							<input type = "submit" name="getMonthly" value="' . $row['QUARTER'] . '" />
							</form>'; ?> </TD>
						<TD> <?php echo '<form name = "getWeekly" method ="post" action="getWeekly.php">
							<input type="hidden" name="sensor" value="' . $row['SENSOR_ID'] . '" />
							<input type="hidden" name="year" value="' . $row['YEAR'] . '" />
							<input type="hidden" name="quarter" value="' . $row['QUARTER'] . '" />
							<input type="hidden" name="month" value="' . $row['MONTH'] . '" />
							<input type = "submit" name="getWeekly" value="' . $row['MONTH'] . '" />
							</form>'; ?> </TD>
						<TD> <?php echo '<form name = "getDaily" method ="post" action="getDaily.php">
							<input type="hidden" name="sensor" value="' . $row['SENSOR_ID'] . '" />
							<input type="hidden" name="year" value="' . $row['YEAR'] . '" />
							<input type="hidden" name="quarter" value="' . $row['QUARTER'] . '" />
							<input type="hidden" name="month" value="' . $row['MONTH'] . '" />
							<input type="hidden" name="week" value="' . $row['WEEK'] . '" />
							<input type = "submit" name="getDaily" value="' . $row['WEEK'] . '" />
							</form>'; ?> </TD>
						<TD> <?php echo $row['TIME_ID']?> </TD>
						<TD> <?php echo $row['AVG(F.SVALUE)']; ?> </TD>
						<TD> <?php echo $row['MIN(F.SVALUE)']; ?> </TD>
						<TD> <?php echo $row['MAX(F.SVALUE)']; ?> </TD> 
						</tr>
						<?php 
	    				} ?>
				</TABLE>

				<a href="index.php">
   				<input type="button" value="Return to Userpage" />
				</a>
			<?php } ?>
	</div>
</html>