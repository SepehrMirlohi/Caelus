<?php

class Alert
{

    public static string $Error_Template = '
        <section class="alert-section">
        <div class="alert alert-2-secondary">
          <h3 class="alert-title">?</h3>
          <p class="alert-content">?</p>
        </div>
        </section>
    ';
    public static string $Warning_Template = '
        <div class="alert alert-2-secondary">
          <h3 class="alert-title">?</h3>
          <p class="alert-content">?</p>
        </div>
    ';
    public static string $Success_Template = '
        <div class="alert alert-2-secondary">
          <h3 class="alert-title">?</h3>
          <p class="alert-content">?</p>
        </div>
    ';
    public static function Error($title, $message): void
    {
        echo Helpers::set_parameters(self::$Error_Template, [$title, $message]);
    }
//    public static function Warning($color, $message): void
//    {
//
//    }
//    public static function Success($color, $message): void
//    {
//
//    }
}
