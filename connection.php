<?php
$connection = oci_connect('cleckshophub', 'root', '//localhost/xe');
if (!$connection) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
