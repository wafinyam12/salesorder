<?php

$db = $_SESSION['posisi_peg'];
$tipe = $_SESSION['session_id'];
$tipesales = $_SESSION['session_tipe'];
$idpeg = $_SESSION['id_peg'];
$nmpeg = $_SESSION['nama_peg'];
$user = $_SESSION['session_user'];
$pass = $_SESSION['session_pass'];

$curl = curl_init();
$curl2 = curl_init();
$curl3 = curl_init();
$curl4 = curl_init();
$curl4x = curl_init();
$curl5 = curl_init();
$curl6 = curl_init();
$curl7 = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Login",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $db . '",
		"Password": "' . $pass . '",
		"UserName": "' . $user . '"}',
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$token = "";

if ($err) {
  echo "cURL Error #:" . $err;
} else {

  $data2 = json_decode($response);
  $token = $data2->SessionId;

}




//Load Customer
$endpofloat2 = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(BusinessPartners,SalesPersons,PaymentTermsTypes)';

if ($tipe == 1) {
  $params1 = array('$expand' => 'BusinessPartners($select=CardCode,CardName,Address,FederalTaxID,Phone1,Phone2,Cellular,SalesPersonCode,CreditLimit,CurrentAccountBalance,Currency),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)', '$filter' => 'BusinessPartners/SalesPersonCode eq SalesPersons/SalesEmployeeCode and BusinessPartners/PayTermsGrpCode eq PaymentTermsTypes/GroupNumber and (contains(BusinessPartners/CardCode,\'C\') or contains(BusinessPartners/CardCode,\'A\')) and BusinessPartners/Valid eq \'tYES\' and BusinessPartners/CardType eq \'C\' and BusinessPartners/SalesPersonCode eq ' . $idpeg . '', '$orderby' => 'BusinessPartners/CardCode asc');
} else {
  $params1 = array('$expand' => 'BusinessPartners($select=CardCode,CardName,Address,FederalTaxID,Phone1,Phone2,Cellular,SalesPersonCode,CreditLimit,CurrentAccountBalance,Currency),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)', '$filter' => 'BusinessPartners/SalesPersonCode eq SalesPersons/SalesEmployeeCode and BusinessPartners/PayTermsGrpCode eq PaymentTermsTypes/GroupNumber and BusinessPartners/Valid eq \'tYES\' and BusinessPartners/CardType eq \'C\' and (BusinessPartners/SalesPersonCode eq ' . $idpeg . ' or BusinessPartners/CardCode eq \'C00000001\')', '$orderby' => 'BusinessPartners/CardCode asc');
}
$url2 = $endpofloat2 . '?' . http_build_query($params1);
curl_setopt_array($curl3, [
  CURLOPT_URL => $url2,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 60,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_POSTFIELDS => json_encode([
    'CompanyDB' => $db,
    'Password' => $pass,
    'UserName' => $user
  ]),
  CURLOPT_HTTPHEADER => [
    "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
    "Prefer:odata.maxpagesize=8500",
    "Content-Type: application/json"
  ]
]);

$response3 = curl_exec($curl3);
$err3 = curl_error($curl3);

curl_close($curl3);

$cardcode = "";
$cardname = "";
$address = "";
$payterms = "";

if ($err3) {
  echo "cURL Error #:" . $err3;
} else {

  $data3x = json_decode($response3, true);
  $data31 = json_decode($response3);

}



if ($tipe == 1) {

  if ($tipesales == 1) {
    $endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
    $var1 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode', '$filter' => '(ItemsGroupCode eq 100 or ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109 or ItemsGroupCode eq 115) and SalesItem eq \'tYES\' and Valid eq \'tYES\'', '$orderby' => 'ItemsGroupCode,ItemCode asc');
  } else {
    $endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Items,U_HR_GRP_PROFILE)';
    $var1 = array('$expand' => 'Items($select=ItemCode,ItemName,ItemsGroupCode,U_IT_Profil,U_IT_Tebal,U_HR_AZ,Properties1,Properties2,Properties3,Properties4,Properties5,Properties6,Properties7,Properties8,Properties9,Properties10,Properties11,Properties12,Properties13,Properties14,Properties15,Properties16,Properties17,Properties18),U_HR_GRP_PROFILE($select=Code,Name,U_GrpName)', '$filter' => 'Items/U_IT_Profil eq U_HR_GRP_PROFILE/Code and Items/SalesItem eq \'tYES\' and Items/Valid eq \'tYES\' and (Items/ItemsGroupCode eq 101 or Items/ItemsGroupCode eq 102)', '$orderby' => 'Items/ItemCode asc');
  }

  $url3 = $endurl . '?' . http_build_query($var1);
  curl_setopt_array($curl4, [
    CURLOPT_URL => $url3,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POSTFIELDS => json_encode([
      'CompanyDB' => $db,
      'Password' => $pass,
      'UserName' => $user
    ]),
    CURLOPT_HTTPHEADER => [
      "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
      "Prefer:odata.maxpagesize=50000",
      "Content-Type: application/json"
    ]
  ]);

  $response4 = curl_exec($curl4);
  $err4 = curl_error($curl4);

  curl_close($curl4);

  $itemcode = "";
  $itemname = "";
  $profil = "";
  $tebal = "";
  $az = "";
  $batik = "";
  $warna = "";
  $radial = "";
  $crimping = "";
  $nokcrimping = "";
  $upcloser = "";
  $endcloser = "";
  $endstopper = "";
  $pu = "";
  $pe = "";
  $flasing = "";
  $flasing300 = "";
  $flasing450 = "";
  $flasing600 = "";
  $flasingcustom = "";
  $code = "";
  $nama = "";
  $grpname = "";
  $plat300 = "";
  $plat450 = "";
  $plat600 = "";

  if ($err4) {
    echo "cURL Error #:" . $err4;
  } else {

    $data4x = json_decode($response4, true);
    $data41 = json_decode($response4);

  }


  $endurlx = 'https://172.16.226.2:50000/b1s/v1/U_HR_GRP_PRICE';
  curl_setopt_array($curl4x, [
    CURLOPT_URL => $endurlx,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POSTFIELDS => json_encode([
      'CompanyDB' => $db,
      'Password' => $pass,
      'UserName' => $user
    ]),
    CURLOPT_HTTPHEADER => [
      "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
      "Prefer:odata.maxpagesize=1000",
      "Content-Type: application/json"
    ]
  ]);

  $response4x = curl_exec($curl4x);
  $err4x = curl_error($curl4x);

  curl_close($curl4x);

  $kodepl = "";
  $harga1 = "";
  $harga2 = "";
  $harga3 = "";
  $hargahet = "";
  $utebal = "";

  if ($err4x) {
    echo "cURL Error #:" . $err4x;
  } else {

    $data4xx = json_decode($response4x, true);
    $data4x1 = json_decode($response4x);



  }

} else {

  $endpofloat3 = 'https://172.16.226.2:50000/b1s/v1/Items';
  $params2 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode,ItemPrices', '$filter' => '(ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109) and SalesItem eq \'tYES\' and Valid eq \'tYES\'', '$orderby' => 'ItemCode asc');
  $url3 = $endpofloat3 . '?' . http_build_query($params2);
  curl_setopt_array($curl4, [
    CURLOPT_URL => $url3,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POSTFIELDS => json_encode([
      'CompanyDB' => $db,
      'Password' => $pass,
      'UserName' => $user
    ]),
    CURLOPT_HTTPHEADER => [
      "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
      "Prefer:odata.maxpagesize=50000",
      "Content-Type: application/json"
    ]
  ]);

  $response4 = curl_exec($curl4);
  $err4 = curl_error($curl4);

  curl_close($curl4);

  $itemcode = "";
  $itemname = "";
  $kode_pl = "";
  $hrg_pl = "";
  $base_pl = "";
  $price1 = "";
  $price2 = "";
  $price3 = "";
  $price4 = "";

  if ($err4) {
    echo "cURL Error #:" . $err4;
  } else {

    $data4x = json_decode($response4, true);
    $data41 = json_decode($response4);

  }
}

//Load Gudang
$endpofloat5 = 'https://172.16.226.2:50000/b1s/v1/Warehouses';
$params4 = array('$select' => 'WarehouseCode,WarehouseName', '$filter' => 'startswith(WarehouseCode,\'WRF\') and Inactive eq \'tNO\'', '$orderby' => 'WarehouseCode asc');
$url5 = $endpofloat5 . '?' . http_build_query($params4);
curl_setopt_array($curl6, [
  CURLOPT_URL => $url5,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 60,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_POSTFIELDS => json_encode([
    'CompanyDB' => $db,
    'Password' => $pass,
    'UserName' => $user
  ]),
  CURLOPT_HTTPHEADER => [
    "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
    "Prefer:odata.maxpagesize=50",
    "Content-Type: application/json"
  ]
]);

