            <div class="container-fluid">
              <div class="row page-titles">
                <div class="col-md-12">
                  <h4 class="text-white">Editar Transaccion #<?php echo $_GET['id'] ?></h4>
                </div>
                <div class="col-md-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="index.php?pagina=pedidos">Transaccion</a></li>
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
                <div class="card" style="width: 100%;">
                  <div class="card-body">
                    <h4 class="card-title">Edite los datos</h4>

                    <form id="add_pago_form" action="procesos/crud.php" method="post" class="form-horizontal form-material">
                      <div class="modal-body">
                        <?php $id_t = $_GET['id'];
                        $con_transaccion = $link->query("SELECT * FROM transaccion
                                inner join clientes on transaccion.cliente = clientes.id_clientes
                              WHERE transaccion.estado='1' and transaccion.id='$id_t' ");
                        if ($consu = mysqli_fetch_array($con_transaccion)) {
                        ?>
                          <input type="hidden" name="accion" value="edit_transaccion">
                          <input type="hidden" name="tipo_trans" value="<?php echo $consu['tipo']; ?>">
                          <input type="hidden" name="id_trans" value="<?php echo $consu['id']; ?>">

                          <div class="row">
                            <div class="col-md-2 m-b-20">
                              <input type="date" name="fecha_trans" class="form-control fecha" value="<?php echo date("Y-m-d", strtotime($consu['fecha'])); ?>" required="">
                            </div>

                            <div class="col-md-10 m-b-20">
                              <select name="comercio_trans" id="comercio" class="form-control comercio" required="">
                                <?php

                                $consul_comercios = $link->query("SELECT * FROM clientes
                                        where clientes.estado_clientes ='1' order by clientes.razon_com_clientes ASC");
                                while ($row = mysqli_fetch_array($consul_comercios)) {
                                  echo '<option value="' . $row['id_clientes'] . '" ';
                                  if ($row['id_clientes'] == $consu['cliente']) {
                                    echo ' selected ';
                                  }
                                  echo ' >' . utf8_encode($row['razon_com_clientes']) . '</option>';
                                } ?>
                              </select>
                            </div>
                            <div class="col-md-12 m-b-20">
                              <textarea placeholder="ingrese el detalle del pago" rows="5" class="form-control detalle" name="detalle_trans" required=""><?php echo $consu['detalle']; ?></textarea>
                            </div>
                            <?php if ($consu['tipo'] != 'pago') { ?>
                              <div class="col-md-12 m-b-20">
                                <select name="tipo_pedido" id="tipo_pedido" class="form-control tipo_pedido" required="">
                                  <option value="" disabled="" selected="">Seleccione Tipo de Pedido</option>
                                  <?php
                                  $consul_rubros = $link->query("SELECT * FROM categorias
                                        WHERE estado_categoria ='1' order by titulo_categoria ASC");
                                  while ($row = mysqli_fetch_array($consul_rubros)) {
                                    echo '<option value="' . $row['id_categoria'] . '" ';
                                    if ($consu['tipo_pedido'] == $row['id_categoria']) {
                                      echo ' selected ';
                                    }
                                    echo '>' . $row['titulo_categoria'] . '</option> ';
                                  }
                                  ?>

                                </select>
                              </div><?php } ?>
                            <div class="col-md-12 m-b-20">
                              <input type="number" placeholder="Monto del Pago" step="any" min="1" class="form-control monto" value="<?php if ($consu['tipo'] == 'pedido') {
                                                                                                                                        echo $consu['monto'];
                                                                                                                                      } else {
                                                                                                                                        echo $consu['monto2'];
                                                                                                                                      } ?>" name="monto_trans" required="">
                            </div>
                          </div>
                          <button type="submit" id="agregar_pago" class="btn btn-info waves-effect">Modificar</button>
                          <a href="?pagina=<?php echo $consu['tipo']; ?>s" class="btn btn-danger waves-effect">Cancelar</a>
                        <?php } ?>
                      </div>
                    </form>


                  </div>
                </div>
              </div>
            </div>