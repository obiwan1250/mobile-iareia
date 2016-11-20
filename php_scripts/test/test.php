<html>
<body>
            
<?php

if(!empty($_POST['name']) and !empty($_POST['useremail'])){
  $name = $_POST['name'];
  $useremail = $_POST['useremail'];
}
  if (!filter_var($useremail, FILTER_VALIDATE_EMAIL) === false) {
        echo ("$useremail is a valid email address.");
} else {
    echo("$useremail is not a valid email address");
}  
if (empty($_POST['name']) && empty($_POST['useremail'])) {  
    include 'loginform.php'; 
    echo "Please enter valid username and email address";
} 
/* if($_POST['username'] and $_POST['password']){
  $userName = $_POST['UserNameInput'];
  $passWord = $_POST['UserPassWord'];
 */

/* if(isset($_POST['SubmitLogin'])) {
    $userName = $_POST('UserNameInput');
    $passWord = $_POST('UserPassWord');
    Echo "Welcome back, " . $username;
}
else {
    include 'loginform.php';
    echo "Please enter your username and password";
} */
?>

</body>
</html>