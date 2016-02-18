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
  * Register this plugin with SquirrelMail
  *
  */
function squirrelmail_plugin_init_login_alias() 
{

   global $squirrelmail_plugin_hooks;


   // check for use of alias when logging in
   //
   $squirrelmail_plugin_hooks['login_before']['login_alias'] 
      = 'login_alias_lookup_alias';


   // show option on personal information page
   //
   $squirrelmail_plugin_hooks['optpage_loadhook_personal']['login_alias']
      = 'login_alias_display_options';

}



/**
  * Returns info about this plugin
  *
  */
function login_alias_info()
{

   return array(
                 'english_name' => 'Login Aliases',
                 'authors' => array(
                    'Jay Guerette' => array(
                       'email' => 'JayGuerette@pobox.com',
                    ),
                    'Tyler Akins' => array(),
                    'David Minor' => array(
                       'email' => 'dave@dminor.com',
                    ),
                    'Paul Lesniewski' => array(
                       'email' => 'paul@squirrelmail.org',
                       'sm_site_username' => 'pdontthink',
                    ),
                 ),
                 'version' => '2.5',
                 'required_sm_version' => '1.2.1',
                 'requires_configuration' => 0,
                 'requires_source_patch' => 0,
                 'summary' => 'Allows users to specify a optional personalized login alias.',
                 'details' => 'This plugin allows users to specify a optional personalized login alias by browsing to Options->Personal Information.  The alias can have spaces in it.  Once set, the user can log in using either their normal username or their alias.',
                 'per_version_requirements' => array(
                    '1.5.2' => array(
                       'required_plugins' => array()
                    ),
                    '1.5.0' => array(
                       'required_plugins' => array(
                          'compatibility' => array(
                             'version' => '2.0.7',
                             'activate' => FALSE,
                          )
                       )
                    ),
                    '1.4.10' => array(
                       'required_plugins' => array()
                    ),
                    '1.2.1' => array(
                       'required_plugins' => array(
                          'compatibility' => array(
                             'version' => '2.0.7',
                             'activate' => FALSE,
                          )
                       )
                    ),
                 ),
               );

}



/**
  * Returns version info about this plugin
  *
  */
function login_alias_version() 
{
   $info = login_alias_info();
   return $info['version'];
}



/**
  * Called when logging the user in; check alias list and remap 
  * username if alias is found
  *
  */
function login_alias_lookup_alias() 
{
   include_once(SM_PATH . 'plugins/login_alias/functions.php');
   login_alias_lookup_alias_do();
}



/**
  * Inserts text box on personal options page so user can set an alias
  *
  */
function login_alias_display_options()
{
   include_once(SM_PATH . 'plugins/login_alias/functions.php');
   login_alias_display_options_do();
}



