<?php

    function getUrlString($title)
    {
        return trim ( preg_replace( "/-+/", "-", preg_replace( "/[^a-z0-9]+/", "-", strtolower( $title ) ) ), "-" ) . "-" . time(); 
    }