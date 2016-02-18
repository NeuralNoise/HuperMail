<?php
   /*
    *  addgraphics
    *  By Mark Motley <mmotley@la-mirada.net>
    *  Changes By Paul Lesneiwski <pdontthink@angrynerds.com>
    *  (c) 2000 (GNU GPL - see ../../COPYING)
    *
    *  This plugin adds a customized graphic at the top of the left-hand
    *  frame of the main SquirrelMail mailbox screen.
    *
    *  The graphic can be any size, but you should probably try to make
    *  it rather small (like 200 x 100).  It should also be a GIF with
    *  transparency to support changing of the background color by the
    *  user.
    *
    *  The graphic can be changed on a per-domain basis if you host
    *  more than one (virtual) domain.
    *
    *  See config.php for image specification.
    *
    */

   // include compatibility plugin
   //
   if (defined('SM_PATH'))
      include_once(SM_PATH . 'plugins/compatibility/functions.php');
   else if (file_exists('../plugins/compatibility/functions.php'))
      include_once('../plugins/compatibility/functions.php');
   else if (file_exists('./plugins/compatibility/functions.php'))
      include_once('./plugins/compatibility/functions.php');


   function addgraph_left_do() {

      global $left_size, $left_image, $differentImagePerDomain, $left_image_alt,
             $addgraphics_virtualDomains, $left_image_url, $image_height, 
             $image_width, $div_style;

      include_once(SM_PATH . 'plugins/addgraphics/config.php');


      if ($differentImagePerDomain) {

         // get global variable for versions of PHP < 4.1
         //
         if (!compatibility_check_php_version(4,1)) {
           global $HTTP_SERVER_VARS;
           $_SERVER = $HTTP_SERVER_VARS;
         }


         // grab hostname into local var
         //
         $hostname = $_SERVER['HTTP_HOST'];


         // get the correct image for this domain
         //
         $check_image_width = FALSE;
         $new_left_image = '';
         $new_left_image_alt = '';
         $new_left_image_url = '';
         $new_image_width = '';
         $new_image_height = '';
         $new_div_style = '';
         foreach (array_keys($addgraphics_virtualDomains) as $virtualDomain) {

            if (stristr($hostname, $virtualDomain)) {

               $new_left_image = $addgraphics_virtualDomains[$virtualDomain]['left_image'];
               $new_left_image_alt = $addgraphics_virtualDomains[$virtualDomain]['left_image_alt'];
               $new_left_image_url = $addgraphics_virtualDomains[$virtualDomain]['left_image_url'];

               if (isset($addgraphics_virtualDomains[$virtualDomain]['image_width']))
                  $new_image_width = $addgraphics_virtualDomains[$virtualDomain]['image_width'];
               else
                  $check_image_width = TRUE;

               if (isset($addgraphics_virtualDomains[$virtualDomain]['image_height']))
                  $new_image_height = $addgraphics_virtualDomains[$virtualDomain]['image_height'];

               if (isset($addgraphics_virtualDomains[$virtualDomain]['div_style']))
                  $new_div_style = $addgraphics_virtualDomains[$virtualDomain]['div_style'];
            }
         }


         // if not found, look for global settings from vlogin
         if (empty($new_left_image))
         {
            global $addgraphics_left_image;
            if (!empty($addgraphics_left_image))
            {
               $new_left_image = $addgraphics_left_image;

               if (empty($new_image_width))
               {
                  global $addgraphics_image_width;
                  if (!empty($addgraphics_image_width))
                     $new_image_width = $addgraphics_image_width;
                  else
                     $check_image_width = TRUE;
               }
            }
         }
         if (empty($new_left_image_alt))
         {
            global $addgraphics_left_image_alt;
            if (!empty($addgraphics_left_image_alt))
               $new_left_image_alt = $addgraphics_left_image_alt;
         }
         if (empty($new_left_image_url))
         {
            global $addgraphics_left_image_url;
            if (!empty($addgraphics_left_image_url))
               $new_left_image_url = $addgraphics_left_image_url;
         }
         if (empty($new_image_height))
         {
            global $addgraphics_image_height;
            if (!empty($addgraphics_image_height))
               $new_image_height = $addgraphics_image_height;
         }
         if (empty($new_div_style))
         {
            global $addgraphics_div_style;
            if (!empty($addgraphics_div_style))
               $new_div_style = $addgraphics_div_style;
         }


         if (!empty($new_left_image)) $left_image = $new_left_image;
         if (!empty($new_image_width)) $image_width = $new_image_width;
         if (!empty($new_left_image_alt)) $left_image_alt = $new_left_image_alt;
         if (!empty($new_left_image_url)) $left_image_url = $new_left_image_url;
         if (!empty($new_image_height)) $image_height = $new_image_height;
         if (!empty($new_div_style)) $div_style = $new_div_style;

         if ($check_image_width && empty($image_width))
            $image_width = $left_size - 20;

      }


      $left_size_mod = (isset($image_width) ? $image_width : $left_size - 20);


      if ($left_size_mod > 0) {

         if( isset($div_style) && !empty($div_style) )
            $div_style_prop = ' STYLE="' . $div_style . '"';
         else 
            $div_style_prop = ' STYLE="text-align: center;"';

         echo "<DIV$div_style_prop>";

         if (!empty($left_image_url))
            echo "<A HREF=\"$left_image_url\" TARGET=\"_blank\">";

         echo "<IMG SRC=\"$left_image\" WIDTH=\"$left_size_mod\" ";

         if (isset($image_height) && !empty($image_height))
            echo "HEIGHT=\"$image_height\" ";

         echo "ALT=\"$left_image_alt\" BORDER=\"0\">";

         if (!empty($left_image_url))
            echo '</A>';

         echo "</DIV>\n";

      }

   }

?>
