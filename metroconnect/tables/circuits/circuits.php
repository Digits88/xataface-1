<?php

class tables_circuits {

    function getPermissions(&$record){
         $auth =& Dataface_AuthenticationTool::getInstance();
         $user =& $auth->getLoggedInUser();
         if ( !isset($user) ) return Dataface_PermissionsTool::NO_ACCESS();
             // if the user is null then nobody is logged in... no access.
             // This will force a login prompt.
         $role = $user->val('Role');

         if (preg_match("/^ETK/",$role)) return Dataface_PermissionsTool::NO_ACCESS();

         return Dataface_PermissionsTool::getRolePermissions($role);
             // Returns all of the permissions for the user's current role.
    }

    function cacti_link__renderCell( &$record ){
       return "<a href='". $record->strval('cacti_link') . "' target='_new'> Graph </a>";
    }

    function cacti_link__htmlValue( &$record ){
       return "<a href='". $record->strval('cacti_link')."' target='_new'> Graph </a>";
    }

    function evc_string__renderCell( &$record ){
      
      $val = $record->strval('evc_string'); 
      $evc = preg_match('/\d{10}/',$val,$matches) ? $matches[0] : 'No Match';
       return "
	<div style='margin-left:5px; margin-top:-14px;padding: 2px;position: relative;'>
	<a href='javascript:;' id='copyevc$evc' style='position:relative;display:block;outline: none;'>Copy</a>
	<input type='hidden' id='clipevc$evc' name='clipevc$evc' value='$val'/>
	</div>
	";
    }

    function evc_string__htmlValue( &$record ){

	$val = $record->strval('evc_string');
      $evc = preg_match('/\d{10}/',$val,$matches) ? $matches[0] : 'No Match';
       return "
        <div style='margin-left:5px; margin-top:0px;padding: 2px;position: relative;'>
        <a href='javascript:;' id='copyevc$evc' style='position:relative;display:block;outline: none;'>Copy</a>
        <input type='hidden' id='clipevc$evc' name='clipevc$evc' value='$val'/>
        </div>
        ";

    }

    function block__custom_javascripts(){
	echo "\n\t\t";
    	echo '<script src="/metroconnect/ZeroClipboard/jquery.js" type="text/javascript" language="javascript"></script>';
	echo "\n\t\t";
    	echo '<script src="/metroconnect/ZeroClipboard/ZeroClipboard.js" type="text/javascript" language="javascript"></script>';
	echo "\n\t\t";
	echo '<script src="/metroconnect/ZeroClipboard/GlueCircuits.js" type="text/javascript" language="javascript"></script>';
	echo "\n\n";
    }
	
    function block__custom_stylesheets() {
	
	echo "<style type='text/css'>
	a:active
	{
		outline: none;
	}

	a:focus
	{
		-moz-outline-style: none;
	}
	</style>
	";
    }
} 
?>
