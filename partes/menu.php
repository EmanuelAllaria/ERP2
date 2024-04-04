<div class="side-mini-panel" style="overflow: visible;">
    <ul class="mini-nav ps ps--theme_default" data-ps-id="3dcf70be-dfe7-91c6-8fce-825a4a476496" style="overflow: hidden;">
        <div class="togglediv">
            <a href="javascript:void(0)" id="togglebtn"><i class="ti-menu"></i></a>
        </div>
        <li class="<?php if (!$_GET['pagina']) {
                        echo 'selected';
                    } ?>">
            <a href="javascript:void(0)"><i class="icon-speedometer"></i></a>
            <div class="sidebarmenu">
                <h3 class="menu-title">Escritorio</h3>
                <div class="searchable-menu">
                    <form role="search" class="menu-search">
                        <input type="text" placeholder="Buscar..." class="form-control">
                        <a href="index.html"><i class="fa fa-search"></i></a>
                    </form>
                </div>
            </div>
        </li>
        <li class="<?php if ($_GET['pagina'] == 'pedidos' || $_GET['pagina'] == 'pedidos_add') {
                        echo 'selected';
                    } ?>">
            <a href="?pagina=pedidos">
                <i class="ti-shopping-cart-full"></i>
            </a>
            <div class="sidebarmenu">
                <!-- Left navbar-header -->
                <h3 class="menu-title">Pedidos</h3>
                <ul class="sidebar-menu ps ps--theme_default">
                    <li><a href="index.php?pagina=pedidos">Listado de Pedidos</a></li>
                    <li><a href="index.php?pagina=pedidos_add_2">Nuevo Pedido</a></li>
                    <hr>
                    <div class="col-md-12">
                        <label class="m-t-20">Desde</label>
                        <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="<?php echo date('01-m-Y') . ' - ' . date('d-m-Y'); ?>">
                    </div>
                </ul>
            </div>
        </li>

        <li class="<?php if ($_GET['pagina'] == 'pagos' || $_GET['pagina'] == 'pagos_add') {
                        echo 'selected';
                    } ?>">
            <a href="?pagina=pagos"><i class="ti-money"></i></a>
            <div class="sidebarmenu">
                <h3 class="menu-title">Pagos</h3>
                <ul class="sidebar-menu ps ps--theme_default" data-ps-id="b85117c3-ad11-6795-57f3-a9e92ac8a53e">
                    <li><a href="index.php?pagina=pagos">Listado de Pagos</a></li>
                    <li><a href="index.php?pagina=pagos_add">Nuevo Pago</a></li>
                    <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                        <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                    </div>
                    <div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;">
                        <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                    </div>
                </ul>
            </div>
        </li>

        <li class="<?php if ($_GET['pagina'] == 'clientes' || $_GET['pagina'] == 'clientes_add' || $_GET['pagina'] == 'clientes_edit' || $_GET['pagina'] == 'comercio_add' || $_GET['pagina'] == 'comercio_edit') {
                        echo 'selected';
                    } ?>">
            <a href="?pagina=clientes"><i class="ti-user"></i></a>
            <div class="sidebarmenu">
                <h3 class="menu-title">Clientes</h3>
                <div class="searchable-menu">
                    <form role="search" class="menu-search">
                        <input type="text" placeholder="Buscar..." class="form-control"> <a href="index.html"><i class="fa fa-search"></i></a>
                    </form>
                </div>
                <ul class="sidebar-menu ps ps--theme_default" data-ps-id="b85117c3-ad11-6795-57f3-a9e92ac8a53e">
                    <li><a href="index.php?pagina=clientes">Listado de Clientes</a></li>
                    <li><a href="index.php?pagina=clientes_mapa">Mapa de Clientes</a></li>
                    <li><a href="index.php?pagina=clientes_add">Nuevo Cliente</a></li>
                    <li><a href="index.php?pagina=rubros">Listado de Rubros</a></li>
                    <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                        <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                    </div>
                    <div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;">
                        <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                    </div>
                </ul>
            </div>
        </li>
        <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
            <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;">
            <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </ul>
</div>