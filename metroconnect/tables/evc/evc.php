<?php

class tables_evc {

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
       return "<a href='". $record->strval('cacti_link') . "' target='_new'>Graph</a>";
    }

    function cacti_link__htmlValue( &$record ){
       return "<a href='". $record->strval('cacti_link')."' target='_new'>Graph</a>";
    }	
 
    function service_config__renderCell( &$record ){


	$service_config = $record->strval('evc_string');
	$nice_service_config = str_replace("\n","<br>\n",str_replace(' ','&nbsp',$service_config));
	$nice_service_config = "!<br>\n! ".$record->strval('device')." :<br>\n!<br>\n".$nice_service_config;
      	$evc = preg_match('/service instance (\d+)/',$service_config,$matches) ? $matches[0] : 'No Match';

       return "
        $nice_service_config<br>
        <div style='margin-left:5px; margin-top:-14px;padding: 2px;position: relative;'>
        <a href='javascript:;' id='copyevc$evc' style='position:relative;display:block;outline: none;'>Copy</a>
        <input type='hidden' id='clipevc$evc' name='clipevc$evc' value='$val'/>
        </div>
        ";


    }
    function service_config__htmlValue( &$record ){

	$service_config = $record->strval('service_config');						// Get config
	$host = $record->strval('device');						// Get config
	$interface = $record->strval('interface');						// Get config
	$service_instance = $record->strval('service_id');						// Get config
        $nice_service_config = str_replace("\n","<br>\n",str_replace(' ','&nbsp',$service_config));	// Nice HTML
	$nice_service_config = "!<br>\n! ".$record->strval('device')." :<br>\n!<br>\n".$nice_service_config;

	// Lets work out the upgrade config here

	$upgrade_code = "show run interface $interface  | section $service_instance"."_eth\n\n\n";
	$upgrade_code.= "conf t\n";
	
		
	foreach (explode("\n",$service_config) as $line) { 

		if (preg_match('/^interface/',$line)) { 
			$upgrade_code.="$line\n";
		}
		if (preg_match('/service instance/',$line)) { 
                        $upgrade_code.="$line\n";
                }	
		if (preg_match('/service-policy input (\w+)/',$line,$matches)) {
			$policy_name = $matches[1];
			$upgrade_code.="  no service-policy input $policy_name\n";
			$upgrade_code.="  service-policy input " ;
		}
	}

	// $nice_upgrade_code = preg_replace('/\r+/g','',$upgrade_code);
	// $nice_upgrade_code = preg_replace('/\n/g','<br>',$nice_upgrade_code);
	// $nice_upgrade_code = preg_replace('/ /g','&nbsp',$nice_upgrade_code);

	$nice_upgrade_code = str_replace("\n\n","\n",$upgrade_code);     // Nice HTML	
	$nice_upgrade_code = str_replace("\n","<br>",str_replace(' ','&nbsp',$nice_upgrade_code));     // Nice HTML	

	// Here is the decomm code

	$decomm_code=  "! -----------------------------------------\n";
	$decomm_code.= "! Auto-generated decommission code for:\n";
	$decomm_code.= "!  $host -> $interface\n";
	$decomm_code.= "!\n";
	$decomm_code.= "!---------------\n";
	$decomm_code.= "!Service Config:\n";
	$decomm_code.= "!---------------\n";
	$decomm_code.= "!\n";
	foreach (explode("\n",$service_config) as $line) { 
		$decomm_code.= "!   $line\n";
	}
	$decomm_code.= "!\n";
	$decomm_code.= "! -----------------------------------------\n";
	$decomm_code.= " \n";
	$decomm_code.= "conf t\n";

        foreach (explode("\n",$service_config) as $line) {

                if (preg_match('/^interface/',$line)) {
                        $decomm_code.="$line\n";
                }
                if (preg_match('/service instance/',$line)) {
                        $decomm_code.="   no $line\n";
                }
        }

        // $nice_decomm_code = preg_replace('/\r+/g','',$decomm_code);
        // $nice_decomm_code = preg_replace('/\n/g','<br>',$nice_decomm_code);
        // $nice_decomm_code = preg_replace('/ /g','&nbsp',$nice_decomm_code);
	$decomm_code.="end\n";
        $nice_decomm_code = str_replace("\n\n","\n",$decomm_code);     // Nice HTML  
        $nice_decomm_code = str_replace("\n","<br>",str_replace(' ','&nbsp',$nice_decomm_code));     // Nice HTML  


       return "
        $nice_service_config<br><hr><br>
        <div style='margin-left:5px; margin-top:-14px;padding: 2px;position: relative;'>
        <span>
	|
	<a href='javascript:;' id='copy_si' style='outline: none;'>Copy Config</a>
	|
        <a href='javascript:;' id='copy_upgrade' style='outline: none;'>Copy Upgrade</a>
	|
	<a href='javascript:;' id='copy_decomm' style='outline: none;'>Copy Decomm</a>
	|
        </span>
	<input type='hidden' id='clip_si' value='$nice_service_config'/>
        <input type='hidden' id='clip_upgrade' value='$nice_upgrade_code'/>
        <input type='hidden' id='clip_decomm' value='$nice_decomm_code'/>
        </div>
	<hr>
        ";

    }
    function interface__renderCell ( &$record ) {

	$local_int = $record->strval('interface');
	$local_int = str_replace('TenGigabitEthernet','Te',$local_int);	
	$local_int = str_replace('GigabitEthernet','Gi',$local_int);	
	$local_int = str_replace('FastEthernet','Fa',$local_int);	

	return($local_int);
    }
  
    function circuit_id__renderCell ( &$record ) {

	$local_id = $record->strval('circuit_id');
	
	if (preg_match('/^2(.+)/',$local_id,$matches)) {
		return("1".$matches[1]." (Backup)");
	}
	else {
	 return ($local_id);
	}
    }   
 
    function provider__renderCell(&$record){

	
                if ($record->strval('provider')=='Internet Solutions') {
                        return('IS');
                }
                else {
                        return $record->display('provider');
                }
    }

    function block__custom_javascripts(){
        echo "\n\t\t";
        echo '<script src="/metroconnect/ZeroClipboard/jquery.js" type="text/javascript" language="javascript"></script>';
        echo "\n\t\t";
        echo '<script src="/metroconnect/ZeroClipboard/ZeroClipboard.js" type="text/javascript" language="javascript"></script>';
        echo "\n\t\t";
        echo '<script src="/metroconnect/ZeroClipboard/GlueEVCs.js" type="text/javascript" language="javascript"></script>';
        echo "\n\n";
    }

	
} 
?>
