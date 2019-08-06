var main = function(){
	
	$("#test-field").click(function(){ 
		$.post(
			Apayus.url("test/field"), 
			{ name: "John", time: "2pm" },
			function(data){ 
		     		alert("Data Loaded: " + data); 
		   	}
		);

	}); 
	
	$("#test-person").click(function(){ 
		$.post(
			Apayus.url("test/person"), 
			{ name: "John", time: "2pm" },
			function(data){ 
		     		alert("Data Loaded: " + data); 
		   	}
		);

	}); 
	
	$("#main-person").click(function(){ 
		$.post(
			Apayus.url("main/person"), 
			{ name: "John", time: "2pm" },
			function(data){ 
		     		alert("Data Loaded: " + data); 
		   	}
		);

	}); 

}
