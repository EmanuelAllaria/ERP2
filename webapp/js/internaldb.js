window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
// DON'T use "var indexedDB = ..." if you're not in a function.
// Moreover, you may need references to some window.IDB* objects:
window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction || {READ_WRITE: "readwrite"}; // This line should only be needed if it is needed to support the object's constants for older browsers
window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;

if (!window.indexedDB) {
    window.alert("Your browser doesn't support a stable version of IndexedDB. Such and such feature will not be available.");
}

//var request = window.indexedDB.open("MyTestDatabase", 3);
var objeto;
var posicion;
var credito_s;
var credito_a;
var total_billetera=0;
var fechas_liquidar='';
var cant_clientess = [];

function creadb(){
var db  = indexedDB.open("internaldb");

db.onupgradeneeded = function(event) {
  db = db.result;
  var objcliente = db.createObjectStore("clientes", { keyPath: "id" });
  var objeccredito = db.createObjectStore("creditos", { keyPath: "id" });
  var objecpagos = db.createObjectStore("pagos", { keyPath: "id", autoIncrement:true });
  var objecperfil = db.createObjectStore("perfil", { keyPath: "id" });
  var objecrutas = db.createObjectStore("rutas", { keyPath: "id" });
  // Create an index to search customers by name. We may have duplicates
  // so we can't use a unique index.
  objcliente.createIndex("id", "id", { unique: false });
  //objcliente.createIndex("email", "email", { unique: true });

  objeccredito.createIndex("id", "id", { unique: false });
  objeccredito.createIndex("by_cliente", "cliente2", { unique: false });
  objecperfil.createIndex("id", "id", { unique: false });
  objecrutas.createIndex("id", "id", { unique: false });
  objecrutas.createIndex("by_ruta", "ruta", { unique: false });
  objecrutas.createIndex("by_posicion", "posicion", { unique: false });
  objecrutas.createIndex("by_cliente", "cliente", { unique: true });
  objecpagos.createIndex("id", "id", { unique: false });
  objecpagos.createIndex("by_fecha", "fecha", { unique: false });
};

db.onerror = function(event) {
  // Do something with request.errorCode!
  //console.log("failed opening DB: "+request.errorCode)
};
db.onsuccess = function(event) {
  // Do something with request.result!
  db = db.result;
  //console.log("opened DB");
  }
//db.close();
}


function syncroniza(data,tipo){
/*  console.log(data)
  console.log('entra ahora al syncroniza')
  console.log(db);
*/
  //indexedDB.deleteDatabase("internaldb");
  //creadb();
  var db = indexedDB.open("internaldb");

  db.onsuccess = function (event) {
  db = event.target.result;
  var datito = db.transaction([tipo], "readwrite");
  var otrodato = datito.objectStore(tipo);
  if(tipo=='clientes'){
    for(var i='0'; i<data.clientes.length; i++){
     otrodato.put(data.clientes[i]);
  }
}

  if(tipo=='creditos'){
    for(var i='0'; i<data.creditos.length; i++){
     otrodato.put(data.creditos[i]);
  }
}

if(tipo=='rutas'){
  rutao = {
apellido: "",
calle: "0",
cliente: "0",
cobrador: "0",
crc: "1",
crc2: "1",
cuando: "0000-00-00 00:00:00",
id: "0",
id_ruta: "0",
nombre: "Sin Ruta",
nombre_ruta: "Sin Ruta",
numcalle: "",
posicion: "1",
razon: "",
rubro: "",
ruta: "0",
sucursal: ""
  }

   otrodato.put(rutao);

  for(var i='0'; i<data.rutas.length; i++){
   otrodato.put(data.rutas[i]);
  }
}

  if(tipo=='perfil'){
       otrodato.put(data.perfil);
}
//console.log(db)
//db.close();
}
}


