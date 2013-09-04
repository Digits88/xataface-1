<?php
class tables_upe_interface {

	function interface_desc__renderCell(&$record){
		
		if (strlen($record->strval('interface_desc'))>=75) {
			return substr_replace($record->strval('interface_desc'),"...",70);
		}
		else {
			return($record->strval('interface_desc'));
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


}

?>

