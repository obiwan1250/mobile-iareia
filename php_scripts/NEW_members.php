<html>
<head>
</head>
<body>
<?php

$db_host = "97.64.131.214";
$db_username = "root";
$db_pass = "1aREIA2014#";
$db_name = "grass roots db";
$table = "membership";

$link = mysqli_connect("$db_host","$db_username","$db_pass","$db_name");
if (!$link) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
echo "Connection Link =" . mysqli_get_host_info($link) . "\n";
echo "<br />";

// sending query
echo "db_host = {$db_host}";
echo "<br />";
echo "user = {$db_username}";
echo "<br />";
echo "db_name = {$db_name}";
echo "<br />";
echo "table = {$table}";
echo "<br />";

// $sql = ("SELECT RecordID, Member_Code, Last_Name_Contact1, First_Name_Contact1,Middle_Initial_Contact1,Email_Address1,HD#,SD# FROM `membership` ");
$sql = ("SELECT * FROM `membership` ");

/* @var $result type */
$result = mysqli_query($link, $sql);
echo "SQL State = {$sql}";
echo "<br />";
// echo "Result = $result";
// echo "<br />";

//if (!$result) {
  //  die('Invalid query: ' . mysqli_error());
// }    

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "Code: " . $row["Member_Code"]. " Org:  " . $row["Organization_Name"]. "  " . "LName " . $row["Last_Name_Contact1"];
        echo '<br />';
        }   
} else {
    echo "0 results";
}
$fields_num = mysql_num_fields($result);

echo "<h1>{$table}</h1>";
echo "<h2>Membership  File";
echo "To search, press Ctrl-F then enter your search string";
echo "<table border='1'><tr>";
// printing table headers
for ($i=0; $i<fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
//for($i=0; $i<$fields_num; $i++)
//{
  //  $field = mysql_fetch_field($result);
    //echo "<td>{$field->name}</td>";
//}
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
if ($result->num_rows > 0) {
    echo "<table><tr><th>RecordID</th><th>Member Code</th><th>Last Name</th><th>First Name</th><th>MI</th><th>Email Addresss</th><th>HD#</th><th>SD#</th></tr>";
}    
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["RecordID"]."</td><td>".$row["Member_Code"]." ".$row["Last_Name_Contact1"].$row["First_Name_Contact1"].$row["Middle_Initial_Contact1"].$row["Email_Address1"].$row["HD#"].$row["SD#"]."</td></tr>";
    echo "</table>";
} 
   

foreach ($row as $cell) {
        if (!filter_var($cell, FILTER_VALIDATE_EMAIL) === false) {
            echo "<td><a href='mailto:$cell'>$cell</a></td>";
        } else {
            echo "<td>$cell</td>";
        }
    }
    echo "</tr>\n";
}
mysql_free_result($result);


?>
</body>
</html>