function pagomultiple(dnd,callback) {
  var cantidadcuotas='1';
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("creditos","readwrite");
    var otrodato = datito.objectStore("creditos");
    //var object = dataload.objectStore("creditos");
    var index = otrodato.index("by_cliente");
    var elements = [];

    index.openCursor().onsuccess = function (e) {

        var result = e.target.result;
        if (result === null) {
            return;
        }
        //console.log(result.value);
        if(result.value.cliente2==localStorage.lectura){elements.push(result.value);}
        result.continue();
    };

    datito.oncomplete = function() {
      //loadcliente();

        var totaltotal ='0';
        var cuotamax ='0';
        var sumatotal ='0';
        for (var key in elements) {
          var pendientecuotas = parseFloat(elements[key].totcuotas) - parseFloat(elements[key].pagas);

          if(cuotamax < pendientecuotas){ cuotamax = pendientecuotas; }
          if (cantidadcuotas <= pendientecuotas ){
            sumatotal = parseFloat(cantidadcuotas) * parseFloat(elements[key].valor);
          }
          else
          {
            sumatotal = parseFloat(pendientecuotas) * parseFloat(elements[key].valor);
          }


          console.log('Cuotas Totales: '+ pendientecuotas);
          console.log('Cuotas Maxima: '+ cuotamax);
          console.log('Suma Totales: '+ sumatotal);
          totaltotal = parseFloat(totaltotal) + parseFloat(sumatotal);
      }
          elements = [];

  //      console.log('entra al elements');
//      console.log(outerHTML);

      // $('#listado_creditos').html(outerHTML);
var resultonga = totaltotal+'@'+cuotamax;
callback('todos',resultonga);
console.log('resultonga: '+resultonga)

    };
//    console.log('entra al loadcreditos');
}

}


function loadcreditos() {
  $('.html').removeClass("visible");
  $('.cobranza').addClass("visible");
  console.log('Carga Creditos');
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("creditos","readwrite");
    var otrodato = datito.objectStore("creditos");
    //var object = dataload.objectStore("creditos");
    var index = otrodato.index("by_cliente");
    var elements = [];

    index.openCursor().onsuccess = function (e) {

        var result = e.target.result;
        if (result === null) {
            return;
        }
        //console.log(result.value);
        if(result.value.cliente2==localStorage.lectura){elements.push(result.value);}
        result.continue();
    };

    datito.oncomplete = function() {
      loadcliente();
        var outerHTML = '';
        var cliecantcredit = '0';
        var total_cuotas = '0';
        var cerdito_limite = '0';
        for (var key in elements) {

            outerHTML += '<a href="javascript:void(0)" onclick="abremodal(this)" data-cobra="'+ elements[key].cobrador +'" data-cliente="'+ elements[key].cliente2 +'" data-cliente2="'+ elements[key].cliente +'" data-id="'+ elements[key].credito +'" data-vc="'+ elements[key].valor +'" data-codigo="'+ elements[key].codigo +'" data-tc="'+ elements[key].totcuotas +'" data-up="'+ elements[key].upago +'" data-cp="'+ elements[key].pagas +'" data-ca="'+elements[key].atrasadas +'" class="waves-effect waves-light" style="color: #fff;" >'
            +'<div class="note clearfix slideInRight animated" style="border-bottom: 1px solid;">'
            +'    <div class="time pull-left">'
              +'     <div class="hour">'+ elements[key].credito +'</div>'
                  +'   <div class="shift">#</div>'
                  +' </div>'
                  +' <div class="to-do pull-left">'
                  +'   <div class="title">Cod.: '+ elements[key].codigo +' || $ '+ elements[key].valor +'<br/> U. Pago: '+ elements[key].upago +' </div>'
                  +' <div class="subject">C. Pag('+ elements[key].pagas +') || C. Atr('+ elements[key].atrasadas +') || C. Tot.('+ elements[key].totcuotas +')</div>'
                +'</div>'
          +'  </div>'
        +'  </a>';
        total_cuotas = parseFloat(total_cuotas) + parseFloat(elements[key].valor);
        cliecantcredit++;}
          outerHTML += '<center><button class="btn btn-danger" onclick="pagomultiple(\'todos\',abremodal);" style="text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;">Abonar todos los creditos</button><input type="hidden" id="cant_creditos_cliente" value="'+cliecantcredit+'"></center><input type="hidden" id="tot_clientes_cuotas" value="'+total_cuotas+'"></center>';
        elements = [];
        if(outerHTML==''){
          outerHTML+='<span style="text-align:center">No se encontraron creditos de este cliente</span><input type="hidden" id="cant_creditos_cliente" value="0">';
        }
  //      console.log('entra al elements');
//      console.log(outerHTML);
      $('#listado_creditos').html(outerHTML);
    };
//    console.log('entra al loadcreditos');
}
}

