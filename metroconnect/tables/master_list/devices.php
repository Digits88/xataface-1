<?php

class tables_devices {

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
