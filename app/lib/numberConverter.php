<?php

function convertNum($num): array|string
{
    $latinNums = array('0','1','2','3','4','5','6','7','8','9');
    $persianNums = array('۰','١','٢','٣','۴','۵','۶','٧','٨','٩');
    return str_replace($latinNums, $persianNums, $num);
}
function convertEnNum($num): array|string
{
    $latinNums = array('0','1','2','3','4','5','6','7','8','9');
    $persianNums = array('۰','١','٢','٣','۴','۵','۶','٧','٨','٩');
    return str_replace($persianNums, $latinNums, $num);
}