function loadcliente() {
//  console.log('carga cliente')
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("clientes","readwrite");
    var otrodato = datito.objectStore("clientes");
    //var object = dataload.objectStore("creditos");
    var clientesRequest = otrodato.get(localStorage.lectura);
  //  console.log(otrodato)
     clientesRequest.onerror = function () {//console.log('error')
   }
       clientesRequest.onsuccess = function () {
                 //var outerHTML = '';
                // for (var key in elements) {
                   var result = clientesRequest.result;
                   var datoscliente;
                //   if (result === undefined) {
    //  console.log(result)
        datoscliente='<div class="user clearfix rotateInDownLeft animated">'
                        +'<div class="photo pull-left">'
                          +'<img src="img/frente_sinfoto.jpg">' //+result.avatar+'">'
                        +'</div>'
                        +'<div class="desc pull-left">'
                          +'<p class="name">'+result.apellido+', '+result.nombre+'  <span style="font-size: x-small;font-weight: normal;"> <br/>DNI/CUIT: '+result.dni+'</span></p>'
                          +'<p class="position">'+result.direccion_com+' '+result.dirnum_com+' &nbsp;&nbsp;<br/>'+result.rubro+'</p>'
                        +'</div>'
                        +'<div class="idle pull-right"><span class="cerrado"></span></div>'
                      +'</div>';
                   //}
                   $('#datos_cliente').html(datoscliente);
          //         console.log('datoscliente');
          //         console.log(datoscliente);
              // };

    };

//    console.log('entra al loadcliente');
  }
}


function buscaposicion(cliente) {
//  console.log('carga cliente')
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("rutas","readwrite");
    var otrodato = datito.objectStore("rutas");
    var index = otrodato.index("by_cliente");
    var clientesRequest = index.get(cliente);
  //  console.log(otrodato)
     clientesRequest.onerror = function () {//console.log('error')
   }
       clientesRequest.onsuccess = function () {
         console.log(index)
      var result = clientesRequest.result;
      localStorage.ruta=result.posicion;
      credito_a= parseInt(result.posicion) - 1 ;
      credito_s= parseInt(result.posicion) + 1 ;
      console.log('anterior: '+credito_a+' siguiente: '+credito_s);
    };

//    console.log('entra al loadcliente');
  }
}


function loadrutas() {
//  console.log('Carga listado rutas');
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("rutas","readwrite");
    var otrodato = datito.objectStore("rutas");
    //var object = dataload.objectStore("creditos");
   var index = otrodato.index("by_ruta");
    var elements = [];

    index.openCursor(null, 'nextunique').onsuccess = function (e) {

        var result = e.target.result;
        if (result === null) {
            return;
        }
        //console.log(result.value);

          elements.push(result.value);

      //  console.log('direction')
      //  console.log(result.direction);
        result.continue();
    };

        datito.oncomplete = function() {
    //    console.log(elements)
        var cantidadclie ='';
        var outerHTML = '';
    //    console.log('cantidad: '+elements.length)
        for (var key in elements) {
//              console.log('valor1' +elements[key].id_ruta);
  //            console.log('key: ' +key);

var cantidadcli = cant_cli_rut(elements[key].id_ruta);
console.log('devuelve: '+cant_cli_rut(elements[key].id_ruta))
            outerHTML += '<a href="javascript:void(0)" onclick="load_clientes_order('+elements[key].id_ruta+')"><div  class="media  heading flipInY animated">'
                      +'     <div class="price">'+elements[key].id_ruta+'<small>';
if(cantidadcli=='1'){outerHTML += 'Cliente';}
                      else
                    {outerHTML += 'Clientes';}

                      outerHTML += '</small></div> '
                      +'     <div class="media-body pl-3"> '
                      +'        <div class="address">'+elements[key].nombre_ruta+'</div> '
                      +'     </div> '
                      +'  </div> '
                      +'</a>';
        console.log(outerHTML);

    };
/*
    outerHTML += '<a href="javascript:void(0)" onclick="load_clientes_order('+elements[key].id_ruta+')">'
              +'    <div  class="media  heading flipInY animated">'
              +'       <div class="price">X'
              +'          <small>Clientes</small></div>'
              +'          <div class="media-body pl-3">'
              +'          <div class="address">Sin Ruta</div>'
              +'       </div>'
              +'   </div>'
              +'</a>';
*/

      $('#listado_rutas').html(outerHTML);
    }
//    console.log('entra al loadcreditos');
}
}

