<?php

$tipe = $_SESSION['session_id'];
$tipesales = $_SESSION['session_tipe'];
$idpeg = $_SESSION['id_peg'];
$nmpeg = $_SESSION['nama_peg'];

?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-align-left"></i> Form Master Items</li>
  </ol>
</nav>

<div class="page-content">
  <div class="row">
    <div class="col-6">
      <h4>Master Items</h4>
    </div>
    <div class="col-6 text-right">
      <!--<a href="?page=datapenjualan">
        <button class="btn btn-sm btn-info">Data Transaksi Penjualan</button>
      </a>-->
    </div>
  </div>
  <div class="form-container">
    <div class="row" style="padding: 0 12px;">
      <div class="col-md-12 vertical-form">
        <div class="position-relative form-group" style="text-align: left; ">
          <table width="100%" style="font-family:arial,tahoma; font-size: 14px;">
            <tr>
              <td width="40%" valign="top">Kode Items </td>
              <td width="2%" valign="top"></td>
              <td>
                <form method="POST" action="?page=entry_items&cek=y" id="form_cek">
                  <input class="form-control form-control-sm" type="text" name="kode_brg" id="kode_brg" size="50"
                    onkeyup="mykode()">
                  <input type="hidden" class="form-control form-control-sm" id="kode" name="kode"> <br>
                  <button class="btn btn-warning" title="Cek Kode ke All Database">Check Code</button>
                </form>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                <h6>
              </td>
            </tr>

            <script>
              function mykode() {
                var x = document.getElementById("kode_brg").value;
                document.getElementById("kode").value = x;
              }

            </script>

            <?php
            $cek = $_GET['cek'];

            if ($cek == 'y') {
              $kode = $_POST['kode'];

              $db_all = array();
              $db_allline = array();
              $alert = array();

              $query_tampil = "SELECT * FROM tbl_db ORDER BY id_db ASC";
              $sql_tampil = mysqli_query($conn, $query_tampil) or die($conn->error);
              while ($data = mysqli_fetch_array($sql_tampil)) {
                $db_allline['nodb'] = $data['id_db'];
                $db_allline['namadb'] = $data['nama_db'];
                $db_allline['userdb'] = $data['user_db'];
                $db_allline['passdb'] = $data['pass_db'];
                $db = $data['nama_db'];
                $user = $data['user_db'];
                $pass = $data['pass_db'];

                $curl = curl_init();
                $curl2 = curl_init();
                $curl3 = curl_init();

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


                //Load Barang
                $endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
                $var1 = array('$filter' => 'ItemCode eq \'' . $kode . '\' and Valid eq \'tYES\'', '$orderby' => 'ItemsGroupCode,ItemCode asc');
                $url2 = $endurl . '?' . http_build_query($var1);
                curl_setopt_array($curl2, [
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
                    "Prefer:odata.maxpagesize=50000",
                    "Content-Type: application/json"
                  ]
                ]);

                $response2 = curl_exec($curl2);
                $err2 = curl_error($curl2);

                curl_close($curl2);

                $itemcode = "";
                $itemname = "";

                if ($err2) {
                  echo "cURL Error #:" . $err2;
                } else {

                  $data2x = json_decode($response2, true);
                  $data21 = json_decode($response2);


                  if (count($data2x['value']) > 0) {
                    $itemcode = $data21->value[0]->ItemCode;
                    $itemname = $data21->value[0]->ItemName;
                  }

                }
                if ($itemcode != "") {
                  array_push($alert, $db_allline['namadb'] . " - Ready ");
                } else {
                  array_push($alert, $db_allline['namadb'] . " - Belum ");
                }
                $data2 = json_encode($db_all, true);
                $info2 = json_encode($alert, true);
              }
              ?>
              <tr>
                <td width="40%">Hasil Cek </td>
                <td width="2%"></td>
                <td> <b><textarea rows="5" class="form-control form-control-sm" name="cek" id="cek"
                      readonly><?php echo " Kode Items : " . $kode . " " . $info2; ?></textarea></b> </td>
              </tr>
            <?php } ?>
          </table>
        </div>


        <form method="post" id="form_items" autocomplete="off">
          <div class="position-relative form-group" style="text-align: left; ">
            <table width="100%" border="0" style="font-family:arial,tahoma; font-size: 14px;">
              <tr>
                <td width="40%">Tipe Items</td>
                <td width="2%"></td>
                <td>
                  <input type="radio" id="jenistrans" name="jenistrans" value="101" checked>
                  <label for="jadi">Bahan Jadi</label>
                  <input type="radio" id="jenistrans" name="jenistrans" value="100">
                  <label for="baku">Bahan Baku</label>
                  <input type="radio" id="jenistrans" name="jenistrans" value="102">
                  <label for="pembantu">Bahan Pembantu</label>
                </td>
              </tr>
              <tr>
                <td width="40%">Kode Items </td>
                <td width="2%" valign="top"> </td>
                <td>
                  <input class="form-control form-control-sm" type="text" name="kode_brg2" id="kode_brg2" size="50"
                    onkeyup="mykode2()"><br>
                </td>
              </tr>

              <script>
                function mykode2() {
                  var jn = document.getElementById("jenistrans").value;
                  var x = document.getElementById("kode_brg2").value;
                  var g = x.substring(9, 10);
                  var b = x.substring(13, 14);
                  var w = x.substring(15, 17);
                  var p = x.substring(1, 3);
                  var fl = x.substring(5, 8);
                  var ncpPrefix = x.substring(0, 3);  // Get the first three characters of the kode_brg2
                  var kd = x.substring(0, 2);
                  var tebalCode = x.substring(10, 12);
                  var plt = x.substring(0, 1);


                  if (jn == "101") {
                    if (g == "3") {
                      document.getElementById("grade").value = "300";
                    } else {
                      document.getElementById("grade").value = "550";
                    }

                    if (b == "0") {
                      document.getElementById("Batik").removeAttribute("checked");
                    } else {
                      document.getElementById("Batik").setAttribute("checked", "checked");
                    }

                    if (w == "00") {
                      document.getElementById("Warna").removeAttribute("checked");
                    } else {
                      document.getElementById("Warna").setAttribute("checked", "checked");
                    }

                    // Mengatur status checkbox FlashingCustom dan Flashing
                    if (fl == "L20" || fl == "L30" || fl == "L40" || fl == "L50" || fl == "L60" || fl == "L90" || fl == "L12") {

                      document.getElementById("FlashingCustom").setAttribute("checked", "checked");
                      document.getElementById("Flashing").setAttribute("checked", "checked");
                    } else if (p == "FL") {

                      document.getElementById("Flashing").setAttribute("checked", "checked");
                      // Make sure FlashingCustom is unchecked if not matched
                      document.getElementById("FlashingCustom").removeAttribute("checked");
                    } else {

                      document.getElementById("FlashingCustom").removeAttribute("checked");
                      document.getElementById("Flashing").removeAttribute("checked");
                    }

                    if (fl == "R4C" && p == "FL") {
                      document.getElementById("Flashing450").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("Flashing450").removeAttribute("checked");
                    }

                    if (fl == "R3A" && p == "FL") {
                      document.getElementById("Flashing300").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("Flashing300").removeAttribute("checked");
                    }

                    if (fl == "R6A" && p == "FL") {
                      document.getElementById("Flashing600").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("Flashing600").removeAttribute("checked");
                    }

                    if (fl == "R6A" && plt == "O") {
                      document.getElementById("PlatRoll600").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("PlatRoll600").removeAttribute("checked");
                    }

                    if (fl == "R3A" && plt == "O") {
                      document.getElementById("PlatRoll300").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("PlatRoll300").removeAttribute("checked");
                    }

                    if (fl == "R4C" && plt == "O") {
                      document.getElementById("PlatRoll450").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("PlatRoll450").removeAttribute("checked");
                    }

                    // New code to check the NokCrimping checkbox if kode_brg2 starts with "NCP"
                    if (ncpPrefix === "NCP") {
                      document.getElementById("NokCrimping").setAttribute("checked", "checked");
                    } else if (p == "CL" || p == "RK" || p == "CP") {
                      document.getElementById("Crimping").setAttribute("checked", "checked");
                    } else {
                      document.getElementById("Crimping").removeAttribute("checked");
                      document.getElementById("NokCrimping").removeAttribute("checked");
                    }

                  }
                  if (jn == "101") {
                    // Mengatur nilai radio berdasarkan nilai `b`
                    if (kd == "BB") {
                      document.querySelector('input[name="jenistrans"][value="100"]').checked = true;
                      document.getElementById("sales").removeAttribute("checked");
                      document.getElementById("Batik").removeAttribute("checked");
                    } else {
                      document.querySelector('input[name="jenistrans"][value="none"]').checked = true;
                      document.getElementById("Batik").setAttribute("checked", "checked");
                    }
                  }
                }
              </script>

              <tr>
                <td width="40%">Nama Items</td>
                <td width="2%"></td>
                <td>
                  <b>
                    <textarea class="form-control form-control-sm" name="nama_brg" id="nama_brg"
                      oninput="autoSelectKodeAZ()"></textarea>
                  </b>
                </td>
              </tr>

              <tr>
                <td width="40%">Jenis Items</td>
                <td width="2%"></td>
                <td>
                  <input type="checkbox" id="inventory" name="inventory" value="Inventory" checked>
                  <label for="inventory"> Inventory</label>
                  <input type="checkbox" id="sales" name="sales" value="Sales" checked>
                  <label for="sales"> Sales</label>
                  <input type="checkbox" id="purchasing" name="purchasing" value="Purchasing" checked>
                  <label for="purchasing"> Purchasing</label>
                </td>
              </tr>

              <tr>
                <td width="40%" valign="top">Properties Items</td>
                <td width="2%"></td>
                <td>
                  <input type="checkbox" id="Batik" name="Batik" value="Batik">
                  <label for="Batik"> Batik</label>
                  <input type="checkbox" id="Warna" name="Warna" value="Warna">
                  <label for="Warna"> Warna</label>
                  <input type="checkbox" id="Radial" name="Radial" value="Radial">
                  <label for="Radial"> Radial</label>
                  <input type="checkbox" id="Crimping" name="Crimping" value="Crimping">
                  <label for="Crimping"> Crimping</label>
                  <input type="checkbox" id="NokCrimping" name="NokCrimping" value="Nok Crimping">
                  <label for="Nok Crimping"> Nok Crimping</label>
                  <input type="checkbox" id="UpCloser" name="UpCloser" value="Up Closer">
                  <label for="Up Closer"> Up Closer</label><br>
                  <input type="checkbox" id="EndCloser" name="EndCloser" value="End Closer">
                  <label for="End Closer"> End Closer</label>
                  <input type="checkbox" id="EndStopper" name="EndStopper" value="End Stopper">
                  <label for="End Stopper"> End Stopper</label>
                  <input type="checkbox" id="PU" name="PU" value="PU">
                  <label for="PU"> PU</label>
                  <input type="checkbox" id="PE" name="PE" value="PE">
                  <label for="PE"> PE</label>
                  <input type="checkbox" id="Flashing" name="Flashing" value="Flashing">
                  <label for="Flashing"> Flashing</label>
                  <input type="checkbox" id="Flashing300" name="Flashing300" value="Flashing 300">
                  <label for="Flashing 300"> Flashing 300</label><br>
                  <input type="checkbox" id="Flashing450" name="Flashing450" value="Flashing 450">
                  <label for="Flashing 450"> Flashing 450</label>
                  <input type="checkbox" id="Flashing600" name="Flashing600" value="Flashing 600">
                  <label for="Flashing 600"> Flashing 600</label>
                  <input type="checkbox" id="PlatRoll300" name="PlatRoll300" value="Plat Roll 300">
                  <label for="Plat Roll 300"> Plat Roll 300</label>
                  <input type="checkbox" id="PlatRoll450" name="PlatRoll450" value="Plat Roll 450">
                  <label for="Plat Roll 450"> Plat Roll 450</label>
                  <input type="checkbox" id="PlatRoll600" name="PlatRoll600" value="Plat Roll 600">
                  <label for="Plat Roll 600"> Plat Roll 600</label> <br>
                  <input type="checkbox" id="FlashingCustom" name="FlashingCustom" value="Flashing Custom">
                  <label for="FlashingCustom"> Flashing Custom</label>
                </td>
              </tr>
              <tr>
                <td width="40%">Tipe AZ/Z/A</td>
                <td width="2%"></td>
                <td>
                  <select name="kodeaz" id="kodeaz" onchange="autoSelectTipe()">
                    <option value="" selected>- Pilih AZ/Z/A -</option>
                    <option value="AZ070">AZ070</option>
                    <option value="AZ100">AZ100</option>
                    <option value="AZ150">AZ150</option>
                    <option value="AZ200">AZ200</option>
                    <option value="Z0070">Z0070</option>
                    <option value="Z0100">Z0100</option>
                    <option value="Z0150">Z0150</option>
                    <option value="Z0200">Z0200</option>
                    <option value="Z0220">Z0220</option>
                    <option value="Z0275">Z0275</option>
                    <option value="AZ000">AZ000</option>
                    <option value="Z0000">Z0000</option>
                    <option value="SS430">SS430</option>
                  </select><br><br>
                </td>
              </tr>

              <!-- JavaScript to Auto-Select Kode AZ/Z/A and Auto-Populate and Select Tipe -->
              <script>
                function autoSelectKodeAZ() {
                  const namaItems = document.getElementById('nama_brg').value.trim();
                  const kodeazDropdown = document.getElementById('kodeaz');

                  // Default option if no match is found
                  kodeazDropdown.value = '';

                  // List of valid AZ codes
                  const validKodeAZ = ["AZ070", "AZ100", "AZ150", "AZ200", "Z0070", "Z0100", "Z0150", "Z0200", "Z0220", "Z0275", "AZ000", "Z0000", "SS430"];

                  // Regular expression to find AZ code in the string
                  const azMatch = namaItems.match(/\b(AZ\d{3}|Z\d{4}|SS\d{3})\b/i);

                  if (azMatch && validKodeAZ.includes(azMatch[0].toUpperCase())) {
                    kodeazDropdown.value = azMatch[0].toUpperCase();
                  } else {
                    kodeazDropdown.value = 'AZ000';
                  }
                  // Call to update the second dropdown based on kodeaz value
                  autoSelectTipe();
                }

                document.getElementById('nama_brg').addEventListener('input', function () {
                  var namaBarang = this.value;

                  // Find the position of the first occurrence of "MM"
                  var mmIndex = namaBarang.indexOf("MM");

                  // Check if "MM" is found and is at least three characters away from the start
                  if (mmIndex !== -1 && mmIndex >= 3) {
                    // Extract the four characters before "MM"
                    var tebalCode = namaBarang.substring(mmIndex - 4, mmIndex).trim();

                    // Set the extracted value to the 'tebal' field
                    document.getElementById("tebal").value = tebalCode;
                  } else {
                    // Set default value if "MM" is not found or is too close to the start
                    document.getElementById("tebal").value = "0.00";
                  }
                });

                document.getElementById('nama_brg').addEventListener('input', function () {
                  var namaBarang = this.value;

                  // Find the position of the first occurrence of "RADIAL"
                  var radialIndex = namaBarang.indexOf("RADIAL");

                  // Check if "RADIAL" is found and is at least three characters away from the start
                  if (radialIndex !== -1 && radialIndex >= 3) {
                    document.getElementById("Radial").setAttribute("checked", "checked")
                  } else {
                    // Set default value if "RADIAL" is not found or is too close to the start
                    document.getElementById("Radial").removeAttribute("checked")
                  }
                });

                document.getElementById('nama_brg').addEventListener('input', function () {
                  var namaBarang = this.value;
                  var jenistrans = document.querySelector('input[name="jenistrans"]:checked').value;

                  console.log("Full Name: ", namaBarang);
                  console.log("Selected JenisTrans: ", jenistrans);

                  // Use a regular expression to find "G" followed by 3 digits
                  var regex = /G(\d{3})/;
                  var match = namaBarang.match(regex);

                  // Check if the regex found a match and the transaction type is "100"
                  if (match && jenistrans == "100") {
                    var gCode = match[1]; // Extract the 3 digits after "G"
                    console.log("Extracted Code: ", gCode);

                    // Set the extracted value to the 'grade' field
                    document.getElementById("grade").value = gCode;
                    console.log("Grade Set To: ", gCode);
                  } else if (jenistrans == "100"){
                    // Set default value if no match is found or conditions not met
                    document.getElementById("grade").value = "0";
                    console.log("Grade Set To Default: 0 (Conditions not met or not a match)");
                  }
                });

              </script>
              <tr>
                <td width="40%">Database </td>
                <td width="2%"></td>
                <td> <select name="kodedb" id="kodedb">
                    <option value="0" selected>- ALL DATABASE -</option>
                    <?php
                    $query_db = "SELECT * FROM tbl_db ORDER BY id_db ASC";
                    $sql_db = mysqli_query($conn, $query_db) or die($conn->error);
                    while ($datadb = mysqli_fetch_array($sql_db)) {
                      $selected = ($datadb['nama_db'] == 'NEW_UDMW_LIVE') ? 'selected' : '';
                      echo "<option value='$datadb[nama_db]' $selected>$datadb[nama_db]</option>";

                    }
                    ?>

                    <option value="UD_SIMULASI">UD_SIMULASI</option>
                    <option value="UD2_SIMULASI">UD2_SIMULASI</option>
                    <option value="SIMULASI_NEW_UD">SIMULASI_NEW_UD</option>
                  </select><br><br>
                </td>
              </tr>

              <tr>
                <td width="40%">Grade Items</td>
                <td width="2%"></td>
                <td>
                  <input class="form-control form-control-sm" type="text" id="grade" name="grade" value=""><br>
                </td>
              </tr>
              <tr>
                <td width="40%">Tebal Items </td>
                <td width="2%"></td>
                <td> <b><input class="form-control form-control-sm" type="text" name="tebal" id="tebal" value=""
                      step="0.01"> <br></b> </td>
              </tr>
              <tr>
                <td width="40%">Nama Bahan Baku </td>
                <td width="2%"></td>
                <td> <b><textarea class="form-control form-control-sm" name="nama_bahan"
                      id="nama_bahan"></textarea></b><br>
                </td>
              </tr>
              <tr>
                <td width="40%">Profil Items </td>
                <td width="2%"></td>
                <td> <b>
                    <input type="hidden" class="form-control form-control-sm" name="kode_prof" id="kode_prof" readonly>
                    <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_dataprofil"
                      rows="2" name="nama_profil" id="nama_profil" readonly></textarea></b> </td>
              </tr>
            </table>
          </div>

          <div class="text-right tombol-kanan">
            <input type="submit" name="simpan_items" id="simpan_items" class="btn btn-info" value="Simpan">
          </div>
        </form>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addBomModal">Add BOM</button>
      </div>
    </div>
  </div>
