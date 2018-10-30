<?php
$name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
}
$myfile = fopen($name, "r") or die("Unable to open file!");
echo fread($myfile,filesize($name)); 
fclose($myfile);
?>