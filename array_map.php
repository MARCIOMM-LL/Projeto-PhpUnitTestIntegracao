<?php

function fun1($v)
{
    return ($v + 10);	 // add 7
}

function fun2($v1, $v2)
{
    if ($v1 == $v2) return 1;
    else return 0;
}

$arr1 = array(1, 2, 3, 4, 5);
$arr2 = array(1, 3, 3, 4, 8);

echo "<pre>";
print_r(array_map("fun1", $arr1));

print_r(array_map("fun2", $arr1, $arr2));
echo "<pre />";


//Array_reduce
// PHP function to illustrate the use of array_reduce()
function own_function($element1, $element2)
{
    return $element1 . " and " . $element2;
}

$array = array(15, 120, 45, 78);
print_r(array_reduce($array, "own_function", "Initial"));