function cant_cli_rut(ruta){
  $('#regcant').val('');
  var cuentoclie=0;
  console.log('entra a calcular la cantidad de clientes con: '+ruta)
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  //    console.log('entra a success db')
  db = event.target.result;
    var datito = db.transaction("rutas","readwrite");
    var otrodato = datito.objectStore("rutas");
    var index = otrodato.index("by_ruta");

index.openCursor().onsuccess = function (e) {
//    console.log('entra a success index')
        var result = e.target.result;
        if (result === null) {
            return;
        }

        //console.log(result.value);
        if(result.value.id_ruta==ruta){
          cuentoclie++;
        console.log('detecta un cliente: '+cuentoclie)
        result.continue();
    };

}

datito.oncomplete = async function() {
  //$('#regcant').val(cant_clientess);
  $('#regcant').val(cuentoclie);
  var cosa = $('#regcant').val();
  localStorage.cantclietmp=cosa;
  console.log(cosa);
  // return cant_clientess.length;

}

}
var caquita=$('#regcant').val();
return caquita
}


function load_clientes_order(ruta) {
  $('.html').removeClass("visible");
  $('.rutas_det').addClass("visible");
    var db = indexedDB.open("internaldb");

    db.onsuccess = function (event) {
    db = event.target.result;
      var data = db.transaction(["rutas"], "readonly");
      var object = data.objectStore("rutas");
      var index = object.index('by_posicion');
      var elements = [];

      index.openCursor().onsuccess = function (e) {
          var result = e.target.result;
          if (result === null) {return;}
          if(result.value.id_ruta==ruta){ elements.push(result.value);}
          result.continue();
      };

      data.oncomplete = function() {
          var outerHTML = '';
          for (var key in elements) {
    if(elements[key].cliente !='0'){
                  outerHTML +='<a href="javascript:void(0)" onclick="lectura('+elements[key].cliente +')">'
                            +'    <div  class="media  heading flipInY animated">'
                            +'       <div class="price" style="height: 100px;padding-top: 10%">'+elements[key].posicion
                            +'         </div>'
                            +'          <div class="media-body pl-3" style="padding-top: 0px;">'
                            +'          <div class="address" style="margin-top: 15px;">'+elements[key].rubro +' <br/> '+elements[key].razon +'<br/>'+elements[key].apellido +', '+elements[key].nombre +'<br/>'+elements[key].calle +' '+elements[key].numcalle +'</div>'
                            +'       </div>'
                            +'   </div>'
                            +'</a>';
    }
          }
          elements = [];
          console.log(outerHTML);
          $('#listado_rutas_detalle').html(outerHTML);
      };
    }

}



