

<?php
/*
 * This little form will be the start of something big.  I am going to force 
 * myself to continue to program, as this skill set will enble be to create
 * some killer apps for IaREIA, and possibly other REIA's 
 * 
 */



if(isset($_POST['submit'])) { 
    $name = $_POST['name'];
    $email = $_POST['submit'];
    echo "User Has submitted the form and entered this name : <b> $name </b>";
    echo "<br>You can use the following form again to enter a new name."; 
}
?>
    
<?php  
echo 'PHP_SELF =', ($_SERVER['PHP_SELF']);
echo "<br>\n";
echo "Now, let's get on to creating the form!"
?>  

<form name="test" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
    
   <input type="text" name="name"><br>
   <input type="email" name ="email"><br>
   <input type="submit" name="submit" value="Submit Form"><br>
</form>



