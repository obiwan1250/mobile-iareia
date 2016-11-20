<?php 
error_reporting(E_ALL); 

function mysql_error_report($q,$e,$f) 
{ 
if (isset($_SESSION['id_user'])) {$id = $_SESSION['id_user'];} else {$id = 0;} 
if ($_SESSION) {$session = mysql_real_escape_string(session_id());} else {$session = 0;} 
$ip = mysql_real_escape_string(getenv('REMOTE_ADDR')); 

$query = mysql_real_escape_string($q); 
$error = mysql_real_escape_string($e); 
$function = mysql_real_escape_string($f); 

$query1 = "INSERT INTO log_mysql_errors (id_session, id_user, date, ip, function, mysql_error, mysql_query) VALUES ('$session', '$id', NOW(), inet_aton('$ip'), '$function', '$error', '$query')"; 
$result1 = mysql_query($query1); 
} 

$query1 = "SELECT * FROM example;"; 
$result1 = mysql_query($query1); 

if ($result1) 
{ 
echo 'and there was much rejoicing'; 
} 
else {mysql_error_report($query1,mysql_error(),__FUNCTION__);} 
?>
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

