<div class="container-fluid">

  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado del Personal</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item"><a href="index.php?pagina=personal">Personal</a></li>
        <?php if ($_GET['buscar']) {
          echo '<li class="breadcrumb-item"><a href="#">Buscar: [' . $_GET['buscar'] . ']</a></li>';
        } ?>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block" method="get">
        <input type="hidden" name="pagina" value="personal">
        <input type="text" id="buscador" name="buscar" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <?php if ($_GET['buscar']) {
            echo '<h4 class="card-title">Resulados de [' . $_GET['buscar'] . ']...</h4>';
          } else {
            echo '<h4 class="card-title">Listado</h4>';
          } ?>
          <a href="#" data-toggle="modal" data-target="#newPersonal" class="btn btn-info btn-rounded">Cargar nuevo Personal</a>

          <h6 class="card-subtitle"></h6>
          <div class="table-responsive">
            <table id="personal_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <!--  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th> -->
                  <th>Avatar</th>
                  <th>Nombre</th>
                  <th>E-mail</th>
                  <th>Telefono</th>
                  <th>Direccion </th>
                  <th>Area </th>
                  <th>Estado </th>
                  <th>Acciones&nbsp;&nbsp;&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $busqueda = '';
                if ($_GET['buscar']) {
                  $palabra = $_GET['buscar'];
                  $busqueda = "and (apellido like '%$palabra%' or nombre like '%$palabra%' or dni like '%$palabra%' )";
                }
                //$con_clientes = $link->query("SELECT * FROM clientes inner join clientes_comercios on clientes_comercios.cliente_comclientes = clientes.id_clientes INNER join ciudad on ciudad.id_ciudad = clientes.ciudad_clientes where clientes_comercios.estado_comclientes ='1' order by clientes.apellido_clientes, clientes.nombre_clientes ASC ");
                $con_personal = $link->query("SELECT * FROM personal left join ciudad on ciudad.id_ciudad = personal.ciudad where estado !='0' $busqueda order by apellido ASC ");
                while ($row = mysqli_fetch_array($con_personal)) {
                ?>
                  <tr>
                    <td class="font-weight-normal">
                      <a href="index.php?pagina=personal_view&id=<?php echo $row['id'] ?>">
                        <img src="img/avatar/<?php echo $row['foto']; ?>" class="img-circle" width="50px">
                      </a>
                    </td>

                    <td class="font-weight-normal">
                      <a href="index.php?pagina=personal_view&id=<?php echo $row['id'] ?>">
                        <?php echo $row['apellido'] . ', ' . $row['nombre'] ?>
                      </a>
                    </td>

                    <td>
                      <a href="mailto:<?php echo $row['email'] ?>?subject=Big%20Pollo&body=Hola,<?php echo $row['nombre'] ?>"><?php echo $row['email'] ?>
                      </a>
                    </td>
                    <td>
                      <a href="tel:<?php if ($row['telefono'] != '') {
                                      echo $row['telefono'];
                                    } else {
                                      echo $row['celular'];
                                    } ?>">
                        <?php if ($row['celular'] != '') {
                          echo $row['celular'];
                        } else {
                          echo $row['telefono'];
                        } ?>
                      </a>
                    </td>
                    <td class="font-weight-normal"><?php echo $row['direccion'] . ', ' . $row['direccion_num'] . ' ( ' . $row['ciudad_alias'] . ' )' ?></td>
                    <td class="font-weight-normal"><?php echo $row['area'] ?></td>
                    <td class="font-weight-normal"><span class="label label-<?php if ($row['estado'] == '2') {
                                                                              echo 'danger">Inactivo';
                                                                            } else {
                                                                              echo 'success">Activo';
                                                                            } ?></span></td>
                                                <td >
                                                  <a class=" btn-pure btn-outline-success view-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=personal_view&id=<?php echo $row['id'] ?>" data-toggle="tooltip" data-original-title="Ver"><i class="ti-eye" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#editPersonal_<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>" data-toggle="tooltip" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#del_<?php echo $row['id'] ?>" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                <?php echo '
                                            <div class="modal fade" id="del_' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <h4 >Seguro que desea eliminar el Personal "' . $row['nombre'] . ' ' . $row['apellido'] . '" ?</h4>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                    <button onclick="elimina_p(' . $row['id'] . ')" class="btn btn-primary">Si, Eliminar</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                } ?>
              </tbody>
              <tfoot>
                <tr>


                  <td colspan="6">
                    <div class="text-right">
                      <ul class="pagination">
                        <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li>
                        <li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li>
                        <li class="footable-page active"><a data-page="0" href="#">1</a></li>
                        <li class="footable-page"><a data-page="1" href="#">2</a></li>
                        <li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li>
                        <li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- add Pesonal -->
