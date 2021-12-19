<?php
$options = file_get_contents('people.json');
$options = json_decode($options, true);
print_r($options);
$en_name = array_rand($options);
echo $en_name;