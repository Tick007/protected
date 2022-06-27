<?php
for($i=0; $i<30; $i++) {

$start = $i*1000;
if($i==0) $end=999;
else $end = ($i+1)*1000-1;

echo $start.' - '.$end.'<br>';
echo htmlspecialchars('<iframe width="100%" height="400" src="/transfer/Read1CXML?transfer=1&start='.$start.'&end='.$end.'"></iframe');
//echo '<iframe width="100%" height="400" src="/transfer/Read1CXML?transfer=1&start='.$start.'&end='.$end.'"></iframe';
echo '<br><br>';
}
?>