<?php
header("Access-Control-Allow-Origin:*");
$path= dirname(__FILE__);
$dir="";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if(@$_GET["name"]){
    $dir=$_GET["name"];
  }
}
//$files=getDir($dir);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$order=$_POST["order"];
	$name = $_POST["name"];
	switch($order){
		case "getDir":
			echo json_encode(getDir($name));
			break;
		case "createDir":
		    return mkdir($name);
		    break;
	    case "deleteDir":
	        return dir_delete($name);
	        break;
	    case "getFile":
	        return getFile($name);
	        break;
	    case "writeFile":
	        $txt = $_POST["txt"]||"";
	        return writeFile($name,$txt);
	        break;
	    case "deleteFile":
	        return unlink($name);
	        break;
	    case "uploadFile":
	        return uploadFile($name);
	        break;
		case "rename":
			$toname = $_POST["toname"];
			$toname=getFileName($toname);
			rename($name,$toname);
			echo $toname;
		    break;	
		case "move":
		    $toname = $_POST["toname"];
			$toname=getFileName($toname);
			rename($name,$toname);
			echo $toname;
		    break;
		case "copy":
		    $toname = $_POST["toname"];
			$toname=getFileName($toname);
			if(is_dir($name)){
				dir_copy($name,$toname);
			}else{
				copy($name,$toname);
			}
			echo $toname;
		    break;
		case "enZip":
		    $toname = $_POST["toname"];
			$toname=getFileName($toname);
			if(is_dir($name)){
				dir_copy($name,$toname);
			}else{
				copy($name,$toname);
			}
			echo $toname;
		    break;
		case "unZip":
		    $toname = $_POST["toname"];
			$toname=getFileName($toname);
			if(is_dir($name)){
				dir_copy($name,$toname);
			}else{
				copy($name,$toname);
			}
			echo $toname;
		    break;
		
	}
	
    die();
}else{
    @$order=$_GET["order"];
    @$name = $_GET["name"];
    if($order){
      switch($order){
        case "getDir":
            echo json_encode(getDir($name));
            break;
        case "createDir":
            echo mkdir($name);
            break;
        case "deleteDir":
            echo rmdir($name);
            break;
        case "getFile":
            return getFile($name);
            break;
        case "writeFile":
            $txt = $_POST["txt"];
            return writeFile($name,$txt);
            break;
        case "deleteFile":
            return unlink($name);
            break;
        case "downloadFile":
            return downloadFile($name);
            break;
        case "downloadFile":
            return downloadFile($name);
            break;
      }
      die();
    }
}

    function getDir($name){
        $path= dirname(__FILE__);
        if($name!=""){
            $path=$path."/".$name;
            $name=$name."/";
        }
        $files=array();
        $files["dir"]=array();
        $files["file"]=array();
        $dir=opendir($path);
        while(($filename=readdir($dir))!==false){
            if($filename!="." && $filename != ".."){
                //文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){
                    // 如果读取的某个对象是文件夹，则递归
                    $files["dir"][]= $name.$filename;
                }else{
					$files["file"][]= $name.$filename;
                }
            }
        }
        @closedir($path);
        return $files;
    }

    function getDirAll($name){
        $path= dirname(__FILE__);
        if($name!=""){
            $path=$path."/".$name;
            $name=$name."/";
        }
        $files=array();
        $dir=opendir($path); 
        while(($filename=readdir($dir))!==false){
            if($filename!="." && $filename != ".."){
                //文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){
                    // 如果读取的某个对象是文件夹，则递归
                    $files[]= $name.$filename;
                    $_files=getDivAll($name.$filename);
                    $files=array_merge($files,$_files);
                }else{
                        $files[]= $name.$filename;
                }
            }
        }
        @closedir($path);
        return $files;
    }
    function deleteFile($name){
        return unlink($name);
    }
    function getFile($name){
          $myfile = fopen($name, "r") or die("Unable to open file!");
          echo fread($myfile,filesize($name)); 
          fclose($myfile);
    }
    function writeFile($name,$txt){
          $myfile = fopen($name, "w") or die("Unable to open file!");
          fwrite($myfile, $txt);
          fclose($myfile);
          echo "ture";
    }
    function downloadFile($name){
        header('Content-Type: application/zip;charset=utf8');
        header('Content-disposition: attachment; filename='.$name);//.date('Y-m-d') 
        header('Content-Length: ' . filesize($name));
        readfile($name);
    }
    function uploadFile($name){
        if(!$name){
            $name=$_FILES["file"]["name"];
        }
        if ($_FILES["file"]["error"] > 0){
            echo "Error: " . $_FILES["file"]["error"] . "<br />";
        }else{
            echo "Upload: " . $_FILES["file"]["name"] . ":--:".$name."<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Stored in: " . $_FILES["file"]["tmp_name"]."<br />";
            if (file_exists($name)){
                echo "file_exists:".$name. " already exists. "."<br />";
            }else{
                move_uploaded_file($_FILES["file"]["tmp_name"],$name);
                echo "Stored in: " . $name;
            }
        }
    }
	function getFileName($file){
		$i=1;
		$dirname=pathinfo($file,PATHINFO_DIRNAME);
		$extension=pathinfo($file,PATHINFO_EXTENSION);
		$name=pathinfo($file,PATHINFO_FILENAME);
		$path=$file;
		while(file_exists($path)){
			$path=$dirname."/".$name."(".$i.").".$extension;
			$i++;
		}
		return $path;
	}
