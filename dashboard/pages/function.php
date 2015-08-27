<?php

function cekUrl($checkUrl) {
    $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if (strpos($url, $checkUrl) !== false) {
        return true;
    } else {
        return false;
    }
}