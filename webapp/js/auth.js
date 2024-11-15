$(document).ready(function(){
  var url ='https://sistemabigpollo.mayoristasbahia.com//procesos/functions-online.php?';
  
    //Login Function
    $("#login").click(function(){
		$('erroracc').html('');
		$("#login").html('Conectando...');
   	var usuario=$("#usuario").val();
   	var password=$("#password").val();
   	var dataString="usuario="+usuario+"&password="+password+"&login=";
    if($.trim(usuario).length>0 & $.trim(password).length>0){
			$.ajax({
				type: "POST",
				url: url,
				data: dataString,
				//dataType: "json",
				crossDomain: true,
				cache: false,
				success: function(data){
					console.log(data)

				if(data['estado']=="success"){
		//		var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
				var cobrador=data['perfil'].vendedor;
        //console.log()
				localStorage.crudo=JSON.stringify(data);
        localStorage.candado=data['perfil'].pin;
				localStorage.id=data.perfil.id;
        localStorage.avatar=data.perfil.avatar;
  			localStorage.notificacion='';
				localStorage.login = "true";
				localStorage.usuario = usuario;
				localStorage.fechalog = data.fecha;
				localStorage.nombrecobrador = data['perfil'].apellido+', '+data['perfil'].nombre;
				localStorage.lista = data['perfil']['lista'];

				var carrito = new Array();
				var enviodb = new Array();
				localStorage.carrito = carrito;
				localStorage.enviodb = enviodb;
				localStorage.cantprod = 0;
				localStorage.cargalogin = 0;
				if(localStorage.precarga !='1'){localStorage.precarga = 0;}
				window.location.href = "index.html";

					}
					else if (data['estado']="failed")
					{
					$('erroracc').html('<center><br><h3 style="color: #b90707;">Datos incorrectos</h3></center>');
					$("#login").html('Ingresar');
					}
				}
			});
		}return false;

    });


    //logout function
    $("#logout").click(function(){
    	localStorage.login="false";
			localStorage.cargalogin = 0;
			localStorage.removeItem('residcliente');
    	window.location.href = "login.html";
		navigator.app.exitApp();
    });

    //Displaying user email on home page
    $("#email1").html(localStorage.email);


     //muestro saludo
   	// $("#saludo").html("Hola "+localStorage.usuario);


     //Buscar Function
    $("#buscarahora").click(function(){
    	var buscar=$("#buscador").val();
    	var buscalupa="busca="+buscar+"&ok=1";
    	if($.trim(buscar).length>0)
		{
			$.ajax({
				type: "POST",
				url: urlbusca,
				data: buscalupa,
				crossDomain: true,
				cache: false,
				// beforeSend: function(){ $("#buscarahora").html('Buscando...');},
				success: function(data){
					if(data=="false")
					{
						alert("No se encontrar Registros");
				     //   $("#buscarahora").html(buscar+' <i class="fa fa-search"></i>');
					}
					else
					{
						 if (localStorage.catnombre != undefined) {
							localStorage.removeItem('catnombre');
    													}
                        localStorage.buscar=buscar;
												localStorage.usados = 0;
						//$("#contenido").load('tables.html');
                        window.location.href = "busqueda.html";

					}
                    				}
			});
		}return false;

    });


});
