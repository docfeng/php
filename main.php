<!DOCTYPE html>
<html>
	<META HTTP-EQUIV="pragma" CONTENT="no-cache"> 
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate"> 
<META HTTP-EQUIV="expires" CONTENT="0">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<head>

<style type="text/css">
	body{ font-size:14px; line-height:24px;} 
textarea{width:100%;height:350px}
	* {margin:0;padding:0;}
		input[onclick^="ins"] {margin:0;padding:0;width:18px;}
</style>

</head>
<body>
<input type="button" value='.' 
onclick="ins('.')" />
<input type="button" value="a" 
onclick="ins('alert()')"/>
<input type="button" value='""' 
onclick="ins('&quot;&quot;')"/>
<input type="button" value='=' 
onclick="ins('=')" />
<input type="button" value='f' 
onclick="ins('function(){}')" />
<input type="button" value='[]' 
onclick="ins('[]')" />
<input type="button" value='{}' 
onclick="ins('{}')" />
<input type="button" value='{' 
onclick="ins('{')" />
<input type="button" value='}' 
onclick="ins('}')" />
<input type="button" value='del' 
onclick="del()" />
	<input type="button" value='eval' 
onclick="eval(page1.txt.value)" />
	<input type="button" value='reload' onclick='location.reload()'/>
		<input type="button" value='file' onclick='alert(fso.createFile("1.js"))'/>
<form id="page1">
Name: <input type="text" name="name">
<input type="button" value="write" 
onclick="w()"/>
<input type="button" value="read" 
onclick="read()"/><br>
<textarea name="txt" ></textarea>
</form>

<input id="button1" type="button" value="page1" 
style="color:red;"
onclick="shift(1)">
<input id="button2" type="button" value="page2" onclick="shift(2);">
<input id="button3" type="button" value="page3" onclick="shift(3);">
<input id="button4" type="button" value="page3" onclick="shift(4);">
<input id="button5" type="button" value="page3" onclick="shift(5);">
<input id="button6" type="button" value="page3" onclick="shift(6);">
<script>
var index=1;
var button=button1;
var name_=Array()
var txt_=Array()
		
shift=function(i){
  eval("button" + index).style.color="black";
  if(page1.name.value!=""){
     eval("button" + index).value=
   	         page1.name.value;
     set("name"+index,page1.name.value);
     if(page1.txt.value!=""){
     	set("txt"+index,page1.txt.value);
      }
  }
  index=i;
  eval("button" + index).style.color="red";
  page1.name.value=get("name"+index)||"";
  page1.txt.value=get("txt"+index)||"";
}  


read=function(){
var path="r.php";
var str="name="+page1.name.value;
post(path,str,function(a){page1.txt.value=a});

}
w=function(){
var path="w.php";
var str='name='+page1.name.value+'&txt=' +encodeURIComponent(page1.txt.value);
post(path,str,function(a){alert(a)});

}
 



function del() {
var textObj=page1.txt;
 if (textObj.setSelectionRange) { 
var rangeStart = textObj.selectionStart; 
var rangeEnd = textObj.selectionEnd; 
var tempStr1 = textObj.value.substring(0, rangeStart-1); 

textObj.focus();
var tempStr2 = textObj.value.substring(rangeEnd); 
textObj.value = tempStr1;
textObj.blur();
textObj.value+= tempStr2; 

textObj.focus();
} 
} 


function ins(textFeildValue) {
var textObj=page1.txt;
 if (textObj.setSelectionRange) { 
var rangeStart = textObj.selectionStart; 
var rangeEnd = textObj.selectionEnd; 
var tempStr1 = textObj.value.substring(0, rangeStart); 

textObj.focus();
var tempStr2 = textObj.value.substring(rangeEnd); 
textObj.value = tempStr1 + textFeildValue 
textObj.blur();
textObj.value+= tempStr2;

textObj.focus();
} 
} 
window.onload=function(){alert()}
	
set=function(a,b){
     localStorage.setItem(a,b);
}
get=function(a){
     return localStorage.getItem(a)
}

post=function(path,str,fun) {  
  var xmlHttp=null; 
  try { // Firefox, Opera 8.0+, Safari 
   xmlHttp=new XMLHttpRequest();
  }catch (e) { // Internet Explorer 
    try { 
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); 
    }catch (e) { 
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      } 
    } 
  if (xmlHttp==null) { 
      alert ("您的浏览器不支持AJAX！"); 
       return; 
  }
  xmlHttp.onreadystatechange=function(){
    if(xmlHttp.readyState==4) { 
     fun(xmlHttp.responseText)
    }
  }
  xmlHttp.open("POST",path,true); 
  xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xmlHttp.send(str); 
  xmlHttp=null;
  }
</script>
<script src="js/_ini.js"></script>
</body>
</html>