<?php

# clean and escape the input data

function clean($input, $maxlength) {
  $input = str_replace('\\"', "&quot;", $input);
  $input = str_replace("\\'", "&apos;", $input);
  $input = str_replace("'", "&apos;", $input);
  $input = str_replace('"', "&quot;", $input);
  $input = str_replace('0xAE', "&reg;", $input);
  $input = trim($input);
  $input = substr($input, 0, $maxlength);
  $input = mysql_real_escape_string($input);
  return ($input);
}

function br2nl($string) {
  $return = eregi_replace('<br[[:space:]]*/?' . '[[:space:]]*>','\\'.chr(13).chr(10),$string);
  return $return;
}

# log who did what

function logaccess($user, $source, $detail) {

  include('settings.php');

  $dbaconn = dbconn($DBserver,$DBname,$DBuser,$DBpassword);

  $query = "insert into log set " .
    "log_id        = NULL, " .
    "log_user      = \"" . $user   . "\", " .
    "log_source    = \"" . $source . "\", " .
    "log_detail    = \"" . $detail . "\"";

  $insert = mysql_query($query, $dbaconn) or die(mysql_error());

}

function check_userlevel($level) {

  $q_string = "select * from users where usr_name = \"" . $_SESSION['username'] . "\"";

  $q_login_level = mysql_query($q_string) or die(mysql_error());

  $a_login_level = mysql_fetch_row($q_login_level);

  if ($a_login_level[1] <= $level) {
    return(1);
  } else {
    return(0);
  }
}


//var count=30;

//var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

//function timer()
//{
//  count=count-1;
//  if (count <= 0)
//  {
//     clearInterval(counter);
     //counter ended, do something here
//     return;
//  }

  //Do code for showing the number of seconds here
//}

?>
