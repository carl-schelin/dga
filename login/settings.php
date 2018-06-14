<?php

# add a space at the end as the company will be inserted into strings.
$Sitecompany            = 'Hobgoblin Consulting ';
# by default, enable debugging in case we missed a server entry. ALL means full on screen debugging
$Sitedebug              = 'ALL';

# set the default timezone
date_default_timezone_set('America/Denver');

# Set the environment here so other places in the code can be tested without changing code.
$hostname = php_uname('n');

if ($hostname == "guardian.schelin.org") {
  $Siteenv              = "PROD";
  $Sitedebug            = "YES";
  $Sitedebug            = "NO";

# Set site specific variables
  $Sitehttp             = "dga.shadowrun.us";
  $Siteurl              = "http://" . $Sitehttp;

# Header graphic
  $Siteheader		= "dgaheader.png";

# Path details
  $Sitedir              = "/usr/local/httpd/dga";
  $Siteinstall          = "/";

# Who to contact
  $Siteadmins           = ",carl@schelin.org";
  $Sitedev              = "carl@schelin.org";
  $EmergencyContact	= "carl@schelin.org";

# MySQL specific settings
  $DBtype		= "mysql";
  $DBserver		= "localhost";
  $DBname		= "dga";
  $DBuser		= "dga";
  $DBpassword		= "sp@nGw1p";
  $DBprefix             = "";
}

if ($hostname == "lnmt1cuomdev1.internal.pri") {
  $Siteenv              = "DEV";
  $Sitedebug            = "NO";
  $Sitedebug            = "YES";

# Set site specific variables
  $Sitehttp             = "lnmt1cuomdev1.internal.pri";
  $Siteurl              = "http://" . $Sitehttp;

# Header graphic
  $Siteheader		= "dgaheader.png";

# Path details
  $Sitedir              = "/var/www/html";
  $Siteinstall          = "/dga";

# Who to contact
  $Siteadmins           = ",carl@schelin.org";
  $Sitedev              = "carl@schelin.org";
  $EmergencyContact     = "carl@schelin.org";

# MySQL specific settings
  $DBtype		= "mysql";
  $DBserver		= "localhost";
  $DBname		= "dga";
  $DBuser		= "dga";
  $DBpassword		= "sp@nGw1p";
  $DBprefix             = "";
}

# enable debugging

if ( $Sitedebug == 'YES' || $Sitedebug == 'ALL' ) {
# set ini variables to manage error handling
  ini_set('error_reporting', E_ALL | E_STRICT);
  if ($Sitedebug == 'ALL') {
    ini_set('display_errors', 'on');
  } else {
    ini_set('display_errors', 'off');
  }
  ini_set('log_errors', 'On');
  ini_set('error_log', '/var/tmp/inventory.log');
}

# site details
$Sitename		= "Denver Gamers Association";
$Sitecopyright		= "Copyright &copy; Denver Gamers Association, All Rights Reserved except where otherwise noted.";
$Sitefooter		= "dgafooter.png";
# disable access to the site and print a maintenance message

$Sitemaintenance        = "1";

# Default variable to determine whether a popup alert is presented or a full login page
$called                 = 'no';

# Root directory for the Inventory Program
$Sitepath               = $Sitedir . $Siteinstall;
$Siteroot               = $Siteurl . $Siteinstall;

#######
##  Application and Utility specific locations
##  Sitepath is the prefix for OS level files such as include() or fopen()
##  Siteroot is the prefix for URL based files
#######

## Admin scripts (db modifiers)
$Adminpath              = $Sitepath . "/admin";
$Adminroot              = $Siteroot . "/admin";

## Login
$Loginpath              = $Sitepath . "/login";
$Loginroot              = $Siteroot . "/login";

# Access levels
$AL_Admin               = 1;
$AL_Edit                = 2;
$AL_ReadOnly            = 3;
$AL_Guest               = 4;

# Set a default theme for users not logged in.
if (!isset($_SESSION['theme'])) {
  $_SESSION['theme']    = 'sunny';
}

?>
