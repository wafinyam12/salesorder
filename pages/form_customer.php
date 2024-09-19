<?php
$db = $_SESSION['posisi_peg'];
$user = $_SESSION['session_user'];
$pass = $_SESSION['session_pass'];

$curl = curl_init();
$curl2 = curl_init();
$curl3 = curl_init();
$curl4 = curl_init();
$curl5 = curl_init();

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

$endpofloat5 = 'https://172.16.226.2:50000/b1s/v1/SalesPersons';
$params4 = array('$select' => 'SalesEmployeeCode,SalesEmployeeName');
$url5 = $endpofloat5 . '?' . http_build_query($params4);
curl_setopt_array($curl2, [
    CURLOPT_URL => $url5,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => [
        "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
        "Prefer:odata.maxpagesize=50000",
        "Content-Type: application/json"
    ]
]);

$response6 = curl_exec($curl2);
$err6 = curl_error($curl2);

curl_close($curl2);

$slpcode = "";
$slpname = "";

if ($err6) {
    echo "cURL Error #:" . $err6;
} else {

    $data6x = json_decode($response6, true);
    $data61 = json_decode($response6);

}

$endpofloat6 = 'https://172.16.226.2:50000/b1s/v1/BusinessPartnerGroups';
$params5 = array('$select' => 'Code, Name');
$url6 = $endpofloat6 . '?' . http_build_query($params5);
curl_setopt_array($curl3, [
    CURLOPT_URL => $url6,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => [
        "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
        "Prefer:odata.maxpagesize=50000",
        "Content-Type: application/json"
    ]
]);

$response7 = curl_exec($curl3);
$err6 = curl_error($curl3);
curl_close($curl3); // Tutup cURL setelah digunakan

if ($err6) {
    echo "cURL Error #:" . $err6;
} else {
    // Decode response JSON
    $data7x = json_decode($response7, true);

    // Pastikan bahwa $data7x memiliki 'value' dan itu berupa array
    if (isset($data7x['value']) && is_array($data7x['value'])) {
        // Simpan data ke dalam variabel untuk penggunaan di HTML
        $businessGroups = $data7x['value'];
    } else {
        $businessGroups = []; // Jika tidak ada data, gunakan array kosong
    }
}

$endpofloat7 = 'https://172.16.226.2:50000/b1s/v1/States';
$params6 = array('$select' => 'Code, Name', '$filter' => 'Country eq \'ID\'');
$url7 = $endpofloat7 . '?' . http_build_query($params6);
curl_setopt_array($curl4, [
    CURLOPT_URL => $url7,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => [
        "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
        "Prefer:odata.maxpagesize=50",
        "Content-Type: application/json"
    ]
]);

$response8 = curl_exec($curl4);
$err7 = curl_error($curl4);
curl_close($curl4); // Tutup cURL setelah digunakan

if ($err7) {
    echo "cURL Error #:" . $err7;
} else {
    // Decode response JSON
    $data8x = json_decode($response8, true);

    // Pastikan bahwa $data7x memiliki 'value' dan itu berupa array
    if (isset($data8x['value']) && is_array($data8x['value'])) {
        // Simpan data ke dalam variabel untuk penggunaan di HTML
        $state = $data8x['value'];
    } else {
        $state = []; // Jika tidak ada data, gunakan array kosong
    }
}

$endpofloatpostal = 'https://172.16.226.2:50000/b1s/v1/U_HR_POSTAL';
$paramspostal = array('$select' => 'U_prov_name, U_city_name, U_dis_name, U_subdis_name');
$urlpostal = $endpofloatpostal . '?' . http_build_query($paramspostal);
curl_setopt_array($curl5, [
    CURLOPT_URL => $urlpostal,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => [
        "Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
        "Prefer:odata.maxpagesize=100000",
        "Content-Type: application/json"
    ]
]);

$responsepostal = curl_exec($curl5);
$errpostal = curl_error($curl5);

curl_close($curl5);

$provinsi = "";
$kota = "";
$kec = "";
$desa = "";

if ($errpostal) {
    echo "cURL Error #:" . $errpostal;
} else {

    $data9x = json_decode($responsepostal, true);
    $data91 = json_decode($responsepostal);


}

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light">
        <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-align-left"></i> Form Customer
        </li>
    </ol>