</div>


<?php
if (isset($_POST['simpan_items'])) {
  echo "<br>Data yang dipilih:<br>";
  echo $_POST['Batik'] . "<br>";
  echo $_POST['Warna'] . "<br>";
  echo $_POST['Radial'] . "<br>";
  echo $_POST['Crimping'] . "<br>";
  echo $_POST['NokCrimping'] . "<br>";
  echo $_POST['UpCloser'] . "<br>";
  echo $_POST['EndCloser'] . "<br>";
  echo $_POST['EndStopper'] . "<br>";
  echo $_POST['PU'] . "<br>";
  echo $_POST['PE'] . "<br>";
  echo $_POST['Flashing'] . "<br>";
  echo $_POST['Flashing300'] . "<br>";
  echo $_POST['Flashing450'] . "<br>";
  echo $_POST['Flashing600'] . "<br>";
  echo $_POST['PlatRoll300'] . "<br>";
  echo $_POST['PlatRoll450'] . "<br>";
  echo $_POST['PlatRoll600'] . "<br>";
  echo $_POST['FLashingCustom'] . "<br>";
}
?>


<!-- Modal Profile -->
<div class="modal fade" id="modal_dataprofil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-striped display tabel-data">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama Profile</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $curl1x = curl_init();
            $curl2x = curl_init();

            $db1 = "NEW_UDMW_LIVE";
            $pass1 = "utomo123";
            $user1 = "manager";

            curl_setopt_array($curl1x, [
              CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Login",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $db1 . '",
                    "Password": "' . $pass1 . '",
                    "UserName": "' . $user1 . '"}',
              CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
              ],
            ]);

            $response1x = curl_exec($curl1x);
            $err1x = curl_error($curl1x);

            curl_close($curl1x);
            $token1x = "";

            if ($err1x) {
              echo "cURL Error #:" . $err1x;
            } else {
              $data21x = json_decode($response1x);
              $token1x = $data21x->SessionId;
            }

            curl_setopt_array($curl2x, [
              CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/U_HR_GRP_PROFILE",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 60,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_POSTFIELDS => json_encode([
                'CompanyDB' => $db1,
                'Password' => $pass1,
                'UserName' => $user1
              ]),
              CURLOPT_HTTPHEADER => [
                "Cookie:B1SESSION=" . $token1x . "; ROUTEID=.node1",
                "Prefer:odata.maxpagesize=1000",
                "Content-Type: application/json"
              ]
            ]);

            $response2x = curl_exec($curl2x);
            $err2x = curl_error($curl2x);
            curl_close($curl2x);
            $kodeprof = "";
            $nmprof = "";
            if ($err2x) {
              echo "cURL Error #:" . $err2x;
            } else {
              $data2xx = json_decode($response2x, true);
              $data2x1 = json_decode($response2x);
            }

            for ($i = 0; $i < count($data2xx['value']); $i++) {
              $code = $data2x1->value[$i]->Code;
              $nmprofil = $data2x1->value[$i]->Name;
              ?>
              <tr>
                <td><?php echo $code; ?></td>
                <td><?php echo $nmprofil; ?></td>
                <td class="td-opsi">
                  <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihprofil"
                    name="tombol_pilihprofil" data-dismiss="modal" data-kodeprofil="<?php echo $code; ?>"
                    data-namaprofil="<?php echo $nmprofil; ?>"> <b>pilih</b>
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

<!-- Modal bom -->
<div class="modal fade" id="addBomModal" tabindex="-1" role="dialog" aria-labelledby="addBomModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBomModalLabel">Add New BOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addBomForm">
          <div class="form-group">
            <label for="treeCode">Kode Bahan Jadi</label>
            <input type="text" class="form-control" id="treeCode" name="treeCode" required>
          </div>
          <div class="form-group">
            <label for="warehouse">Warehouse</label>
            <input type="text" class="form-control" id="warehouse" name="warehouse" required>
          </div>
          <h5>BOM Lines</h5>
          <div id="bomLinesContainer">
            <!-- BOM lines will be added here -->
          </div>
          <button type="button" class="btn btn-secondary" id="addBomLine">Add BOM Line</button>
          <button type="submit" class="btn btn-primary">Save BOM</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- modal message -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="messageText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<script>
  $(document).ready(function () {
    var count = 0;
    $('.datepicker').datepicker({
      format: "yyyy-mm-dd",
      orientation: "bottom left",
      todayBtn: "linked",
      autoclose: true,
      language: "id",
      todayHighlight: true
    });

    $("button[name='tombol_pilihprofil']").click(function () {
      var kode = $(this).data('kodeprofil');
      var nama = $(this).data('namaprofil');
      $("#kode_prof").val(kode);
      $("#nama_profil").val(nama);
    });

    $('#addBomLine').on('click', function () {
      var lineHtml = `
            <div class="form-group">
                <label for="itemCode">Kode Bahan Baku</label>
                <input type="text" class="form-control" name="itemCode[]" required>
            </div>
            <div class="form-group">
                <label for="lineQuantity">Konversi BOM</label>
                <input type="number" class="form-control" name="lineQuantity[]" step="any" required>
            </div>
            <div class="form-group">
                <label for="lineWarehouse">Warehouse</label>
                <input type="text" class="form-control" name="lineWarehouse[]" required>
            </div>
        `;
      $('#bomLinesContainer').append(lineHtml);
    });

    // Handle form submission
    $('#addBomForm').on('submit', function (event) {
      event.preventDefault();

      $.ajax({
        type: 'POST',
        url: 'ajax/save_bom.php', // URL of your PHP script to handle form submission
        data: $(this).serialize(),
        success: function (response) {
          $('#messageText').text(response);
          $('#messageModal').modal('show');

          if (response.toLowerCase().includes('error')) {
            $('#messageModalLabel').text('Error');
          } else {
            $('#messageModalLabel').text('Success');
            $('#addBomModal').modal('hide');
            location.reload(); // Reload the page to reflect changes
          }
        },
        error: function () {
          $('#messageText').text('An unexpected error occurred.');
          $('#messageModalLabel').text('Error');
          $('#messageModal').modal('show');
        }
      });
    });

    $("#form_items").on("submit", function (event) {
      event.preventDefault();

      var jenistrans = $("#jenistrans").val();
      var kodebrg = $("#kode_brg2").val();
      var namabrg = $("#nama_brg").val();
      var inventory = $("#inventory").val();
      var sales = $("#sales").val();
      var purchasing = $("#purchasing").val();
      var Batik = $("#Batik").val();
      var Warna = $("#Warna").val();
      var Radial = $("#Radial").val();
      var Crimping = $("#Crimping").val();
      var NokCrimping = $("#NokCrimping").val();
      var UpCloser = $("#UpCloser").val();
      var EndCloser = $("#EndCloser").val();
      var EndStopper = $("#EndStopper").val();
      var pu = $("#PU").val();
      var pe = $("#PE").val();
      var Flashing = $("#Flashing").val();
      var Flashing300 = $("#Flashing300").val();
      var Flashing450 = $("#Flashing450").val();
      var Flashing600 = $("#Flashing600").val();
      var PlatRoll300 = $("#PlatRoll300").val();
      var PlatRoll450 = $("#PlatRoll450").val();
      var PlatRoll600 = $("#PlatRoll600").val();
      var FlashingCustom = $("#FlashingCustom").val();
      var kodeaz = $("#kodeaz").val();
      var grade = $("#grade").val();
      var tebal = $("#tebal").val();
      var namabahan = $("#nama_bahan").val();
      var kode_prof = $("#kode_prof").val();
      var kodedb = $("#kodedb").val();
      var itemProperty = $('#item_property').val();

      if (kodebrg == "") {
        document.getElementById("kode_brg2").focus();
        Swal.fire(
          'Data Belum Lengkap',
          'maaf, tolong isi kode items',
          'warning'
        )
      } else
        if (namabrg == "") {
          document.getElementById("nama_brg").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong isi nama items',
            'warning'
          )
        } else if (kodeaz == "") {
          document.getElementById("kodeaz").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, tolong pilih tipe AZ',
            'warning'
          )
        } else if (tebal == "") {
          document.getElementById("tebal").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum mengisi tebal items',
            'warning'
          )
        } else if (namabahan == "") {
          document.getElementById("nama_bahan").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum mengisi nama bahan baku',
            'warning'
          )
        } else if (kode_prof == "") {
          document.getElementById("kode_prof").focus();
          Swal.fire(
            'Data Belum Lengkap',
            'maaf, anda belum memilih profil items',
            'warning'
          )
        }
        else {
          Swal.fire({
            title: 'Simpan ?',
            text: 'apakah anda telah mengisi data items dengan benar ',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
          }).then((simpan) => {
            if (simpan.value) {
              $(this).find(':input[type=submit]').attr('disabled', 'disabled');
              var count_data = 0;
              var form_data = $(this).serialize();
              $.ajax({
                url: "ajax/simpan_items.php",
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
                    }).then((ok) => {
                      document.getElementById("simpan_items").removeAttribute("disabled");
                      return;
                    });
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
                      document.getElementById("simpan_items").removeAttribute("disabled");
                    });
                  } else if (data.result) {
                    Swal.fire({
                      title: 'Berhasil',
                      text: 'Data Items Berhasil Disimpan',
                      icon: 'success',
                      confirmButtonColor: '#3085d6',
                      confirmButtonText: 'OK'
                    }).then((ok) => {
                      document.getElementById("simpan_items").removeAttribute("disabled");
                    });
                  }
                }
              });
            } else {
              document.getElementById("simpan_items").removeAttribute("disabled");
            }

          })
        }
    });
  });
</script>