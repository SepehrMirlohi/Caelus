<?php

class EmailValidate
{
    public static function ValidateEmail($email)
    {

        $list = explode("@", $email);
        $username = $list[0];
        $domain = $list[1];

        $username = str_replace(".", "", $username);
        if (ctype_alnum($username)) {
            if (strpos($domain, ".")) {
                if (substr_count($domain, '.') == 1) {
                    $tld = explode('.', $domain);
                    if ($tld[1] == "com" || $tld[1] == "ir") {
                        return $email = "$username" . "@" . "$domain";
                    } else {
                        return [
                          "status" => false,
                          "message" => "com and ir TLDs are allowed",
                          "error-code"  => 3,
                        ];
                    }
                } else {
                    return [
                        "status" => false,
                        "message" => "one TLD allowed in domain",
                        "error-code"  => 4,
                    ];
                }
            } else {
                return [
                    "status" => false,
                    "message" => "invalid domain",
                    "error-code"  => 5,
                ];
            }
        } else {
            return [
                "status" => false,
                "message" => "invalid username",
                "error-code"  => 6,
            ];
        }
        if (substr_count($email, '@') == 1) {
            $email = explode('@', $email);
            $username = $email[0];
            $domain = $email[1];
            $username = str_replace(".", "", $username);
            if (ctype_alnum($username)) {
                if (strpos($domain, ".")) {
                    if (substr_count($domain, '.') == 1) {
                        $tld = explode('.', $domain);
                        if ($tld[1] == "com" || $tld[1] == "ir" || $tld[1] == "net" || $tld[1] == "edu" || $tld[1] == "org") {
                            return $email = "$username" . "@" . "$domain";
                        } else return ["status" => 'false', "message" => "ایمیل نامعتبر. فقط پسوند های com, ir , net. edu, org قابل قبول است", "error-code" => 3,];
                    } else return ["status" => 'false', "message" => "شکل پسوند ایمیل نامعبتر است!", "error-code" => 4,];
                } else return ["status" => 'false', "message" => "ایمیل نامعتبر!", "error-code" => 5,];
            } else return ["status" => 'false', "message" => "فقط اعداد و حروف در ایمیل قابل قبول است!", "error-code" => 6,];
        } else return ["status" => 'false', "message" => "شکل ایمیل نامعتبر. لطفا از اعداد و حروف در قبل از @ استفاده کنید", "error-code" => 7,];
    }
}