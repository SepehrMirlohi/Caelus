<?php


class PhoneValidate {
    public function Validate($incomingPhoneNumber, $regCode = REGCODE): int|string
    {
        if (strlen($incomingPhoneNumber) == 11) {
            $mob = intval($incomingPhoneNumber);
            $mob = substr($mob, 0);
            $newMobile = REGCODE . "$mob";

            $newMobile = intval($newMobile);

            echo $newMobile;
            die();
        } else {
            echo  "enter in 11 digits";
            die();
        }
    }

}
////test
//$validate = new PhoneValidate();
//$validate->Validate("09030225165");