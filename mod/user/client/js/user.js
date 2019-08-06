$('.form-horizontal fieldset.dos legend input').bind({
	'click': function(){
		var check = $('.form-horizontal fieldset.dos legend input');
		
		var ma= $('.form-horizontal fieldset.dos input.form-control');
		for(var i = 0; i< ma.length; i++){
			if (check.get(0).checked)
				ma.get(i).disabled = false;
			else
				ma.get(i).disabled = true;
		}
	}
});

var user = {
	name:"",
	user:"",
	pass:"",
	email:""
};

var limpiarPass = function(){
    $('fieldset.dos div.oldpass').removeClass("has-error");
    $('fieldset.dos div.newpass').removeClass("has-error");
    $('fieldset.dos div.rnewpass').removeClass("has-error")
    $('.form-horizontal fieldset input.oldpass').get(0).value='';
    $('.form-horizontal fieldset input.newpass').get(0).value='';
    $('.form-horizontal fieldset input.rnewpass').get(0).value='';
}

var cargarMipagina = function(){
	$.post(
		Apayus.url("user/load"), 
		user, 
		function(user){ 
			user = JSON.parse(user);
			$('.form-horizontal fieldset input.name').get(0).value = user.name;
			$('.form-horizontal fieldset input.user').get(0).value = user.user;
			$('.form-horizontal fieldset input.email').get(0).value = user.email;
	});	
};

var modificarUser = function(){
	var userUpdate ={};
	var check = $('.form-horizontal fieldset.dos legend input');
	$('fieldset.dos div.oldpass').removeClass("has-error");
	if (check.get(0).checked){
		if(!$('.form-horizontal fieldset input.name').get(0).value || !$('.form-horizontal fieldset input.user').get(0).value ||
		!$('.form-horizontal fieldset input.oldpass').get(0).value || !$('.form-horizontal fieldset input.newpass').get(0).value || 
		!$('.form-horizontal fieldset input.rnewpass').get(0).value || !$('.form-horizontal fieldset input.email').get(0).value)
			return false;
		if($('.form-horizontal fieldset input.newpass').get(0).value == $('.form-horizontal fieldset input.rnewpass').get(0).value){
            $('fieldset.dos div.newpass').removeClass("has-error");
            $('fieldset.dos div.rnewpass').removeClass("has-error")
            $('fieldset.dos div.newpass').removeClass("has-feedback");
            $('fieldset.dos div.rnewpass').removeClass("has-feedback");
            userUpdate = {
				name:$('.form-horizontal fieldset input.name').get(0).value,
				user:$('.form-horizontal fieldset input.user').get(0).value,
				oldpass:calcMD5($('.form-horizontal fieldset input.oldpass').get(0).value),
				pass:calcMD5($('.form-horizontal fieldset input.newpass').get(0).value),
				email:$('.form-horizontal fieldset input.email').get(0).value
			};
        }
		else{
            $('fieldset.dos div.newpass').addClass("has-error");
            $('fieldset.dos div.rnewpass').addClass("has-error");
            $('.form-horizontal fieldset input.newpass').get(0).value='';
            $('.form-horizontal fieldset input.rnewpass').get(0).value='';
            return false;
		}
	}
	else{
		if(!$('.form-horizontal fieldset input.name').get(0).value || !$('.form-horizontal fieldset input.user').get(0).value ||
		!$('.form-horizontal fieldset input.email').get(0).value)
			return false;
		userUpdate = {
			name:$('.form-horizontal fieldset input.name').get(0).value,
			user:$('.form-horizontal fieldset input.user').get(0).value,
			email:$('.form-horizontal fieldset input.email').get(0).value
		};
	}	
	$.post(
		Apayus.url("user/save"), 
		userUpdate, 
		function(userUpdate){ 
			userUpdate = JSON.parse(userUpdate);
			if(userUpdate instanceof Object) {
                window.location = Apayus.url("user/edit");
            }else{
				$('fieldset.dos div.oldpass').addClass("has-error");
                $('.form-horizontal fieldset input.oldpass').get(0).value='';
			}
	});
};

var login = {
	user:"",
	pass:""  
}

$(this).keyup(function(event){
    if (event.which == 13) {
        autenticar();
    }
});

var autenticar = function(){
	var pass = "";
	if($('.login input.loginPass').get(0).disabled)
	{
		login = {
			user:$('.login input.loginUser').get(0).value
		};
	}
	else
	{		
		login = {
			user:$('.login input.loginUser').get(0).value,
			pass:calcMD5($('.login input.loginPass').get(0).value)
		};
	}
	console.log(login);
	
	$.post(
		Apayus.url("user/dologin"), 
		login, 
		function(response){
			if(response == "success"){
				window.location = Apayus.url("main/home");
			}else if(response == "remember") {
				alert("se le ha enviado un correo con su nueva contrasena, por favor cambiela...");
			}else if(response == "noremember") 
				alert("No se le ha podido envía un mensaje de confirmación al consumir el servicio de generación de nueva contraseña, para mas información refiérase a su administrador de red...");
			else {
				$('#div-user').addClass('has-error');
                $('#div-pass').addClass('has-error');
                $('#div-user').addClass('has-feedback');
                $('#div-pass').addClass('has-feedback');
			}
	});
	return false;
};

var noRememberPass = function(){
	if($('input.no-remember').get(0).checked)
		$('.login input.loginPass').get(0).disabled = true;	
	else
		$('.login input.loginPass').get(0).disabled = false;
};

var activateLogin = function(){
	$('#mypage').toggleClass('blur-4x');
};
