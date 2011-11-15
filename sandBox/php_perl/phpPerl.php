<?php 

$perl = new Perl();



$perl->eval('use Digest::MD5 qw(md5_hex);');
var_dump($perl->md5_hex("Hello"));


?>