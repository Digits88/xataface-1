<?
/**
 * A delegate class for the entire application to handle custom handling of 
 * some functions such as permissions and preferences.
 */

class Audit {

	function last_updated__pullValue(&$record, $element){

        	if ($record->val('last_updated') == NULL){ return "";}
        	else { 
			return date('Y-m-d H:i:s', strtotime($record->strval('last_updated')));
		}
        }

        function last_updated__htmlValue(&$record){

                if ($record->val('last_updated') == NULL){ return "";}
                else {
			return date('Y-m-d H:i:s', strtotime($record->strval('last_updated')));
                }
        }

        function last_updated__renderCell(&$record){

                if ($record->val('last_updated') == NULL){ return "";}
                else {
                        return date('Y-m-d H:i:s', strtotime($record->strval('last_updated')));
                }
        }

        function beforeInsert(&$record){
        
                $auth =& Dataface_AuthenticationTool::getInstance();
                $user =& $auth->getLoggedInUsername();
                $record->setValue('updated_by', $user);
        }

        function beforeUpdate(&$record){


                $auth =& Dataface_AuthenticationTool::getInstance();
                $user =& $auth->getLoggedInUsername();
		
		$emailAddress = "MC Database Updates <Allan.Houston@dimensiondata.com>";
		$emailSubject = "Record updated by $user";
		$vals = $record->getValues();
		$emailMessage = "New values:\n";
		foreach ($vals as $key=>$val ){
     			$emailMessage.= "$key : $val\n";
 		}
		
                $record->setValue('updated_by', $user);
		mail($emailAddress,$emailSubject, $emailMessage);
        }
}

class conf_ApplicationDelegate {
    /**
     * Returns permissions array.  This method is called every time an action is 
     * performed to make sure that the user has permission to perform the action.
     * @param record A Dataface_Record object (may be null) against which we check
     *               permissions.
     * @see Dataface_PermissionsTool
     * @see Dataface_AuthenticationTool
     */

     function getNavItem($key, $label){

	 $auth =& Dataface_AuthenticationTool::getInstance();
         $user =& $auth->getLoggedInUser();

	 if ($user) { 
			$username = & $auth->getLoggedInUserName();
			$role = $user->val('Role');
	}

        $query =& Dataface_Application::getInstance()->getQuery();

	if (preg_match("/^ETK/",$role)) {
        switch ($key){
            case 'gis_etk_nofibre':
		if ($username=='djolley') {
        		return array('selected' => ($query['-table'] == $key ));
		}	
		break;
            case 'shortcode':
        	return array('selected' => ($query['-table'] == $key ));
                // non-admin users can see these
                throw new Exception("Use default rendering");
	    case 'network_links':
		if (preg_match("/^ETK$/",$role)) {
			return array('selected' => ($query['-table'] == $key ));
		}
		else {
			return null;
		}
                // non-admin users can see these
                throw new Exception("Use default rendering");
        }
        // Non-admin users can't see any other table.
        return null;
 
    	} else {

        	//Admin users can see everything..
        	return array('selected' => ($query['-table'] == $key ));
    	}
     }

     function beforeHandleRequest(){
	
	$auth =& Dataface_AuthenticationTool::getInstance();
        $user =& $auth->getLoggedInUser();
	
	if ($user) { 
        	
		$role = $user->val('Role');
		$app = Dataface_Application::getInstance();

        	$query =& $app->getQuery();
            		// Make sure you assign by reference (i.e. =& )
            		// for this if you want to make changes to the query
       		if (preg_match("/^ETK/",$role)) { 
        		if ( $query['-table'] == 'upe_interface') { $query['-table'] = 'shortcode';}
    
		}
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
