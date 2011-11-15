<?php

$today = new DateTime();

$firstDay = new DateTime($today->format("Y-m-") . "01 00:00:00");

$today->modify("+1 month");
$today = new DateTime($today->format("Y-m-") . "01 00:00:00");
$today->modify("-1 day");

$lastDay = new DateTime($today->format("Y-m-d") . " 00:00:00");

$today->modify("next saturday");
$saturday = new DateTime($today->format("Y-m-d") . " 23:59:59");

print $sunday->format("d/m/Y H:i:s");
print "\n";
print $saturday->format("d/m/Y H:i:s");
?>