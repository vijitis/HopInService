<?php

#connect to a backend database
$mysqli = new mysqli("localhost", "hopin_admin", "temporarypassword", "hopin");

if($mysqli->connect_errno){
  echo "Could not connect to the database\n";
  echo "Error: ". $mysqli->connect_errno . "\n";
  exit;
}

/* Grab data from database */
//SELECT fullname, email, longitude, latitude, MAX( current_timee )
//FROM users GROUP BY email DESC
//$query = "SELECT id, email, username, signed_up, last_login, active_status, driver_status FROM auth WHERE driver_status = 1";
$query = "SELECT auth.id, auth.fullname, users.email, users.latitude, users.longitude, MAX( users.current_timee ) AS curr_time , users.vehicle_name, users.specs, users.seats_num, auth.active_status, auth.driver_status
FROM users INNER JOIN auth ON auth.email = users.email WHERE auth.driver_status =1 GROUP BY email";

$result = $mysqli->query($query);

$i = 0;

/* Master array for the records  */
$users = array();

while($row = $result->fetch_assoc()) {
	$id = $row['id'];
  $fullname = $row['fullname'];
  $emailid = $row['email'];
	$latitude = $row['latitude'];
  $longitude = $row['longitude'];
  $curr_time = $row['curr_time'];
  $vehicle = $row['vehicle_name'];
  $specs = $row['specs'];
  $seats_num = $row['seats_num'];
  $driver_status = $row['driver_status'];
  $active_status = $row['active_status'];

  $i++;

  $users[] = array('user' => $row);

//  print "<br />";  print("$fid, ");  print("$femailid, ");  print("$fusername, ");  print("$femail, ");  print("$factive_status");  print "<br />";
}

header('Content-type: application/json');
echo json_encode(array('users'=>$users));

$mysqli->close();

?>