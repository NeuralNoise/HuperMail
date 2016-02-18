<?php

/**
 * login.php -- simple login screen
 *
 * This a simple login screen. Some housekeeping is done to clean
 * cookies and find language.
 *
 * @copyright 1999-2011 The SquirrelMail Project Team
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version $Id: login.php 14084 2011-01-06 02:44:03Z pdontthink $
 * @package squirrelmail
 */

/** This is the login page */
define('PAGE_NAME', 'login');

/**
 * Path for SquirrelMail required files.
 * @ignore
 */
define('SM_PATH','../');

/* SquirrelMail required files. */
require_once(SM_PATH . 'functions/global.php');
require_once(SM_PATH . 'functions/i18n.php');
require_once(SM_PATH . 'functions/plugin.php');
require_once(SM_PATH . 'functions/constants.php');
require_once(SM_PATH . 'functions/page_header.php');
require_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/forms.php');

/**
 * $squirrelmail_language is set by a cookie when the user selects
 * language and logs out
 */
set_up_language($squirrelmail_language, TRUE, TRUE);

/**
 * In case the last session was not terminated properly, make sure
 * we get a new one, but make sure we preserve session_expired_*
 */
$sep = '';
$sel = '';
sqGetGlobalVar('session_expired_post', $sep, SQ_SESSION);
sqGetGlobalVar('session_expired_location', $sel, SQ_SESSION);

/* blow away session */
sqsession_destroy();

/**
 * in some rare instances, the session seems to stick
 * around even after destroying it (!!), so if it does,
 * we'll manually flatten the $_SESSION data
 */
if (!empty($_SESSION)) {
    $_SESSION = array();
}

/**
 * Allow administrators to define custom session handlers
 * for SquirrelMail without needing to change anything in
 * php.ini (application-level).
 *
 * In config_local.php, admin needs to put:
 *
 *     $custom_session_handlers = array(
 *         'my_open_handler',
 *         'my_close_handler',
 *         'my_read_handler',
 *         'my_write_handler',
 *         'my_destroy_handler',
 *         'my_gc_handler',
 *     );
 *     session_module_name('user');
 *     session_set_save_handler(
 *         $custom_session_handlers[0],
 *         $custom_session_handlers[1],
 *         $custom_session_handlers[2],
 *         $custom_session_handlers[3],
 *         $custom_session_handlers[4],
 *         $custom_session_handlers[5]
 *     );
 * 
 * We need to replicate that code once here because PHP has
 * long had a bug that resets the session handler mechanism
 * when the session data is also destroyed.  Because of this
 * bug, even administrators who define custom session handlers
 * via a PHP pre-load defined in php.ini (auto_prepend_file)
 * will still need to define the $custom_session_handlers array 
 * in config_local.php.
 */
global $custom_session_handlers;
if (!empty($custom_session_handlers)) {
    $open    = $custom_session_handlers[0];
    $close   = $custom_session_handlers[1];
    $read    = $custom_session_handlers[2];
    $write   = $custom_session_handlers[3];
    $destroy = $custom_session_handlers[4];
    $gc      = $custom_session_handlers[5];
    session_module_name('user');
    session_set_save_handler($open, $close, $read, $write, $destroy, $gc);
}

/* put session_expired_* variables back in session */
sqsession_is_active();
if (!empty($sel)) {
    sqsession_register($sel, 'session_expired_location');
    if (!empty($sep)) 
        sqsession_register($sep, 'session_expired_post');
}

// Disable Browser Caching
//
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: Sat, 1 Jan 2000 00:00:00 GMT');

do_hook('login_cookie');

$loginname_value = (sqGetGlobalVar('loginname', $loginname) ? htmlspecialchars($loginname) : '');

/* Output the javascript onload function. */

require_once('loginGUI.php');
