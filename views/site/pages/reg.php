<?php

//$pattern= '%<a([^\n^\r.]*?)>(.*?)</a>%is';
$pattern= '|<a[^>]+>(.+?)</a>|';
$context = '<a href="http://www.armchairracer.com.au/">Home</a>&nbsp;::&nbsp; <a href="http://www.armchairracer.com.au/index.php?main_page=index&amp;cPath=25923&amp;zenid=l9q3mp3tsbnhlh5miup9l36bt6">CARS</a>&nbsp;::&nbsp; <a href="http://www.armchairracer.com.au/index.php?main_page=index&amp;cPath=25923_25957&amp;zenid=l9q3mp3tsbnhlh5miup9l36bt6">NINCO</a>&nbsp;::&nbsp; NINCO 55073 - Ford GT DHL #9 ';






preg_match_all($pattern, $context, $matches);
echo 'matches = ';
print_r($matches);



?>