$response6 = curl_exec($curl6);
$err6 = curl_error($curl6);

curl_close($curl6);

$whsecode = "";
$whsename = "";

if ($err6) {
  echo "cURL Error #:" . $err6;
} else {

  $data6x = json_decode($response6, true);
  $data61 = json_decode($response6);

}


$endpofloat6 = 'https://172.16.226.2:50000/b1s/v1/Projects';
$params5 = array('$select' => 'Code,Name', '$filter' => 'Active eq \'tYES\'', '$orderby' => 'Code asc');
$url6 = $endpofloat6 . '?' . http_build_query($params5);
curl_setopt_array($curl7, [
  CURLOPT_URL => $url6,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 60,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_POSTFIELDS => json_encode([
    'CompanyDB' => $db,
    'Password' => $pass,
    'UserName' => $user
  ]),
  CURLOPT_HTTPHEADER => [
    "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
    "Prefer:odata.maxpagesize=200",
    "Content-Type: application/json"
  ]
]);

$response7 = curl_exec($curl7);
$err7 = curl_error($curl7);

curl_close($curl7);

$projectcode = "";
$projectname = "";

if ($err7) {
  echo "cURL Error #:" . $err7;
} else {

  $data7x = json_decode($response7, true);
  $data71 = json_decode($response7);

}
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-align-left"></i> Form Transaksi Penjualan
    </li>
  </ol>
</nav>

