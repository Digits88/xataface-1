<?php

class tables_devices {
 
    function valuelist__short_code_list() {

                $res = mysql_query("select shortcode,location from shortcode order by shortcode asc");
                $out = array();
                while ( $row = mysql_fetch_assoc($res) ) $out[$row['shortcode']] = $row['shortcode'].' - '.$row['location'];
                return $out;
        }

    function google_maps__renderCell( &$record ){

       if ($record->strval('google_maps')) { 
         return "<a href='". $record->strval('google_maps') . "' target='_new'> Map </a>";
       }
       else { return "";}      
    }

    function google_maps__htmlValue( &$record ){

	 if ($record->strval('google_maps')) {
         return "<a href='". $record->strval('google_maps') . "' target='_new'> Map </a>";
       }
       else { return "";} 

    }	
	
} 
?>
