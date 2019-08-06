/*
 * @author: Apayus CS Team
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011
 * @update: 23/4/2011
 * @description: This is simple and Light loader libs 
 *
 * */
var Apayus = {
	url : function(module, action){
		var location = window.location.pathname;
		var indexpos = location.lastIndexOf("index.php");
		var baseurl = (indexpos != -1) ? location.substr(0, indexpos) : location;
		baseurl += "index.php";
		return (!action) ? baseurl+"/"+module :  baseurl+"/"+module+"/"+action;
	}
}
