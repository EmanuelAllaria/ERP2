<style>
.iconotd {
font-family: 'FontAwesome', 'Second Font name';
font-size:large;
text-align: center;
}
</style>          <div class="container-fluid">
              <div class="row page-titles">
                  <div class="col-md-12">
                      <h4 class="text-white">Listado de Categorias</h4>
                  </div>
                  <div class="col-md-6">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                          <li class="breadcrumb-item active">Categorias</li>
                      </ol>
                  </div>
                  <div class="col-md-6 text-right">
                      <form class="app-search d-none d-md-block d-lg-block">
                          <input type="text" class="form-control" placeholder="Buscar...">
                      </form>
                  </div>
              </div>
                  <div class="row">
                      <div class="col-12">
                          <div class="card">
                              <div class="card-body">

                                  <div class="table-responsive">
                                      <table id="clientes_lista" class="table m-t-30 table-hover contact-list footable-loaded footable" data-page-size="10">
                                          <thead>
                                              <tr>

                                                  <th class="footable-sortable">Nombre<span class="footable-sort-indicator"></span></th>
                                                  <th class="footable-sortable">Color<span class="footable-sort-indicator"></span></th>
                                                <!--  <th class="footable-sortable">Cliente<span class="footable-sort-indicator"></span></th> -->
                                                  <th class="footable-sortable">Icono<span class="footable-sort-indicator"></span></th>
                                                  <th class="footable-sortable">Imagen<span class="footable-sort-indicator"></span></th>
                                                <!-- <th class="footable-sortable">Estado<span class="footable-sort-indicator"></span></th> -->
                                                  <th class="footable-sortable" style="width: 110px;">Acciones<span class="footable-sort-indicator"></span></th>
                                                  <th class="footable-sortable">#<span class="footable-sort-indicator"></span></th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?php

                                              $con_categoria = $link->query("SELECT * FROM categorias WHERE estado_categoria='1' ");
                                              $i=0;
                                              while ($row= mysqli_fetch_array($con_categoria)){
                                                echo '

                                              <tr>

                                                  <td class="font-weight-normal"><span class="footable-toggle"></span>'.$row['titulo_categoria'].'</td>
                                                  <td class="font-weight-normal" style="background-color: '.$row['color_categoria'].';">'.$row['color_categoria'].'</td>';
                                                  if($row['icono_categoria']!=''){
                                                      echo '<td class="font-weight-normal iconotd" style="vertical-align: middle;font-size: 30px;padding: 0px;">&#x'.$row['icono_categoria'].';</td>';
                                                  }else{echo '<td></td>';}
                                                  echo '
                                                  <td class="font-weight-normal"></td>
                                                  <td>';
                                                  if ($_SESSION['tipo']!='User'){
                                                    echo ' <a class="btn-pure btn-outline-success success-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=pedido_fac&id='.$row['id_categoria'].'" ><i class="ti-eye" aria-hidden="true"></i></a>
                                                    &nbsp;&nbsp;<a class="btn-pure btn-outline-info edit-row-btn btn-lg" style="padding:0px;" href="index.php?pagina=categoria_edit&id='.$row['id_categoria'].'" data-original-title="Editar"><i class="ti-pencil" aria-hidden="true"></i></a>

                                                    &nbsp;&nbsp;<a class="btn-pure btn-outline-danger delete-row-btn btn-lg" style="padding:0px;" href="#" data-toggle="modal" data-target="#delcat_'.$row['id_categoria'].'" data-original-title="Borrar"><i class="ti-close" aria-hidden="true"></i></a>';
                                                    echo '
                                                          <div class="modal fade" id="delcat_'.$row['id_categoria'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <h4 ><center>Seguro que desea eliminar<br> la categoria "'.$row['titulo_categoria'].'" ?</center></h4>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                            <a href="procesos/crud.php?accion=delcat&id='.$row['id_categoria'].'" class="btn btn-primary">Si, Eliminar</a>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>';

                                                 }
                                                 echo ' </td>
                                                    <td class="font-weight-normal"><span class="footable-toggle"></span>'.$row['id_categoria'].'</td>
                                              </tr>
                                              ';$i++;}
                                              echo '
                                          </tbody>
                                          <tfoot>
                                              <tr>
                                                  <td colspan="2">';
                                                    if ($_SESSION['tipo']!='User'){
                                                      echo '<a href="index.php?pagina=categoria_add" class="btn btn-info btn-rounded" >Crear Nueva Categoria</a> ';}
                                                      echo '
                                                  </td>

                                                  <td colspan="7">
                                                      <div class="text-right">
                                                          <ul class="pagination"> <li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li><li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li><li class="footable-page active"><a data-page="0" href="#">1</a></li><li class="footable-page"><a data-page="1" href="#">2</a></li><li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li><li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li></ul>
                                                      </div>
                                                  </td>
                                              </tr>
                                          </tfoot>
                                      </table>
                                  </div>
                              </div>
                          </div> '; ?>
                          <!-- Column -->

                          <!-- Column -->

                          <!-- Column -->

                          <!-- Column -->

                      </div>
                  </div>

    </div>
    <script>

function selecticon(obj){
//console.log( $('#icono option:selected').val())
var icono = $('#icono option:selected').val();
$('#iconitos').val(icono);
}



    $('#comercio_toma')
  .editableSelect()
  .on('select.editable-select', function (e, li) {
      $('#comercio_pago').val(li.val());
  });
    </script>
