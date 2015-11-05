<html>
<head>
	<title>Admin Dashboard </title>
	<script type="text/javascript" src="libraries/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="libraries/jquery-ui/jquery-ui.js"></script>
	<link rel="stylesheet" href="libraries/jquery-ui/jquery-ui.min.css"></script>
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
    <li><a href="#tabs-1">Sensor Management</a></li>
    <li><a href="#tabs-2">User Management</a></li>
    <li><a href="#tabs-3">Account Settings</a></li>
 
  </ul>
  <div id="tabs-1">
   	Sensor management systems goes here
  </div>
  <div id="tabs-2">
   	User Management system goes here!
  </div>
  <div id="tabs-3">
   	Account Settings go here!
  </div>
</div>
</div> <!-- end of container-->
</body>
</html>