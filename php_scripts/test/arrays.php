<?php

$array = array(3, 'string', 4.5);

foreach($array AS $value) {
    if ($value) {
        echo $value . '<br />';
    }
}
$count = count($array);
for ($i = 0; $i < count; $i++) {
    echo $i . ': ' . $array[$i] . '<br />';
    }
$array = array('Andrew', 'Alita', 'Leslie');
    print_r($array);
    
if(in_array('Andrew', $array)) {
    echo 'Value was found!';
    echo '<br />';
}else {
    echo 'Value was not found!';
    echo '<br />';
}
$unsorted = array(9, 5, 4, 8, 1, 2, 'Tom','Bob', 'bob', 'Cindarella');
sort ($unsorted);
print_r($unsorted);
echo '<br />';
rsort ($unsorted);
print_r($unsorted);
echo '<br />';
natcasesort ($unsorted);
print_r($unsorted);
echo '<br />';
shuffle($unsorted);
print_r($unsorted);
echo '<br />';
$numbers = array(1,5,2,9,8,10,14,17,3,12,6);
print_r($numbers);
echo '<br />';
sort($numbers);
print_r($numbers);
echo '<br />';
echo array_sum($numbers);
echo '<br />';
echo '<br />';
// string to uppper case
$old = array('lemons','limes','strawberries');
$new = array_map('strtoupper', $old);
print_r($new);
echo '<br />';



$array = array('name' => 'Marcus', 'website' => 'http://ureddit.com');
foreach($array AS $key => $value){
    echo $key . ' is equal to ' . $value . '<br />';
}
    echo $key 
?>            
    