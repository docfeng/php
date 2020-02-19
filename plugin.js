var read = function(path, CharSet) {
	var CharSet = CharSet || "utf-8";
	var str = "";
	var stm = new ActiveXObject("adodb.stream")
	stm.Type = 2; //以本模式读取
	stm.mode = 3;
	stm.charset = CharSet;
	stm.open();
	stm.loadfromfile(path);
	str = stm.readtext();
	stm.Close();
	stm = null;
	return str;
}
/** 
 * 函数名称：WriteToTextFile
 * 作用：利用Adodb.Stream对象来写入UTF-8编码的文件
 * 示例：WriteToTextFile("File/FileName.htm",Content,UTF-8)
 * @param {Object} FileUrl
 * @param {Object} Str
 * @param {Object} CharSet
 */
var write = function(FileUrl, txt, CharSet) {
	var CharSet = CharSet || "utf-8"
	var stm = new ActiveXObject("adodb.stream")
	stm.Type = 2 //以本模式读取
	stm.mode = 3
	stm.charset = CharSet
	stm.open()
	stm.WriteText(txt)
	stm.SaveToFile(FileUrl, 2)
	stm.flush
	stm.Close
	stm = null
}

var alert = function(a) {
	WScript.Echo(a);
}

var get = function(url) {
	var xmlHttp = null;
	try { // Firefox, Opera 8.0+, Safari 
		xmlHttp = new XMLHttpRequest();
	} catch (e) { // Internet Explorer 
		try {
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	if (xmlHttp == null) {
		alert("您的浏览器不支持AJAX!");
		return;
	}
	xmlHttp.open("get", url, false);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.send();
	return xmlHttp.responseText;
}
var post = function(url,data) {
	var xmlHttp = null;
	try { // Firefox, Opera 8.0+, Safari 
		xmlHttp = new XMLHttpRequest();
	} catch (e) { // Internet Explorer 
		try {
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	if (xmlHttp == null) {
		alert("您的浏览器不支持AJAX!");
		return;
	}
	xmlHttp.open("post", url, false);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.send(data);
	return xmlHttp.responseText;
}

var args = WScript.Arguments;
/* WScript.Echo(args(0));
WScript.Echo(args(1)); */
try {
	var fso = new ActiveXObject("Scripting.FileSystemObject");
	var list = [];
	var filePath = args(0);
	var projectPath = args(1);
	var cmd = args(2);
	var file = fso.GetFile(filePath);
	var fileName = file.name;
	var forder = file.ParentFolder;
	var forderPath = forder.path;
	var forderName = forder.name;
	switch(cmd){
		case "get":
			var json=read(projectPath+"/ini.json");
			json=eval("("+json+")")
			var url=json.url;
			var txt=post(url,"name="+fileName)
			write(filePath,txt)
			alert("true")
			break;
		case "post":
			var txt=read(filePath);
			var json=read(projectPath+"/ini.json");
			json=eval("("+json+")")
			var url=json.url;
			var txt=post(url,"name="+fileName+"&txt="+encodeURIComponent(txt))
			alert("true")
			break;
	}
	/* var txt=read(filePath)
	WScript.Echo(txt);
	 */
	/* if (forderPath == projectPath) {
		alert(222)
	}
	var txt=get("http://localhost/")
	alert(txt) */
	/* var file = fso.CreateTextFile(path + ".js", true);
	var fc = new Enumerator(f.files);
	for (; !fc.atEnd(); fc.moveNext()) {
		list.push(fc.item());
		//var txt=fso.GetFile(fc.item()).OpenAsTextStream(1).readAll();
		var txt = fso.openTextFile(fc.item(), true, 0).readAll()
		WScript.Echo(txt);
		//file.write(txt);
	}
	WScript.Echo("111");
	file.Close(); */
	/* if (args.length == 0) {
		throw "No Argument";
	} else {
		WScript.Echo(args(0));
	} */
} catch (e) {
	WScript.Echo(e.message);
}