/**
 * 文件夹删除
 *
 * @param string $path 文件夹
 * @return bool
 */
function dir_delete($path = ''){
    if (empty($path)){
        return false;
    }
 
    $dir = opendir($path);
    while (false !== ($file = readdir($dir))){
        if (($file != '.') && ($file != '..')){
            if (is_dir($path . '/' . $file)){
                dir_delete($path . '/' . $file);
            }else{
                unlink($path . '/' . $file);
            }
        }
    }
    closedir($dir);
	rmdir($path);
    return true;
}
/**
 * 文件夹文件拷贝
 *
 * @param string $src 来源文件夹
 * @param string $dst 目的地文件夹
 * @return bool
 */
function dir_copy($src = '', $dst = '')
{
    if (empty($src) || empty($dst))
    {
        return false;
    }
 
    $dir = opendir($src);
    dir_mkdir($dst);
    while (false !== ($file = readdir($dir)))
    {
        if (($file != '.') && ($file != '..'))
        {
            if (is_dir($src . '/' . $file))
            {
                dir_copy($src . '/' . $file, $dst . '/' . $file);
            }
            else
            {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
 
    return true;
}
 
/**
 * 创建文件夹
 *
 * @param string $path 文件夹路径
 * @param int $mode 访问权限
 * @param bool $recursive 是否递归创建
 * @return bool
 */
function dir_mkdir($path = '', $mode = 0777, $recursive = true)
{
    clearstatcache();
    if (!is_dir($path))
    {
        mkdir($path, $mode, $recursive);
        return chmod($path, $mode);
    }
 
    return true;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="apple-mobile-web-app-title" content="github文件读写" />
<META HTTP-EQUIV="pragma" CONTENT="no-cache" /> 
<meta name="apple-mobile-web-app-capable" content="yes" /> 
<meta name="apple-touch-fullscreen" content="yes" />
<meta http-equiv="Content-Type" content="application/xhtml+xml;charset=utf-8" />
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate"> 
<META HTTP-EQUIV="expires" CONTENT="0">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<title>文件管理系统</title>
<meta name="description" content="版本号1.0" />
<script src="http://git.docfeng.top/script/http.js?2"></script>
<script src="http://git.docfeng.top/script/base.js"></script>
<style type="text/css">
*{
    margin:0px;
    padding:0px;
}
html,body{
  width:100%;
  height:100%;
  overflow-y:hidden;
}
body{
  //font-size:2em; 
  //line-height:24px;
}
dialog{
    height:100%;width:100%;background-color:green;
}
.contain{
    display: flex;
    height:100%;width:100%;
    flex-direction: column;
}
.contain>header{
  flex-basis:30px;width:100%;background-color:green;
}
.contain>.main{
    flex-grow:1;width:100%;background-color:white;
    overflow-y:scroll;
    //padding-top: 20px;
    //padding-bottom: 20px;
}
.contain>footer{
    flex-basis:30px;width:100%;background-color:red;
}
.dir,.file{
  width:100%;
  background-color:gray;
}
.dir tr>td:last-child,.file tr>td:last-child{ 
    width:50px;
}
.dir span,.file span{
    text-align:center;
    display:block;
    float:left;
    width:25px;
}
.dir tr{
  width:100%;
  list-style: none;
  border:1px solid black;
  background-color:yellow;
}
.file tr{
  width:100%;
  list-style: none;
  border:1px solid black;
  background-color:palegreen;//greenyellow;
}


dialog{
    height:100%;width:100%;background-color:green;
}
.dialog{
    display: flex;
    height:100%;width:100%;
    flex-direction: column;
}
.dialog>header{
  flex-basis:20px;width:100%;background-color:green;
}
.dialog>footer{
    flex-basis:20px;width:100%;background-color:red;
}
.dialog>section{
    flex-grow:100;width:100%;background-color:white;
    //padding-top: 20px;
    //padding-bottom: 20px;
}
#file_txt{
  width:100%;
  height:400px;
}
.contentMenu{
	width: 100px;
	height: auto;
	border: solid 1px darkgray;
	display: none;
	position: fixed;
	left: 0px;
	top: 0px;
	background-color: gainsboro;
}
.contentMenu>ul{
	list-style-type: none;
	text-align: center;
	line-height: 24px;
}
.contentMenu>ul>li,.subMenu>ul>li{
	border: solid 1px darkgray;
}
.contentMenu>ul>li:hover{
	background-color: darkgrey;
}
.subMenu{
	width: 100px;
	height: auto;
	border: 1px solid darkgray;
	position: absolute;
	display: none;
	background-color: gainsboro;
}
.subMenu>span{
	width: 100%;
}
.subMenu>ul{
	width: 100px;
	position: absolute;
	background-color: gainsboro;
	top:-24px;
	left: 98%;;
	list-style-type: none;
	text-align: center;
	line-height: 24px;
}
.contentMenu span:before {
	content: "+";
}
.subMenu>ul>li:hover{
	background-color: darkgrey;
}
.contentMenu li:hover > div{
	display: block;
}
</style>
</head>
<body>
<div class="contain">
    <header>
        <input type="button" value='reload' onclick='location.reload()'/>
        <input type="button" value='createDir' onclick='let name=prompt("文件夹名称","");let path=Data.path;name=path?path+"/"+name:name;fso.createDir(name);Data.dir.push(name);APP.showDir();'/>
        <input type="button" value="deleteDir" onclick="fso.deleteDir(Data.path);"/>
        <input type="button" value="createFile" onclick='let name=prompt("文件名称","");let path=Data.path;name=path?path+"/"+name:name;fso.writeFile(name,"");Data.file.push(name);APP.showFile();'/>
        <input type="button" value="upload" onclick="msg.uploadFile();"/>
        <br />
		<span id="pathUl"></span>
		<span id="nameUl"></span>
    </header>
    <div class="main">
		<table id="dirUl" class="dir" ondblclick="msg.dir(event.srcElement.innerHTML)" onclick="fso.dirclick(event)"></table>
		<table id="fileUl" class="file" ondblclick="msg.file(event.srcElement.innerHTML)" onclick="fso.fileclick(event)"></table>
    </div>
    <footer>
    </footer>
</div>

<dialog id="dialog_dir" onclick="if(this.open){this.close()}">
    <div class="dialog">
    <header>文件夹操作</header>
    <section  onclick="event.stopPropagation();">
        <label for="dir_name">名称:</label>
        <input type="text" id="dir_name" /><br/>
        <input type="button" value="getDir" onclick="fso.getDir(dir_name.value)"/>
        <input type="button" value="createDir" onclick="fso.createDir(dir_name.value)"/>
        <input type="button" value="deleteDir" onclick="fso.deleteDir(dir_name.value)"/>
    </section>
    <footer>
        <menu>
            <button type="button" onclick="">取消</button>
        </menu>
    </footer>
    </div>
</dialog>
<dialog id="dialog_file" onclick="if(this.open){this.close()}">
    <div class="dialog">
    <header>文件操作</header>
    <section  onclick="event.stopPropagation();">
        <input type="button" value="getFile" onclick="fso.getFile(file_name.value)"/>
        <input type="button" value="writeFile" onclick="fso.writeFile(file_name.value,file_txt.value)"/>
        <input type="button" value="deleteFile" onclick="fso.deleteFile(file_name.value)"/>
        <input type="button" value="open" onclick="window.open(file_name.value)"/>
        <input type="button" value="editor" onclick="window.open('editor.php#path='+file_name.value)"/>
        <input type="button" value="downloadFile" onclick="fso.downloadFile(file_name.value)"/>
        <br />
        <label>name:</label>
        <input type="text" id="file_name" /><br/>
        <textarea id="file_txt"></textarea>
    </section>
    <footer>
        <menu>
            <button type="button">取消</button>
        </menu>
    </footer>
    </div>
</dialog>
<dialog id="dialog_uploadFile" onclick="if(this.open){this.close()}">
    <div class="dialog">
    <header>文件上传</header>
    <section  onclick="event.stopPropagation();">
        <form action="" method="post" enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="file" id="file" /> 
            <input type="text" name="order" id="order" value="uploadFile" /> 
            <input type="text" name="name" id="filename" value="" /> 
            
            <br />
            <input type="submit" name="submit" value="Submit" />
        </form>
    </section>
    <footer>
        <menu>
            <button type="button">取消</button>
        </menu>
    </footer>
    </div>
</dialog>
<div class="contentMenu">
	<ul>
		<li>刷新</li>
		<li>复制</li>
		<li>剪切</li>
		<li>粘贴</li>
		<li >
			<span>zip</span>
			<div class="subMenu">
				<ul>
					<li>压缩</li>
					<li>
						<span>zip</span>
						<div class="subMenu">
							<ul>
								<li>解压当前目录</li>
								<li>解压子目录</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</li>
		<li>
			<span>新建</span>
			<div class="subMenu">
				<ul>
					<li>文件夹</li>
					<li>文件</li>
				</ul>
			</div>
		</li>
		<li>删除</li>
		<li>重命名</li>
		<li>下载</li>
	</ul>
</div>
<script>
	Data={
		"path":""
	}
	window.addEventListener("load",function(){
		APP.ini();
	},false);
	window.addEventListener("hashchange",function(){
		APP.ini();
	},false);


APP={
	ini:function(){
		var t=this;
		Data.path=hash()["path"]||"";
		fso.getDir(Data.path).then(function(json){
			Object.assign(window.Data,json);
			t.showDir();
			t.showFile();
			t.showPath();
		});
	},
	
	showDir:function(){
		var dirs=Data.dir;
		var dirHtml=""
		for(var i=0;i<dirs.length;i++){
		  dirHtml+="<tr><td>"+dirs[i]+"</td><td><span>X</span><span>e</span></td></tr>";
		}
		dirUl.innerHTML=dirHtml;
	},
	showFile:function(){
		var files=Data.file;
		var fileHtml=""
		for(var i=0;i<files.length;i++){
		    fileHtml+="<tr><td>"+files[i]+"</td><td><span>X</span><span>e</span></td></tr>";
		}
		fileUl.innerHTML=fileHtml;
	},
	showPath:function(){
		var pathArr=Data.path.split("/");
		var pathHtml="";
		for(var i=0;i<pathArr.length;i++){
		    pathHtml+="<button>"+pathArr[i]+"</button>";
		}
		pathUl.innerHTML=pathHtml;
	},
	createDir:function(){
		
	},
	createFile:function(){
		
	},
}

    fso={
        async getDir(name){
            var re=await http.post("","order=getDir&name="+name);
            return JSON.parse(re);
        },
        async createDir(name){
            if(!confirm("是否创建文件夹:"+name)){return false;}
            var re=await http.post("","order=createDir&name="+name);
            alert(re);
        },
        async deleteDir(name){
            if(name==""){alert("不允许删除根目录");return false;}
            if(confirm("是否删除文件夹:"+name)){
              var re = await http.post("","order=deleteDir&name="+name);
              alert(re);
			  if(name==Data.path){
				  window.history.go(-1);
			  }
            }
        },
        
        async getFile(name){
            var re=await http.post("","order=getFile&name="+name);
            file_txt.value=re;
        },
		async rename(name,toname){
		    var re=await http.post("","order=rename&name="+name+"&toname="+toname);
		    file_txt.value=re;
			return re;
		},
		async move(name,toname){
		    var re=await http.post("","order=move&name="+name+"&toname="+toname);
		    file_txt.value=re;
			return re;
		},
		async copy(name,toname){
		    var re=await http.post("","order=copy&name="+name+"&toname="+toname);
		    file_txt.value=re;
			return re;
		},
        async deleteFile(name){
            if(!confirm("是否删除文件:"+name)){return false;}
            var re=await http.post("","order=deleteFile&name="+name);
            alert(re);
        },
        async writeFile(name,txt){
            if(!confirm("是否写入文件:"+name)){return false;}
            var txt=encodeURIComponent(txt);
            var re=await http.post("","order=writeFile&name="+name+"&txt="+txt);
            alert(re);
        },
        async downloadFile(name){
           window.open("?order=downloadFile&name="+name);
        },
        dirclick:function(event){
          var src=event.srcElement;
          var obj=src.parentNode.parentNode;
            if("tr"==obj.tagName.toLowerCase()){
              var index=obj.rowIndex;
              if("span"==src.tagName.toLowerCase()){
                var h=src.innerHTML;
                if("X"==h){
                  var name=Data.dir[index]
                  this.deleteDir(name);
                }
                if("e"==h){
                  var name=Data.dir[index]
                  this.edit(name);
                }
              }
          }
      },
		fileclick:function(event){
			var src=event.srcElement;
			var obj=src.parentNode.parentNode;
			  if("tr"==obj.tagName.toLowerCase()){
			    var index=obj.rowIndex;
			    if("span"==src.tagName.toLowerCase()){
			      var h=src.innerHTML;
			      if("X"==h){
			        var name=Data.file[index]
			        this.deleteFile(name);
			      }
			      if("e"==h){
								var name=Data.file[index]
			        this.edit(name);
			      }
			    }
			}
		},
		edit:function(name){
			alert(name)
		},
		async enZip(name,toname){
		    var re=await http.post("enzip.php","order=enZip&name="+name+"&toname="+toname);
		    return re;
		},
		async unZip(name,toname){
		   var re=await http.post("unzip.php","order=unZip&name="+name+"&toname="+toname);
		   return re;
		},
    }
    msg={
        dir(name){
            //dialog_dir.showModal();
            //dialog_dir.querySelector("#dir_name").value=name;
            location.hash="path="+name;
        },
        file(name){
			dialog_file.showModal();
			dialog_file.querySelector("#file_name").value=name;
        },
		uploadFile(){
			dialog_uploadFile.showModal();
		}
    }
//监听回退按钮
window.addEventListener("load",function(){
	if (window.history && window.history.pushState) {
		window.addEventListener('popstate',function(e) {
			var state = e.state;
			//history.pushState(null, null, document.URL);
			var hash = window.location.hash;
			if (hash === '') {
				window.history.pushState('forward', null, '');
				
			};
		},false);
		if(!window.history.state){
			window.history.replaceState('onback', null, '');
			window.history.pushState('onback', null, '');
			
		}
		/* setTimeout(function(){
			var event = new MouseEvent('dblclick',{
    'view': window,
    'bubbles': true,
    'cancelable': true
  });
			var r=dirUl.rows[1]
			alert(r.innerHTML)
			r.dispatchEvent(event);
		},200) */
		
	}
},false);

var div=document.querySelector('.contentMenu');
var showContentMenu=function(e){
    e.preventDefault();
    div.style.display='block';
	var src=e.srcElement;
	var index;
	var table;
	var type;
	var name;
	if(src.tagName.toLowerCase()=="td"){
		index=src.parentNode.rowIndex;
		table=src.parentNode.parentNode.parentNode;
	}
	if(src.tagName.toLowerCase()=="span"){
		index=src.parentNode.parentNode.rowIndex;
		table=src.parentNode.parentNode.parentNode.parentNode;
	}
	if(table&&table.id=="dirUl"){
		name=Data.dir[index];
		type="dir"
	}
	if(table&&table.id=="fileUl"){
		name=Data.file[index];
		type="file";
	}
	div.dataset.type=type;
	div.dataset.name=name;
	div.dataset.index=index;
	
	nameUl.innerHTML=name;
	
    var x=document.body.clientWidth-e.clientX
    var y=document.body.clientHeight-e.clientY
	var style=window.getComputedStyle(div,null);
	var width=parseInt(style.width);
	var height=parseInt(style.height);
    div.style.left=x>width?((e.clientX+10)+'px'):((e.clientX-width-10)+'px');
    div.style.top=y>height?((e.clientY+10)+'px'):((e.clientY-height-10)+'px');
}
document.addEventListener("long",showContentMenu,true);
document.addEventListener("contextmenu",showContentMenu,true);
div.addEventListener('click',function(){
	 var ele=event.srcElement;
	 var tag=event.currentTarget;
	 var name=tag.dataset.name;
	 var type=tag.dataset.type;
	 var index=tag.dataset.index;
	 if(ele.tagName.toLowerCase()=="li"){
		 switch(ele.innerHTML){
			case "刷新":
				alert(name)
				break;
			case "复制":
				div.dataset.cmd="copy";
				div.dataset.cmd_name=name;
				div.dataset.cmd_index=index;
				tag.dataset.cmd_type=type;
				break;
			case "剪切":
				div.dataset.cmd="move";
				div.dataset.cmd_name=name;
				div.dataset.cmd_index=index;
				tag.dataset.cmd_type=type;
				break;
			case "粘贴":
				var cmd=div.dataset.cmd;
				var name=div.dataset.cmd_name;
				var index=div.dataset.cmd_index;
				var type=tag.dataset.cmd_type;
				var toname=name.split("/").pop();
				var path=Data.path;
				toname=path?path+"/"+toname:toname;
				var p;
				if(cmd=="copy"){
					p=fso.copy(name,toname)
				}
				if(cmd=="move"){
					p=fso.move(name,toname)
				}
				p.then(function(name){
					if(type=="dir"){
						Data.dir.push(name);
						APP.showDir()
					}
					if(type=="file"){
						Data.file.push(name);
						APP.showFile();
					}
				})
				break;
			case "文件夹":
				var toname=prompt("创建文件夹：",);
				if(toname){
					var path=Data.path;
					toname=path?path+"/"+toname:toname;
					fso.createDir(toname);
					Data.dir.push(toname);
					APP.showDir();
				}
				break;
			case "文件":
				var toname=prompt("创建文件：",);
				if(toname){
					var path=Data.path;
					toname=path?path+"/"+toname:toname;
					fso.writeFile(toname,"");
					Data.file.push(toname);
					APP.showFile();
				}
				break;
			case "删除":
				if(type=="dir"){
					fso.deleteDir(name);
					Data.dir.splice(index,1);
					APP.showDir()
				}
				if(type=="file"){
					fso.deleteFile(name);
					Data.file.splice(index,1);
					APP.showFile();
				}
				break;
			case "重命名":
				var toname=prompt("重命名："+name,name);
				if(toname){
					fso.rename(name,toname);
				}
				break;
			case "下载":
				window.open("filedownload.php?name="+name);
				break;
			case "压缩":
				var toname=name+".zip";
				fso.enZip(name,toname).then(function(path){
					APP.ini();
				});
				break;
			case "解压当前目录":
				var toname="";
				fso.unZip(name,toname).then(function(path){
					APP.ini();
				});
				break;
			case "解压子目录":
				var toname=name.replace(".zip","");
				fso.unZip(name,toname).then(function(path){
					APP.ini();
				});
				break;
		 }
		 div.style.display='none';
	 }
})		
		

var obj={
    handleEvent:function(e){
          //alert(event.type)
          var a=this[event.type]
          //alert(a);
          a&&a(e)
    },
    touchstart:function(e){
        var x=e.touches[0].pageX;
        var y=e.touches[0].pageY;
        this.a=setTimeout(function(a){
                var event = document.createEvent("MouseEvents");
                // initEvent接受3个参数：
                // 事件类型，是否冒泡，是否阻止浏览器的默认行为
                event.initMouseEvent("long", true, false,window,1,e.screenX, e.screenY, x, y, e.ctrlKey, e.altKey, e.shiftKey, e.metaKey, e.button, e.relatedTarget);
                //event.clientX=e.clientX;
                //event.clientY=e.clientY;
                //event.eventType = 'message';
                //触发document上绑定的自定义事件
                e.srcElement.dispatchEvent(event);
        },300);
    },
    touchmove:function(){
        clearTimeout(this.a)
    },
    touchend:function(){
        clearTimeout(this.a)
    },
    touchcancel:function(a){
        clearTimeout(this.a)
    }
}
document.addEventListener("touchstart",obj);
document.addEventListener("touchmove",obj);
document.addEventListener("touchend",obj);
document.addEventListener("touchcancel",obj)

</script>
</body>
</html>