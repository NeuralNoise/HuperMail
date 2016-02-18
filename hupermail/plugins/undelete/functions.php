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
  * Show move all button on mailbox list page
  *
  */
function show_undelete_button()
{

   global $auto_expunge;

   sq_change_text_domain('undelete');


   // display the undelete button?
   //
   if (!$auto_expunge)
      echo '<input type="submit" name="undeleteButton" title="' . _("Undelete the selected messages") . '" value="' . _("Undelete") . "\" />\n";


   sq_change_text_domain('squirrelmail');

}



