<?php

class tables_gis_etk_nofibre {

    function google_earth_link__renderCell( &$record ){
       return "<a href='http://". $record->strval('google_earth_link') . "' target='_new'>KML</a>";
    }

    function google_earth_link__htmlValue( &$record ){
       return "<a href='". $record->strval('google_earth_link')."' target='_new'>KML</a>";
    }	
 
} 
?>
