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
    $DialedPts = $DialedMailed * 1;
    $VMCount = $escaped_POST['VMCount']; // 1 pt
    $VMCountPts = $VMCount * 1;
    $TargetsReached = $escaped_POST['TargetsReached']; // 3 Pts
    $TargetsReachedPts = $TargetsReached * 3; 
    $ColdCalls = $escaped_POST['ColdCalls']; // 3 Pts
    $ColdCallsPts = $ColdCalls * 3;
    $Optins = $escaped_POST['Optins']; // 5 pts
    $OptinsPts = $Optins * 5;
    $Dollars = $escaped_POST['Dollars']; // 1 pt for each dollar collected
    $DollarsPts = $Dollars * 1;
    $TotalPts = ($DialedPts + $VMCountPts + $TargetsReachedPts + $ColdCallsPts + $OptinsPts + $DollarsPts);
    $TotalPts = $escaped_POST['$TotalPts']; 
    
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
    
<script>
    function validate(){
    var Date = document.getElementByID ('Date');
    var FName = document.getElementByID('FName');
    
    if (Date.value === ""){
        alert ("Please enter the Date");
        return false;
    }
    if (FName.value === ""){
        alert ("Please enter your First Name");
        return false;
    }
}    

var DialedMailed = document.getElementById("DialedMailed");
var VMCount = document.getElementById("VMCount");
var TargetsReached = document.getElementById("TargetsReached");
var ColdCalls = document.getElementById("ColdCalls");
var Optins = document.getElementById("Optins");

var DialedMailedstored = DialedMailed.getAttribute("data-in");
var VMCountstored = VMCount.getAttribute("data-in");
var TargetsReachedstored = TargetsReached.getAttribute("data-in");
var ColdCallsstored = ColdCalls.getAttribute("data-in");
var Optinsstored = Optins.getAttribute("data-in");
setInterval(function(){
 if( DialedMailed == document.activeElement ){
  var temp = DialedMailed.value;
  if( DialedMailedstored != temp ){
   DialedMailedstored = temp;
   DialedMailed.setAttribute("data-in",temp);
   calculate();
  }
 }
 if( VMCount == document.activeElement ){
  var temp = VMCount.value;
  if( VMCountstored != temp ){
   VMCountstored = temp;
   VMCount.setAttribute("data-in",temp);
   calculate();
  }
 }
 if( TargetsReached == document.activeElement ){
  var temp = TargetsReached.value;
  if( TargetsReachedstored != temp ){
   TargetsReachedstored = temp;
   TargetsReachedstored.setAttribute("data-in",temp);
   calculate();
  }
 }
 if( ColdCalls == document.activeElement ){
  var temp = ColdCalls.value;
  if( ColdCallsstored != temp ){
   ColdCallsstored = temp;
   ColdCallsstored.setAttribute("data-in",temp);
   calculate();
  }
 }
 if( Optins == document.activeElement ){
  var temp = Optins.value;
  if( Coptinsstored != temp ){
   Optinsstored = temp;
   Optinsstored.setAttribute("data-in",temp);
   calculate();
  }
 }
 },50);

function calculate(){
 TotalPts.innerHTML = DialedMailed.value + VMCount.value + (TargetsReached.value * 2) + (ColdCalls.value * 3) + (Optins.value * 5);
}
DialedMailed.onblur = calculate;
VMCount.onblur = calculate;
TargetsReached.onblur = calculate;
ColdCalls.onblur = calculate;
Optins.onblur = calculate;
calculate();

</script>

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
<legend>New Data Entry Screen - We cannot control the results.  We can only control our activities.  </legend>
<label>Date (YYYY-MM-DD): 
    <input type="text" name="Date">
    <br><br>
</label>
<label>First Name: 
    <input type="text" name="FName">
    <br><br>
</label>
Dialed Calls or Emails Sent: 
    <input type="text" name="DialedMailed">
    <input type="number" name="DialedMailedPts" value="<?php echo htmlspecialchars($DialedMailedPts); ?>" />
    <br><br>

<label>V/M Messages Left: 
    <input type="text" name="VMCount">
    <input type="number" name="VMCountPts" value="<?php echo htmlspecialchars($VMCountPts); ?>" />
    <br><br>
</label>
<label>Targets Reached:
    <input type="text" name="TargetsReached">
    <input type="number" name="TargetsReachedPts" value="<?php echo htmlspecialchars($TargetsReachedPts); ?>" />
    <br><br>
</label>
<label>Cold Calls:
    <input type="text" name="ColdCalls">
    <input type="number" name="ColdCallsPts" value="<?php echo htmlspecialchars($ColdCallsPts); ?>" />
    <br><br>
</label>
<label>Opt-ins Accounts Added:
    <input type="text" name="Optins">
    <input type="number" name="OptinsPts" value="<?php echo htmlspecialchars($OptinsPts); ?>" />
    <br><br>
</label>
<label>Dollars:
    <input type="text" name="Dollars">
    <input type="number" name="DollarsPts" value="<?php echo htmlspecialchars($DollarsPts); ?>" />
    <br><br>
</label>
<label>Total Points Today:
    <input type="number" name="TotalPts" value="$TotalPts">
    <br><br>
</label>
<br />
<br />
<Input type ="button" value="Click to Calculate Total Pts" />
<br />
<input type ="submit" value="Click to Submit New Record" />
</fieldset>
<br />
</form>

<?php

?>

</body>
</html>
