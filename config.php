<?php
$connection=mysqli_connect('localhost','root','','app') or die("connection failed : ".mysqli_connect_error());
if ($connection) {
	//echo "Connection Successfully";
}
else{
	echo "Failed to connect to database";
}
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>