<div class="page-content">
  <div class="row">
    <div class="col-6">
      <h4>Transaksi Penjualan</h4>
    </div>
    <div class="col-6 text-right">
    </div>
  </div>
  <div class="form-container">
    <div class="row" style="padding: 0 12px;">
      <div class="col-md-12 vertical-form">
        <h6><i class="fas fa-list-alt"></i> Lengkapi form ini untuk menambah data penjualan</h6>

        <form method="post" id="form_penjualan" autocomplete="off">
          <div class="position-relative form-group" style="text-align: left; ">
            <table width="100%" border="0" style="font-family:arial,tahoma; font-size: 14px;">


              <tr>
                <td width="40%">Tipe Trans </td>
                <td width="2%"></td>
                <td> <input type="hidden" class="form-control form-control-sm" name="kodetrans" id="kodetrans"
                    onchange="nm()"> <select name="trans" id="trans" onchange="Stampiltrans()">
                    <option value="" selected>-Pilih Transaksi-</option>
                    <option value="1">Sales Quotation</option>
                    <option value="2">Sales Orders</option>
                  </select>
                  <font color="red"><b>(*)</b></font>
                </td>
              </tr>
              <tr>
                <td width="40%"><span id="tgltrans"></span></td>
                <td width="2%"></td>
                <td>
                  <font color="blue"><b><input class="" size="10" type="date" name="tglsales"
                        value="<?php echo date('Y-m-d'); ?>" id="tglsales" disabled=""></b></font>
                </td>
              </tr>
              <tr>
                <td width="40%"><span id="tgltrans2"></span></td>
                <td width="2%"></td>
                <td>
                  <font color="blue"><b><input class="" size="10" type="date" name="tgldelivery" value=""
                        id="tgldelivery"></b></font>
                </td>
              </tr>

              <tr>
                <td width="40%">Jenis Trans</td>
                <td width="2%"></td>
                <td>
                  <input type="radio" id="jenistrans" name="jenistrans" value="1" onchange="Stampiljenis()"
                    checked="true">
                  <label for="item">Item</label>
                  <input type="radio" id="jenistrans" name="jenistrans" value="2" onchange="Stampiljenis()">
                  <label for="service">Service</label>
                </td>
              </tr>


              <tr>
                <td colspan="3">
                  <h6>
                </td>
              </tr>
              <tr>
                <td width="40%">Customer </td>
                <td width="2%"></td>
                <td> <b>

                    <input type="text" class="form-control form-control-sm" data-toggle="modal"
                      data-target="#modal_datacus" rows="2" name="kode_cus" id="kode_cus" readonly>
                    <br>
                  </b>
                </td>
              </tr>

              <tr>
                <td width="40%">Nama </td>
                <td width="2%"></td>
                <td> <b><textarea class="form-control form-control-sm" name="nama_cus" id="nama_cus"
                      readonly></textarea><br></b> </td>
              </tr>

              <tr>
                <td width="40%">Payment Terms </td>
                <td width="5%"></td>
                <td> <b><input type="text" class="form-control form-control-sm" name="payterms" id="payterms"
                      readonly><br></b> </td>
              </tr>


              <tr>
                <td width="40%">Cust. Currency </td>
                <td width="5%"></td>
                <td> <b><input type="text" class="form-control form-control-sm" name="cus_curr" id="cus_curr"
                      readonly><br></b> </td>
              </tr>
              <tr>
                <td width="40%">Currency Rate </td>
                <td width="5%"></td>
                <td> <b><input type="number" class="form-control form-control-sm" name="cus_rate" id="cus_rate"><br></b>
                </td>
              </tr>



              <tr>
                <td width="40%">Cust Ref. No. </td>
                <td width="5%"></td>
                <td> <b><input type="text" class="form-control form-control-sm" name="cus_ref" id="cus_ref"><br></b>
                </td>
              </tr>

              <tr>
                <td width="40%">Kontak Person </td>
                <td width="5%"></td>
                <td> <b><input type="text" class="form-control form-control-sm" name="kontak" id="kontak"><br></b> </td>
              </tr>

              <tr>
                <td width="40%">Alamat Kirim </td>
                <td width="5%"></td>
                <td> <b><textarea class="form-control form-control-sm" rows="2" name="alamat"
                      id="alamat"></textarea><br></b> </td>
              </tr>
              <tr>
                <td colspan="3">
                  <h6>
                </td>
              </tr>
              <tr>
                <td width="40%">Gudang </td>
                <td width="2%"></td>
                <td> <b>
                    <input type="hidden" class="form-control form-control-sm" name="kode_gudang" id="kode_gudang"
                      readonly>
                    <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_datagudang"
                      rows="2" name="nama_gudang" id="nama_gudang" readonly></textarea><br></b> </td>
              </tr>
              <tr>
                <td width="40%">Project </td>
                <td width="2%"></td>
                <td> <b>
                    <input type="hidden" class="form-control form-control-sm" name="kode_project" id="kode_project"
                      readonly>
                    <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_dataproject"
                      rows="2" name="nama_project" id="nama_project" readonly></textarea><br></b> </td>
              </tr>
              <tr>
                <td width="40%">Sales </td>
                <td width="2%"></td>
                <td> <b>

                    <input type="text" class="form-control form-control-sm" value="<?php echo $nmpeg; ?>"
                      name="nama_sales" id="nama_sales" readonly><br></b> </td>
              </tr>

              <?php if ($tipe == "2") { ?>
                <tr>
                  <td width="40%">Harga Pengiriman </td>
                  <td width="5%"></td>
                  <td> <b><select class="form-control form-control-sm" name="note1" id="note1" onchange="mynote()">
                        <option value="Harga belum termasuk pengiriman">Harga belum termasuk pengiriman</option>
                        <option value="Harga termasuk pengiriman ke lokasi">Harga termasuk pengiriman ke lokasi</option>
                        <option value="Harga Exwork Warehouse Utomo Solaruv">Harga Exwork Warehouse Utomo Solaruv</option>
                        <option value="Harga CIF Port Jakarta/Surabaya">Harga CIF Port Jakarta/Surabaya</option>
                      </select></b> </td>
                </tr>
                <tr>
                  <td width="40%">Waktu Pengiriman </td>
                  <td width="5%"></td>
                  <td> <b><select class="form-control form-control-sm" name="note2" id="note2" onchange="mynote()">
                        <option value="3-7 Hari setelah pembayaran">3-7 Hari setelah pembayaran</option>
                        <option value="2-3 Bulan setelah PO diterima">2-3 Bulan setelah PO diterima</option>
                      </select></b> </td>
                </tr>
                <tr>
                  <td width="40%">Term Pembayaran </td>
                  <td width="5%"></td>
                  <td> <b><select class="form-control form-control-sm" name="note3" id="note3" onchange="mynote()">
                        <option value="Pembayaran 100% sebelum pengiriman">Pembayaran 100% sebelum pengiriman</option>
                        <option value="DP 30% dan Pelunasan sebelum material dikirim/diambil">DP 30% dan Pelunasan sebelum
                          material dikirim/diambil</option>
                        <option value="DP 30% dan Pelunasan setelah material diterima">DP 30% dan Pelunasan setelah
                          material diterima</option>
                        <option value="14 Hari Setelah material diterima">14 Hari Setelah material diterima</option>
                        <option value="30 Hari Setelah material diterima">30 Hari Setelah material diterima</option>
                        <option value="Lainnya">Lainnya</option>
                      </select></b> </td>
                </tr>
                <tr>
                  <td width="40%">Masa Berlaku </td>
                  <td width="5%"></td>
                  <td> <b><select class="form-control form-control-sm" name="note4" id="note4" onchange="mynote()">
                        <option
                          value="Penawaran ini berlaku 7 hari dari tanggal terbit dan dapat berubah sebelum PO diterima">
                          Penawaran ini berlaku 7 hari</option>
                        <option
                          value="Penawaran ini berlaku 14 hari dari tanggal terbit dan dapat berubah sebelum PO diterima">
                          Penawaran ini berlaku 14 hari</option>
                        <option
                          value="Penawaran ini berlaku 30 hari dari tanggal terbit dan dapat berubah sebelum PO diterima">
                          Penawaran ini berlaku 30 hari</option>
                      </select></b> </td>
                </tr>
                <tr>
                  <td width="40%">Garansi </td>
                  <td width="5%"></td>
                  <td> <b><select class="form-control form-control-sm" name="note5" id="note5" onchange="mynote()">
                        <option value="Garansi Inverter 5 tahun">Garansi Inverter 5 tahun</option>
                        <option value="Garansi PV Module 12 tahun">Garansi PV Module 12 tahun</option>
                      </select></b> </td>
                </tr>
                <tr>
                  <td width="40%">Note Tambahan </td>
                  <td width="5%"></td>
                  <td> <b><textarea class="form-control form-control-sm" rows="2" name="note6" id="note6"
                        onkeyup="mynote()"></textarea></b> </td>
                </tr>
                <tr>
                  <td width="40%">Remark </td>
                  <td width="5%"></td>
                  <td> <b><textarea class="form-control form-control-sm" rows="8" name="remark"
                        id="remark"></textarea></b> </td>
                </tr>
              <?php } else { ?>
                <tr>
                  <td width="40%">Remark </td>
                  <td width="5%"></td>
                  <td> <b><textarea class="form-control form-control-sm" rows="2" name="remark"
                        id="remark"></textarea></b> </td>
                </tr>
              <?php } ?>
              <script>
                function nm() {
                  var x = document.getElementById("kodetrans").value;
                  if (x == 1) {
                    document.getElementById("nama_cus").removeAttribute("readonly", "readonly");
                  } else {
                    document.getElementById("nama_cus").setAttribute("readonly", "readonly");
                  }

                }

                function mynote() {
                  var x1 = document.getElementById("note1").value;
                  var x2 = document.getElementById("note2").value;
                  var x3 = document.getElementById("note3").value;
                  var x4 = document.getElementById("note4").value;
                  var x5 = document.getElementById("note5").value;
                  var x6 = document.getElementById("note6").value;
                  var all = "1. " + x1 + "., \n";
                  all = all + "2. " + x2 + "., \n";
                  all = all + "3. " + x3 + "., \n";
                  all = all + "4. " + x4 + "., \n";
                  all = all + "5. " + x5 + "., \n";
                  all = all + "6. " + x6 + "., \n";
                  document.getElementById("remark").value = all;
                }

              </script>


            </table>
          </div>

          <div class="row kotak-form-tabel-penjualan">
            <div class="col-md-3 kotak-form-obat-terjual" style="display: ;">

              <div class="position-relative form-group">
                <label for="kode_obat" class="">Kode Barang</label>
                <div class="input-group">
                  <input type="text" class="form-control form-control-sm" id="kode_obat" readonly>
                  <div class="input-group-append">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                      data-target="#modal_dataobat" id="lihat_data_obat"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
              <div class="position-relative form-group">
                <label for="nm_obat" class="">Nama Barang</label>
                <textarea name="nm_obat" id="nm_obat" placeholder="" rows="2" cols="5"
                  class="form-control form-control-sm" readonly></textarea>

              </div>

              <div class="position-relative form-group">
                <label for="series" class="">Series</label>
                <input type="text" class="form-control" name="series" id="series">
              </div>

              <div class="position-relative form-group">
                <label for="uom" class="">UoM</label>
                <input type="text" class="form-control" name="uom" id="uom">
              </div>

              <?php if ($tipe == '1') { ?>
                <div class="position-relative form-group">
                  <label for="pj_barang" class="">Panjang</label>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="pj_barang" name="pj_barang"
                      aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="0" step="0.01"
                      onkeyup="Shitung()">
                    <div class="input-group-append">
                      <span class="input-group-text" id="">meter</span>
                    </div>
                  </div>
                </div>
                <div class="position-relative form-group">
                  <label for="lbr_barang" class="">Lembar</label>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="lbr_barang" name="lbr_barang"
                      aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="0"
                      onkeyup="Shitung()">
                    <div class="input-group-append">
                      <span class="input-group-text" id="">lembar</span>
                    </div>
                  </div>
                </div>
                <div class="position-relative form-group">
                  <label for="lb_barang" class="">Lebar</label>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="lb_barang" name="lb_barang"
                      aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="0" placeholder="0"
                      onkeyup="delayedShitung()">
                    <div class="input-group-append">
                      <span class="input-group-text" id="">milimeter</span>
                    </div>
                  </div>
                </div>
              <?php } ?>


              <div class="position-relative form-group">
                <label for="qty" class="">Quantity</label>
                <div class="input-group input-group-sm">
                  <?php if ($tipe == '1') { ?>
                    <input type="text" class="form-control" id="qty" name="qty" aria-label="Sizing example input"
                      aria-describedby="inputGroup-sizing-sm" value="0.00" step="0.01" readonly>
                  <?php } else { ?>
                    <input type="number" class="form-control" id="qty" name="qty" aria-label="Sizing example input"
                      aria-describedby="inputGroup-sizing-sm" value="0" step="any" onkeyup="Shitunguj()">
                  <?php } ?>
                  <div class="input-group-append">
                    <span class="input-group-text" id=""><?php if ($tipe == '1') {
                      echo "meter lari";
                    } else {
                      echo "(unit/pcs)";
                    } ?></span>
                  </div>
                </div>
              </div>

              <div class="position-relative form-group">
                <label for="UoM_serv" class="">UoM Service</label>
                <div class="input-group input-group-sm">
                  <select class="form-control" name="uomserv" id="uomserv" disabled>
                    <option value="ML" selected="selected">Meter Lari</option>
                    <option value="MTR">Meter</option>
                    <option value="KG">Kilogram</option>
                    <option value="BLN">Bulan</option>
                    <option value="BOX">Box</option>
                    <option value="BTL">Botol</option>
                    <option value="DRUM">Drum</option>
                    <option value="LBR">Lembar</option>
                    <option value="LB">Lebar</option>
                    <option value="LJR">Lonjor</option>
                    <option value="M3">M3</option>
                    <option value="ORG">Orang</option>
                    <option value="PAIL">Pail</option>
                    <option value="PAK">Pack</option>
                    <option value="PCS">Pieces</option>
                    <option value="PSG">Pasang</option>
                    <option value="RIM">Rim</option>
                    <option value="RIT">Ritase</option>
                    <option value="ROLL">Roll</option>
                    <option value="SAK">Sak</option>
                    <option value="SITE">Site</option>
                    <option value="SET">Set</option>
                    <option value="THN">Tahun</option>
                    <option value="UNIT">Unit</option>
                    <option value="M2">M2</option>
                  </select>
                </div>
              </div>

              <?php if ($tipesales == 1) { ?>
                <div class="position-relative form-group">
                  <label for="hrg_sat" class="">Harga Sat (exc PPN)</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">

                      <input type="text" class="input-group-text" name="cus_curr2" id="cus_curr2" size="2" readonly>
                    </div>
                    <input type="text" class="form-control" id="hrg_sat" name="hrg_sat" aria-label="Sizing example input"
                      aria-describedby="inputGroup-sizing-sm" onkeyup="Shitunguj()">
                  </div>
                </div>
              <?php } else { ?>
                <div class="position-relative form-group">
                  <label for="hrg_sat" class="">Harga Sat (exc PPN)</label>
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">

                      <input type="text" class="input-group-text" name="cus_curr2" id="cus_curr2" size="2" readonly>
                    </div>

                    <input type="hidden" class="form-control form-control-sm" name="kode_pl" id="kode_pl" readonly>
                    <input type="number" class="form-control" id="hrg_sat" name="hrg_sat"
                      aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" readonly
                      onkeyup="Shitung()">

                  </div>
                </div>
              <?php } ?>


              <div class="position-relative form-group">
                <label for="toth_hrg" class="">Total Harga <span id="totket"></span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-prepend">

                    <input type="text" class="input-group-text" name="cus_curr3" id="cus_curr3" size="2" readonly>
                  </div>
                  <input name="toth_hrg" id="toth_hrg" placeholder="" type="number" class="form-control form-control-sm"
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" step="0.01" readonly>
                </div>
              </div>
              <div class="position-relative form-group text-right mt-2 mb-2">

                <button type="button" class="btn btn-success btn-sm" id="tambah_obat">tambah</button>
              </div>

            </div>

            <div class="col-md-9 kotak-tabel-obat-terjual" id="table-scroll">
              <table class="table display tabel-data" style="font-family:arial,tahoma; font-size: 12px;">
                <thead>
                  <tr>
                    <?php if ($tipe == "1") { ?>
                      <th class="text-left">Kode</th>
                      <th class="text-left">Nama</th>
                      <th class="text-right">Pjg</th>
                      <th class="text-right">Lbr</th>
                      <th class="text-right">Lb</th>

                      <th class="text-right">Series</th>
                      <th class="text-right">UoM</th>
                    <?php } else { ?>
                      <th class="text-left">Kode</th>
                      <th class="text-left">Nama</th>
                      <th class="text-right"></th>
                      <th class="text-right"></th>

                      <th class="text-right">Series</th>
                      <th class="text-right">UoM</th>
                    <?php } ?>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga<br>(exc PPN)</th>
                    <th class="text-right">Total<br><span id="totket2"></span></th>
                    <th class="text-center"></th>
                  </tr>
                </thead>
                <tbody id="keranjang_obat">

                </tbody>
                <tfoot>
                  <tr id="baris_kosong">
                    <td colspan="10" class="text-center">Belum ada data</td>
                  </tr>
                  <tr class="baris_total" style="display: none;">
                    <td colspan="9" class="text-right" style="font-weight: bold;">Sub Total <span id="totket3"></span> :
                      <input type="text" name="hidden_totalpenjualan" id="hidden_totalpenjualan" align="right" size="10"
                        disabled="">
                    </td>
                    <td class="td-opsi">
                      <button type="button" class="btn-transition btn btn-outline-danger btn-sm"
                        title="hapus semua item" id="hapus_semua_obat">del all</button>
                    </td>
                  </tr>
                  <tr class="baris_total" style="display: none;">
                    <td colspan="9" class="text-right" style="font-weight: bold;">Discount : <input type="text"
                        name="total_diskon" id="total_diskon" align="right" size="4" onkeyup="Shitungpercent()"> %
                      <input type="text" name="hidden_totaldiskon" id="hidden_totaldiskon" align="right" size="10"
                        onkeyup="Shitungtotal()" readonly>
                    </td>
                    <td class="td-opsi">

                    </td>
                  </tr>

                  <tr class="baris_total" style="display: none;">
                    <td colspan="9" class="text-right" style="font-weight: bold;">Total <span id="totket4"></span> :
                      <input type="text" name="hidden_totalpenjualanppn" id="hidden_totalpenjualanppn" align="right"
                        size="10" disabled="">
                    </td>
                    <td class="td-opsi">

                    </td>
                  </tr>
                </tfoot>
              </table>
              <div class="baris_total text-right" style="display: none;">

              </div>
            </div>
          </div>
          <div class="text-right tombol-kanan">
            <input type="submit" name="simpan_penjualan" id="simpan_penjualan" class="btn btn-info" value="Simpan">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Modal Customer -->
