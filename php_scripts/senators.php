<html>
<head>
</head>
<body>
<?php

$db_host = "97.64.131.214";
$db_username = "root";
$db_pass = "3aREIA2015#";
$db_name = "grass roots db";
$table = "Senators";

$con = mysql_connect("$db_host","$db_username","$db_pass") or die ("Could not connect to MySQL");
if (!mysql_select_db($db_name)) 
    die ("Can't select database");

// sending query
$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Iowa {$table}</h1>";
echo "<h2>General Assembly: 85 (01/14/2013 - 01/11/2015)</h2>";
echo "To search, press Ctrl-F then enter your search string <br>";
echo "<table border='2'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
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

?>
</body>
</html>