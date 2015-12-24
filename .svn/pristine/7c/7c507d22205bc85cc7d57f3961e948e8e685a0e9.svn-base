<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File: outputfilter.protect_email.php
 * Type: outputfilter
 * Name: protect_email
 * Purpose: Converts @ sign in email addresses to %40 as 
 * a simple protection against spambots
 * -------------------------------------------------------------
 */
 function smarty_outputfilter_src($output, &$smarty)
 {
     $output = preg_replace( "/href=\"public\/([^\"]*\.css)\"/", 'href="'.Common_Config::STATIC_BASE_URL.'${1}"', $output);
     return preg_replace( '/src=\"public\/([^\"]*)\"/', 'src="'.Common_Config::STATIC_BASE_URL.'${1}"', $output);

     $output = preg_replace( "/href=\"([^\":]*\.css)\"/", 'href="'.Common_Config::STATIC_BASE_URL.'${1}"', $output);
     return preg_replace( '/src=\"([^\":]*)\"/', 'src="'.Common_Config::STATIC_BASE_URL.'${1}"', $output);
 }