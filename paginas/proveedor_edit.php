<?php
$proveedor = $_GET['id'];
$con_proveedor = $link->query("SELECT * FROM proveedores left join ciudad on ciudad.id_ciudad = proveedores.ciudad_proveedor where  proveedores.estado_proveedor ='1' and id_proveedor='$proveedor' ");
$row = mysqli_fetch_array($con_proveedor);
$prov = $row['provincia_id'];
?>
<script src="./js/funciones.js?v=1"></script>
<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Editar Proveedor</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item "><a href="index.php?pagina=proveedores">Proveedores</a></li>
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
        <h4 class="card-title">Modifique los datos del proveedor</h4>
        <form class="form-horizontal form-material">

          <div class="row">

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
                <div class="col-md-4 m-b-20">
                  <input placeholder="Razon Social" type="text" name="razon" class="form-control razon" required="" value="<?php echo $row['razon_com_proveedor']; ?>">
                </div>
                <div class="col-md-4 m-b-20">
                  <input placeholder="Nombre de fantasía" type="text" name="nombref" class="form-control nombref" required="" value="<?php echo $row['nombre_fantasia']; ?>">
                </div>
                <div class="col-md-4 m-b-20">
                  <select class="form-control rubro" name="rubro">
                    <option value="" disabled selected>Seleccione un rubro comercial</option>
                    <?php
                    $con_rubro = $link->query("SELECT * FROM rubros where estado_rubros ='1' order by nombre_rubros asc");
                    while ($rubro  = mysqli_fetch_array($con_rubro)) {
                      echo '
                                        <option value="' . $rubro['id_rubros'] . '" ';
                      if ($rubro['id_rubros'] == $row['rubro_com_proveedor']) {
                        echo ' selected';
                      }
                      echo ' >' . $rubro['nombre_rubros'] . '</option>';
                    }
                    ?>
                    <option value='2545' <?php if ($row['rubro_com_proveedor'] == '2545') {
                                            echo ' selected';
                                          } ?>>Otros</option>
                  </select>
                </div>

                <div class="col-md-6 m-b-20">
                  <select class="form-control condicioniva" name="condicioniva" required>
                    <option value="" disabled>Seleccione condicion IVA</option>
                    <option value="cf" <?php if ($row['condicioniva_com_proveedor'] == 'cf') {
                                          echo ' selected';
                                        } ?>>Consumidor Final</option>
                    <option value="ri" <?php if ($row['condicioniva_com_proveedor'] == 'ri') {
                                          echo ' selected';
                                        } ?>>Responsable Inscripto</option>
                    <option value="nor" <?php if ($row['condicioniva_com_proveedor'] == 'nor') {
                                          echo ' selected';
                                        } ?>>No Responsable</option>
                    <option value="m" <?php if ($row['condicioniva_com_proveedor'] == 'm') {
                                        echo ' selected';
                                      } ?>>Responsable Monotributista</option>
                    <option value="e" <?php if ($row['condicioniva_com_proveedor'] == 'e') {
                                        echo ' selected';
                                      } ?>>Excento</option>
                  </select>
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="number" placeholder="CUIT/CUIL (sin guion)" maxlength="11" value="<?php echo $row['cuitcuil_com_proveedor']; ?>" class="form-control cuit" name="cuit" />
                </div>

                <div class="col-md-6 m-b-20">
                  <input type="email" placeholder="E-mail" class="form-control email" name="email" id="email" value="<?php echo $row['email_proveedor']; ?>">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="email" placeholder="E-mail" class="form-control email2" name="emailDos" id="emailDos" value="<?php echo $row['emailDos_proveedor']; ?>">
                </div>
                <div class="col-md-4 m-b-20">
                  <input type="phone" placeholder="Telefono Fijo Comercial" class="form-control telfijo" value="<?php echo $row['telefono_com_proveedor']; ?>" name="telfijo">
                </div>
                <div class="col-md-4 m-b-20">
                  <input type="phone" placeholder="Celular Comercial" class="form-control celular" name="celular" value="<?php echo $row['celular_com_proveedor']; ?>">
                </div>
                <div class="col-md-4 m-b-20">
                  <input type="phone" placeholder="Celular Comercial Secundario" class="form-control celular2" name="celular2" value="<?php echo $row['celularDos_com_proveedor']; ?>">
                </div>
                <div class="col-md-6 m-b-20">
                  <select class="form-control provincia" onchange="buscaciudad()" name="provincia" id="provincia" required="">
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
                  <select id="ciudad" name="ciudad" class="form-control ciudad" required="">
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
                  <input type="text" placeholder="Dirección " class="form-control direccion" name="direccion" value="<?php echo $row['direccion_com_proveedor']; ?>">
                </div>
                <div class="col-md-4 m-b-20">
                  <input type="number" placeholder="Numero" class="form-control numero" name="numero" value="<?php echo $row['dirnum_com_proveedor']; ?>">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="text" placeholder="Piso" class="form-control piso" name="piso" value="<?php echo $row['piso_com_proveedor']; ?>">
                </div>
                <div class="col-md-6 m-b-20">
                  <input type="text" placeholder="Depto" class="form-control depto" name="depto" value="<?php echo $row['depto_com_proveedor']; ?>">
                </div>
                <div class="col-md-12 m-b-20">
                  <textarea class="form-control notas" name="notas" placeholder="Ingrese una nota" value="<?php echo $row['notas_proveedor']; ?>"><?php echo $row['notas_proveedor']; ?></textarea>
                </div>

              </div>
            </div>

              <div class="col-md-12 m-b-20">
                  <h3>Datos Bancarios</h3>
                </div>
              <div class="row col-md-12">
                <div class="col-md-3 m-b-20">
                  <input type="text" placeholder="Banco" class="form-control banco" name="banco" value="<?php echo $row['banco']; ?>">
                </div>
                <div class="col-md-3 m-b-20">
                  <input type="number" placeholder="Número de cuenta" class="form-control nroCuenta" name="nroCuenta" value="<?php echo $row['nroCuenta']; ?>">
                </div>
                <div class="col-md-3 m-b-20">
                  <input type="number" placeholder="CBU" class="form-control cbu" name="cbu" value="<?php echo $row['cbu']; ?>">
                </div>

                <div class="col-md-3 m-b-20">
                  <input type="text" placeholder="Alias" class="form-control alias" name="alias" value="<?php echo $row['alias']; ?>">
                </div>

                <div class="form-group">
                  <div class="col-md-12 m-b-20" style="text-align:right">
                    <a href="javascript:void(0);" class="btn btn-success waves-effect" onclick="editar_prov(<?=$_GET['id']?>);" id="editar_proveedor">Guardar Cambios</a>
                    <a href="index.php?pagina=proveedores" class="btn btn-danger waves-effect">Cancelar</a>
                  </div>
                </div>
                <!-- </div>
                <div class="col-md-3 m-b-20">
                  <label for="tipo_cuenta">Tipo de Cuenta</label>

                  <select class="form-control tipo_cuenta" id="tipo_cuenta" name="tipo_cuenta">
                    <option value="" disabled="" selected="">Seleccione uno</option>
                    <option value="1">Caja de Ahorro</option>
                    <option value="2">Cuenta Corriente</option>
                  </select>

                </div>
                <div class="col-md-3 m-b-20">
                  <label for="numero_cuenta">Numero</label>
                  <input type="number" placeholder="Numero de Cuenta/CBU" class="form-control numero_cuenta" id="numero_cuenta" name="numero_cuenta" required="">
                </div>
                <div class="col-md-3 m-b-20">
                  <label for="alias_cuenta">Alias</label>
                  <input type="number" placeholder="Alias" class="form-control alias_cuenta" id="alias_cuenta" name="alias_cuenta" required="">
                </div>
                <div class="col-md-12 m-b-20">
                  <hr>
                  <?php $consul_bancos_prov = $link->query("SELECT * FROM `bancos_proveedores` INNER JOIN bancos on bancos.id_banco = bancos_proveedores.entidad_bancoprov WHERE estado_bancoprov = 1 and proveedor_bancoprov=$proveedor") or die(mysqli_error());
                  if (mysqli_num_rows($consul_bancos_prov)) {
                    echo '<table style="width:100%">
                                                  <tr>
                                                    <th>Entidad</th>
                                                    <th>Tipo de Cuenta</th>
                                                    <th>Numero </th>
                                                    <th>Alias</th>
                                                    <th>Acciones</th>
                                                  </tr>';

                    while ($banco_prov = mysqli_fetch_array($consul_bancos_prov)) {
                      if ($banco_prov['tipocuenta_bancoprov'] == 1) {
                        $tipo_cuenta = 'Caja de Ahorro';
                      }
                      if ($banco_prov['tipocuenta_bancoprov'] == 2) {
                        $tipo_cuenta = 'Cuenta Corriente';
                      }
                      echo '<tr>
                                                    <td>' . $banco_prov['nombre_banco'] . '</td>
                                                    <td>' . $tipo_cuenta . '</td>
                                                    <td>' . $banco_prov['numero_bancoprov'] . '</td>
                                                    <td>' . $banco_prov['alias_bancoprov'] . '</td>
                                                    <td><a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#edit_cuenta_' . $banco_prov['id_bancoprov'] . '" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                                                        &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#del_cuenta_' . $banco_prov['id_bancoprov'] . '" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                                                    </td>
                                                  </tr>';
                    }
                    echo '</table>';
                  } else {
                    echo '<p style="text-align: center;">El Proveedor no dispone de cuentas bancarias registradas</p>';
                  }
                  ?>


                </div>

                <div class="form-group">
                  <div class="col-md-12 m-b-20">
                    <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
                      <span><i class="ion-upload m-r-5"></i>Cargar Imagen</span>
                      <input type="file" class="upload">
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div>
                 -->
              </div>


        </form>
      </div>
    </div>
  </div>

</div>