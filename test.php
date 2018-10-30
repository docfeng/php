<?php
$name = $txt = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $txt = $_POST["txt"];
}

$myfile = fopen($name, "w") or die("Unable to open file!");
fwrite($myfile, $txt);
fclose($myfile);
echo "ture";
?>