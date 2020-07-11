<?php

    function getUrlString($title)
    {
        return trim ( preg_replace( "/-+/", "-", preg_replace( "/[^a-z0-9]+/", "-", strtolower( $title ) ) ), "-" ) . "-" . time(); 
    }

    function getExactTime($date)
    {
        $timestamp = strtotime($date);
        $ago = time() - $timestamp;

        if ($ago < 60) {
            return ($ago) == 1 ? 'a second ago' : $ago . " seconds ago";
        } elseif ($ago < 3600) {
            return floor($ago / 60) == 1 ? 'a minute ago' : floor($ago / 60) . " minutes ago";
        } elseif ($ago < 3600 * 24) {
            return floor($ago / 3600) == 1 ? 'an hour ago' : floor($ago / 3600) . " hours ago";
        } elseif ($ago < 3600 * 24 * 30) {
            return floor($ago / (3600 * 24)) == 1 ? 'a day ago' : floor($ago / (3600 * 24)) . " days ago";
        } elseif ($ago < 3600 * 24 * 30 * 12) {
            return floor($ago / (3600 * 24 * 30)) == 1 ? 'a month ago' : floor($ago / (3600 * 24 * 30)) . " months ago";
        } else {
            return floor($ago / (3600 * 24 * 30 * 12)) == 1 ? 'a year ago' : floor($ago / (3600 * 24 * 30 * 12)) . " years ago";
        }
    }