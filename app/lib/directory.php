<?php


function give_directory_count(): string{
    $controller = substr($_SERVER['QUERY_STRING'], 4);
    $count = sizeof(explode("/", $controller));
    return $directory = str_repeat("../", $count - 1);
}


