<?php
$path= dirname(__FILE__);
$name = $_POST["name"];
$toname = $_POST["toname"];
if(file_exists($toname)){
	//删除压缩文件
	unlink($toname); 
}
if(!file_exists($name)){
	die();
}


function echodir($path){
  $files=array();
  $dir=opendir($path); 
  while(($filename=readdir($dir))!==false){
    if($filename!="." && $filename != ".." && substr($filename,0,1)!="."){
    //文件夹文件名字为'.'和‘..’，不要对他们进行操作
      if(is_dir($path."/".$filename)){
      // 如果读取的某个对象是文件夹，则递归
			$_files=echodir($path."/".$filename);
			$files=array_merge($files,$_files);
      }else{
		  $files[]= $path."/".$filename;
      }
    }
  }
    @closedir($path);
  return $files;
}

$zip = new ZipArchive();
if ($zip->open($toname, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
}

$zip->open($toname,ZipArchive::CREATE);   

if(is_dir($name)){
	$files=echodir($name);
	foreach($files as $file){
		$zip->addFile($file,substr($file, strlen($name) + 1));
	}
}else{
	$zip->addFile($name,basename($name));
}
echo $toname;
$zip->close();  //关闭压缩包
?>