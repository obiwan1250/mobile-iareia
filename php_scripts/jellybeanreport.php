<html>
<head>
</head>    
<body>
<?php
date_default_timezone_set("Asia/Bangkok");
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
// echo "Connected successfully";  

$sql = "SELECT * FROM {$table}  GROUP BY Date,  TotalPts DESC  ";
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

$firstrow = mysqli_fetch_assoc($result);
$date=$firstrow['Date'];



// reset the result resource
mysqli_data_seek($result, 0);
// printing table rows


while($row = mysqli_fetch_assoc($result)) {


if($date != $row['Date'] ){


    $date=$row['Date'];


        echo "<tr bgcolor='#D3D3D3'>";
    echo "<td colspan='4'></td>";
        echo "<td colspan='2'><strong>Today Best</strong></td>";
        echo "<td colspan='2'>" .$top_name. "</td>";

        echo "<td colspan='2'><strong>Total Points</strong></td>";
        echo "<td colspan='2'>".$top_points."</td>";

        echo "</tr>\n";
    //reset total points
    $top_points=0;



    echo "<tr>";
    echo "<td>" . $row['Record'] . "</td>";
    echo "<td>" . $row['Date'] . "</td>";
    echo "<td>" . $row['FName'] . "</td>";
    echo "<td>" . $row['Dialed-Emailed'] . "</td>";
    echo "<td>" . $row['VM_Count'] . "</td>";
    echo "<td>" . $row['Non-Targets Reached'] . "</td>";
    echo "<td>" . $row['Targets Reached'] . "</td>";
    echo "<td>" . $row['Cold Calls'] . "</td>";
    echo "<td>" . $row['Opt-ins'] . "</td>";
    echo "<td>" . $row['Dollars'] . "</td>";
    echo "<td>" . $row['TotalPts'] . "</td>";
    echo "<td>" . $row['GoodDay-BadDay'] . "</td>";

    echo "</tr>\n";
    if($top_points < $row['TotalPts']){
        $top_name= $row['FName'];
        $top_points=$row['TotalPts'];
    }



}else{



    echo "<tr>";
    echo "<td>" . $row['Record'] . "</td>";
    echo "<td>" . $row['Date'] . "</td>";
    echo "<td>" . $row['FName'] . "</td>";
    echo "<td>" . $row['Dialed-Emailed'] . "</td>";
    echo "<td>" . $row['VM_Count'] . "</td>";
    echo "<td>" . $row['Non-Targets Reached'] . "</td>";
    echo "<td>" . $row['Targets Reached'] . "</td>";
    echo "<td>" . $row['Cold Calls'] . "</td>";
    echo "<td>" . $row['Opt-ins'] . "</td>";
    echo "<td>" . $row['Dollars'] . "</td>";
    echo "<td>" . $row['TotalPts'] . "</td>";
    echo "<td>" . $row['GoodDay-BadDay'] . "</td>";

    echo "</tr>\n";


    if(@$top_points < $row['TotalPts']){
        $top_name= $row['FName'];
    $top_points=$row['TotalPts'];
    }



}

}

?>
</body>
</html>
