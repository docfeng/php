<?php
$path= dirname(__FILE__);
$name = $_POST["name"];
$toname = $_POST["toname"];
if(!file_exists($name)){
	die();
}
/* if(!file_exists($toname)||!is_dir($toname)){
	mkdir($toname);
} */

/* if(!$toname){
	$toname=pathinfo($name,PATHINFO_FILENAME);
} */
$toname=pathinfo($name,PATHINFO_DIRNAME);
echo $toname;
$zip = new ZipArchive();
if ($zip->open($name, ZIPARCHIVE::CREATE) !== TRUE) {
	exit ('无法打开文件，或者文件创建失败');
}
$zip->extractTo($toname);
$zip->close(); 
?>