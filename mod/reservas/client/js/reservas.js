
//*Variables globales*//
var pageTotal =5;
var pageActive =1;
var elementsPerPage= 10;
var grid ={};
var filters=[];
var gridTimer = setTimeout("alert('...')",999999);

//**//
var actualizarVariables= function() {
	$('#mygrid .btn-group input').get(0).value = pageActive;
	$('#count').html("-"+pageTotal+"-");
	$('#page').html("-"+pageActive+"-");
    $('.paginado button').get(0).disabled =false;
    $('.paginado button').get(1).disabled =false;
    $('.paginado button').get(2).disabled =false;
    $('.paginado button').get(3).disabled =false;
	if(pageActive==1){
		$('.paginado button').get(0).disabled =true;
		$('.paginado button').get(1).disabled =true;
	}
    if(pageActive==pageTotal){
        $('.paginado button').get(2).disabled =true;
        $('.paginado button').get(3).disabled =true;
    }
};

/*Cargar la tabla, heads y rows*/
$(function(){
	$.getJSON( Apayus.url("reservas/header"), function(field){
		var ths = "";
		var opts = "";
		$("#example thead").html("<tr class=\"active\"></tr>");
		$("#filtro-campo").html("<option selected=\"true\"></option>");
		for(var i in field){
			ths = ths + "<th>"+field[i]['title']+"</th>";
			opts = opts +"<option value=\""+field[i]['field']+"\" type=\""+field[i]['type']+"\">"+field[i]['title']+"</option>";
		}
		$("#example thead tr").append(""+ths+"");
		$("#filtro-campo").append(opts);
	});
    grid = {
        "filters": [
           
        ],
        "page": 1,
        "count": 10
    };
    $.post(
        Apayus.url("reservas/reload"),
        grid,
        function(result){
			$("#example tbody").html("");
			var grid = JSON.parse(result);
            var trs = "";
            $.each(grid['data'], function(index, value){
                var tds = "";
                for(var i in value){
			tds += "<td>"+value[i]+"</td>";
                };
                trs += "<tr>"+tds+"</tr>";
            });
            pageActive=parseInt(grid['page']);
            $("#example tbody").append(""+trs+"");
            $("#page").html("");
            $("#page").append("<span>-"+pageActive+"-</span>");

            pageTotal=grid['total'];
            $("#count").html("");
            $("#count").append("<span>-"+pageTotal+"-</span>");
            var exampleTable = $('#example').get(0);
            Sortable.initTable(exampleTable);
        });
    exampleTable = $('#example').get(0);
    Sortable.initTable(exampleTable);
    actualizarVariables();
    clearTimeout(gridTimer);
    gridTimer = setTimeout(recargarGrid,600000);
});

/*eventos*/
//Ocultar y mostrar panel derecho
$('#btn-sp-control').click(function activateSP(){	
	if($('#btn-sp-control').hasClass('active'))
	{
		$('#div-sp-content').css('display','none');
		$('#div-sp').removeClass('col-md-3');
		$('#div-sp').addClass('col-md-0');
		$('.contenedorGrid').removeClass('col-md-9');
		$('.contenedorGrid').addClass('col-md-12');
	}
	else
	{
		$('#div-sp-content').css('display','block');
		$('.contenedorGrid').removeClass('col-md-12');
		$('.contenedorGrid').addClass('col-md-9');
		$('#div-sp').removeClass('col-md-0');
		$('#div-sp').addClass('col-md-3');
	}
	//borrar linea al arreglar error
	$('#btn-sp-control').toggleClass('active');
});

$('#mygrid .btn-group input').keyup(function(event){
	if (event.which == 13) {
		if($('#mygrid .btn-group input').get(0).value > pageTotal)
			$('#mygrid .btn-group input').addClass("input-error");
		else{
			$('#mygrid .btn-group input').removeClass("input-error");
            pageActive = $('#mygrid .btn-group input').get(0).value;
			recargarGrid();
		}
	}
});

$('#cpape').keypress(function(event){
    var keynum = window.event ? window.event.keyCode : event.which;
    if ((keynum == 8) || (keynum == 46))
        return true;
    return /\d/.test(String.fromCharCode(keynum));
});

