<script src="./js/funciones.js?v=1"></script>
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">Nuevo Adelanto</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="index.php?pagina=adelanto">Adelantos</a></li>
                <li class="breadcrumb-item active">Nuevo</li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <form class="app-search d-none d-md-block d-lg-block">
                <input type="text" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 d-flex justify-content-center align-items-center" style="margin:33px;">
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Adelantos</h4>
                </div>
                <div class="card-body">

                    <form id="precioadd" style="width:100%" name="precioadd" method="post">
                        <div class="row" id="form-alqui">
                            <input name="accion" value="add" type="hidden">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="movimiento">Tipo de Movimiento</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-exchange"></i></span>
                                        </div>
                                        <select class="form-control" id="movimiento" name="tipo" aria-describedby="basic-addon2">
                                            <option value="adelanto">Adelanto</option>
                                            <option value="dif caja">Diferencia De Caja</option>
                                            <option value="dif caja">Sueldos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="form-alqui">
                            <input name="accion" value="add" type="hidden">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="personal">Personal</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-user"></i></span>
                                        </div>
                                        <select class="form-control asignado" id="asignado" name="asignado">
                                            <option value="" disabled="" selected="">Seleccione un personal</option>
                                            <?php
                                            $con_zona = $link->query("SELECT * FROM personal where estado ='1' and email2_per = '1' order by nombre asc, apellido asc");
                                            while ($zona  = mysqli_fetch_array($con_zona)) {
                                              echo '<option value="' . $zona['id'] . '">' . $zona['nombre'] . ', ' . $zona['apellido'] . '</option>';
                                            }
                                            ?>
                                          </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comprobante">Nº Comprobante</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-outdent"></i></span>
                                        </div>
                                        <input id="comprobante" name="comprobante" id="comprobante" placeholder="Ingrese el Nº de comprobante" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comprobante">Ingrese monto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-money"></i></span>
                                        </div>
                                        <input id="monto" name="monto" placeholder="Ingrese monto" class="form-control" step="any" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="detalle-alqui" name="detalle" rows="4" placeholder="Ingrese el detalle del mismo" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="button" id="agregarAdelanto">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    
    </div>
</div>
