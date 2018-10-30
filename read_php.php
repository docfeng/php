<?php
// 定义变量并设置为空值
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $txt = $_POST["txt"];
 // $website = test_input($_POST["website"]);
 // $comment = test_input($_POST["comment"]);
  //$gender = test_input($_POST["gender"]);
}
echo $name."<br/>".$txt;

$myfile = fopen($name, "r") or die("Unable to open file!");
echo fread($myfile,filesize($name)); 
fclose($myfile);
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>