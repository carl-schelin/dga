<?php

// Get the User Agent
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

// Apple detection array
$Apple = array();
$Apple['UA'] = $ua;
$Apple['Device'] = false;
$Apple['Types'] = array('iPhone','iPod','iPad');
foreach ($Apple['Types'] as $d => $t) {
  $Apple[$t] = (stripos($Apple['UA'], $t) !== false);
  $Apple['Device'] |= $Apple[$t];
}

// is this an apple device?
  if ($Apple['Device']) {
    print "  @import \"" . $Siteroot . "/apple.css\";\n";
  } else {
// is this an android device?
    if (stripos($ua,'android') !== false) {
      print "  @import \"" . $Siteroot . "/android.css\";\n";
    } else {
      print "  @import \"" . $Siteroot . "/site.css\";\n";
    }
  }
  print "  @import \"" . $Siteroot . "/menu.css\";\n";
?>
