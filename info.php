<?php

echo 'Pic : ' . number_format(memory_get_peak_usage(), 0, '.', ',') . " octets\n";
echo 'Usage : ' . number_format(memory_get_usage(), 0, '.', ',') . " octets\n";

    
phpinfo();

?>
