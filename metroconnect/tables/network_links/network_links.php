<?php

class tables_network_links {

	// Do a value list for the shortcodes in a friendlier format

	function valuelist__short_code_list() {

		$res = mysql_query("select shortcode,location from shortcode order by shortcode asc");
		$out = array();
    		while ( $row = mysql_fetch_assoc($res) ) $out[$row['shortcode']] = $row['shortcode'].' - '.$row['location'];
    		return $out;
	}

	function bandwidth__validate(&$record, $value, &$params){
		
		$media_type = $record->val('media');
	
		if (!is_array($value)) { 
			//print "<pre> Inside".print_r($params,1)."</pre>";	
			//print "VALUE: $value";
			$val = $value;
	
			// '64kbps','128kbps','256kbps','512kbps','1mbps','2mbps','5mbps','10mbps','20mbps',
			// '30mbps','40mbps','50mbps','100mbps','1gbps','10gbps'
			
			if 	($media_type == 'Fibre' && !(in_array($val,array('100mbps','1gbps','10gbps')))) { 
		
				$params['message'] = "$val: The bandwidth for Fibre should be one of the following:".
						     " '100mbps','1gbps','10gbps'";
				return false;
			}
			elseif 	($media_type == 'Diginet' && !(in_array($val,array('64kbps','128kbps','256kbps',
										   '512kbps','1mbps','2mbps')))) {

                        	$params['message'] = "$val: The bandwidth for Diginet should be one of the following: ".
						     "'64kbps','128kbps','256kbps','512kbps','1mbps','2mbps'";
                        	return false;
			}
			elseif  ($media_type == 'Wireless' && !(in_array($val,array('1mbps','2mbps','5mbps','10mbps',
										     '20mbps','30mbps','40mbps','50mbps')))) {

                        	$params['message'] = "$val: The bandwidth for Wireless should be one of the following: ".
						     "'1mbps','2mbps','5mbps','10mbps','20mbps','30mbps','40mbps','50mbps'";
                        	return false;

                	}
			return true;
		}
		else { 
			//print "<pre> Outside:".print_r($params,1)."</pre>";
			$params=array();	
			//print "VALUE: $value";
			return true;
		}
	}

	function getPermissions(&$record){
         $auth =& Dataface_AuthenticationTool::getInstance();
         $user =& $auth->getLoggedInUser();
         if ( !isset($user) ) return Dataface_PermissionsTool::NO_ACCESS();
             // if the user is null then nobody is logged in... no access.
             // This will force a login prompt.
         $role = $user->val('Role');
         return Dataface_PermissionsTool::getRolePermissions($role);
             // Returns all of the permissions for the user's current role.
    }

}
?>

