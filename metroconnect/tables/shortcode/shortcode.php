<?php

class tables_shortcode extends Audit {

    function google_maps__renderCell( &$record ){

       if ($record->strval('google_maps')) { 
         return "<a href='". $record->strval('google_maps') . "' target='_google_maps'> Map </a>";
       }
       else { return "";}      
    }

    function google_maps__htmlValue( &$record ){

	 if ($record->strval('google_maps')) {
         return "<a href='". $record->strval('google_maps') . "' target='_google_maps'> Map </a>";
       }
       else { return "";} 

    }

    function conforms_arch__renderCell (&$record) {


	if ($record->strval('conforms_arch') == 1) {
         return "<span style='margin-left:12px;'><input type='checkbox' checked disabled></span>";
       }
       else { return "";}

    }

    function guest_wireless__renderCell (&$record) {


        if ($record->strval('guest_wireless') == 1) {
         return "<span style='margin-left:12px;'><input type='checkbox' checked disabled></span>";
       }
       else { return "";}

    }
	

	
   // Per field permissions delegates

   function shortcode__permissions(&$record) 	{ return($this->etk_perms(0));}
   function location__permissions(&$record)	{ return($this->etk_perms(0));}
   function address__permissions(&$record) 	{ return($this->etk_perms(1));}
   function gps__permissions(&$record) 		{ return($this->etk_perms(1));}
   function zone__permissions(&$record) 	{ return($this->etk_perms(0));}
   function sub_zone__permissions(&$record) 	{ return($this->etk_perms(0));}
   function google_maps__permissions(&$record) 	{ return($this->etk_perms(0));}
   function contact__permissions(&$record) 	{ return($this->etk_perms(1));}
   function telephone__permissions(&$record) 	{ return($this->etk_perms(1));}
   function cell__permissions(&$record) 	{ return($this->etk_perms(1));}
   function comment__permissions(&$record) 	{ return($this->etk_perms(1));}
   function connectivity__permissions(&$record) { return($this->etk_perms(0));}
   function type__permissions(&$record) 	{ return($this->etk_perms(0));}
   function status__permissions(&$record) 	{ return($this->etk_perms(0));}
   function last_updated__permissions(&$record) { return($this->etk_perms(1));}
   function updated_by__permissions(&$record) 	{ return($this->etk_perms(1));}
   function conforms_arch__permissions(&$record){ return($this->etk_perms(1));}
   function guest_wireless__permissions(&$record){ return($this->etk_perms(1));}
	

	
   function etk_perms($editable=0) { 

	// Return field viewer or editor permissions
	// If "$editable" is set then this is used to override whatever the users
	// default permission is 

	 $auth =& Dataface_AuthenticationTool::getInstance();
         $user =& $auth->getLoggedInUser();
	 $username = & $auth->getLoggedInUserName();

         if ( !isset($user) ) return Dataface_PermissionsTool::NO_ACCESS();
             // if the user is null then nobody is logged in... no access.
             // This will force a login prompt.
         $role = $user->val('Role');
	
	 if ($username=='guest') {
		return Dataface_PermissionsTool::getRolePermissions("Field Viewer"); 
	 } 
	 elseif ($editable) { 
	 	return Dataface_PermissionsTool::getRolePermissions("Field Editor");
	 }
	 elseif ($role == 'ETK-RO') { 
		return Dataface_PermissionsTool::getRolePermissions("Field Viewer");		
	 }
	 else { 
         	return Dataface_PermissionsTool::getRolePermissions($role);
	 }
   }	
} 
?>
