//creamos el objeto app
var app = {};

//nuestra api key
//app.apikey = "D8QXCVJXrNOZRPSEKGNYmaYA6YmkYkzu";
app.apikey = "Bw0OmM3F8v9Hp7T78d8TbzAnMX4PxX3L";
//en la URL indicamos la ciudad, en este ejemplo q=Malaga
app.url = "http://dataservice.accuweather.com/currentconditions/v1/2931?apikey=" + app.apikey + "&language=es-ES&details=true";
console.log(app.url);

app.cargaDatos = function(){

$.ajax({
url: app.url,
success: function( data ) {

//guardamos en la variable datos dentro del objeto app toda la información “en bruto”
//console.log('Datos: '+data);
app.datos = data;

//a continuación lanzamos la función procesaDatos que se encarga del manipulado de los datos
app.procesaDatos();
},

//algo no ha ido bien, por simplificar el ejemplo no vamos a analizar los tipos de error, solo mostramos un mensaje
//en el mundo real analizaríamos el error que nos devuelve la API para actuar de una manera u otra
error: function(){
//alert("¡Ups! No puedo obtener información de la API");
}
});

}

app.procesaDatos = function(){
//guardamos los datos por separado en variables
app.titulo = app.datos[0].WeatherText;
app.temperatura = app.datos[0].Temperature.Metric.Value;
//app.viento = app.datos[0].Wind.Speed.Metric.Unit;
//app.dirviento = app.datos[0].Wind.Direction.Localized;
app.nubes = app.datos[0].CloudCover;
app.icono = app.datos[0].WeatherIcon;

var idc = app.icono;

if(app.icono=='1' || app.icono=='2' || app.icono=='3' || app.icono=='4' || app.icono=='5' || app.icono=='6' || app.icono=='7' || app.icono=='8' || app.icono=='9'){idc='0'+app.icono;}

$('#icono').html('<img typeof="foaf:Image" class="img-responsive" src="https://developer.accuweather.com/sites/default/files/'+idc+'-s.png" width="75" height="45">');
                                                                       https://developer.accuweather.com/sites/default/files/06-s.png
$('#estado_clima').html(app.titulo);
$('#temperatura').html(app.temperatura);

//obtenemos el icono (una clase) para el código que indicamos
//app.icono = app.obtenIcono( condicionIcono );
//vamos al siguiente paso en el proceso: el “pintado” de los elementos en pantalla
//app.muestra();
}
app.cargaDatos();



  function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('w3time').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
  }

  function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
  }



// });
