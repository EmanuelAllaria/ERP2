<?php
$cliente = $_GET['id'];
$con_cliente = $link->query("SELECT * FROM clientes LEFT JOIN ciudad on ciudad.id_ciudad = clientes.ciudad_clientes WHERE clientes.estado_clientes ='1' and id_clientes='$cliente' ");
$row = mysqli_fetch_array($con_cliente);
$prov = $row['provincia_id'];
?>
<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Editar Cliente</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item "><a href="index.php?pagina=clientes">Clientes</a></li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Modifique los datos del cliente</h4>
        <form class="form-horizontal form-material">
          <div class="row">

            <div class="col-md-6 m-b-20">

              <h3>Datos Comerciales </h3>

            </div>

            <div class="col-md-6 m-b-20 afip">

              <a href="javascriot:void(0)" class="btn-success btn btn-afip " onclick="datos_afip()" disabled=""><i class="fa fa-refresh"></i> AFIP</a>

            </div>

          </div>
          <div class="row">
            <div class="col-md-4 m-b-20">
              <input placeholder="Nombre y Apellido / Razon Social" type="text" name="razon_editf" class="form-control razon_editf" value="<?php echo $row['razon_com_clientes']; ?>" required="">
            </div>

            <div class="col-md-4 m-b-20">

              <select name="tipodni_editf" id="tipodni_editf" class="form-control tipodni_editf" required="">

                <option value="" disabled selected>Seleccione Tipo documento</option>

                <option value="DNI" <?php if ($row['tipodni_clientes'] == 'DNI') {
                                      echo ' selected';
                                    } ?>>DNI</option>

                <option value="CUIT" <?php if ($row['tipodni_clientes'] == 'CUIT') {
                                        echo ' selected';
                                      } ?>>CUIT</option>

                <option value="CUIL" <?php if ($row['tipodni_clientes'] == 'CUIL') {
                                        echo ' selected';
                                      } ?>>CUIL</option>

              </select>

            </div>

            <div class="col-md-4 m-b-20">

              <input type="number" placeholder="Numero documento (sin guion)" maxlength="11" class="form-control cuit_editf" name="cuit_editf" value="<?php echo $row['dni_clientes']; ?>">

            </div>

            <div class="col-md-6 m-b-20">

              <select class="form-control condicioniva_editf" name="condicioniva_editf" required>

                <option value="" disabled selected>Seleccione condicion IVA</option>

                <option value="cf" <?php if ($row['condicioniva_com_clientes'] == 'cf') {
                                      echo ' selected';
                                    } ?>>Consumidor Final</option>

                <option value="ri" <?php if ($row['condicioniva_com_clientes'] == 'ri') {
                                      echo ' selected';
                                    } ?>>Responsable Inscripto</option>

                <option value="nor" <?php if ($row['condicioniva_com_clientes'] == 'nor') {
                                      echo ' selected';
                                    } ?>>No Responsable</option>

                <option value="m" <?php if ($row['condicioniva_com_clientes'] == 'm') {
                                    echo ' selected';
                                  } ?>>Responsable Monotributista</option>

                <option value="e" <?php if ($row['condicioniva_com_clientes'] == 'e') {
                                    echo ' selected';
                                  } ?>>Excento</option>

              </select>

            </div>

            <div class="col-md-6 m-b-20">

              <select class="form-control rubro_editf" name="rubro_editf">

                <option value="" disabled selected>Seleccione un rubro comercial</option>

                <?php

                $con_rubro = $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");

                while ($rubro  = mysqli_fetch_array($con_rubro)) {
                  echo '

                                            <option value="' . $rubro['id_rubros'] . '"';

                  if ($rubro['id_rubros'] == $row['rubro_com_clientes']) {
                    echo ' selected ';
                  }

                  echo '>' . utf8_decode($rubro['nombre_rubros']) . '</option>';
                }

                ?>

                <option value='2545'>Otros</option>

              </select>

            </div>
            <div class="col-md-4 m-b-20">
              <input type="phone" placeholder="Telefono Fijo" value="<?php echo $row['telefono_clientes']; ?>" class="form-control telfijo_editf" name="telfijo_editf">
            </div>
            <div class="col-md-4 m-b-20">
              <input type="phone" placeholder="Celular" value="<?php echo $row['celular_clientes']; ?>" class="form-control celular_editf" name="celular_editf" required="">
            </div>

            <div class="col-md-4 m-b-20">

              <input type="email" placeholder="E-mail" value="<?php echo $row['email_clientes']; ?>" class="form-control email_editf" name="email_editf" id="email_editf">

            </div>


            <div class="col-md-6 m-b-20">
              <select class="form-control provincia_editf" onchange="buscaciudad()" name="provincia_editf" id="provincia_editf" required="">
                <option value="" disabled="" selected="">Seleccione una Provincia</option>
                <?php $consul_provincia = $link->query("SELECT * FROM provincia order by provincia_nombre asc") or die(mysqli_error());
                while ($provincia = mysqli_fetch_array($consul_provincia)) {
                  echo '
                                        <option value="' . $provincia['id_provincia'] . '" ';
                  if ($provincia['id_provincia'] == $row['provincia_id']) {
                    echo ' selected';
                  }
                  echo ' >' . $provincia['provincia_nombre'] . '</option>';
                } ?>
              </select>
            </div>
            <div class="col-md-6 m-b-20">
              <select id="ciudad_editf" name="ciudad_editf" class="form-control ciudad_editf" required="">
                <?php $consul_ciudades2 = $link->query("SELECT * FROM ciudad order by ciudad_nombre asc") or die(mysqli_error());
                echo '<option value="" disabled="" selected="">Seleccione una Localidad</option>';
                while ($ciudad = mysqli_fetch_array($consul_ciudades2)) {
                  echo '<option value="' . $ciudad['id_ciudad'] . '"  ';
                  if ($ciudad['id_ciudad'] == $row['id_ciudad']) {
                    echo ' selected';
                  }
                  echo ' >' . $ciudad['ciudad_nombre'] . '</option>';
                } ?>
              </select>
            </div>

            <div class="col-md-8 m-b-20">

              <input type="text" placeholder="Dirección" class="form-control direccion_com_editf" name="direccion_com_editf" value="<?php echo $row['direccion_clientes']; ?>" required="">

            </div>

            <div class="col-md-4 m-b-20">

              <input type="number" placeholder="Numero" class="form-control numero_com_editf" name="numero_com_editf" value="<?php echo $row['dirnum_clientes']; ?>" required="">

            </div>

            <div class="col-md-6 m-b-20">

              <input type="text" placeholder="Piso" class="form-control piso_com_editf" name="piso_com_editf" value="<?php echo $row['piso_clientes']; ?>">

            </div>

            <div class="col-md-6 m-b-20">

              <input type="text" placeholder="Depto" class="form-control depto_com_editf" name="depto_com_editf" value="<?php echo $row['depto_clientes']; ?>">

            </div>

            <div class="col-md-12 m-b-20">

              <textarea class="form-control notas_edit_editf" name="notas" placeholder="Ingrese una nota"><?php echo $row['notas_clientes']; ?></textarea>

            </div>
          </div>
      </div>


      <div class="col-md-12 m-b-20">
        <h3>Datos Adicinales</h3>
        <div class="row">

          
          <div class="col-md-3 m-b-20">
            <label for="cumple">Fecha de Cumpleaños</label>
            <input type="date" placeholder="Cumpleaños" class="form-control cumple_editf" id="cumple_editf" value="<?php echo date('Y-m-d', strtotime($row['fechacumple_clientes'])); ?>" name="cumple_editf" required="">

          </div>
          <div class="col-md-3 m-b-20">
            <label for="ecivil">Zona / Personal</label>
            <select class="form-control asignado_editf" id="asignado_editf" name="asignado_editf">
              <option value="" disabled="" selected="">Seleccione un personal</option>
              <?php
              $con_zona = $link->query("SELECT * FROM personal where estado ='1' and area='Reparto' order by nombre asc, apellido asc");
              while ($zona  = mysqli_fetch_array($con_zona)) {
                echo '<option value="' . $zona['id'] . '"';
                if ($zona['id'] == $row['asignado_clientes']) {
                  echo ' selected ';
                }
                echo '>' . $zona['nombre'] . ', ' . $zona['apellido'] . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-3 m-b-20">
            <label for="ecivil">Lista de precios</label>
            <select class="form-control listap" id="listap_editf" name="listap">
              <option value="" disabled="" selected="">Seleccione uno</option>
              <option value="1" <?php if ($row['lista_precio'] == '1') {
                                  echo 'selected';
                                } ?>>Lista 1</option>
              <option value="2" <?php if ($row['lista_precio'] == '2') {
                                  echo 'selected';
                                } ?>>Lista 2</option>
              <option value="3" <?php if ($row['lista_precio'] == '3') {
                                  echo 'selected';
                                } ?>>Lista 3</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 m-b-20">
            <label for="financia">Habilitado Financiacion</label>
            <select class="form-control financiacion_editf" id="financia_editf" name="financia_editf">
              <option value="" disabled="" selected="">Seleccione</option>
              <option value="1" <?php if ($row['financiacion_com_clientes'] == '1') {
                                  echo 'selected';
                                } ?>>SI</option>
              <option value="0" <?php if ($row['financiacion_com_clientes'] == '0') {
                                  echo 'selected';
                                } ?>>NO</option>
            </select>
          </div>
          <div class="col-md-4 m-b-20">
            <label for="ecivil">Tope de Financiacion</label>
            <input type="number" step="any" placeholder="Indique el limite" class="form-control " value="<?php echo $row['topefinancia_com_clientes'] ?>" id="limitefinancia_editf" name="limitefinancia_editf" required="">
          </div>
        <div class="col-md-4 m-b-20">
                <label for="financia">Días Financiación</label>
                <select class="form-control financia_dias" id="financia_dias_editf" name="financia_dias">
                  <option <?php if ($row['dias_financiacion'] == '7') {
                                  echo 'selected';
                                } ?>>7</option>
                  <option <?php if ($row['dias_financiacion'] == '14') {
                                  echo 'selected';
                                } ?>>14</option>
                                <option <?php if ($row['dias_financiacion'] == '30') {
                                  echo 'selected';
                                } ?>>30</option>
                                <option <?php if ($row['dias_financiacion'] == '90') {
                                  echo 'selected';
                                } ?>>90</option>
                </select>
              </div>
        </div>
        <div class="form-group">
          <div class="col-md-12 m-b-20">
            <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
              <span><i class="ion-upload m-r-5"></i>Cargar Imagen</span>
              <input type="file" class="upload">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12 m-b-20" style="text-align:right">
            <a href="javascript:void(0);" onclick="editar_cliente('<?php echo $_GET['id'] ?>')" class="btn btn-success waves-effect">Guardar</a>
            <a href="index.php?pagina=clientes" class="btn btn-danger waves-effect">Cancelar</a>
          </div>
        </div>
      </div>


      </form>
    </div>
  </div>
</div>

</div>