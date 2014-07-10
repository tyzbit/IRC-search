<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 2);
echo '<div id="gentime">results generated in '.$total_time.' seconds.</div>';

?>