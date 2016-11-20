<html>
<head>
</head>    
<body>
<?php

$db_host = "97.64.131.214";
$db_user = "root";
$db_pass = "3aREIA2015#";
$db_name = "jelly_bean_contest";
$table = "daily_input_file";

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";  

$sql = "SELECT * FROM {$table} ORDER BY Date ASC, TotalPts DESC";
// $result = $conn->query($sql);
$result = mysqli_query($conn,$sql);

$field_cnt = $result->field_count;
    // printf("Result set has %d fields.\n", $field_cnt);

echo "<h1>Jelly Bean Points Data</h1>";
echo "<h2>Recorded Points List</h2>";
// printf("Result set has %d fields.\n", $field_cnt);
echo "To search, press Ctrl-F then enter your search string";
echo "<table border='1'><tr>";

// printing table headers
for($i=0; $i<$field_cnt; $i++) {
    $field = mysqli_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";

// printing table rows
while($row = mysqli_fetch_row($result)) {
    echo "<tr>";

// $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell) {
       echo "<td>$cell</td>";
}
    echo "</tr>\n";
}
mysql_free_result($result);

?>
</body>
</html>
