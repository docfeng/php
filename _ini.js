fj={}
fj.get=function(url,fun) {  
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
		if (xmlHttp.readyState==4) { 
fun(xmlHttp.responseText)
				}
			}
		xmlHttp.open("GET",url,true); 
			xmlHttp.send(null); 
		}

fj.GetXmlHttpObject=function(){
		 
		 	 return xmlHttp;  
}

fj.get("ini.js",function(q){eval(q)})