<?php
class phpzip{
  public $zip;
  public function __construct() {
    /*$this->smtp_conn = 0;
    $this->zip = new ZipArchive();
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
    }*/
  }
  public function add($zip_name,$path){
    $zip = new ZipArchive();
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
    }
    if(is_array($path)){ 
      $this->addFiles($zip,$path);
    }else{
        if(is_dir($path)){  //如果文件夹
            $this->addDir($zip,$path);
        }else{
            $this->addFile($zip,$path);
        }
    }
    $zip->close();  //关闭压缩包
  }
  public function addFiles($zip,$files){
    foreach ($files as $file) {
        //向压缩包中添加文件
        $zip->addFile($file,basename($file));
        //向压缩包中添加中文名称文件
        /*$fileContent = file_get_contents($file);
        $file = iconv( 'utf-8','GBK', basename($file));
        $zip->addFromString($file, $fileContent);
        */
    }
  }
  function addDir($zip,$path,$relpath=false){
    $handler=opendir($path); //打开当前文件夹由$path指定。
    if(!$relpath)$relpath=$path;
    while(($filename=readdir($handler))!==false){
        if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
            if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                $this->addDir($zip,$path."/".$filename,$relpath);
            }else{ //将文件加入zip对象
                $new_name=str_replace($relpath."/","",$path."/".$filename);
                $zip->addFile($path."/".$filename,$new_name);
            }
        }
    }
    @closedir($path);
  }
  public function addFile($zip,$file_name,$new_name=""){
     if(!file_exists($file_name)){ 
      return 0;
    }
    if(!$new_name){$new_name=basename($file_name);}
    $zip->addFile($file_name,$new_name);
  }
  public function del($zip_name,$file_name){
     if(!file_exists($file_name)){ 
      return 0;
    }
    $zip = new ZipArchive();
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
    }
    $zip->deleteName($file_name);
    $zip->close();
  }
  public function rename($zip_name,$from_file_name,$to_file_name){
     if(!file_exists($zip_name)){ 
      return 0;
    }
    $zip = new ZipArchive();
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
    }
    echo $zip->renameName($from_file_name,$to_file_name);
    $zip->close();
  }
  public function unZip($zip_name,$dir_name=""){
     if(!file_exists($zip_name)){ 
      return 0;
    }
    if(!$dir_name){
        $dir_name=pathinfo($zip_name,PATHINFO_FILENAME);
    }
    $zip = new ZipArchive();
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
    }
    $zip->extractTo($dir_name);
    $zip->close(); 
  }
  public function getList($zip_name){
     if(!file_exists($zip_name)){ 
      return 0;
    }
    $zip = new ZipArchive();
    if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
     exit ('无法打开文件，或者文件创建失败');
    }
    $file_dir_list = array();
    $file_list = array();
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $numfiles = $zip->getNameIndex($i);
        if (preg_match('/\/$/i', $numfiles)){
          $file_dir_list[] = $numfiles;
        }else{
          $file_list[] = $numfiles;
        }
    }
    $zip->close();
    return array('files'=>$file_list, 'dirs'=>$file_dir_list);
  }
  
}
?>