<?php
require_once "koneksi.php";
session_start();
if (!@$_SESSION['posisi_peg']) {
  echo "<script>window.location='login.php';</script>";
} else {
  $tipe = $_SESSION['session_id'];
  $tipesales = $_SESSION['session_tipe'];
  ?>

  <!doctype html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="asset/img/logo/logo.jpg">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="asset/bootstrap_4/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="asset/private_style/style_index.css">
    <link rel="stylesheet" href="asset/font_awesome/css/all.css">
    <link rel="stylesheet" href="asset/DataTables/datatables.min.css">
    <link rel="stylesheet" href="asset/sweetalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="asset/bootstrap_datepicker1.9.0/css/bootstrap-datepicker.min.css">


    <!-- Grafik OMSET -->
    <style>
      .container {
        width: 100%;
        margin: 15px 10px;
      }

      .chart {
        width: 100%;
        float: left;
        text-align: center;
      }
    </style>

    <?php $thn = date('Y'); ?>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript">
      $(function () {
        var chart;
        $(document).ready(function () {
          $.getJSON("dataline.php", function (json) {

            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'mygraph',
                type: 'line'

              },
              title: {
                text: 'Comparison Penjualan dan Invoice per Bulan Tahun ini'

              },
              subtitle: {
                text: '(Penjualan dan Invoice di <?php echo $thn; ?>)'

              },
              xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
              },
              yAxis: {
                title: {
                  text: 'Nilai (Rp)'
                },
                plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
                }]
              },
              tooltip: {
                formatter: function () {
                  return '<b>' + this.series.name + '</b><br/>' +
                    <?php echo $thn; ?> + this.x + ': ' + this.y.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                }
              },
              legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 120,
                borderWidth: 0
              },
              series: json
            });
          });

        });

      });
    </script>
    <!-- END Grafik OMSET -->


    <title>
      Aplikasi Sales Order |
      <?php
      if (@$_GET['page'] == '') {
        echo "Dashboard";
      } else if (@$_GET['page'] == 'dataobat') {
        echo "Data Barang";
      } else if (@$_GET['page'] == 'datapegawai') {
        echo "Data Customer";
      } else if (@$_GET['page'] == 'datapenjualan' || @$_GET['page'] == 'entry_datapenjualan' || @$_GET['page'] == 'datapenjualan_perobat') {
        echo "Data Penjualan";
      } else if (@$_GET['page'] == 'laporan') {
        echo "Laporan";
      }
      ?>
    </title>

    <!--SCROLL-->
    <style>
      #table-scroll {
        height: 450px;
        width: 100%;
        overflow: auto;
        margin-top: 2px;
      }
    </style>

    <!--LOADER-->
    <style>
      #loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid navy;
        /*border-right: 16px solid green;*/
        border-bottom: 16px solid navy;
        width: 180px;
        height: 180px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
      }

      @-webkit-keyframes spin {
        0% {
          -webkit-transform: rotate(0deg);
        }

        100% {
          -webkit-transform: rotate(360deg);
        }
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }

      .center {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
      }
    </style>

    <!--POPUP FUNCTION-->
    <script type="text/javascript">
      function popupwindow(url, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'scrollbars=1,status=0, resizable=0, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      }

      function Sremarks() {
        if (($('#pj_barang').val() != 0) || ($('#lbr_barang').val() != 0)) {
          var pj_barang = Number(document.getElementById('pj_barang').value).toFixed(2);
          var lbr_barang = Number(document.getElementById('lbr_barang').value);

          document.getElementById('qty').value = Number(pj_barang * lbr_barang).toFixed(2);

        } else {
          document.getElementById('qty').value = "0";
        }
      }


      function Shitung() {
        if (($('#pj_barang').val() != 0) || ($('#lbr_barang').val() != 0)) {
          var pj_barang = Number(document.getElementById('pj_barang').value).toFixed(2);
          var lbr_barang = Number(document.getElementById('lbr_barang').value);


          document.getElementById('qty').value = Number(pj_barang * lbr_barang).toFixed(2);

        } else {
          document.getElementById('qty').value = "0";
        }

        if (($('#qty').val() != 0) || ($('#hrg_sat').val() != 0)) {
          var qty = Number(document.getElementById('qty').value).toFixed(2);
          var lb_barang = Number(document.getElementById('lb_barang').value);


          if (lb_barang != 0) {
            var hrg_sat = document.getElementById('hrg_sat').value;
            document.getElementById('hrg_sat').value = (hrg_sat / 1219 * lb_barang).toFixed(2);
            var hrg_sat = document.getElementById('hrg_sat').value;
            var hrg_satppn = 0;
            hrg_satppn = (1.11 * hrg_sat);
            document.getElementById('toth_hrg').value = Number(qty * hrg_satppn).toFixed(2);
          } else {
            var hrg_sat = document.getElementById('hrg_sat').value;

            var hrg_satppn = 0;
            hrg_satppn = (1.11 * hrg_sat);
            document.getElementById('toth_hrg').value = Number(qty * hrg_satppn).toFixed(2);
          }



        } else {
          document.getElementById('toth_hrg').value = "0";
          document.getElementById('hrg_satppn').value = "0";
        }
      }
      var timeout = null;
      function delayedShitung() {
        // Clear timeout jika masih ada waktu tersisa
        clearTimeout(timeout);

        // Set timeout baru selama 3 detik (3000 ms)
        timeout = setTimeout(function () {
          Shitung();
        }, 2000);
      }

      function Shitunguj() {
        if (($('#qty').val() != 0) || ($('#hrg_sat').val() != 0)) {
          var qty = Number(document.getElementById('qty').value).toFixed(2);

          var hrg_sat = document.getElementById('hrg_sat').value;

          var hrg_satppn = 0;

          hrg_satppn = (1.11 * hrg_sat);
          document.getElementById('toth_hrg').value = Number(qty * hrg_satppn).toFixed(2);

        } else {
          document.getElementById('toth_hrg').value = "0";
          document.getElementById('hrg_satppn').value = "0";
        }
      }

      function Shitungx(id) {
        if (($('#td_pjobat' + id).val() != 0) || ($('#td_lbrobat' + id).val() != 0)) {
          var pj_barang = Number(document.getElementById('td_pjobat' + id).value).toFixed(2);
          var lbr_barang = Number(document.getElementById('td_lbrobat' + id).value);

          document.getElementById('td_jmlobat' + id).value = Number(pj_barang * lbr_barang).toFixed(2);

        } else {
          document.getElementById('td_jmlobat' + id).value = "0";
        }

        if (($('#td_jmlobat' + id).val() != 0) || ($('#td_satobat' + id).val() != 0)) {
          var qty = Number(document.getElementById('td_jmlobat' + id).value).toFixed(2);

          var hrg_sat = document.getElementById('td_satobat' + id).value;

          var hrg_satppn = 0;

          var subline = Number(document.getElementById('td_subtotal' + id).value);
          var subtot = Number(document.getElementById('hidden_totalpenjualan').value);
          var tot = Number(document.getElementById('hidden_totalpenjualanppn').value);
          hrg_sat2 = hrg_sat.replace(/,/g, "");


          hrg_satppn = (1.11 * hrg_sat2);
          document.getElementById('td_satobat' + id).value = Number(hrg_sat2).toFixed(2);
          document.getElementById('td_subtotal' + id).value = Number(qty * hrg_satppn).toFixed(2);
          document.getElementById('hidden_totalpenjualan').value = Number((subtot - subline) + (qty * hrg_satppn)).toFixed(2);
          document.getElementById('hidden_totalpenjualanppn').value = Number((tot - subline) + (qty * hrg_satppn)).toFixed(2);

        } else {
          document.getElementById('td_subtotal' + id).value = "0";

        }
      }

      function Shitungujx(id) {
        if (($('#td_jmlobat' + id).val() != 0) || ($('#td_satobat' + id).val() != 0)) {
          var qty = Number(document.getElementById('td_jmlobat' + id).value).toFixed(2);

          var hrg_sat = document.getElementById('td_satobat' + id).value;

          var hrg_satppn = 0;

          var subline = Number(document.getElementById('td_subtotal' + id).value);
          var subtot = Number(document.getElementById('hidden_totalpenjualan').value);
          var tot = Number(document.getElementById('hidden_totalpenjualanppn').value);


          hrg_satppn = (1.11 * hrg_sat);
          document.getElementById('td_subtotal' + id).value = Number(qty * hrg_satppn).toFixed(2);
          document.getElementById('hidden_totalpenjualan').value = Number((subtot - subline) + (qty * hrg_satppn)).toFixed(2);
          document.getElementById('hidden_totalpenjualanppn').value = Number((tot - subline) + (qty * hrg_satppn)).toFixed(2);

        } else {
          document.getElementById('td_subtotal' + id).value = "0";

        }
      }

      function Shitungtotal() {
        if (($('#hidden_totalpenjualan').val() != 0)) {
          var sub_total = document.getElementById('hidden_totalpenjualan').value;
          var diskon = document.getElementById('hidden_totaldiskon').value;

          document.getElementById('hidden_totalpenjualanppn').value = Number(sub_total - diskon).toFixed(2);
          document.getElementById('total_diskon').value = Number((diskon / sub_total) * 100).toFixed(2);


        } else {
          document.getElementById('hidden_totalpenjualanppn').value = "0.00";
        }
      }

      function Shitungpercent() {
        if (($('#hidden_totalpenjualan').val() != 0)) {
          var sub_total = document.getElementById('hidden_totalpenjualan').value;
          var diskon = document.getElementById('hidden_totaldiskon').value;
          var dispercent = document.getElementById('total_diskon').value;

          document.getElementById('hidden_totalpenjualanppn').value = Number(((sub_total / 1.11) - diskon) * 1.11).toFixed(2);
          document.getElementById('hidden_totaldiskon').value = Number((dispercent * (sub_total / 1.11)) / 100).toFixed(2);

        } else {
          document.getElementById('hidden_totalpenjualanppn').value = "0.00";
        }
      }

      function Stampiltrans() {
        if (($('#trans').val() == 1)) {
          document.getElementById('tgltrans').innerHTML = "Tgl Quotation";
          document.getElementById('tgltrans2').innerHTML = "Valid Until";
          document.getElementById('kodetrans').value = "1";
          document.getElementById("nama_cus").removeAttribute("readonly", "readonly");


        } else if (($('#trans').val() == 2)) {
          document.getElementById('tgltrans').innerHTML = "Tgl Orders";
          document.getElementById('tgltrans2').innerHTML = "Tgl Delivery";
          document.getElementById('kodetrans').value = "2";
          document.getElementById("nama_cus").setAttribute("readonly", "readonly");


        } else {
          document.getElementById('tgltrans').innerHTML = "";
          document.getElementById('tgltrans2').innerHTML = "";
          document.getElementById('kodetrans').value = "";
        }
      }

      function Stampiljenis() {
        if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
          document.getElementById("nm_obat").setAttribute("readonly", "readonly");
          document.getElementById("pj_barang").removeAttribute("readonly", "readonly");
          document.getElementById("lbr_barang").removeAttribute("readonly", "readonly");
          document.getElementById("lb_barang").removeAttribute("readonly", "readonly");
          document.getElementById("qty").setAttribute("readonly", "readonly");
          document.getElementById("hrg_sat").removeAttribute("readonly", "readonly");
          document.getElementById("toth_hrg").setAttribute("readonly", "readonly");
          document.getElementById("uomserv").setAttribute('disabled', true);

          document.getElementById("totket").innerHTML = "(inc PPN)";
          document.getElementById("totket2").innerHTML = "(inc PPN)";
          document.getElementById("totket3").innerHTML = "(inc PPN)";
          document.getElementById("totket4").innerHTML = "(inc PPN)";

        } else {
          document.getElementById("nm_obat").removeAttribute("readonly", "readonly");
          document.getElementById("pj_barang").setAttribute("readonly", "readonly");
          document.getElementById("lbr_barang").setAttribute("readonly", "readonly");
          document.getElementById("lb_barang").setAttribute("readonly", "readonly");
          document.getElementById("qty").removeAttribute("readonly", "readonly");
          document.getElementById("hrg_sat").setAttribute("readonly", "readonly");
          document.getElementById("toth_hrg").removeAttribute("readonly", "readonly");
          document.getElementById("uomserv").removeAttribute('disabled');

          document.getElementById("totket").innerHTML = "(exc PPN)";
          document.getElementById("totket2").innerHTML = "(exc PPN)";
          document.getElementById("totket3").innerHTML = "(exc PPN)";
          document.getElementById("totket4").innerHTML = "(exc PPN)";

        }
        document.getElementById("nm_obat").value = "";
        document.getElementById("kode_obat").value = "";
        document.getElementById("series").value = "";
        document.getElementById("uom").value = "";
        document.getElementById("pj_barang").value = "0";
        document.getElementById("lbr_barang").value = "0";
        document.getElementById("lb_barang").value = "0";
        document.getElementById("qty").value = "0.00";
        document.getElementById("hrg_sat").value = "";
        document.getElementById("toth_hrg").value = "";
      }

    </script>
  </head>

  <body class="bg-light">

    <div id="container">

      <!-- LOADING -->
      <div id="loader" class="center"></div>

      <div id="main">
        <div class="col-md-12 bg-info p-1 title">
          <div class="row">
            <div class="col-md-6">
              <span class="text-white font-weight-light">Sales Order | UTOMODECK GROUP</span>
            </div>
            <div class="col-md-6 text-right">

              <span class="text-white tanggal-jam" id="tanggal"><?php echo date('d M Y'); ?> -</span><span
                class="text-white tanggal-jam" id="jam">
              </span>
              <div class="btn-group">
                <button type="button" class="btn btn-light btn-sm dropdown-toggle font-weight-light"
                  data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user-circle"></i> <?php echo $_SESSION['posisi_peg']; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-1">

                  <div class="col-12 text-center nama-posisi">
                    <h2>
                      <i class="fas fa-user-circle"></i>
                    </h2>
                    <span class="nama"><?php echo $_SESSION['nama_peg']; ?></span><br>
                    <span class="posisi">ID : <span id="id_session"
                        class="posisi"><?php echo $_SESSION['id_peg']; ?></span></span>
                  </div>
                  <div class="row tombol">
                    <div class="col-6">

                    </div>
                    <div class="col-6 text-right">
                      <button class="btn btn-sm btn-danger" id="tombol_keluar">Logout</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2 sidebar">
            <div class="accordion" id="menu">
              <ul class="list-group">
                <li href="#" class="list-group-item list-group-item-action menu-utama" data-toggle="collapse"
                  data-target="#menu-collapse" aria-expanded="true" aria-controls="menu-collapse"
                  style="border-radius: 5px 5px 0 0;">
                  Menu <i class="fas fa-list float-right mt-1"></i>
                </li>
              </ul>
              <div id="menu-collapse" class="collapse show" aria-labelledby="" data-parent="#menu">
                <div class="accordion" id="daftar_menu">
                  <ul class="list-group">
                    <a href="./" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == '') {
                      echo "active";
                    } ?>" style="border-radius: 0;">
                      <i class="fas fa-home"></i> Home
                    </a>

                    <a href="#" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'dataobat' || @$_GET['page'] == 'datapegawai') {
                      echo "show";
                    } ?>" data-toggle="collapse" data-target="#menu-collapse-master" aria-expanded="true"
                      aria-controls="menu-collapse-master">
                      <i class="fas fa-folder"></i> Data Master <i class="fas fa-angle-down float-right mt-1"></i>
                    </a>
                    <div id="menu-collapse-master" class="collapse <?php if (@$_GET['page'] == 'dataobat' || @$_GET['page'] == 'datapegawai') {
                      echo "show";
                    } ?>" aria-labelledby="" data-parent="#daftar_menu">
                      <ul class="list-group list-group-collapse">
                        <a href="?page=dataobat" class="list-group-item list-group-item-action list-group-item-collapse <?php if (@$_GET['page'] == 'dataobat') {
                          echo "active";
                        } ?>" style="border-radius: 0px;">
                          <i class="fas fa-angle-right"></i> Data Barang <i class="fas fa-capsules float-right mt-1"></i>
                        </a>
                        <?php if ($tipesales !== "99" && $tipesales !== "111") { ?>
                          <a href="?page=datapegawai" class="list-group-item list-group-item-action list-group-item-collapse <?php if (@$_GET['page'] == 'datapegawai') {
                            echo "active";
                          } ?>">
                            <i class="fas fa-angle-right"></i> Data Customer <i class="fas fa-users float-right mt-1"></i>
                          </a>
                        <?php } ?>
                      </ul>
                    </div>

                    <?php if ($tipesales !== "88" && $tipesales !== "99" && $tipesales !== "111" && $tipesales !== "222") { ?>
                      <a href="?page=entry_datapenjualan" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'entry_datapenjualan') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-shopping-bag"></i> Transaksi Penjualan
                      </a>
                      <a href="?page=datapenjualan" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'datapenjualan' || @$_GET['page'] == 'dataquotation' || @$_GET['page'] == 'datapenjualan_perobat' || @$_GET['page'] == 'entry_datapenjualan_upd' || @$_GET['page'] == 'entry_dataquotation_upd') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-chart-bar"></i> Data Sales Orders
                      </a>
                    <?php } ?>

                    <?php if ($tipe == "2" && $tipesales == "99") { ?>
                      <a href="?page=datapenjualanall" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'datapenjualanall') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-chart-bar"></i> Data Orders ALL Sales
                      </a>
                    <?php } ?>
                    <?php if ($tipe == "1" && $tipesales == "88") { ?>
                      <a href="?page=dataspk" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'dataspk') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-chart-bar"></i> Production Orders
                      </a>
                    <?php }
                    if ($tipe == "1" && $tipesales == "111") { ?>
                      <a href="?page=entry_items&cek=n" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'entry_items') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-chart-bar"></i> Master Items
                      </a>
                    <?php } ?>

                    <?php if ($tipe == "1" && $tipesales == "111") { ?>
                      <a href="?page=customer" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'customer') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-chart-bar"></i> Tambah Customer
                      </a>
                    <?php } ?>

                    <?php if ($tipe == "1" && $tipesales == "222") { ?>
                      <a href="?page=entry_datatransport" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'entry_datatransport') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-shopping-bag"></i> Input DN Transport
                      </a>
                      <a href="?page=datadntransport" class="list-group-item list-group-item-action <?php if (@$_GET['page'] == 'datadntransport') {
                        echo "active";
                      } ?>">
                        <i class="fas fa-shopping-bag"></i> Data DN Transport
                      </a>
                    <?php } ?>

                  </ul>
                </div>
              </div>
            </div>
          </div>

          <script src="asset/Jquery/jquery-3.3.1.min.js"></script>
          <script src="asset/sweetalert/dist/sweetalert2.min.js"></script>
          <script src="asset/bootstrap_datepicker1.9.0/js/bootstrap-datepicker.min.js"></script>
          <script src="asset/bootstrap_datepicker1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
          <script src="asset/ChartJs/Chart.min.js"></script>

          <div class="col-md-10 content" >
            <?php
            if (@$_GET['page'] == '') {
              include 'pages/home.php';
              // echo "Halaman Dashboard";
            } else if (@$_GET['page'] == 'detail_datapenjualan_bln') {
              include 'pages/datapenjualanperbulan.php';
            } else if (@$_GET['page'] == 'dataobat') {
              include 'pages/dataobat.php';
            } else if (@$_GET['page'] == 'datapegawai') {
              include 'pages/datapegawai.php';
            } else if (@$_GET['page'] == 'datapenjualan') {
              include 'pages/datapenjualan.php';
            } else if (@$_GET['page'] == 'datadelivery') {
              include 'pages/datapjl_perobat.php';
            } else if (@$_GET['page'] == 'dataquotation') {
              include 'pages/dataquotation.php';
            } else if (@$_GET['page'] == 'datadntransport') {
              include 'pages/datadntransport.php';
            } else if (@$_GET['page'] == 'entry_datatransport') {
              include 'pages/entry_datatransport.php';
            } else if (@$_GET['page'] == 'datapenjualan_perobat') {
              include 'pages/datapjl_perobat.php';
            } else if (@$_GET['page'] == 'entry_datapenjualan') {
              include 'pages/form_entrypenjualan.php';
            } else if (@$_GET['page'] == 'entry_datapenjualan_upd') {
              include 'pages/form_entrypenjualan_upd_terms.php';
            } else if (@$_GET['page'] == 'entry_dataquotation_upd') {
              include 'pages/form_entryquotation_upd.php';
            } else if (@$_GET['page'] == 'entry_dataquotation_copyorders') {
              include 'pages/form_entryquotation_upd_copyorders.php';
            } else if (@$_GET['page'] == 'detail_delivery') {
              include 'pages/form_detail_delivery.php';
            } else if (@$_GET['page'] == 'entry_items') {
              include 'pages/form_entryitems.php';
            } else if (@$_GET['page'] == 'datapenjualanall') {
              include 'pages/datapenjualanall.php';
            } else if (@$_GET['page'] == 'dataspk') {
              include 'pages/datapenjualanspk.php';
            } else if (@$_GET['page'] == 'entry_datapenjualan_upd_spk') {
              include 'pages/form_entrypenjualan_upd_spk.php';
            } else if (@$_GET['page'] == 'laporan') {
              include 'pages/laporan.php';
            } else if (@$_GET['page'] == 'customer') {
              include 'pages/form_customer.php';
            }
            ?>
          </div>

        </div>
      </div>
    </div>

    <footer>
      <i class="fas fa-copyright"></i> IT Utomodeck Group
    </footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
      crossorigin="anonymous"></script>
    <script src="asset/bootstrap_4/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="asset/DataTables/datatables.min.js"></script>
    <script>
      var id_session = $("#id_session").text();
      $(document).ready(function () {
        $('#example').DataTable({

        });
        $('#example2').DataTable({

        });
        $('#example3').DataTable({

        });

        $('#tbldata_penjualan').DataTable({
          lengthMenu: [[25, 50, -1], [25, 50, "All"]]
        });

        $('#tbl_riwayatperamalan').DataTable({
          lengthMenu: [[30, 50, -1], [30, 50, "All"]]
        });

        $('#tbl_pjlobat').DataTable({
          lengthMenu: [[50, -1], [50, "All"]]
        });

        $('#tabel_dataobat').DataTable({
          // ordering: false,
          lengthMenu: [[30, 50, 100, -1], [30, 50, 100, "All"]],
          order: [[1, "asc"]]
        });

      });
      $("#tombol_keluar").click(function () {
        // alert("Log Out");
        Swal.fire({
          title: 'Apakah Anda Yakin?',
          text: 'anda akan keluar dari aplikasi',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya'
        }).then((tes) => {
          if (tes.value) {
            $.ajax({
              type: "POST",
              url: "ajax/logout.php",
              success: function (hasil) {
                window.location = './';
              }
            })
          }
        })
      });
      function checkTime(i) {
        if (i < 10) {
          i = "0" + i;
        }
        return i;
      }
      function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        // add a zero in front of numbers<10
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('jam').innerHTML = h + ":" + m + ":" + s;
        t = setTimeout(function () {
          startTime()
        }, 500);
      }
      startTime();
    </script>


    <!-- LOADING PRELOAD -->
    <script>
      document.onreadystatechange = function () {
        if (document.readyState !== "complete") {
          document.querySelector(
            "body").style.visibility = "hidden";
          document.querySelector(
            "#loader").style.visibility = "visible";
        } else {
          document.querySelector(
            "#loader").style.display = "none";
          document.querySelector(
            "body").style.visibility = "visible";
        }
      }; 
    </script>

  </body>

  </html>
  <?php
}
?>