function abremodal(obj,datos){
  console.log('abre el modal');
  console.log('datos: '+datos);
  //console.log(obj);
if(obj=='todos'){
var cant_creditos_cliente = $('#cant_creditos_cliente').val();

console.log(tomodatos);
console.log('Total: '+tot_clientes_cuotas);

var tomodatos = $('#tot_clientes_cuotas').val().split('@');
var tot_clientes_cuotas = tomodatos[0];
console.log(': '+tomodatos[1])
var vc = tot_clientes_cuotas;
var cpen = tomodatos[1];
var f = '1';
}else {
var id = obj.getAttribute("data-id");
var vc = obj.getAttribute("data-vc");
var codigo = obj.getAttribute("data-codigo");
var cobra = obj.getAttribute("data-cobra");
var tc = obj.getAttribute("data-tc");
var up = obj.getAttribute("data-up");
var cliente = obj.getAttribute("data-cliente");
var cliente2 = obj.getAttribute("data-cliente2");
var f ='0';
var cp = obj.getAttribute("data-cp");
var cuantos = $(obj).val();
var ca = obj.getAttribute("data-ca");
var cpen = parseInt(tc) - parseInt(cp);
objeto = {
  credito:id,
  cliente:cliente,
  clienteh:cliente2,
  cobrador:cobra,
  producto:codigo,
  cant:cuantos,
  valor:vc,
  total:parseInt(vc) * parseInt($('#cant_cuotasmodal').val())
  }
//obj.getAttribute("data-nombre");
}
var modal_cuotas;

modal_cuotas='<div class="modal fade bottom" id="modalcuotas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true" aria-hidden="true" style="display: none;">'
+'    <div class="modal-dialog modal-full-height modal-bottom modal-notify modal-danger" role="document">'
+'      <div class="modal-content">'
+'        <div class="modal-body" style="font-size: 18px;">';

if(obj!='todos'){modal_cuotas +='<ul class="list-group z-depth-0">'
+'            <li class="list-group-item justify-content-between">'
+'              Cuotas Abonadas'
+'              <span class="badge badge-primary badge-pill">'+cp+'</span>'
+'            </li>'
+'            <li class="list-group-item justify-content-between">'
+'              Cuotas Atrasadas'
+'              <span class="badge badge-primary badge-pill">'+ca+'</span>'
+'            </li>'
+'            <li class="list-group-item justify-content-between">'
+'              Cuotas Pendiente'
+'              <span class="badge badge-primary badge-pill">'+cpen+'</span>'
+'            </li>'
+'            <li class="list-group-item justify-content-between">'
+'              Cuotas Totales'
+'              <span class="badge badge-primary badge-pill">'+tc+'</span>'
+'            </li>'
+'          </ul>';} else {modal_cuotas +='<ul class="list-group z-depth-0">'
+'            <li class="list-group-item justify-content-between">'
+'              Creditos a Abonar'
+'              <span class="badge badge-primary badge-pill">'+cant_creditos_cliente+'</span>'
+'            </li></ul>';}

modal_cuotas +='<div id="detalle_cobra">'
+'            <div class="center" style="margin-top: 30px;">'
+'              <p>'
+'              </p>'
+'              <span style="text-align:center;font-weight: bold;">Cuotas:</span></br>'
+'              <div class="input-group">'
+'                <span class="input-group-btn">'
+'                  <button type="button" onclick="cantidadresta()" class="btn btn-danger btn-number" data-type="minus" data-field="quant[1]">'
+'                    <span class="glyphicon glyphicon-minus"></span>'
+'                  </button>'
+'                </span>'
+'                <input type="number" id="cant_cuotasmodal" name="quant[1]" onchange="actualizatotal('+vc+');" min="1" max="'+cpen+'" style="height: 45px;text-align: center;font-weight: bold;font-size: xx-large;" class="form-control input-number" value="1">'
+'                <span class="input-group-btn">'
+'                  <button type="button" onclick="cantidadsuma()" class="btn btn-success btn-number" data-type="plus" data-field="quant[1]">'
+'                    <span class="glyphicon glyphicon-plus"></span>'
+'                  </button>'
+'                </span>'
+'              </div>'
+'              <p></p>'
+'            </div>'
+'              <div class="forms compose-list">'
+'                  <div id="cobra-total" class="group clearfix bounceIn animated" style="margin-bottom: 5px;margin-top: 20px;font-weight: bold;font-size: x-large;">'
+'                    <center>TOTAL $ '+vc+'</center>'
+'                  </div>'
+'              </div>'
+'          </div>'
+'        <div class="modal-footer" style="text-align:center">'
+'          <a type="button" class="btn btn-success waves-effect waves-light" href="javascript:void(0)" onclick="realizapago()" style="background:#4cae4c">Abonar'
+'            <i class="far fa-gem ml-1"></i>'
+'          </a>'
+'          <a type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar</a>'
+'        </div>'
+'      </div>'
+'    </div>'
+'  </div>'
+'</div>';
console.log(objeto);
$('body').after(modal_cuotas);
$('#modalcuotas').modal('show');
}