<div class="modal fade" id="newPersonal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4>
          <center>Ingrese los datos del personal</center>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" class="form-control" id='nombre'>
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" class="form-control" id='apellido'>
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" id='email'>
            <label for="telefono">Telefono</label>
            <input type="tel" name="telefono" class="form-control" id='telefono'>
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" class="form-control" id='direccion'>
            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control" id='password'>
            <label for="pin">Pin</label>
            <input type="pin" name="pin" class="form-control" id='pin'>
            <label for="area">Area</label>
            <select id="area" name="area" class="form-control">
              <option value='' selected>Seleccione un Area</option>
              <option value="Admin">Administracion</option>
              <option value="Reparto">Reparto</option>
              <option value="Despacho">Despacho</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <a href="#" onclick="nuevoPersonal()" class="btn btn-success">Confirmar</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<?php
$con_personal2 = $link->query("SELECT * FROM personal LEFT JOIN ciudad ON ciudad.id_ciudad = personal.ciudad WHERE estado != '0' $busqueda ORDER BY apellido ASC");
while ($row2 = mysqli_fetch_array($con_personal2)) {
?>
  <div class="modal fade" id="editPersonal_<?php echo $row2['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <center>Ingrese los datos a editar</center>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_<?php echo $row2['id']; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $row2['id']; ?>">
            <div class="personal_editado">
              <h3>Personal:</h3>
              <h4><?php echo $row2['apellido'] . ',' . $row2['nombre']; ?></h4>
            </div>
            <div class="form-group">
              <label for="telefono">Celular</label>
              <input type="tel" name="telefono" id="telefono-edit-<?php echo $row2['id']; ?>" class="form-control" value="<?php echo $row2['celular']; ?>">
              <label for="direccion">Dirección</label>
              <input type="text" name="direccion" id="direccion-edit-<?php echo $row2['id']; ?>" class="form-control" value="<?php echo $row2['direccion']; ?>">
              <label for="area">Area</label>
              <select id="area-edit-<?php echo $row2['id']; ?>" name="area" class="form-control">
                <option value='' <?php echo ($row2['area'] == '') ? 'selected' : ''; ?>>Seleccione un Area</option>
                <option value="Admin" <?php echo ($row2['area'] == 'Admin') ? 'selected' : ''; ?>>Administracion</option>
                <option value="Reparto" <?php echo ($row2['area'] == 'Reparto') ? 'selected' : ''; ?>>Reparto</option>
                <option value="Despacho" <?php echo ($row2['area'] == 'Despacho') ? 'selected' : ''; ?>>Despacho</option>
              </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="editarPersonal(<?php echo $row2['id']; ?>);" class="btn btn-success">Confirmar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
?>


<script>
  $(function() {
    var availableTags = [<?php
                          mysqli_data_seek($con_personal, 0);
                          while ($com_sul = mysqli_fetch_array($con_personal)) {
                            echo '"' . $com_sul['nombre'] . '",';
                          } ?>];
    $("#buscador").autocomplete({
      source: availableTags
    });
  });

  function nuevoPersonal() {
    if (
      $('#nombre').val() == '' ||
      $('#apellido').val() == '' ||
      $('#email').val() == '' ||
      $('#telefono').val() == '' ||
      $('#direccion').val() == '' ||
      $('#password').val() == '' ||
      $('#pin').val() == '' ||
      $('#area').val() == ''
    ) {
      alert('Complete todos los campos');
      return;
    } else {

      var string = "accion=altaPersonal&area=" + $('#area').val() + "&nombre=" + $('#nombre').val() + "&apellido=" + $('#apellido').val() + "&email=" + $('#email').val() + "&telefono=" + $('#telefono').val() + "&direccion=" + $('#direccion').val() + "&password=" + $('#password').val() + "&pin=" + $('#pin').val();
      $.ajax({
        type: "POST",
        url: "procesos/crud.php?",
        data: string,
        success: function(data) {
          if (data == 'TRUE') {
            alert('El personal se creo correctamente');
            window.location.href = "index.php?pagina=personal";
          } else {
            alert('No se pudo crear el personal');
          }
        }
      });
    }
  }

  function editarPersonal(id) {
    if (
      $('#telefono-edit-' + id).val() == '' ||
      $('#direccion-edit-' + id).val() == '' ||
      $('#area-edit-' + id).val() == ''
    ) {
      alert('Complete todos los campos');
    } else {

      var string = "accion=editarPersonal&area=" + $('#area-edit-' + id).val() + "&telefono=" + $('#telefono-edit-' + id).val() + "&direccion=" + $('#direccion-edit-' + id).val() + "&id=" + id;
      $.ajax({
        type: "POST",
        url: "procesos/crud.php?",
        data: string,
        success: function(data) {
          if (data == 'TRUE') {
            alert('El personal se editó correctamente');
            window.location.href = "index.php?pagina=personal";
          } else {
            alert('No se pudo editar el personal');
            console.log(data);
          }
        }
      });
    }
  }
</script>