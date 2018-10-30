<?php
echo "wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww";
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  if($_POST["url"]){
    $string=get_html($_POST["url"]);
    $string=mb_convert_encoding($string,'UTF-8','GBK');
    echo "<textarea width=100% height=350px>".$string."</textarea>";
  }

}

function get_html($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HEADER,0);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
//$p='/<div id="kui-page-read-txt".*?>(.*?)<\/div>/';
//preg_match($p,$string,$tea);
//foreach ($tea as $value) { echo "$value <br>"; }
?>