function actualizatotal(v){
  console.log('actualizo el total');
  var cantidad= $('#cant_cuotasmodal').val();
  var total = parseFloat(v) * parseFloat(cantidad);
  $('#cobra-total').html('<center>TOTAL $ '+total+'</center>');

}


function tomogps(callback){

    var objgps;
    var onSuccess = function(position) {
      objgps.lat = position.coords.latitude;
      objgps.lon = position.coords.longitude;
    alert(objeto.lat  + '\n' + objeto.lon);

    callback(objgps);

    };

    // onError Callback receives a PositionError object
    //
    function onError(error) {
        alert('code: '    + error.code    + '\n' +
              'message: ' + error.message + '\n');

    }

  navigator.geolocation.getCurrentPosition(onSuccess, onError);
}

function realizapago(objgps) {
alert('entro con GPS');
console.log(objgps);

  console.log('entra a realizar pago');
  objeto.cant=$('#cant_cuotasmodal').val();
  objeto.total = objeto.valor * objeto.cant;
  objeto.fecha = hoyFecha();
  //objeto.fecha = '12/05/2019';
  	var a = new Date;
    var horas =  a.getHours()
    var minutos = a.getMinutes();
    var segundos = a.getSeconds();
  objeto.hora =addZero(horas)+':'+addZero(minutos)+':'+addZero(segundos);
  objeto.estado = '1';
  console.log(objeto);

//  console.log('carga cliente')

  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("pagos","readwrite");
    var otrodato = datito.objectStore("pagos");
        //var object = dataload.objectStore("creditos");
    var clientesRequest = otrodato.add(objeto);
  //  console.log(otrodato)
       clientesRequest.onerror = function () { }
       clientesRequest.onsuccess = function () {
                            Swal.fire({
                              // position: 'top-end',
                              type: 'success',
                              title: 'El Pago se realizo correctamente',
                              //html: '',
                              showConfirmButton: false,
                              timer: 2000,
                              allowOutsideClick: false,
                              onClose: ()=>{
                              localStorage.removeItem('lectura');
                              window.location.href = "index.html";
                                  }
                              })
                    };

//    console.log('entra al loadcliente');
  }
}

function lectura(id){
  localStorage.lectura = id;
  window.location.href = "index.html";
}

function confirma_trans(){

  transferir()

}

function transferir(){
//tomar db y pasarla a variable
console.log('inicia tranferencia');
var db = indexedDB.open("internaldb");
db.onsuccess = function (event){
db = event.target.result;
var datito = db.transaction("pagos","readwrite");
var otrodato = datito.objectStore("pagos");

var elements = [];

  otrodato.openCursor().onsuccess = function (e) {

      var result = e.target.result;
      if (result === null) {
          return;
      }
      //console.log(result.value);
      if(result.value.estado!='0'){elements.push(result.value);}
      result.continue();
  };

    datito.oncomplete = function() {
        billetera_json = JSON.stringify(elements);
        console.log(billetera_json);
        elements = [];

        var dataString='transfiere&c='+localStorage.id+'&b='+billetera_json;
// conectar ajax y enviar json con cobrador, etc.

        $.ajax({
          type: "POST",
          url: url_gral,
          data: dataString,
          crossDomain: true,
          cache: false,
          success: function(data){
            // si devuelve true, pasar los pagos a (0)
            //sino indicar error y no realizar nada.
                  if(data !='FALSE'){
                    // desactivabilletera();

                //    eliminodb('internaldb');
                    /*eliminodb('creditos');
                    eliminodb('rutas');
                    */

                    var DBDeleteRequest = indexedDB.deleteDatabase("internaldb");

// When i had the base open, the closure was blocked, so i left this here
DBDeleteRequest.onblocked = function(event) {
  console.log("Blocked");
};

DBDeleteRequest.onerror = function(event) {
    console.log("Error deleting database.");
  console.log(event);
};

DBDeleteRequest.onsuccess = function(event) {
  console.log("Database deleted successfully");
};

                    syncro();

                    Swal.fire({
                      // position: 'top-end',
                      type: 'success',
                      title: 'La Billetera se transfirio correctamente',
                      //html: '',
                      showConfirmButton: false,
                      timer: 2000,
                      allowOutsideClick: false,
                      onClose: ()=>{
                        localStorage.removeItem('lectura');
                      window.location.href = "index.html";

                    }
                    })



                  } else { alert('error al transferir'); }
          }
        })

  };








//    console.log('entra al loadcreditos');
}


}

