<?php

/**
  * SquirrelMail Login Alias Plugin
  * Copyright (c) 2001 Jay Guerette <JayGuerette@pobox.com>
  * Copyright (c) 2001 Tyler Akins
  * Copyright (c) 2002 David Minor <dave@dminor.com>
  * Copyright (c) 2002-2008 Paul Lesniewski <paul@squirrelmail.org>
  * Licensed under the GNU GPL. For full terms see the file COPYING.
  *
  * @package plugins
  * @subpackage login_alias
  *
  */



/**
  * Called when logging the user in; check alias list and remap 
  * username if alias is found
  *
  */
function login_alias_lookup_alias_do() 
{

    global $login_username, $$login_username, $plugins, $data_dir, 
           $prefs_are_cached, $prefs_cache, $foundLoginAlias,
           $prefs_dsn;


   // figure out where prefs are stored
   //
   if (isset($prefs_dsn) && !empty($prefs_dsn))
      $prefsInDB = true;
   else
   {
      $prefsInDB = false;

      // auto-create login alias file if needed
      //
      touch(getHashedFile('login_alias', $data_dir, "login_alias.pref"));
   }


   $user = $login_username;
    

   // if password_forget is loaded, use the obfuscated name
   //
   if (in_array('password_forget',$plugins))
   {
      if (!isset($$login_username))
         sqGetGlobalVar($login_username, $$login_username, SQ_FORM);

      if ($$login_username != '')
         $user = $$login_username;
   }


   // try to grab alias
   //
   $prefs_are_cached = FALSE;
   $prefs_cache = FALSE;
   session_unregister('prefs_are_cached');
   session_unregister('prefs_cache');

   $realname = getPref($data_dir, 'login_alias', $user);

   $prefs_are_cached = FALSE;
   $prefs_cache = FALSE;
   session_unregister('prefs_are_cached');
   session_unregister('prefs_cache');


   // this can be used by other plugins
   //
   $foundLoginAlias = 0;


   // no such alias found - bail
   //
   if ($realname == '') return;


   // before we do the reassignment, check if Login Manager
   // (Vlogin) is installed, if so, check if the alias is in
   // the domain that the user is logging in at (otherwise,
   // ignore the alias)
   //
   if (in_array('vlogin', $plugins)) 
   {
      global $dontUseHostName;

      include_once (SM_PATH . 'plugins/vlogin/functions.php');
      load_vlogin_config();

      $hostname = determine_user_hostname();

      if (!$dontUseHostName && strpos($realname, $hostname) === FALSE)
         return;
   }


   // this can be used by other plugins
   //
   $foundLoginAlias = 1;


   // if password_forget is loaded, use the obfuscated name 
   //
   if (in_array('password_forget', $plugins) && $$login_username != '') 
      $$login_username = $realname;
   else 
      $login_username = $realname;

}



/**
  * Inserts text box on personal options page so user can set an alias
  *
  */
function login_alias_display_options_do()
{

   global $username, $data_dir, $optpage_data;

   $login_alias = getPref($data_dir, $username, 'login_alias');

   sq_change_text_domain('login_alias');


   // add our own section to the option page
   //
   $optpage_data['grps']['login_alias'] = _("Login Alias");
   $optpage_data['vals']['login_alias'] = array(array(
      'name'              => 'login_alias',
      'caption'           => _("Alternate Login Username"),
      'type'              => SMOPT_TYPE_STRING,
      'size'              => SMOPT_SIZE_MEDIUM,
      'refresh'           => SMOPT_REFRESH_NONE,
      'save'              => 'login_alias_save_options',
      'initial_value'     => $login_alias,
      'trailing_text'     => _("You can use this or your regular account name to log in to your account."),
   ));


   sq_change_text_domain('squirrelmail');

}



/**
  * Saves user's alias information
  *
  */
