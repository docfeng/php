<?php
$str='<p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; line-height: 200%;"><img border="0" src="upfiles/2009/07/1246430143_4.jpg" alt=""/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; line-height: 200%;"><img border="0" src="upfiles/2009/07/1246430143_3.jpg" alt=""/></p><p style="padding: 0px; margin-top: 0px; margin-bottom: 0px; line-height: 200%;"><img border="0" src="upfiles/2009/07/1246430143_1.jpg" alt=""/></p>';
 
/*$p='/<img.*?src="(.*?)".*?>+?/'; */
$p="/<[img|IMG].*?src=['|\"](.*?(?:[.gif|.jpg]))['|\"].*?[\/]?>/"; 
preg_match_all($p,$str,$m); 
print_r($m);
foreach ($m as $value) { 
  echo "rt $value tr<br>"; 
}
echo "aaa";
?>