<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Agregar Proveedor</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=proveedores">Proveedores</a></li>
        <li class="breadcrumb-item active">Agregar Proveedor</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div style="height: 83vh;" class="card col-md-3">
      <div class="card-body">
        <h4 class="card-title">Ingrese los datos del Proveedor</h4>
        <form class="form-horizontal form-material">
          <div class="col-md-10 m-b-20">
            <select class="form-control provincia" onchange="buscaciudad()" name="provincia" id="provincia" required="">
              <option value="" disabled="" selected="">Provincia</option>
                    <?php $consul_provincia = $link->query("SELECT * FROM provincia order by provincia_nombre asc") or die(mysqli_error());
                    while ($provincia = mysqli_fetch_array($consul_provincia)) {
                      echo '
                                <option value="' . $provincia['id_provincia'] . '">' . $provincia['provincia_nombre'] . '</option>';
                    } ?>
            </select>
          </div>
          <div class="col-md-10 m-b-20">
            <select id="ciudad" name="ciudad" class="form-control ciudad" required="" disabled="disabled">
                    <?php $consul_ciudades2 = $link->query("SELECT * FROM ciudad order by ciudad_nombre asc") or die(mysqli_error());
                    echo '<option value="" disabled="" selected="">Ciudad</option>';
                    while ($ciudad = mysqli_fetch_array($consul_ciudades2)) {
                      echo '<option value="' . $ciudad['id_ciudad'] . '">' . $ciudad['ciudad_nombre'] . '</option>';
                    } ?>
            </select>
          </div>
          <div class="col-md-10 m-b-20">
            <input type="text" placeholder="Dirección" class="form-control direccion" name="direccion" value="" required="">
          </div>
          <div class="col-md-10 m-b-20">
            <input type="number" placeholder="Numero" class="form-control numero" name="numero" value="" required="">
          </div>
          <div class="col-md-10 m-b-20">
            <input type="text" placeholder="Piso" class="form-control piso" name="piso" value="">
          </div>
          <div class="col-md-10 m-b-20">
            <input type="text" placeholder="Depto" class="form-control depto" name="depto" value="">
          </div>
        </form>
      </div>

      <div class="row form-group">
        <div class="col-md-12 text-center m-b-20" text-cente style="text-align:right">
          <a href="javascript:void(0);" id="agregar_proveedor" class="btn btn-success waves-effect">Guardar</a>
          <a href="index.php?pagina=proveedores" class="btn btn-danger waves-effect">Cancelar</a>
        </div>
      </div>
    </div>


    <div class="card-body col-md-6">
      <div class="col-md-12 m-b-20">
        <div class="row">
            <div class="col-md-6 m-b-20">
              <h3>Datos Comerciales </h3>
            </div>
            <div class="col-md-6 m-b-20 afip">
              <a href="javascriot:void(0)" class="btn-success btn btn-afip " onclick="datos_afip()" disabled><i class="fa fa-refresh"></i> AFIP</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 m-b-20">
              <input placeholder="Razon Social" type="text" name="razon" class="form-control razon" required="">
            </div>
            <div class="col-md-6 m-b-20">
              <input placeholder="Nombre de fantasía" type="text" name="nombref" class="form-control nombref" required="">
            </div>
        </div>

        <div class="row">
          <div class="col-md-6 m-b-20">
            <select class="form-control condicioniva" name="condicioniva" required>
                <option value="" disabled selected>Seleccione condicion IVA</option>
                <option value="cf">Consumidor Final</option>
                <option value="ri">Responsable Inscripto</option>
                <option value="nor">No Responsable</option>
                <option value="m">Responsable Monotributista</option>
                <option value="e">Excento</option>
            </select>
          </div>

          <div class="col-md-6 m-b-20">
              <select class="form-control rubro" name="rubro">
                    <option value="" disabled selected>Seleccione un rubro comercial</option>
                    <?php
                    $con_rubro = $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");
                    while ($rubro  = mysqli_fetch_array($con_rubro)) {
                      echo '
                                <option value="' . $rubro['id_rubros'] . '">' . $rubro['nombre_rubros'] . '</option>';
                    }
                    ?>
                <option value='2545'>Otros</option>
              </select>
            </div>
        </div>

        <div class="row">
          <div class="col-md-6 m-b-20">
            <input type="email" placeholder="E-mail" class="form-control email" name="email" id="email">
          </div>
          <div class="col-md-6 m-b-20">
            <input type="email" placeholder="E-mail secundario" class="form-control email2" name="email2">
           </div>
        </div>

        <div class="row">
          <div class="col-md-6 m-b-20">
            <input type="phone" placeholder="Celular" class="form-control celular" name="celular" required="">
          </div>
          <div class="col-md-6 m-b-20">
            <input type="phone" placeholder="Celular Secundario" class="form-control celular2" name="celularSecun" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 m-b-20">
            <input type="number" placeholder="CUIT/CUIL (sin guion)" maxlength="11" class="form-control cuit" name="cuit" />
          </div>
          <div class="col-md-6 m-b-20">
            <input type="phone" placeholder="Telefono Fijo" class="form-control telfijo" name="telfijo">
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 m-b-20">
            <textarea class="form-control notas" name="notas" placeholder="Ingrese una nota"></textarea>
          </div>
        </div>
      </div>

      <div class="col-md-12" >
        <div class="row" style="margin-top: -15px;">
            <div class="col-md-6 m-b-20">
              <h3>Datos Bancarios</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 m-b-20">
              <input type="text" placeholder="Banco" class="form-control banco" name="banco">
            </div>
            <div class="col-md-6 m-b-20">
              <input type="number" placeholder="Número de cuenta" class="form-control nroCuenta" name="nroCuenta">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 m-b-20">
              <input type="number" placeholder="CBU" class="form-control cbu" name="cbu">
            </div>
            <div class="col-md-6 m-b-20">
              <input type="text" placeholder="Alias" class="form-control alias" name="alias">
            </div>
        </div>
      </div>
    </div>
    <div class="card-body col-md-3">
      <div class="form-group">
          <div class="col-md-12 m-b-20">
            <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
              <span><i class="ion-upload m-r-5"></i>Cargar Imagen</span>
              <input type="file" class="upload" name="upload">
            </div>
          </div>
      </div>
    </div>
  </div>
  </div>
</div>
</div>