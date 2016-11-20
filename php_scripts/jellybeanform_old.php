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
if(!empty($_POST['Date']) and !empty($_POST['FName'])){
  $fname = $_POST['FName'];
  $date = $_POST['Date'];
}

// echo 'Current time: ' . date('Y-m-d') . "\n";
// Current date/time in your computer's time zone.


$date = new DateTime();
echo $date->format('Y-m-d H:i:sP') . "\n";
/* $format = 'Y-m-d';
$systemdate = DateTimeZone::createFromFormat($format, '2015-01-01');
echo "Format: $format; " . $systemdate->format('Y-m-d') . "\n";
*/

if (isset($_POST['submitted'])) {
    $Date = $_POST['@Date'];
    $FName = $_POST['@FName'];
    $DialedMailed = $_POST['@DialedMailed'];
    $VMCount = $_POST['@VMCount'];
    $NonTargetsReached = $_POST['@NonTargetsReached'];
    $TargetsReached = $_POST['@TargetsReached'];
    $ColdCalls = $_POST['@ColdCalls'];
    $Optins = $_POST['@Optins'];
    $Dollars = $_POST['@Dollars'];
    $TotalPts = $_POST['@TotalPts']; 
    $GoodDayBadDay = $_POST['@GoodDayBadDay']; 
}   

$sqlinsert = "INSERT INTO $TABLE (@Date, @FName, @DialedMailed, 
            @VMCount, @NonTargetsReached, @TargetsReached, @ColdCalls, @Opt-ins, 
            @Dollars, @GoodDayBadDay)";
 
// This html error is killing me!
?> 

<form method="post" action="jellybeanform.php">
<input type="hidden" name="submitted" value="true" />


<fieldset>
<legend>New Data Entry Screen - Count Jelly Beans to Market Dominance!</legend>
<label>Date (YYYY-MM-DD): 
    <input type="text" name="Date">
    <span class="error">* <?php echo $nameErr; ?></span>
    <br><br>
</label>
<label>First Name: 
    <input type="text" name="FName">
    <span class="error">* <?php echo $FNameErr; ?></span>
    <br><br>
</label>
<label>Dialed Calls or Emails Sent: 
    <input type="text" name="DialedMailed">
    <span class="error"><?php echo $DialedMailedErr; ?></span>
    <br><br>
</label>
<label>V/M Messages Left: 
    <input type="text" name="VMCount">
    <span class="error"><?php echo $VMCountErr; ?></span>
    <br><br>
</label>
<label>Targets Reached:
    <input type="text" name="TargetsReached">
    <span class="error"><?php echo $TargetsReachedErr; ?></span>
    <br><br>
</label>
<label>Cold Calls:
    <input type="text" name="ColdCalls">
    <span class="error"><?php echo $ColdCallsErr; ?></span>
    <br><br>
</label>
<label>Opt-ins Accounts Added:
    <input type="text" name="Optins">
    <span class="error"><?php echo $OptinsErr; ?></span>
    <br><br>
</label>
<label>Dollars:
    <input type="text" name="Dollars">
    <span class="error"><?php echo $DollarsErr; ?></span>
    <br><br>
</label>
<label>Three Times $ Cost Generated?: 
    <input type="radio" name="GoodDay-BadDay" value="Y">Yes   
    <input type="radio" name="GoodDay-BadDay" value="N">No
    <span class="error"><?php echo $GoodDay-BadDayErr; ?></span>
</label>
<br />
<br />
<input type ="submit" value="Click to Submit New Daily Record" />
</fieldset>
<br />
</form>

<?php
if (!mysqli_query($conn,$sqlinsert ))  {        
        die('Error inserting new record');
} 
?>

</body>
</html>