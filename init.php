<?php
$path= dirname(__FILE__);
$filename = ".temp" . date('Y-m-d') . '.zip';
if(file_exists($filename)){
	//删除压缩文件
	unlink($filename); 
}
$zip = new ZipArchive();
if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
}
$zip->open($filename,ZipArchive::CREATE);   

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
$files=echodir($path);
foreach($files as $file){
	$zip->addFile($file,substr($file, strlen($path) + 1));
}
$zip->close();  //关闭压缩包

//下载模块
  header('Content-Type: application/zip;charset=utf8');
  header('Content-disposition: attachment; filename=' . $filename);
  header('Content-Length: '. filesize($filename));
  readfile($filename);
?>