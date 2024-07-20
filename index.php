<?php
session_start();
include 'inc/conection.php';
if ($_SESSION['usuario'] != '') { ?>
  <!DOCTYPE html>
  <html>

  <head>

    <link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="./js/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>
    <?php include 'partes/head.php' ?>
    <title>Gestion ERP</title>

    <meta name="description" content="">
    <style>
      .back-to-top {
        cursor: pointer;
        position: fixed;
        bottom: 20px;
        right: 20px;
      }

      #modal_agregar_pago_factura {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999999;
        background: #fff;
        border-radius: 20px;
        box-shadow: #000 5px 5px 20px 0;
        padding: 1em;
      }

      .switch {
        position: relative;
        display: inline-block;
        width: 60px !important;
        height: 34px;
      }

      .switch input#check-cheques-cancelados {
        opacity: 0;
        width: 0 !important;
        height: 0;
      }

      .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
      }

      .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px !important;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
      }

      input#check-cheques-cancelados+.slider {
        background-color: red;
      }

      input#check-cheques-cancelados:checked+.slider {
        background-color: green;
      }

      input#check-cheques-cancelados:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
      }

      /* Rounded sliders */
      .slider.round-sweetch {
        border-radius: 34px;
      }

      .slider.round-sweetch:before {
        border-radius: 50%;
      }
    </style>
  </head>

  <body class="skin-default fixed-layout">
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'error') {
      echo '<script>alert("Datos Incorrectos")</script>';
    } ?>

    <div class="preloader" style="display: none;">
      <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">Elite admin</p>
      </div>
    </div>

    <div id="main-wrapper">
      <?php include 'partes/topbar.php' ?>

      <div class="page-wrapper" style="min-height: 879px;">
        <?php
        if (!isset($_GET['pagina'])) {
          if ($_SESSION['tipo'] == 'Despacho') {
            include 'paginas/estadocamion.php';
          } elseif ($_SESSION['tipo'] == 'Deposito') {
            include 'paginas/estadocamion.php';
          } else {
            include 'partes/escritorio.php';
          }
        } else {
          include 'paginas/' . $_GET['pagina'] . '.php';
        }
        ?>

      </div>

      <?php include 'partes/footer.php' ?>

    </div>

    <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Click para ir al inicio" data-toggle="tooltip" data-placement="left"> <i class="ti-angle-double-up"></i></a>
    <script src="./js/funciones.js?v=1"></script>

    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/perfect-scrollbar.jquery.min.js"></script>
    <script src="./js/waves.js"></script>
    <script src="./js/sidebarmenu.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/moment.js"></script>
    <script src="./js/raphael-min.js"></script>
    <script src="./js/morris.min.js"></script>
    <script src="./js/jquery.sparkline.min.js"></script>
    <script src="./js/jquery.toast.js"></script>
    <script src="./js/dashboard1.js"></script>
    <script src="./js/daterangepicker.js"></script>

    <script>
      $(document).ready(function() {
        $(window).scroll(function() {
          if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
          } else {
            $('#back-to-top').fadeOut();
          }
        });

        $('#back-to-top').click(function() {
          $('#back-to-top').tooltip('hide');
          $('body,html').animate({
            scrollTop: 0
          }, 800);
          return false;
        });

        $('#back-to-top').tooltip('show');

      });

      $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        dateformat: 'DD/MM/YYYY',
        cancelClass: 'btn-inverse'
      });
    </script>

    <?php if (!isset($_GET['pagina'])) {
      echo '<script src="./js/clima.js"></script> ';
    } ?>
    <?php if (isset($_GET['pagina']) && $_GET['pagina'] == 'clientes') {
      echo '<script src="./js/footable.all.min.js"></script>
                 <script src="./js/footable-init.js"></script>';
    } ?>

    <div class="jq-toast-wrap top-right">
      <div class="jq-toast-single jq-has-icon jq-icon-info" style="text-align: left; display: none;">
        <span class="jq-toast-loader jq-toast-loaded" style="-webkit-transition: width 3.1s ease-in; -o-transition: width 3.1s ease-in; transition: width 3.1s ease-in; background-color: #ff6849;"></span><span class="close-jq-toast-single">×</span>
        <h2 class="jq-toast-heading"></h2>
      </div>
    </div>

    <script src="https://kit.fontawesome.com/15fbf0e0d4.js" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        let tipo_pago = $('#tipo_pago_factura_pago').val();
        $('.row.banco').hide();
        $('.row.nro_cheque').hide();
        $('.row.fecha_emision').hide();
        $('.row.fecha_cobro').hide();
        $('.row.titular').hide();
        $('.row.cuit').hide();
        $('.row.monto').hide();
        $('.row.origen').hide();
        $('.row.detalle').hide();
        $('.row.agregar_cheque').hide();
        if (tipo_pago === 'mp') {
          $('.row.fecha_emision').show();
          $('.row.fecha_cobro').show();
          $('.row.titular').show();
          $('.row.cuit').show();
          $('.row.monto').show();
          $('.row.origen').show();
          $('.row.detalle').show();
        } else if (tipo_pago === 'cheque') {
          $('.row.agregar_cheque').show();
        } else if (tipo_pago === 'efectivo') {
          $('.row.fecha_emision').show();
          $('.row.fecha_cobro').show();
          $('.row.cuit').show();
          $('.row.monto').show();
          $('.row.detalle').show();
        } else if (tipo_pago === 'tarjeta de credito' || tipo_pago === 'transferencia') {
          $('.row.banco').show();
          $('.row.fecha_emision').show();
          $('.row.fecha_cobro').show();
          $('.row.titular').show();
          $('.row.cuit').show();
          $('.row.monto').show();
          $('.row.origen').show();
          $('.row.detalle').show();
        }
        $('#tipo_pago_factura_pago').on('change', function() {
          let tipo_pago = $(this).val();
          $('.row.banco').hide();
          $('.row.nro_cheque').hide();
          $('.row.fecha_emision').hide();
          $('.row.fecha_cobro').hide();
          $('.row.titular').hide();
          $('.row.cuit').hide();
          $('.row.monto').hide();
          $('.row.origen').hide();
          $('.row.detalle').hide();
          $('.row.agregar_cheque').hide();
          if (tipo_pago === 'mp') {
            $('.row.fecha_emision').show();
            $('.row.fecha_cobro').show();
            $('.row.titular').show();
            $('.row.cuit').show();
            $('.row.monto').show();
            $('.row.origen').show();
            $('.row.detalle').show();
          } else if (tipo_pago === 'cheque') {
            $('.row.agregar_cheque').show();
          } else if (tipo_pago === 'efectivo') {
            $('.row.fecha_emision').show();
            $('.row.fecha_cobro').show();
            $('.row.cuit').show();
            $('.row.monto').show();
            $('.row.detalle').show();
          } else if (tipo_pago === 'tarjeta de credito' || tipo_pago === 'transferencia') {
            $('.row.banco').show();
            $('.row.fecha_emision').show();
            $('.row.fecha_cobro').show();
            $('.row.titular').show();
            $('.row.cuit').show();
            $('.row.monto').show();
            $('.row.origen').show();
            $('.row.detalle').show();
          }
        });
      });
    </script>
  </body>

  </html>
<?php } else {
  header('location:login.html');
} ?>