<div class="modal fade" id="modal_datacus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="example" class="table table-striped display tabel-data">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama</th>

              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            for ($i = 0; $i < count($data3x['value']); $i++) {
              $cardcode = $data31->value[$i]->BusinessPartners->CardCode;
              $cardname = $data31->value[$i]->BusinessPartners->CardName;
              $address = $data31->value[$i]->BusinessPartners->Address;
              $pay_terms = $data31->value[$i]->PaymentTermsTypes->PaymentTermsGroupName;
              $currency = $data31->value[$i]->BusinessPartners->Currency;
              ?>
              <tr>
                <td><?php echo $cardcode; ?></td>
                <td><?php echo $cardname; ?></td>

                <td class="td-opsi">
                  <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihcus"
                    name="tombol_pilihcus" data-dismiss="modal" data-kodecus="<?php echo $cardcode; ?>"
                    data-namacus="<?php echo $cardname; ?>" data-alamat="<?php echo $address; ?>"
                    data-terms="<?php echo $pay_terms; ?>" data-currency="<?php echo $currency; ?>"> <b>pilih</b>
                  </button>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Gudang -->
<div class="modal fade" id="modal_datagudang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Gudang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-striped display tabel-data">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama</th>

              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            for ($i = 0; $i < count($data6x['value']); $i++) {
              $whsecode = $data61->value[$i]->WarehouseCode;
              $whsename = $data61->value[$i]->WarehouseName;

              ?>
              <tr>
                <td><?php echo $whsecode; ?></td>
                <td><?php echo $whsename; ?></td>
                <td class="td-opsi">
                  <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihgudang"
                    name="tombol_pilihgudang" data-dismiss="modal" data-kodegudang="<?php echo $whsecode; ?>"
                    data-namagudang="<?php echo $whsename; ?>"> <b>pilih</b>
                  </button>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Project -->
<div class="modal fade" id="modal_dataproject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-striped display tabel-data">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama</th>

              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php

            for ($i = 0; $i < count($data7x['value']); $i++) {
              $projectcode = $data71->value[$i]->Code;
              $projectname = $data71->value[$i]->Name;

              ?>
              <tr>
                <td><?php echo $projectcode; ?></td>
                <td><?php echo $projectname; ?></td>
                <td class="td-opsi">
                  <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihproject"
                    name="tombol_pilihproject" data-dismiss="modal" data-kodeproject="<?php echo $projectcode; ?>"
                    data-namaproject="<?php echo $projectname; ?>"> <b>pilih</b>
                  </button>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal Barang -->
<div class="modal fade" id="modal_dataobat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="table-scroll">
        <table id="tabel_dataobat" class="table table-striped display tabel-data">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama</th>
              <?php if ($tipesales == 2) { ?>
                <th>Harga1</th>
                <th>Harga2</th>
                <th>Harga3</th>
                <th>Harga HET</th>
              <?php } else { ?>

                <th>Opsi</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php

            $curl = curl_init();
            $curl4 = curl_init();
            $curl4x = curl_init();
            curl_setopt_array($curl, [
              CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Login",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $db . '",
                "Password": "' . $pass . '",
                "UserName": "' . $user . '"}',
              CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
              ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            $token = "";

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {

              $data2 = json_decode($response);
              $token = $data2->SessionId;

            }

            if ($tipe == 1) {

              if ($tipesales == 1) {
                $endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
                $var1 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode', '$filter' => '(ItemsGroupCode eq 100 or ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109 or ItemsGroupCode eq 115) and SalesItem eq \'tYES\' and Valid eq \'tYES\'', '$orderby' => 'ItemsGroupCode,ItemCode asc');
              } else {
                $endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Items,U_HR_GRP_PROFILE)';
                $var1 = array(
                  '$expand' => 'Items($select=ItemCode,ItemName,ItemsGroupCode,U_IT_Profil,U_IT_Tebal,U_HR_AZ,Properties1,Properties2,Properties3,Properties4,Properties5,Properties6,Properties7,Properties8,Properties9,Properties10,Properties11,Properties12,Properties13,Properties14,Properties15,Properties16,Properties17,Properties18),
                U_HR_GRP_PROFILE($select=Code,Name,U_GrpName)',
                  '$filter' => 'Items/U_IT_Profil eq U_HR_GRP_PROFILE/Code and Items/SalesItem eq \'tYES\' and Items/Valid eq \'tYES\' and (Items/ItemsGroupCode eq 101 or Items/ItemsGroupCode eq 102)',
                  '$orderby' => 'Items/ItemCode asc'
                );
              }
              $url3 = $endurl . '?' . http_build_query($var1);
              curl_setopt_array($curl4, [
                CURLOPT_URL => $url3,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode([
                  'CompanyDB' => $db,
                  'Password' => $pass,
                  'UserName' => $user
                ]),
                CURLOPT_HTTPHEADER => [
                  "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
                  "Prefer:odata.maxpagesize=50000",
                  "Content-Type: application/json"
                ]
              ]);

              $response4 = curl_exec($curl4);
              $err4 = curl_error($curl4);

              curl_close($curl4);

              $itemcode = "";
              $itemname = "";
              $profil = "";
              $tebal = "";
              $az = "";
              $batik = "";
              $warna = "";
              $radial = "";
              $crimping = "";
              $nokcrimping = "";
              $upcloser = "";
              $endcloser = "";
              $endstopper = "";
              $pu = "";
              $pe = "";
              $flasing = "";
              $flasing300 = "";
              $flasing450 = "";
              $flasing600 = "";
              $flasingcustom = "";
              $code = "";
              $nama = "";
              $grpname = "";
              $plat300 = "";
              $plat450 = "";
              $plat600 = "";

              if ($err4) {
                echo "cURL Error #:" . $err4;
              } else {

                $data4x = json_decode($response4, true);
                $data41 = json_decode($response4);

              }


              $endurlx = 'https://172.16.226.2:50000/b1s/v1/U_HR_GRP_PRICE';
              curl_setopt_array($curl4x, [
                CURLOPT_URL => $endurlx,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode([
                  'CompanyDB' => $db,
                  'Password' => $pass,
                  'UserName' => $user
                ]),
                CURLOPT_HTTPHEADER => [
                  "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
                  "Prefer:odata.maxpagesize=1000",
                  "Content-Type: application/json"
                ]
              ]);

              $response4x = curl_exec($curl4x);
              $err4x = curl_error($curl4x);

              curl_close($curl4x);

              $kodepl = "";
              $harga1 = "";
              $harga2 = "";
              $harga3 = "";
              $hargahet = "";
              $utebal = "";

              if ($err4x) {
                echo "cURL Error #:" . $err4x;
              } else {

                $data4xx = json_decode($response4x, true);
                $data4x1 = json_decode($response4x);



              }

            } else {

              $endpofloat3 = 'https://172.16.226.2:50000/b1s/v1/Items';
              $params2 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode,ItemPrices', '$filter' => '(ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109) and SalesItem eq \'tYES\' and Valid eq \'tYES\'', '$orderby' => 'ItemCode asc');
              $url3 = $endpofloat3 . '?' . http_build_query($params2);
              curl_setopt_array($curl4, [
                CURLOPT_URL => $url3,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode([
                  'CompanyDB' => $db,
                  'Password' => $pass,
                  'UserName' => $user
                ]),
                CURLOPT_HTTPHEADER => [
                  "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
                  "Prefer:odata.maxpagesize=50000",
                  "Content-Type: application/json"
                ]
              ]);

              $response4 = curl_exec($curl4);
              $err4 = curl_error($curl4);

              curl_close($curl4);

              $itemcode = "";
              $itemname = "";
              $kode_pl = "";
              $hrg_pl = "";
              $base_pl = "";
              $price1 = "";
              $price2 = "";
              $price3 = "";
              $price4 = "";

              if ($err4) {
                echo "cURL Error #:" . $err4;
              } else {

                $data4x = json_decode($response4, true);
                $data41 = json_decode($response4);

              }
            }


            for ($i = 0; $i < count($data4x['value']); $i++) {
              if ($tipesales == 1) {
                $itemcode = $data41->value[$i]->ItemCode;
                $itemname = $data41->value[$i]->ItemName;
                $itemname = str_replace("\"", "", $itemname);
              } else {
                if ($tipe == 1) {
                  $itemcode = $data41->value[$i]->Items->ItemCode;
                  $itemname = $data41->value[$i]->Items->ItemName;
                  $itemname = str_replace("\"", "", $itemname);
                  $profil = $data41->value[$i]->Items->U_IT_Profil;
                  $tebal = $data41->value[$i]->Items->U_IT_Tebal;
                  $az = $data41->value[$i]->Items->U_HR_AZ;
                  $batik = $data41->value[$i]->Items->Properties1;
                  $warna = $data41->value[$i]->Items->Properties2;
                  $radial = $data41->value[$i]->Items->Properties3;
                  $crimping = $data41->value[$i]->Items->Properties4;
                  $nokcrimping = $data41->value[$i]->Items->Properties5;
                  $upcloser = $data41->value[$i]->Items->Properties6;
                  $endcloser = $data41->value[$i]->Items->Properties7;
                  $endstopper = $data41->value[$i]->Items->Properties8;
                  $pu = $data41->value[$i]->Items->Properties9;
                  $pe = $data41->value[$i]->Items->Properties10;
                  $flasing = $data41->value[$i]->Items->Properties11;
                  $flasing300 = $data41->value[$i]->Items->Properties12;
                  $flasing450 = $data41->value[$i]->Items->Properties13;
                  $flasing600 = $data41->value[$i]->Items->Properties14;
                  $plat300 = $data41->value[$i]->Items->Properties15;
                  $plat450 = $data41->value[$i]->Items->Properties16;
                  $plat600 = $data41->value[$i]->Items->Properties17;
                  $flasingcustom = $data41->value[$i]->Items->Properties18;
                  $code = $data41->value[$i]->U_HR_GRP_PROFILE->Code;
                  $nama = $data41->value[$i]->U_HR_GRP_PROFILE->Name;
                  $grpname = $data41->value[$i]->U_HR_GRP_PROFILE->U_GrpName;

                  if ($warna == 'Y') {
                    $jenis = "WARNA";
                  } else {
                    $jenis = "NATUR";
                  }
                  $const = number_format((($tebal * 100) / 100), 2);
                  $kodetebal = str_replace(".", "", (string) $const);
                  $pricekode = $grpname . '-' . $jenis . '#' . $az . '#' . $kodetebal;


                  $harga1 = "";
                  $harga2 = "";
                  $harga3 = "";
                  $hargahet = "";

                  $harga1x = "";
                  $harga2x = "";
                  $harga3x = "";
                  $hargahetx = "";

                  $harga1xx = "";
                  $harga2xx = "";
                  $harga3xx = "";
                  $hargahetxx = "";

                  for ($j = 0; $j < count($data4xx['value']); $j++) {
                    $kodepl = $data4x1->value[$j]->Code;
                    $harga1 = $data4x1->value[$j]->U_Harga1;
                    $harga2 = $data4x1->value[$j]->U_Harga2;
                    $harga3 = $data4x1->value[$j]->U_Harga3;
                    $hargahet = $data4x1->value[$j]->U_HET;
                    if ($pricekode == $kodepl) {


                      $harga1x = (float) $harga1;
                      $harga2x = (float) $harga2;
                      $harga3x = (float) $harga3;
                      $hargahetx = (float) $hargahet;

                      $harga1xx = (float) $harga1;
                      $harga2xx = (float) $harga2;
                      $harga3xx = (float) $harga3;
                      $hargahetxx = (float) $hargahet;
                      //} 
            
                      if ($batik == 'Y') {
                        $harga1xx = (float) $harga1x + 1000;
                        $harga2xx = (float) $harga2x + 1000;
                        $harga3xx = (float) $harga3x + 1000;
                        $hargahetxx = (float) $hargahetx + 1000;
                      }
                      if ($radial == 'Y') {
                        $harga1xx = (float) $harga1x + 14500;
                        $harga2xx = (float) $harga2x + 14500;
                        $harga3xx = (float) $harga3x + 14500;
                        $hargahetxx = (float) $hargahetx + 14500;
                      }
                      if ($crimping == 'Y') {
                        $harga1xx = (float) $harga1x + 14500;
                        $harga2xx = (float) $harga2x + 14500;
                        $harga3xx = (float) $harga3x + 14500;
                        $hargahetxx = (float) $hargahetx + 14500;
                      }
                      if ($pu == 'Y') {
                        $harga1xx = (float) $harga1x + 170000;
                        $harga2xx = (float) $harga2x + 170000;
                        $harga3xx = (float) $harga3x + 170000;
                        $hargahetxx = (float) $hargahetx + 170000;
                      }
                      if ($pe == 'Y') {
                        $harga1xx = (float) $harga1x + 50000;
                        $harga2xx = (float) $harga2x + 50000;
                        $harga3xx = (float) $harga3x + 50000;
                        $hargahetxx = (float) $hargahetx + 50000;
                      }

                      if ($flasing == 'Y' || $nokcrimping == 'Y') {
                        $harga1xx = round((float) $harga1xx * 1.3);
                        $harga2xx = round((float) $harga2xx * 1.3);
                        $harga3xx = round((float) $harga3xx * 1.3);
                        $hargahetxx = round((float) $hargahetxx * 1.3);
                      }
                      if ($flasing450 == 'Y' || $flasing600 == 'Y' || $plat450 == 'Y' || $plat600 == 'Y') {
                        $harga1xx = round((float) $harga1xx / 2);
                        $harga2xx = round((float) $harga2xx / 2);
                        $harga3xx = round((float) $harga3xx / 2);
                        $hargahetxx = round((float) $hargahetxx / 2);
                      }
                      if ($flasing300 == 'Y' || $flasing300 == 'Y') {

                        $harga1xx = round((float) $harga1xx / 3);
                        $harga2xx = round((float) $harga2xx / 3);
                        $harga3xx = round((float) $harga3xx / 3);
                        $hargahetxx = round((float) $hargahetxx / 3);
                      }
                    }

                  }
                } else {
                  $itemcode = $data41->value[$i]->ItemCode;
                  $itemname = $data41->value[$i]->ItemName;
                  $itemname = str_replace("\"", "", $itemname);
                }
              }

              ?>
              <tr>
                <td><?php echo $itemcode; ?></td>
                <td><?php echo $itemname; ?></td>
                <?php if ($tipesales == 2) { ?>
                  <td>
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat1"
                      name="tombol_pilihobat1" data-dismiss="modal" data-kode="<?php echo $itemcode; ?>"
                      data-nama="<?php echo $itemname; ?>" data-harga="<?php echo round(((float) $harga1xx / 1.11), 2); ?>">
                      <?php if (($harga1 <> null)) {
                        echo number_format(((float) $harga1xx / 1.11), 2);
                      } else {
                        echo "0";
                      } ?>
                    </button>
                  </td>
                  <td>
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat2"
                      name="tombol_pilihobat2" data-dismiss="modal" data-kode="<?php echo $itemcode; ?>"
                      data-nama="<?php echo $itemname; ?>" data-harga="<?php echo round(((float) $harga2xx / 1.11), 2); ?>">
                      <?php if (($harga2 <> null)) {
                        echo number_format(((float) $harga2xx / 1.11), 2);
                      } else {
                        echo "0";
                      } ?>
                    </button>
                  </td>
                  <td>
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat3"
                      name="tombol_pilihobat3" data-dismiss="modal" data-kode="<?php echo $itemcode; ?>"
                      data-nama="<?php echo $itemname; ?>" data-harga="<?php echo round(((float) $harga3xx / 1.11), 2); ?>">
                      <?php if (($harga3 <> null)) {
                        echo number_format(((float) $harga3xx / 1.11), 2);
                      } else {
                        echo "0";
                      } ?>
                    </button>
                  </td>
                  <td>
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat4"
                      name="tombol_pilihobat4" data-dismiss="modal" data-kode="<?php echo $itemcode; ?>"
                      data-nama="<?php echo $itemname; ?>"
                      data-harga="<?php echo round(((float) $hargahetxx / 1.11), 2); ?>">
                      <?php if (($hargahet <> null)) {
                        echo number_format(((float) $hargahetxx / 1.11), 2);
                      } else {
                        echo "0";
                      } ?>
                    </button>
                  </td>
                <?php } else { ?>
                  <td class="td-opsi">

                    <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihobat"
                      name="tombol_pilihobat" data-dismiss="modal" data-kode="<?php echo $itemcode; ?>"
                      data-nama="<?php echo $itemname; ?>" data-harga="<?php echo $data['hrg_obat']; ?>"
                      data-satuan="<?php echo $data['sat_obat']; ?>" data-stok="<?php echo $data['stok']; ?>"
                      data-exp="<?php echo $data['tgl_exp']; ?>"> <b>pilih</b>
                    </button>
                  </td>
                <?php } ?>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    // $(".kotak-form-obat-terjual").slideTo('slow');
    var count = 0;
    var total_penjualan = 0;

    var kodebrg = new Array();
    var namabrg = new Array();
    var pjbrg = new Array();
    var lbrbrg = new Array();
    var lbbrg = new Array();
    var qtybrg = new Array();
    var hrgbrg = new Array();
    var totbrg = new Array();
    var prclst = new Array();

    $('.datepicker').datepicker({
      format: "yyyy-mm-dd",
      orientation: "bottom left",
      todayBtn: "linked",
      autoclose: true,
      language: "id",
      todayHighlight: true
    });



    $("button[name='tombol_pilihobat']").click(function () {
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');


      if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#kode_brg").val(kode);
      } else {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#kode_brg").val(kode);
      }

      var cari = kode.search(/FL|O0/);
      if (cari == "-1") {
        document.getElementById("lb_barang").setAttribute("readonly", "readonly");

      } else {
        document.getElementById("lb_barang").removeAttribute("readonly", "readonly");
      }
    });

    $("button[name='tombol_pilihobat1']").click(function () {
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');
      var harga = $(this).data('harga');
      var pricelist = "HARGA1"

      if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#hrg_sat").val(harga);
        $("#prc_lst").val(pricelist);
      } else {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#kode_brg").val(kode);
        $("#prc_lst").val(pricelist);
      }

      var cari = kode.search(/FL|O0/);
      if (cari == "-1") {
        document.getElementById("lb_barang").setAttribute("readonly", "readonly");
      } else {
        document.getElementById("lb_barang").removeAttribute("readonly", "readonly");
      }
    });
    $("button[name='tombol_pilihobat2']").click(function () {
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');
      var harga = $(this).data('harga');
      var pricelist = "HARGA2"

      if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#hrg_sat").val(harga);
        $("#prc_lst").val(pricelist);
      } else {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#kode_brg").val(kode);
        $("#prc_lst").val(pricelist);
      }

      var cari = kode.search(/FL|O0/);
      if (cari == "-1") {
        document.getElementById("lb_barang").setAttribute("readonly", "readonly");
      } else {
        document.getElementById("lb_barang").removeAttribute("readonly", "readonly");
      }
    });
    $("button[name='tombol_pilihobat3']").click(function () {
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');
      var harga = $(this).data('harga');
      var pricelist = "HARGA3"

      if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#hrg_sat").val(harga);
        $("#prc_lst").val(pricelist);
      } else {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#kode_brg").val(kode);
        $("#prc_lst").val(pricelist);
      }

      var cari = kode.search(/FL|O0/);
      if (cari == "-1") {
        document.getElementById("lb_barang").setAttribute("readonly", "readonly");
      } else {
        document.getElementById("lb_barang").removeAttribute("readonly", "readonly");
      }
    });
    $("button[name='tombol_pilihobat4']").click(function () {
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');
      var harga = $(this).data('harga');
      var pricelist = "HET"

      if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#hrg_sat").val(harga);
        $("#prc_lst").val(pricelist);
      } else {
        $("#kode_obat").val(kode);
        $("#nm_obat").val(nama);
        $("#kode_brg").val(kode);
        $("#prc_lst").val(pricelist);
      }

      var cari = kode.search(/FL|O0/);
      if (cari == "-1") {
        document.getElementById("lb_barang").setAttribute("readonly", "readonly");
      } else {
        document.getElementById("lb_barang").removeAttribute("readonly", "readonly");
      }
    });

    $("button[name='tombol_pilihcus']").click(function () {
      var kodecus = $(this).data('kodecus');
      var namacus = $(this).data('namacus');
      var alamat = $(this).data('alamat');
      var payterms = $(this).data('terms');
      var currency = $(this).data('currency');
      if (currency == "##") {
        currency = "USD";
      }
      if (currency == "IDR") {
        rate = "1";
        $("#cus_rate").val(rate);
        document.getElementById("cus_rate").setAttribute("readonly", "readonly");
      } else {
        document.getElementById("cus_rate").removeAttribute("readonly", "readonly");
      }


      $("#kode_cus").val(kodecus);
      $("#nama_cus").val(namacus);
      $("#alamat").val(alamat);
      $("#payterms").val(payterms);
      $("#cus_curr").val(currency);
      $("#cus_curr2").val(currency);
      $("#cus_curr3").val(currency);

    });

    $("button[name='tombol_pilihgudang']").click(function () {
      var kodegudang = $(this).data('kodegudang');
      var namagudang = $(this).data('namagudang');


      $("#kode_gudang").val(kodegudang);
      $("#nama_gudang").val(namagudang);

    });

    $("button[name='tombol_pilihproject']").click(function () {
      var kodeproject = $(this).data('kodeproject');
      var namaproject = $(this).data('namaproject');


      $("#kode_project").val(kodeproject);
      $("#nama_project").val(namaproject);

    });



    $("#kode_obat").keypress(function (e) {
      var key = e.which;
      if (key == 13) {
        alert();
      }
    })

    $("#kode_cus").keypress(function (e) {
      var key = e.which;
      if (key == 13) {
        alert();
      }
    })

    $("#kode_gudang").keypress(function (e) {
      var key = e.which;
      if (key == 13) {
        alert();
      }
    })

    $("#kode_project").keypress(function (e) {
      var key = e.which;
      if (key == 13) {
        alert();
      }
    })

    $("#kode_sales").keypress(function (e) {
      var key = e.which;
      if (key == 13) {
        alert();
      }
    })


    $("#tambah_obat").click(function () {
      var kode = $("#kode_obat").val();
      var nama = $("#nm_obat").val();
      var pj_brg = $("#pj_barang").val();
      var lbr_brg = Number($("#lbr_barang").val());
      var lb_brg = Number($("#lb_barang").val());
      var qty = Number($("#qty").val()).toFixed(2);
      var hrg_sat = Number($("#hrg_sat").val()).toFixed(2);
      var prc_lst = $("#prc_lst").val();

      var subtotal = Number($("#toth_hrg").val());
      var tipe = '<?php echo $tipe; ?>';
      var diskon = document.getElementById('hidden_totaldiskon').value;
      var diskonpercent = document.getElementById('total_diskon').value;
      var series = $("#series").val();
      var uom = $("#uom").val();

      var uomserv = $("#uomserv").val();
      if ($('input[name=jenistrans][value=1]').is(":checked") == true) {
        jenis = "1";
      } else {
        jenis = "2";
      }

      kodebrg.push(kode);
      namabrg.push(nama);
      pjbrg.push(pj_brg);
      lbrbrg.push(lbr_brg);
      lbbrg.push(lb_brg);
      qtybrg.push(qty);
      hrgbrg.push(hrg_sat);
      totbrg.push(subtotal);
      prclst.push(prc_lst);

      if (jenis == "1") {
        if (kode == "") {
          document.getElementById("lihat_data_obat").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong masukkan kode barang terlebih dahulu',
            'warning'
          )
        } else if (qty == "" || qty <= 0) {
          document.getElementById("qty").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong isi quantity dengan benar',
            'warning'
          )
        } else if (hrg_sat == "" || hrg_sat <= 0) {
          document.getElementById("hrg_sat").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong isi harga satuan dengan benar',
            'warning'
          )


        } else {


          count = count + 1;
          if (tipe == 1) {
            var output = "";
            output = '<tr id="row_' + count + '">';
            output += '<td>' + kode + ' <input type="hidden" name="hidden_kdbrg[]" id="td_kd_obat' + count + '" class="td_kd_obat" value="' + kode + '"></td>';
            output += '<td>' + nama + ' <input type="hidden" name="hidden_nmbrg[]" id="td_nmobat' + count + '" class="td_nmobat" value="' + nama + '"></td>';
            output += '<td class="text-right">' + pj_brg + ' <input type="hidden" name="hidden_pjbrg[]" id="td_expobat' + count + '" class="td_expobat" value="' + pj_brg + '"></td>';
            output += '<td class="text-right">' + lbr_brg + ' <input type="hidden" name="hidden_lbrbrg[]" id="td_hrgobat' + count + '" class="td_hrgobat" value="' + lbr_brg + '"></td>';
            output += '<td class="text-right">' + lb_brg + ' <input type="hidden" name="hidden_lbbrg[]" id="td_hrgobat' + count + '" class="td_hrgobat" value="' + lb_brg + '"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_series[]" id="td_seriobat' + count + '" class="td_seriobat" value="0"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_uom[]" id="td_uomobat' + count + '" class="td_uomobat" value="0"></td>';
            output += '<td class="text-right">' + qty + ' <input type="hidden" name="hidden_qty[]" id="td_jmlobat' + count + '" class="td_jmlobat" value="' + qty + '"></td>';
            output += '<td class="text-right">' + hrg_sat + ' <input type="hidden" name="hidden_hrgsat[]" id="td_satobat' + count + '" class="td_satobat" value="' + hrg_sat + '"></td>';
            output += '<td class="text-right">' + subtotal + ' <input type="hidden" name="hidden_subtotal[]" id="td_subtotal' + count + '" class="td_subtotal" value="' + subtotal + '"></td>';

            output += '<td class="td-opsi"><button type="button" class="hapus_obat btn-transition btn btn-outline-danger btn-sm" name="hapus_obat" id="' + count + '" title="hapus item ini">hapus</button></td>';
            output += '</tr>';
          } else {
            var output = "";
            output = '<tr id="row_' + count + '">';
            output += '<td>' + kode + ' <input type="hidden" name="hidden_kdbrg[]" id="td_kd_obat' + count + '" class="td_kd_obat" value="' + kode + '"></td>';
            output += '<td>' + nama + ' <input type="hidden" name="hidden_nmbrg[]" id="td_nmobat' + count + '" class="td_nmobat" value="' + nama + '"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_pjbrg[]" id="td_expobat' + count + '" class="td_expobat" value="0"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_hrgobat[]" id="td_hrgobat' + count + '" class="td_hrgobat" value="0"></td>';
            output += '<td class="text-right">' + series + ' <input type="hidden" name="hidden_series[]" id="td_seriobat' + count + '" class="td_seriobat" value="' + series + '"></td>';
            output += '<td class="text-right">' + uom + ' <input type="hidden" name="hidden_uom[]" id="td_uomobat' + count + '" class="td_uomobat" value="' + uom + '"></td>';
            output += '<td class="text-right">' + qty + ' <input type="hidden" name="hidden_qty[]" id="td_jmlobat' + count + '" class="td_jmlobat" value="' + qty + '"></td>';
            output += '<td class="text-right">' + hrg_sat + ' <input type="hidden" name="hidden_hrgsat[]" id="td_satobat' + count + '" class="td_satobat" value="' + hrg_sat + '"></td>';
            output += '<td class="text-right">' + subtotal + ' <input type="hidden" name="hidden_subtotal[]" id="td_subtotal' + count + '" class="td_subtotal" value="' + subtotal + '"></td>';

            output += '<td class="td-opsi"><button type="button" class="hapus_obat btn-transition btn btn-outline-danger btn-sm" name="hapus_obat" id="' + count + '" title="hapus item ini">hapus</button></td>';
            output += '</tr>';
          }

          $("#keranjang_obat").append(output);
          $("#baris_kosong").hide();
          total_penjualan = Number(total_penjualan + subtotal);
          total_penjualanx = Number(total_penjualan - diskon);
          diskonpercent = Number((diskon / total_penjualan) * 100);

          $("#total_penjualan").text(total_penjualan);
          $("#hidden_totalpenjualan").val(total_penjualan);
          $("#hidden_totalpenjualanppn").val(total_penjualanx);
          $("#total_diskon").val(diskonpercent);

          $(".baris_total").show();
          reset();

        }
      } else {
        if (nama == "") {
          document.getElementById("nm_obat").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong masukkan nama barang terlebih dahulu',
            'warning'
          )
        } else if (qty == "" || qty <= 0) {
          document.getElementById("qty").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong isi quantity dengan benar',
            'warning'
          )
        } /*else if(subtotal=="" || subtotal<=0) {
              document.getElementById("toth_hrg").focus();
              Swal.fire(
                'Data Belum Lengkap',
                'maaf, tolong isi total harga dengan benar',
                'warning'
              )
              
        

        }*/ else {


          count = count + 1;
          if (tipe == 1) {
            var output = "";
            output = '<tr id="row_' + count + '">';
            output += '<td>' + kode + ' <input type="hidden" name="hidden_kdbrg[]" id="td_kd_obat' + count + '" class="td_kd_obat" value="' + kode + '"></td>';
            output += '<td>' + nama + ' <input type="hidden" name="hidden_nmbrg[]" id="td_nmobat' + count + '" class="td_nmobat" value="' + nama + '"></td>';
            output += '<td class="text-right">' + pj_brg + ' <input type="hidden" name="hidden_pjbrg[]" id="td_expobat' + count + '" class="td_expobat" value="' + pj_brg + '"></td>';
            output += '<td class="text-right">' + lbr_brg + ' <input type="hidden" name="hidden_lbrbrg[]" id="td_hrgobat' + count + '" class="td_hrgobat" value="' + lbr_brg + '"></td>';
            output += '<td class="text-right">' + lb_brg + ' <input type="hidden" name="hidden_lbbrg[]" id="td_hrgobat' + count + '" class="td_hrgobat" value="' + lb_brg + '"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_series[]" id="td_seriobat' + count + '" class="td_seriobat" value="0"></td>';
            output += '<td class="text-right">' + uomserv + '<input type="hidden" name="hidden_uom[]" id="td_uomobat' + count + '" class="td_uomobat" value="' + uomserv + '"></td>';
            output += '<td class="text-right">' + qty + ' <input type="hidden" name="hidden_qty[]" id="td_jmlobat' + count + '" class="td_jmlobat" value="' + qty + '"></td>';
            output += '<td class="text-right">' + subtotal + ' <input type="hidden" name="hidden_hrgsat[]" id="td_satobat' + count + '" class="td_satobat" value="' + subtotal + '"></td>';
            output += '<td class="text-right">' + subtotal + ' <input type="hidden" name="hidden_subtotal[]" id="td_subtotal' + count + '" class="td_subtotal" value="' + subtotal + '"></td>';

            output += '<td class="td-opsi"><button type="button" class="hapus_obat btn-transition btn btn-outline-danger btn-sm" name="hapus_obat" id="' + count + '" title="hapus item ini">hapus</button></td>';
            output += '</tr>';
          } else {
            var output = "";
            output = '<tr id="row_' + count + '">';
            output += '<td>' + kode + ' <input type="hidden" name="hidden_kdbrg[]" id="td_kd_obat' + count + '" class="td_kd_obat" value="' + kode + '"></td>';
            output += '<td>' + nama + ' <input type="hidden" name="hidden_nmbrg[]" id="td_nmobat' + count + '" class="td_nmobat" value="' + nama + '"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_pjbrg[]" id="td_expobat' + count + '" class="td_expobat" value="0"></td>';
            output += '<td class="text-right"><input type="hidden" name="hidden_hrgobat[]" id="td_hrgobat' + count + '" class="td_hrgobat" value="0"></td>';
            output += '<td class="text-right">' + series + ' <input type="hidden" name="hidden_series[]" id="td_seriobat' + count + '" class="td_seriobat" value="' + series + '"></td>';
            output += '<td class="text-right">' + uom + ' <input type="hidden" name="hidden_uom[]" id="td_uomobat' + count + '" class="td_uomobat" value="' + uom + '"></td>';
            output += '<td class="text-right">' + qty + ' <input type="hidden" name="hidden_qty[]" id="td_jmlobat' + count + '" class="td_jmlobat" value="' + qty + '"></td>';
            output += '<td class="text-right">' + subtotal + ' <input type="hidden" name="hidden_hrgsat[]" id="td_satobat' + count + '" class="td_satobat" value="' + subtotal + '"></td>';
            output += '<td class="text-right">' + subtotal + ' <input type="hidden" name="hidden_subtotal[]" id="td_subtotal' + count + '" class="td_subtotal" value="' + subtotal + '"></td>';

            output += '<td class="td-opsi"><button type="button" class="hapus_obat btn-transition btn btn-outline-danger btn-sm" name="hapus_obat" id="' + count + '" title="hapus item ini">hapus</button></td>';
            output += '</tr>';
          }

          $("#keranjang_obat").append(output);
          $("#baris_kosong").hide();
          total_penjualan = Number(total_penjualan + subtotal);
          total_penjualanx = Number(total_penjualan - diskon);
          diskonpercent = Number((diskon / total_penjualan) * 100);

          $("#total_penjualan").text(total_penjualan);
          $("#hidden_totalpenjualan").val(total_penjualan);
          $("#hidden_totalpenjualanppn").val(total_penjualanx);
          $("#total_diskon").val(diskonpercent);

          $(".baris_total").show();
          reset();

        }
      }
    });



    $(document).on("click", ".hapus_obat", function () {
      var row_id = $(this).attr("id");
      var subtotal = Number($("#td_subtotal" + row_id).val());
      var diskon = document.getElementById('hidden_totaldiskon').value;

      total_penjualan = Number(total_penjualan - subtotal);
      total_penjualanx = Number(total_penjualan - diskon);
      diskonpercent = Number((diskon / total_penjualan) * 100);
      $("#total_penjualan").text(total_penjualan);
      $("#hidden_totalpenjualan").val(total_penjualan);
      $("#hidden_totalpenjualanppn").val(total_penjualanx);
      $("#total_diskon").val(diskonpercent);

      $("#row_" + row_id).remove();
      if (total_penjualan == 0) {
        $("#baris_kosong").show();
        $(".baris_total").hide();
        $("#tambah_obat_lagi").click();
      }
    });

    $("#hapus_semua_obat").click(function () {
      Swal.fire({
        title: 'Hapus Semua ?',
        text: 'apakah anda yakin menghapus semua daftar barang',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
      }).then((hapus) => {
        if (hapus.value) {
          $("#keranjang_obat > tr").remove();
          total_penjualan = 0;
          total_penjualanx = 0;
          diskonpercent = "";

          $("#hidden_totalpenjualan").val("0");
          $("#hidden_totalpenjualanppn").val("0");
          $("#total_diskon").val("0");
          $("#hidden_totaldiskon").val("0");
          $("#total_pembayaran").text(total_penjualan);
          $("#baris_kosong").show();
          $(".baris_total").hide();
          $("#tambah_obat_lagi").click();
        }
      })
    });



    $("#form_penjualan").on("submit", function (event) {
      event.preventDefault();

      var trans = $("#trans").val();
      var jenistrans = $("#jenistrans").val();
      var tglsales = $("#tglsales").val();
      var tgldelivery = $("#tgldelivery").val();
      var kode_cus = $("#kode_cus").val();
      var nama_cus = $("#nama_cus").val();
      var cus_ref = $("#cus_ref").val();
      var alamat = $("#alamat").val();
      var kode_gudang = $("#kode_gudang").val();
      var kode_project = $("#kode_project").val();
      var remark = $("#remark").val();
      var idpeg = '<?php echo $idpeg; ?>';
      var total_diskon = document.getElementById('total_diskon').value;
      var kontak = $("#kontak").val();
      var cus_curr = $("#cus_curr").val();
      var cus_rate = $("#cus_rate").val();

      if (trans == "") {
        document.getElementById("trans").focus();
        Swal.fire(
          'Data Belum Lengkap',
          'maaf, tolong pilih tipe transaksi (Quotation/Orders)',
          'warning'
        )
      } else
        if (tgldelivery == "") {
          document.getElementById("tgldelivery").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong isi tanggal valid until / delivery',
            'warning'
          )
        } else if (kode_cus == "") {
          document.getElementById("kode_cus").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong isi nama customer',
            'warning'
          )
        } else if (alamat == "") {
          document.getElementById("alamat").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum mengisi alamat kirim',
            'warning'
          )
        } else if (kode_gudang == "") {
          document.getElementById("kode_gudang").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum mengisi gudang',
            'warning'
          )
        } else if (kode_project == "") {
          document.getElementById("kode_project").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum mengisi project',
            'warning'
          )
        } else if (cus_rate == "") {
          document.getElementById("cus_rate").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum mengisi rate currency',
            'warning'
          )
        }


        else {
          Swal.fire({
            title: 'Simpan ?',
            text: 'apakah anda telah mengisi data penjualan dengan benar ',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
          }).then((simpan) => {
            $(this).find(':input[type=submit]').attr('disabled', 'disabled');
            if (simpan.value) {
              var count_data = 0;

              $(".td_kd_obat").each(function () {
                count_data = count_data + 1;
              });
              if (count_data > 0) {
                var form_data = $(this).serialize();
                $.ajax({
                  url: "ajax/simpan_penjualan.php",
                  method: "POST",
                  data: form_data,
                  success: function (response) {
                    // Log the raw response to the console
                    console.log("Raw response:", response);

                    // Parse the response if it's not already an object
                    let data;
                    try {
                      data = typeof response === 'object' ? response : JSON.parse(response);
                    } catch (e) {
                      console.error("Failed to parse JSON response:", response);
                      Swal.fire({
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                      });
                      return;
                    }

                    // Log the parsed data to the console
                    console.log("Parsed data:", data);

                    if (data.error) {
                      Swal.fire({
                        title: 'Error',
                        text: data.error, // Display the error message
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                      }).then((ok) => {
                        document.getElementById("simpan_penjualan").removeAttribute("disabled");
                      });
                    } else if (data.result) {
                      Swal.fire({
                        title: 'Berhasil',
                        text: 'Data Items Berhasil Disimpan',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                      }).then((ok) => {
                        document.getElementById("simpan_penjualan").removeAttribute("disabled");
                        if (ok.value) {

                          window.open("laporan/?page=nota_penjualan&no_pjl=" + no_penjualan);
                          location.reload();
                        }
                      });
                    }
                  }
                })
              } else {
                alert();
              }
            }
          })
        }
    });
  });
</script>
<!-- success: function (data) {
Swal.fire({
title: 'Berhasil',
text: 'Data Berhasil Disimpan',
type: 'success',
confirmButtonColor: '#3085d6',
confirmButtonText: 'OK'
}).then((ok) => {
document.getElementById("simpan_penjualan").removeAttribute("disabled");
if (ok.value) {

window.open("laporan/?page=nota_penjualan&no_pjl=" + no_penjualan);
location.reload();
}
})
} -->