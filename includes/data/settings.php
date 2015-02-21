<?php
// <<---------Ban Listing Settings (Simplified)--------->> //
$name = 'LiteBans';
$serverip = 'mc.example.com';

function get_banner_name($banner) {
    if ($banner === "CONSOLE") {
        return "Console";
    }
    return $banner;
}
?>