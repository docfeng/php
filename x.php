<?php
//echo "no err";
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  switch ($_POST["id"]) 
  {
    case 1: 
      $string=get_html($_POST["url"]);
      //if(strpos($string,"gbk")){}
      //$string=mb_convert_encoding($string,'UTF-8','GBK');
      echo "<textarea style='width:100%;height:350px'>{$string}</textarea>"; 
      break;
    case 2: 
      $string=get_html($_POST["url"]);
      //$string=mb_convert_encoding($string,'UTF-8','GBK');
      //$p='/<div class="kui-item kui-left">[\s\S]*?href="(.*?)".*?title="(.*?)"[\s\S]*?div>/';
      $p='/<a[^<]*?href="(.*?)".*?>(第.*?)<\/a>/';
      preg_match_all($p,$string,$m); 
      $string=array_map("myfunction",$m[1],$m[2]);
      echo implode("<br/>",$string);
      //print_r($m[1]);
      //$php_json = json_encode($m);
      //preg_replace
      break; 
    case 3: 
      echo "Number 3"; 
      break; 
    case 5:
      $string="";//get_html($_POST["url"]);
      //$string=mb_convert_encoding($string,'UTF-8','GBK');
      echo "<textarea style='width:100%;height:350px'>$string</textarea>"; 
      include "i_xs.html";
      break;
    default: 
      echo "No number between 1 and 3"; 
    }
  /*if($_POST["url"]){
    $string=get_html($_POST["url"]);
    $string=mb_convert_encoding($string,'UTF-8','GBK');
    echo "<textarea style='width:100%;height:350px'>$string</textarea>";
    
  }*/
}
else { 
  switch ($_GET["id"]) 
  {
    case 5://文章
      echo $_GET["url"];
      $string=get_html($_GET["url"]);
      if($_GET["e"]=="g"||strstr($string,"gbk")){
        $string=mb_convert_encoding($string,'UTF-8','GBK');
      }
      $p='/([^a-zA-Z>]+?)<br.*?>/';
      preg_match_all($p,$string,$m); 
      //匹配下一章
      $p1='/<a[^<]*?href=[\'"](.*?)[\'"].*?>(下.*?)<\/a>/';
      preg_match($p1,$string,$m1); 
      //匹配url
      preg_match("/^(http:\/\/[^\/]+).+\//i",$_GET["url"],$s);
      if(substr($m1[1],0,1)=="/"){$s[0]=$s[1];}
      echo "<a href='x.php?id=5&url={$s[0]}{$m1[1]}'>{$m1[2]}</a>";
      $string=$m[1];
      //echo $m[1][1];
      $string=implode("\n",$string);
      include "i_xs.html";
       
      break;
      case 6://文章源
      $string=get_html($_GET["url"]);
      if($_GET["e"]=="g"||strstr($string,"gbk")||strstr($string,"gb2312")){
        $string=mb_convert_encoding($string,'UTF-8','GBK');
      }
      echo "<textarea style='width:100%;height:350px'>$string</textarea>"; 
      break;
    default: 
      echo "default"; 
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
function myfunction($s1,$s2)
{
    preg_match("/^(h?.+\/)/i",$_POST["url"],$string);
   return "<a href='x.php?id=5&url={$string[0]}{$s1}'>{$s2}</a>";
}

?>