function login_alias_save_options($option)
{

   global $username, $data_dir, $prefs_dsn, $prefs_are_cached,
          $prefs_cache;

   $login_alias = trim($option->new_value);


   // figure out where prefs are stored
   //
   // need to fiddle with prefs directly because
   // we have the requirement that we have to see
   // if the alias clashes with a real username
   //
   if (!empty($prefs_dsn))
      $prefsInDB = TRUE;
   else
   {
      $prefsInDB = FALSE;

      // auto-create login alias file if needed
      //
      touch(getHashedFile('login_alias', $data_dir, 'login_alias.pref'));
   }


   // just remove the alias if the user blanked it out
   //      
   if (empty($login_alias)) 
   {                        
                             
      // clear alias value 
      //                   
      $old_login_alias = getPref($data_dir, $username, 'login_alias');
      removePref($data_dir, $username, 'login_alias');
            

      // when dealing with preferences not
      // saved under the $username (in this
      // case "login_alias" instead), need
      // to wipe the prefs cache on both sides
      // of the operation
      //
      $prefs_are_cached = FALSE;
      $prefs_cache = FALSE;
      session_unregister('prefs_are_cached');
      session_unregister('prefs_cache');
            
      removePref($data_dir, 'login_alias', $old_login_alias);
                
      $prefs_are_cached = FALSE;
      $prefs_cache = FALSE;
      session_unregister('prefs_are_cached');
      session_unregister('prefs_cache');
      
      return;                  

   }                            
                


   // now, we proceed with first checking that the requested
   // alias doesn't actually conflict with another real username!
   //
   $xtra_check = '';



   // check if Login Manager (Vlogin) is installed, and if so,
   // we also need to check the proposed alias with domain info
   // tacked on to the end
   //
//TODO: actually, it's not that we need to do an extra check with domain name - we should probably just do one or the other (usernames are or are not full email addresses, most systems won't mix the two
   global $plugins;
   if (in_array('vlogin', $plugins)) 
   {
      global $dontUseHostName, $at;

      include_once (SM_PATH . 'plugins/vlogin/functions.php');
      load_vlogin_config();

      $hostname = determine_user_hostname();

      if (!$dontUseHostName)
         $xtra_check = $login_alias . $at . $hostname;
   }



   // look for DB pref for possible real user...
   // 
   $userExists = FALSE;
   if ($prefsInDB)
   {

      include_once (SM_PATH . 'functions/db_prefs.php');

      $db = new dbPrefs;
      $db->open();

      $query = sprintf("SELECT %s FROM %s ".
                       "WHERE %s = '%s' AND %s = 'hililist'",
                       $db->val_field,
                       $db->table,
                       $db->user_field,
                       $db->dbh->quoteString($login_alias),
                       $db->key_field);
      $res = $db->dbh->query($query);
      if (DB::isError($res)) 
      {
         $db->failQuery($res);
      }
    
      $row = $res->fetchRow(); 
      if ($row)
      {
         $userExists = TRUE;
      }

//TODO: actually, it's not that we need to do an extra check with domain name - we should probably just do one or the other (usernames are or are not full email addresses, most systems won't mix the two
      if (!$userExists && !empty($xtra_check))
      {
         $query = sprintf("SELECT %s FROM %s ".
                          "WHERE %s = '%s' AND %s = 'hililist'",
                          $db->val_field,
                          $db->table,
                          $db->user_field,
                          $db->dbh->quoteString($xtra_check),
                          $db->key_field);
         $res = $db->dbh->query($query);
         if (DB::isError($res)) 
         {
            $db->failQuery($res);
         }
        
         $row = $res->fetchRow();
         if ($row)
         {
            $userExists = TRUE;
         }
      }
   }



   // look for pref file for possible real user...
   //
   else
   {
      if (file_exists(getHashedFile($login_alias, $data_dir, "$login_alias.pref")))
         $userExists = TRUE;

//TODO: actually, it's not that we need to do an extra check with domain name - we should probably just do one or the other (usernames are or are not full email addresses, most systems won't mix the two
      if (!$userExists && !empty($xtra_check)
       && file_exists(getHashedFile($xtra_check, $data_dir, "$xtra_check.pref")))
         $userExists = TRUE;
   }



   // so, does the alias clash with a real username?
   // we can't use this login alias then
   // 
   if ($userExists)
   {   
      global $optpage_save_error;
      sq_change_text_domain('login_alias');
      $optpage_save_error = array(_("Login alias is already in use.  Please select another alias."));
      sq_change_text_domain('squirrelmail');
      return;
   }


      
   // Before we proceed with updating the alias, we also have to
   // see if someone else is using the alias - don't allow this
   // user to steal it from someone else
   //
   // when dealing with preferences not
   // saved under the $username (in this
   // case "login_alias" instead), need
   // to wipe the prefs cache on both sides
   // of the operation
   //
   $old_login_alias = getPref($data_dir, $username, 'login_alias');

   $prefs_are_cached = FALSE;
   $prefs_cache = FALSE;
   session_unregister('prefs_are_cached');
   session_unregister('prefs_cache');

   $alias_email = getPref($data_dir, 'login_alias', $login_alias);

   $prefs_are_cached = FALSE;
   $prefs_cache = FALSE;
   session_unregister('prefs_are_cached');
   session_unregister('prefs_cache');

   if (!empty($alias_email) && $alias_email != $username)
   {
      global $optpage_save_error;
      sq_change_text_domain('login_alias');
      $optpage_save_error = array(_("Login alias is already in use.  Please select another alias."));
      sq_change_text_domain('squirrelmail');
   }



   // OK, now we can finally save the new alias
   //
   else
   {

      removePref($data_dir, 'login_alias', $old_login_alias); 
      setPref($data_dir, 'login_alias', $login_alias, $username);

      $prefs_are_cached = FALSE;
      $prefs_cache = FALSE;
      session_unregister('prefs_are_cached');
      session_unregister('prefs_cache');

      setPref($data_dir, $username, 'login_alias', $login_alias);

   }
    
}



