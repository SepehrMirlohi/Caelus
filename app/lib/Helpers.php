<?php

class Helpers
{
    public static function set_parameters($text, $parameters): string|null
    {
        // This method can set parameters in a string variable and replace ? with items in $parameters
        $result = "";
        $counter = 0;
        while(!empty(strpos($text, "?")))
        {
            $pos =  strpos($text, "?");
            if (!empty($pos) and !isset($parameters[$counter])) return null;
            $result .= substr($text, 0,$pos). $parameters[$counter];

            $text =  substr($text, $pos+1);
            if ($counter == sizeof($parameters) - 1){
                $result .=$text;
            }
            $counter++;
        }
        return $result;

    }
}