function loadbilletera() {
  $('#transfiere').show();
  console.log('total_billetera');
  console.log(total_billetera);
  total_billetera=0;
  fechas_liquidar='';

  console.log('entra a la funcion billetera')
  var db = indexedDB.open("internaldb");
  db.onsuccess = function (event) {
  db = event.target.result;
    var datito = db.transaction("pagos","readwrite");
    var otrodato = datito.objectStore("pagos");
    var index = otrodato.index('by_fecha');
    var elements = [];

    var datito_det = db.transaction("pagos","readwrite");
    var otrodato_det = datito_det.objectStore("pagos");


    var elements_det = [];


    index.openCursor(null, 'nextunique').onsuccess = function (e) {
                    var result = e.target.result;
                    if (result === null) {
                        return;
                    }
                    if(result.value.estado=='1'){
                    elements.push(result.value);
                    fechas_liquidar += result.value.fecha+'<br>';
                  }
                    result.continue();
                };

    otrodato_det.openCursor().onsuccess = function (f) {
                    var result_det = f.target.result;
                    if (result_det === null) {
                        return;
                    }

                    if(result_det.value.estado=='1'){
                      elements_det.push(result_det.value);
                      total_billetera = parseFloat(total_billetera) + parseFloat(result_det.value.total);
                    }

                    console.log(total_billetera);
                    $('#transfiere').html('Transferir billetera ($'+total_billetera+')');
                    $('#total_billetera').html(total_billetera);
                    result_det.continue();
                };

                datito.oncomplete = function () {

                  console.log('elements');
                  console.log(elements);
                    var estructura = '';

                      for(var key in elements){
                        var idcob = remplazastr(elements[key].fecha,'/');

                    //  mifecha = elements[key].fecha.replace('/','');

                      estructura +='<p  class="heading flipInY animated">'
                    +'      <span class="name">Cobranza del : </span>'
                    +'      <span class="position">'+ elements[key].fecha +'</span>'
                    +'    </p>'
                    +'    <div class="active-users" id="datos_cliente">'
                    +'    </div>'
                    +'    <div id="listado_pagos_'+idcob+'" class="alarm-list"></div>'
                    +'    <center><button class="btn btn-success" onclick="" style="text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;" >Billetera Diaria ($<span id="total_pagos_'+idcob+'">0</span>)</button></center>';

                      }
                      estructura +=' <center><button class="btn btn-success" onclick="" style="text-align: center;margin-top: 15px;margin-bottom: 25px;font-weight: bold;" >Total en mi Billetera ($<span id="total_billetera"></span>)</button></center>';
                      elements = [];
                      $('.billetera').html(estructura);

                      datito_det.oncomplete = function () {

                        console.log('elements_det');
                        console.log(elements_det);

                          var iddiv='';
                          var totalesfecha='0';
                            for(var key in elements_det){
                            totalesfecha = parseFloat(totalesfecha)+parseFloat(elements_det[key].total);
                            var pagolist = '<a href="javascript:void(0)" data-cobra="'+ elements_det[key].cobrador +'" data-cliente="'+ elements_det[key].cliente2 +'" data-cliente2="'+ elements_det[key].cliente +'" data-id="'+ elements_det[key].credito +'" data-vc="'+ elements_det[key].valor +'" data-codigo="'+ elements_det[key].codigo +'" data-tc="'+ elements_det[key].totcuotas +'" data-up="'+ elements_det[key].upago +'" data-cp="'+ elements_det[key].pagas +'" data-ca="'+elements_det[key].atrasadas +'" class="waves-effect waves-light" style="color: #fff;" >'
                            +'<div class="note clearfix slideInRight animated" style="border-bottom: 1px solid;">'
                            +'    <div class="time pull-left">'
                              +'     <div class="hour">'+ elements_det[key].id +'</div>'
                                  +'   <div class="shift">#</div>'
                                  +' </div>'
                                  +' <div class="to-do pull-left">'
                                  +'   <div class="title"><b>Cod.: </b>'+ elements_det[key].producto +' <br/><b>Cuotas:</b>  '+ elements_det[key].cant +' ||<b> Valor: </b>$ '+ elements_det[key].valor +' || <b>Total:</b> $  '+ elements_det[key].total
                                  +' <br/> <b>Fecha de Pago:</b> '+ elements_det[key].fecha +' ('+ elements_det[key].hora +') </div>'
                                  // +' <div class="subject">C. Pag('+ elements_det[key].pagas +') || C. Atr('+ elements_det[key].atrasadas +') || C. Tot.('+ elements_det[key].totcuotas +')</div>'
                                +'</div>'
                          +'  </div>'
                        +'  </a>';
                          iddiv='_pagos_'+ remplazastr(elements_det[key].fecha,'/');
                          $('#listado'+iddiv).append(pagolist);
                        $('#total'+iddiv).html(parseFloat($('#total'+iddiv).text())+parseFloat(elements_det[key].total));
                          console.log($('#total'+iddiv).text());
                            }
                            console.log(pagolist);
                            elements_det = [];




                            if(fechas_liquidar==''){
                              $('#txtcobranza').html('No se encontro dinero en su Billetera');
                              $('#transfiere').hide();

                            } else {$('#fechasaliquidar').html(fechas_liquidar);}


                      }


                    };


              /*  datito.oncomplete = function () {
                    var outerHTML = '';
                    for(var key in elements){
                        outerHTML += '<a href="javascript:void(0)" onclick="abremodal(this)" data-cliente="'+ elements[key].cliente2 +'" data-id="'+ elements[key].credito +'" data-vc="'+ elements[key].valor +'" data-codigo="'+ elements[key].codigo +'" data-tc="'+ elements[key].totcuotas +'" data-up="'+ elements[key].upago +'" data-cp="'+ elements[key].pagas +'" data-ca="'+elements[key].atrasadas +'" class="waves-effect waves-light" style="color: #fff;" >'
                        +'<div class="note clearfix slideInRight animated">'
                        +'    <div class="time pull-left">'
                          +'     <div class="hour">'+ elements[key].id +'</div>'
                              +'   <div class="shift">#</div>'
                              +' </div>'
                              +' <div class="to-do pull-left">'
                              +'   <div class="title">Cod.: '+ elements[key].codigo +' || $ '+ elements[key].valor +'<br/> U. Pago: '+ elements[key].upago +' </div>'
                              +' <div class="subject">C. Pag('+ elements[key].pagas +') || C. Atr('+ elements[key].atrasadas +') || C. Tot.('+ elements[key].totcuotas +')</div>'
                            +'</div>'
                      +'  </div>'
                    +'  </a>';}

                    elements = [];
                    $('#listado_pagos').html(outerHTML);

                }; */

            }
      }

function desactivabilletera(){

      var db = indexedDB.open("internaldb");
      db.onsuccess = function (event) {
      db = event.target.result;
        var datito = db.transaction("pagos","readwrite");
        var otrodato = datito.objectStore("pagos");
        var elements = [];

        otrodato.openCursor().onsuccess = function (f) {
                        var result = f.target.result;
                        if (result === null) {
                            return;
                        }
                      elements.put(result.value.estado='0');
                      result.continue();
                  };
                    datito.oncomplete = function () {

              }
        }
    }


    function eliminodb(dbe){
      var db = indexedDB.open("internaldb");
      db.onsuccess = function (event) {
      db = event.target.result;
        var datito = db.transaction(dbe,"readwrite");
        var otrodato = datito.objectStore(dbe);
        otrodato.deleteObjectStore(dbe);
}
    }
