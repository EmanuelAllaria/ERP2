<style>
  .iconotd {
    font-family: 'FontAwesome', 'Second Font name';
    font-size: large;
    text-align: center;
  }
</style>
<div class="container-fluid">
  <div class="row page-titles">
    <div class="col-md-12">
      <h4 class="text-white">Listado de Rubros</h4>
    </div>
    <div class="col-md-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active">Rubros</li>
      </ol>
    </div>
    <div class="col-md-6 text-right">
      <form class="app-search d-none d-md-block d-lg-block">
        <input type="text" class="form-control" placeholder="Buscar...">
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header bg-info">
          <h4 class="mb-0 text-white">Listado de Rubro</h4>
        </div>
        <div class="card-body">

          <div class="table-responsive">
            <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
              <thead>
                <tr>
                  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">Nombre<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable">Imagen<span class="footable-sort-indicator"></span></th>
                  <th class="footable-sortable" style="width: 110px;">Acciones<span class="footable-sort-indicator"></span></th>

                </tr>
              </thead>
              <tbody>
                <?php

                $con_rubros = $link->query("SELECT * FROM `rubros` WHERE `estado_rubros` = 1 AND `listar_rubros` = 1 ORDER BY `rubros`.`nombre_rubros` ASC");
                $i = 0;
                while ($row = mysqli_fetch_array($con_rubros)) {
                  echo '

                                              <tr>
                                                  <td class="font-weight-normal"><span class="footable-toggle"></span>' . $row['id_rubros'] . '</td>
                                                  <td class="font-weight-normal"><span class="footable-toggle"></span>' . $row['nombre_rubros'] . '</td>';
                  if ($row['imagen_rubros'] != '') {
                    echo '<td class="font-weight-normal iconotd" style="vertical-align: middle;font-size: 30px;padding: 0px;"><img class="img-circle img-fluid" style="width: 50px;max-height: 50px;" src="img/rubros/' . $row['imagen_rubros'] . '"</td>';
                  } else {
                    echo '<td></td>';
                  }
                  echo '<td>';
                  if ($_SESSION['tipo'] != 'User') {
                    //echo ' <a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#editarub_'.$row['id_rubros'].'" data-original-title="Detalle"><i class="ti-eye" aria-hidden="true"></i></a>';
                    echo ' &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#editarub_' . $row['id_rubros'] . '" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>

                                                    &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#delrub_' . $row['id_rubros'] . '" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>';
                    echo '
                                                        <div class="modal fade" id="delrub_' . $row['id_rubros'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                          <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>
                                                              <div class="modal-body">
                                                                <h4 ><center>Seguro que desea eliminar<br> el rubro "' . $row['nombre_rubros'] . '" ?</center></h4>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                <a href="procesos/crud.php?accion=delrub&id=' . $row['id_rubros'] . '" class="btn btn-primary">Si, Eliminar</a>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>';
                    echo '
                                                        <div class="modal fade" id="editarub_' . $row['id_rubros'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                          <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>
                                                              <div class="modal-body">
                                                                <h4 ><center>DEtalle del rubro "' . $row['nombre_rubros'] . '" ?</center></h4>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                <a href="procesos/crud.php?accion=edita_rub&id=' . $row['id_rubros'] . '" class="btn btn-success">Editar</a>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>';
                  }
                  echo ' </td>
                                              </tr>
                                              ';
                  $i++;
                }
                echo '
                                          </tbody>

                                      </table>
                                  </div>
                              </div>
                          </div> '; ?>

          </div>
          <div class="col-6">
            <div class="card">
              <div class="card-header bg-info">
                <h4 class="mb-0 text-white">Crear nuevo Rubro</h4>
              </div>

              <form action="procesos/crud.php" method="post">
                <input type="hidden" value="add_rubro" name="accion">
                <hr>
                <div class="form-body">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12 ">
                        <div class="form-group">
                          <label class="form-label">Nombre</label>
                          <input type="text" id="nombre" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12 ">
                        <label for="formFileLg" class="form-label">Seleccione una imagen</label>
                        <input class="form-control form-control-lg" id="imagen" type="file">
                      </div>
                    </div>

                  </div>
                  <div class="form-actions">
                    <div class="card-body">
                      <button type="submit" class="btn btn-success text-white"> <i class="fa fa-check"></i>Guardar</button>
                      <button type="reset" class="btn btn-dark">Limpiar</button>
                    </div>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>

      </div>
      <script>
        function selecticon(obj) {

          var icono = $('#icono option:selected').val();
          $('#iconitos').val(icono);
        }



        $('#comercio_toma')
          .editableSelect()
          .on('select.editable-select', function(e, li) {
            $('#comercio_pago').val(li.val());
          });
      </script>