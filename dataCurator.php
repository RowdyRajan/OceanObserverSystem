<html>
<?php
include	("PHPconnectionDB.php");
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 'd' ){	 }
else{
header("Location:index.php");		 
} 
?>
<head>
	<title>Data Curator Dashboard</title>
	<script type="text/javascript" src="libraries/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="libraries/jquery-ui/jquery-ui.js"></script>
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css"></script>
	
	<style type="text/css">
		#logout{
			float:right;		
		}	
	</style>	
	
	<script>
		$(function() {
    		$("#tabs").tabs();
  		});
	</script>
	
</head>

<body>
<div id="container">
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Upload</a></li>
    <li><a href="#tabs-2">Account Settings</a></li>
    <button id="logout">Log out</button>
  </ul>
  <div id="tabs-1">
   	Upload goes here
  </div>
  <div id="tabs-2">
   	Account Settings go here!
  </div>
</div>
</div> <!-- end of container-->
</body>
<script type="text/javascript">
	//Logout button on click
	$("#logout").click(function(){
			window.location.href = "login.php?status=logout";
  		});
</script>
</html>