function numOnly(target){
    target.on("keypress", function(event){
        var keynum = window.event ? window.event.keyCode : event.which;
        var pressedKeyCode = window.event ? window.event.keyCode :event.keyCode;
        if ((keynum == 8) || (pressedKeyCode == 37) || (pressedKeyCode == 39) || (pressedKeyCode == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    });
}

var goPage1 = function(){
	pageActive = 1;
	recargarGrid();
};

var goPageLess = function(){
	pageActive = pageActive - 1;
	recargarGrid();
};

var goPageMore = function(){
	pageActive = pageActive + 1;
	recargarGrid();
};

var goPageEnd = function(){
	pageActive = pageTotal;
	recargarGrid();
};

//aplicar el filtro escojido
var applyFilter = function(){
	var fCampo = $("#filtro-campo option:selected").attr("value");
	var fOperador = $("#filtro-oper option:selected").val();	
	var fValor = $("#filtro-valor").val();
    if($("#filtro-campo").prop('selectedIndex') < 1 && $("#filtro-oper").prop('selectedIndex') < 1 && fValor == "")
    {
        filters = new Array();
        recargarGrid();
    }
	else if(fCampo=="" || fOperador=="" || fValor=="")
		alert("Datos incompletos");
	else
	{
		var filter = {field:fCampo,operator:fOperador,value:fValor};
		filters = new Array();
        filters.push(filter);
		//filters[0] = filter;
		recargarGrid();
	}	
};
//aplicar la lista de filtros
$('#apply-filter-list').click(function(){
    filters = new Array();
	var  filtros = $('#sp_filter_list div.container');
    filtros.each(function(){
		var cCampo = $($(this).children()[0]).attr('value');
		var cOperador = $($(this).children()[1]).attr('value');
		var cValor = $($(this).children()[2]).html();
		var cfiltro = {field:cCampo,operator:cOperador,value:cValor}
        filters.push(cfiltro);
	});
    recargarGrid();
});
//salvar el filtro a la seleccion
$('#save-filter').click(function(){
	var fCampo = $("#filtro-campo option:selected").html();
    var fCampoValue = $("#filtro-campo option:selected").attr("value");
	var fOperador = $("#filtro-oper option:selected").val();
	var fValor = $("#filtro-valor").val();
	var item = "";
	if(fCampo=="" || fOperador=="" || fValor=="")
		alert("Datos incompletos");
	else
	{		
		item = item + ('<div class="container row">');
		item = item + ('<div class="col-md-4 text-center no-padding" value="'+fCampoValue+'">'+fCampo+'</div>');
		item = item + ('<div class="col-md-2 text-center no-padding" value="'+fOperador+'">'+fOperador+'</div>');
		item = item + ('<div class="col-md-5 text-center no-padding">'+fValor+'</div>');
		item = item + ('<button type="button" class="btn btn-xs btn-danger col-md-1" onclick="delFilterFromList(this)">');
		item = item + ('<span class="glyphicon glyphicon-trash"></span>');
		item = item + ('</button></div>');
		$('#sp_filter_list').append(item);
	}
});

//establecer los operadores al seleccionar un filtro
var onFilterChange = $("#filtro-campo").change(function(){
	var option = $("#filtro-campo option:selected").html();
	$('#filtro-valor').val('');
	var opers = "";
	$("#filtro-oper").html("<option></option>");
	if(option != "")
	{		
		switch ($("#filtro-campo option:selected").attr("type"))
		{
			case 'string':
			{
				opers = opers+"<option>=</option>";
				opers = opers+"<option>!=</option>";
				break;
			}
			case 'date':
			{
				opers = opers+"<option><</option>";
				opers = opers+"<option><=</option>";
				opers = opers+"<option>></option>";
				opers = opers+"<option>>=</option>";
				opers = opers+"<option>=</option>";
				opers = opers+"<option>!=</option>";
				break;
			}
			case 'datetime':
			{
				opers = opers+"<option><</option>";
				opers = opers+"<option><=</option>";
				opers = opers+"<option>></option>";
				opers = opers+"<option>>=</option>";
				opers = opers+"<option>=</option>";
				opers = opers+"<option>!=</option>";
				break;
			}
			default:break;
		}
		$("#filtro-oper").append(opers);
		$('#filtro-valor').get(0).disabled=false;
	}
	if($('#filtro-campo option:selected').attr('type') == 'date' || $('#filtro-campo option:selected').attr('type') == 'datetime'){
		$('#filtro-valor').datepicker({
			dateFormat: "yy/mm/dd",
			firstDay: 0,
			prevText: "Anterior",
			nextText: "Siguiente",
			dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
			dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
			dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
			monthNamesShort:["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul","Ago", "Sep", "Oct", "Nov", "Dic"],
			monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio","Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			showOn: 'both',
			buttonImage: '',
			buttonImageOnly: true,
			changeYear: false,
			numberOfMonths: 1,
			altField: "#filtro-valor",
			altFormat: "DD, d MM, yy",

			onSelect: function(textoFecha, objDatepicker){
				$("#filtro-valor").val(textoFecha);
			}	
		});
        $('#filtro-valor + img').detach();
		var ancho = $("#filtro-valor").css('width');
        //arreglos al datepicker
        $('#ui-datepicker-div').css('box-shadow','2px 2px 5px');
        $('#ui-datepicker-div').css('border','1px solid #428BCA');
        $('#ui-datepicker-div').css('border-radius','3px');
		$('#ui-datepicker-div').css('width',''+ ancho +'');
        $('#ui-datepicker-div').css('background-image','linear-gradient(180deg,lightgreen 30%,white)');
    }
	else if(option == 'Dirección'){
        $('#filtro-valor').get(0).outerHTML = '<select id="filtro-valor" class="form-control"><option></option><option>SI</option><option>CC</option></select>';
	}else if(option == 'Teléfono' || option == 'Mesa'){
        $('#filtro-valor').get(0).outerHTML = '<input id="filtro-valor" class="form-control" type="text" type="hidden">';
        numOnly($('#filtro-valor'));
    }
    else{
        $('#filtro-valor').get(0).outerHTML = '<input id="filtro-valor" class="form-control" type="text" type="hidden">';
    }
});
//limpiar campos filtro
var clearFilter = function(btn){
    $('#filtro-valor').val('');
    $('#filtro-valor').get(0).disabled = true;
    $($('#filtro-campo').get(0)[0]).prop('selected','true');
	$('#filtro-oper').empty();
};
//eliminar la lista de filtros
$('#clear-filter-list').click(function(){
	$('#sp_filter_list').html("");
	filterListLenght = 0;
});
//eliminar un filtro de la lista
var delFilterFromList = function(btn){
    var clase = $(btn).parent().remove();
};

/*Funcion de actualizar el grid*/
var recargarGrid = function(){
	elementsPerPage = $('#mygrid select').get(0).value;
	grid = {
		"filters": filters,
		"page": pageActive,
		"count": elementsPerPage
	};
	$("#example tbody").detach();
	$("#example tbody tr").empty();
    $.post(Apayus.url("reservas/reload"), grid, function(result){
		var grid = JSON.parse(result);
        var trs = "";
        $.each(grid['data'], function(index, value){
            var tds = "";
            for(var i in value){
                tds += "<td>"+value[i]+"</td>";
            };
            trs += "<tr>"+tds+"</tr>";
        });
        pageActive=parseInt(grid['page']);
        $("#example tbody").append(""+trs+"");
        $("#page").html("");
        $("#page").append("<span>-"+pageActive+"-</span>");

        pageTotal=grid['total'];
        $("#count").html("");
        $("#count").append("<span>-"+pageTotal+"-</span>");
		$("#example").append("<tbody></tbody>");
		$("#example tbody").append(""+trs+"");
		actualizarVariables();
	});
    clearTimeout(gridTimer);
    gridTimer = setTimeout(recargarGrid,600000);
};

//function hello(){alert('Hola mundo!!!')};
var exportExcel = function(){
	$( "#fcount" ).attr("value", elementsPerPage);
	$( "#fpage" ).attr("value", pageActive);
	$( "#ffilters" ).attr("value", JSON.stringify(filters));
	$( "#fexpor" ).submit();
};

