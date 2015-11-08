<?php
// mysqli
$username = NULL;
$password = NULL;
$mysqli = new mysqli("localhost", $username, $password,"yhack2015");
$result = $mysqli->query("SELECT SongTitle FROM Songs ORDER BY SongTitle ASC");

echo '<h1>Georgian Songs in Database</h1><br>';
while($row = $result->fetch_assoc()){
	echo htmlentities($row['SongTitle']);
	echo '<br>';
}

?>