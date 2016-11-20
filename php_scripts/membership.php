<html>
<head>
</head>
<body>
<?php

$db_host = "97.64.131.214";
$db_username = "root";
$db_pass = "3aREIA2015#";
$db_name = "grass roots db";
$table = "Membership";

$con = mysql_connect("$db_host","$db_username","$db_pass") or die ("Could not connect to MySQL");
if (!mysql_select_db($db_name)) {
    die("Can't select database");
}
// sending query
$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Listing ($table)</h1>";
echo "<h2>Listing of all Members </h2>";
echo "To search, press Ctrl-F then enter your search string";
echo "<table border='1'><tr>";

// printing table headers
for($i=0; $i<$fields_num; $i++) {
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";


echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result)) {
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        if (!filter_var($cell, FILTER_VALIDATE_EMAIL) === false) {
            echo "<td><a href='mailto:$cell'>$cell</a></td>";
        } else {
            echo "<td>$cell</td>";
        }
    echo "</tr>\n";
}
mysql_free_result($result);
/*
//printing table rows


$result = mysql_query("SELECT lname_contact1, fname_contact1, organization_name, office_phone, SD, HD FROM ($table) ORDER BY lname_contact1");
if (!$result) {
	die("Query to show fields from table failed");
}
    
    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
{
	echo "<table border='1'>	<tr>
	<th>Organization Name</th>
	<th>Last Name</th>
	<th>First Name</th>
	<th>Primary Phone</th>
	<th>SD</th>
	<th>HD</th>
	
	</tr>";
}
while($row = mysql_fetch_array($result))
{
	echo "<tr>";
	echo "<td>" , $row['organization_name'] , "</td>";
	echo "<td>" , $row['lname_contact1'] , "</td>";
	echo "<td>" , $row['fname_contact1'] , "</td>";
	echo "<td>" , $row['office_phone'] , "</td>";
	echo "<td>" , $row['SD'] , "</td>";
	echo "<td>" , $row['HD'] , "</td>";
	echo "</tr>\n";
}
mysql_free_result($result);
*/
?>
</body>
</html>