</nav>


<div class="page-content">
    <div class="row">
        <div class="col-6">
            <h4>Tambah Customer</h4>
        </div>
        <div class="col-6 text-right">
        </div>
    </div>
    <div class="form-container">
        <div class="row" style="padding: 0 12px;">
            <div class="col-md-12 vertical-form">
                <h6><i class="fas fa-list-alt"></i> Lengkapi form ini untuk menambah Customer</h6>

                <form id="addBomForm">
                    <div class="position-relative form-group" style="text-align: left; ">
                        <table width="100%" border="0" style="font-family:arial,tahoma; font-size: 14px;">
                            <tr>
                                <td>
                                    <label for="cardType" style="font-weight:bold">Tipe Business Partner </label>
                                </td>
                                <td>
                                    <select id="cardType" name="cardType">
                                        <option value="C">Customer</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="cardName" style="font-weight:bold">Nama</label></td>
                                <td>
                                    <textarea type="text" class="form-control" id="cardName" name="cardName"
                                        required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="group" style="font-weight:bold">Group</label></td>
                                <td>
                                    <select id="group" name="group" required>
                                        <?php
                                        // Cek apakah $businessGroups ada isinya
                                        if (!empty($businessGroups)) {
                                            foreach ($businessGroups as $group) {
                                                $grpcode = $group['Code'];
                                                $grpname = $group['Name'];
                                                echo "<option value='{$grpcode}'>{$grpname}</option>";
                                            }
                                        } else {
                                            echo '<option>No Group available</option>';
                                        }
                                        ?>

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-weight:bold">Nama Sales </td>
                                <td>
                                    <b>
                                        <input type="hidden" class="form-control form-control-sm" name="kode_sales"
                                            id="kode_sales" readonly>
                                        <textarea class="form-control form-control-sm" data-toggle="modal"
                                            data-target="#modal_datasales" rows="2" name="nama_sales" id="nama_sales"
                                            required readonly></textarea>
                                    </b>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="payment" style="font-weight:bold">Payment Terms</label>
                                </td>
                                <td>

                                    <select id="payment" name="payment" required>
                                        <option value="-1">Cash</option>
                                        <option value="1">CBD</option>
                                        <option value="2">3D</option>
                                        <option value="3">7D</option>
                                        <option value="4">14D</option>
                                        <option value="5">21D</option>
                                        <option value="6">30D</option>
                                        <option value="7">45D</option>
                                        <option value="8">60D</option>
                                        <option value="9">90D</option>
                                        <option value="10">120D</option>
                                        <option value="11">180D</option>
                                        <option value="12">55D</option>
                                        <option value="13">35D</option>
                                        <option value="14">65D</option>
                                        <option value="15">150D</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="address1" style="font-weight:bold">Bill To Street</label>
                                </td>
                                <td>
                                    <textarea type="text" class="form-control" id="address1" name="address1"
                                        required></textarea>
                                    <br>
                                </td>

                            </tr>
                            <tr>

                                <td>
                                    <label for="
                                        address2" style="font-weight:bold">Ship To Street</label>
                                </td>
                                <td>
                                    <textarea type="text" class="form-control" id="address2" name="address2"
                                        required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="state" style="font-weight:bold">State</label></td>
                                <td>

                                    <select id="state" name="state" required>
                                        <?php
                                        // Cek apakah $state ada isinya
                                        if (!empty($state)) {
                                            foreach ($state as $State) {
                                                $statecode = $State['Code'];
                                                $statename = $State['Name'];
                                                echo "<option value='{$statecode}'>{$statename}</option>";
                                            }
                                        } else {
                                            echo '<option>No State available</option>';
                                        }
                                        ?>

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="40%" style="font-weight:bold">Kota </td>
                                <td>
                                    <b>
                                        <textarea class="form-control form-control-sm" data-toggle="modal"
                                            data-target="#modal_datakota" rows="2" name="nama_kota" id="nama_kota"
                                            required readonly></textarea>
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="county" style="font-weight:bold">Kecamatan</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="county" name="county" value="" required readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="county" style="font-weight:bold">Kelurahan</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="desa" name="desa" value="" required readonly>
                                </td>
                            </tr>
                            <tr>

                                <td><label for="nomorHP" style="font-weight:bold">Nomor HP</label></td>
                                <td>
                                    <input type="number" class="form-control" id="nomorHP" name="nomorHP" required
                                        style="text-transform: uppercase;">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="faderalTax" style="font-weight:bold">NPWP</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="faderalTax" name="faderalTax"
                                        style="text-transform: uppercase;">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="text-right tombol-kanan">
                        <button type="submit" class="btn btn-success">Save Customer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Sales -->
        <div class="modal fade" id="modal_datasales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Sales</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="example2" class="table table-striped display tabel-data">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                for ($i = 0; $i < count($data6x['value']); $i++) {
                                    $slpcode = $data61->value[$i]->SalesEmployeeCode;
                                    $slpname = $data61->value[$i]->SalesEmployeeName;

                                    ?>
                                    <tr>

                                        <td>
                                            <?php echo $slpname; ?>
                                        </td>
                                        <td class="td-opsi">
                                            <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih"
                                                id="tombol_pilihsales" name="tombol_pilihsales" data-dismiss="modal"
                                                data-kodesales="<?php echo $slpcode; ?>"
                                                data-namasales="<?php echo $slpname; ?>">
                                                <b>pilih</b>
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

        <!-- modal kota -->
        <div class="modal fade" id="modal_datakota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Sales</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="example" class="table table-striped display tabel-data">
                            <thead>
                                <tr>
                                    <th>Provinsi</th>
                                    <th>Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (isset($data9x['value']) && is_array($data9x['value'])) {
                                    foreach ($data9x['value'] as $item) {
                                        // Mengakses data sebagai array
                                        $provinsi = isset($item['U_prov_name']) ? $item['U_prov_name'] : 'N/A';
                                        $kota = isset($item['U_city_name']) ? $item['U_city_name'] : 'N/A';
                                        $kec = isset($item['U_dis_name']) ? $item['U_dis_name'] : 'N/A';
                                        $desa = isset($item['U_subdis_name']) ? $item['U_subdis_name'] : 'N/A';
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($provinsi); ?></td>
                                            <td><?php echo htmlspecialchars($kota); ?></td>
                                            <td><?php echo htmlspecialchars($kec); ?></td>
                                            <td><?php echo htmlspecialchars($desa); ?></td>
                                            <td class="td-opsi">
                                                <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih"
                                                    id="tombol_pilihkota" name="tombol_pilihkota" data-dismiss="modal"
                                                    data-kota="<?php echo $kota; ?>" data-kecamatan="<?php echo $kec; ?>"
                                                    data-kelurahan="<?php echo $desa; ?>">
                                                    <b>pilih</b>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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

        <!-- Modal for messages -->
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
    </div>
</div>

<script>

    

    // Handle form submission
    $("button[name='tombol_pilihsales']").click(function () {
        var kodesales = $(this).data('kodesales');
        var namasales = $(this).data('namasales');


        $("#kode_sales").val(kodesales);
        $("#nama_sales").val(namasales);

        console.log(kodesales);
        console.log(namasales);

    });

    $("button[name='tombol_pilihkota']").click(function () {
        var kota = $(this).data('kota');
        var kecamatan = $(this).data('kecamatan');
        var desa = $(this).data('kelurahan')


        $("#nama_kota").val(kota);
        $("#county").val(kecamatan);
        $("#desa").val(desa);

        console.log(kota);
        console.log(kecamatan);
        console.log(desas);

    });

    $('#addBomForm').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'ajax/process_form.php', // URL of your PHP script to handle form submission
            data: $(this).serialize(),
            success: function (response) {
                $('#messageText').text(response);
                $('#messageModal').modal('show');
                console.log(response);
                if (response.toLowerCase().includes('error')) {
                    $('#messageModalLabel').text('Error');
                } else {
                    $('#messageModalLabel').text('Success');
                    $('#addBomModal').modal('hide');
                    // location.reload(); // Reload the page to reflect changes
                }
            },
            error: function () {
                $('#messageText').text('An unexpected error occurred.');
                $('#messageModalLabel').text('Error');
                $('#messageModal').modal('show');
            }
        });
    });

</script>