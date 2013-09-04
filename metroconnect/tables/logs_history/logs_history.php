<?php


class tables_logs_history {


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
