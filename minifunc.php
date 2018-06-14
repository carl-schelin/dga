<?php

# the login check.php script set this but when using the minifunc.php script, 
# it doesn't call the check.php login script so the timezone isn't set.
date_default_timezone_set('UTC');

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

?>
