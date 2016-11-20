<html>
<body>
    
<?php

$db_host = "97.64.131.214";
$db_user = "root";
$db_pass = "3aREIA2015#";
$db_name = "jelly_bean_contest";
$table = "daily_input_file";

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name,'3306');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!mysqli_select_db($conn, $db_name)) {
    die("Can't select database");
}


if (@$_POST['submitted']=='sub') {
    if(empty($_POST['Date'])){
        $error[]='Please enter Date';
    }
    if(empty($_POST['FName'])){
        $error[]='Please enter First name';
    }

    if(empty($error)){
        foreach(array_keys($_POST) as $key)
        {
            $escaped_POST[$key] = mysqli_real_escape_string($conn,$_POST[$key]);
        }
    $Date = $escaped_POST['Date'];
    $FName = $escaped_POST['FName'];
    $DialedMailed = $escaped_POST['DialedMailed'];  // 1 pt
    $VMCount = $escaped_POST['VMCount']; // 1 pt
    $TargetsReached = $escaped_POST['TargetsReached']; // 3 Pts
    $ColdCalls = $escaped_POST['ColdCalls']; // 3 Pts
    $Optins = $escaped_POST['Optins']; // 5 pts
    $Dollars = $escaped_POST['Dollars']; // 1 pt for each dollar collected
    //$TotalPts = $escaped_POST['TotalPts'];
    $GoodDayBadDay = $escaped_POST['GoodDay_BadDay'];
        $sql = "INSERT INTO $table (Date, FName,DialedMailed,VMCount,TargetsReached,ColdCalls,Optins,Dollars,GoodDay_BadDay)
VALUES ('$Date', '$FName', '$DialedMailed','$VMCount','$TargetsReached','$ColdCalls','$Optins','$Dollars','$GoodDayBadDay')";


        if (!mysqli_query($conn,$sql ))  {
            echo mysqli_error($conn);
            die('Error inserting new record');
        }else{ 
            $error[]='Record added successfully';
        }
    }
}   
?> 

<form method="post" action="">
<input type="hidden" name="submitted" value="sub" />
<ul>
<?php
if(!empty($error)){

foreach($error as $mesg){
    echo "<li>$mesg</li>";
}

}
?>
    </ul>
<fieldset>
<legend>Let's Count Jelly Beans for Excellence in Service!</legend>
<label>Date (YYYY-MM-DD): 
    <input type="text" name="Date">
    <br><br>
</label>
<label>First Name: 
    <input type="text" name="FName">
    <br><br>
</label>
<label>Dialed Calls or Emails Sent: 
    <input type="text" name="DialedMailed">
    <br><br>
</label>
<label>V/M Messages Left: 
    <input type="text" name="VMCount">
    <br><br>
</label>
<label>Targets Reached:
    <input type="text" name="TargetsReached">
    <br><br>
</label>
<label>Cold Calls:
    <input type="text" name="ColdCalls">
    <br><br>
</label>
<label>Opt-ins Accounts Added:
    <input type="text" name="Optins">
    <br><br>
</label>
<label>Dollars:
    <input type="text" name="Dollars">
    <br><br>
</label>
<label>Three Times $ Cost Generated?: 
    <input type="radio" name="GoodDay_BadDay" value="Y" checked>Yes
    <input type="radio" name="GoodDay_BadDay" value="N">No
</label>
<br />
<br />
<input type ="submit" value="Click to Submit New Daily Record" />
</fieldset>
<br />
</form>

<?php

?>

</body>
</html>