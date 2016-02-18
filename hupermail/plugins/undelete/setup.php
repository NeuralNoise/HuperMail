<?php

/**
  * SquirrelMail Undelete Plugin
  *
  * Copyright (c) 2011-2011 Paul Lesniewski <paul@squirrelmail.org>
  * Copyright (c) 2002 Nick Sayer <nsayer@quack.kfu.com>
  *
  * Licensed under the GNU GPL. For full terms see the file COPYING.
  *
  * @package plugins
  * @subpackage undelete
  *
  */



/**
  * Register this plugin with SquirrelMail
  *
  */
function squirrelmail_plugin_init_undelete()
{

   global $squirrelmail_plugin_hooks;


   // show undelete button on mailbox list page
   //
   $squirrelmail_plugin_hooks['mailbox_display_buttons']['undelete']
      = 'show_undelete_button_stub';


}



/**
  * Returns info about this plugin
  *
  */
function undelete_info()
{

   return array(
                  'english_name' => 'Undelete',
                  'authors' => array(
                     'Paul Lesniewski' => array(
                        'email' => 'paul@squirrelmail.org',
                        'sm_site_username' => 'pdontthink',
                     ),
                     'Nick Sayer' => array(
                        'email' => 'nsayer@quack.kfu.com',
                     ),
                  ),
                  'version' => '2.0',
                  'required_sm_version' => '1.4.0',
                  'requires_configuration' => 0,
                  'summary' => 'Provides a means to undelete messages that have not been expunged',
                  'details' => 'This plugin allows users to undelete messages in a folder that have not yet been expunged.  This plugin will only activate if the auto-expunge feature is disabled in the main SquirrelMail configuration ($auto_expunge in config/config.php or in the configuration tool, go to 3. Folder Defaults ==> 11. Auto Expunge).',
                  'requires_source_patch' => 0,
                  'per_version_requirements' => array(
                     '1.5.0' => SQ_INCOMPATIBLE,
                  ),
               );

}



/**
  * Returns version info about this plugin
  *
  */
function undelete_version()
{
   $info = undelete_info();
   return $info['version'];
}



/**
  * Show undelete button on mailbox list page
  *
  */
function show_undelete_button_stub()
{
   include_once(SM_PATH . 'plugins/undelete/functions.php');
   show_undelete_button();
}



