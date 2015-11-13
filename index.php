<?php
	if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" 
	&& isset($_COOKIE["Role"]) && isset($_COOKIE["Person"]) ){
		$role = $_COOKIE["Role"];		
		if($role == 'a'){
			header("Location:admin.php");
		 } elseif($role == 's'){
			header("Location:scientist.php");	
		 } elseif($role == 'd'){
			header("Location:dataCurator.php");		 			
		 }	
	}
?>
<!--login.html: Initial page for user log in. Take values username and password
and perform query check using login.php Response back will be dependant on PHP result and button selected. -->
<html>
	<body>
		<h1>Ocean Observation System</h1>
		<form name = "login" method ="post" action="login.php">
		Username: <input type="text" name="username"/><br/>
		Password: <input type="password" name="password"/><br/>
		<input type = "submit" name="confirm" value="Log In"/>
		</form>
	</body>
</html>
