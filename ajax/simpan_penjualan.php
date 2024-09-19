<?php
include "../koneksi.php";
session_start();

$db = $_SESSION['posisi_peg'];
$tipe = $_SESSION['session_id'];
$id_pegawai = $_SESSION['id_peg'];
$nmpeg = $_SESSION['nama_peg'];
$user = $_SESSION['session_user'];
$pass = $_SESSION['session_pass'];
$dept = $_SESSION['dept'];
$cabang = $_SESSION['cabang'];


function get_client_ip()
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if (getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'IP tidak dikenali';
	return $ipaddress;
}

$trans = $_POST['trans'];
$jenistrans = $_POST['jenistrans'];
$tglsales = date('Y-m-d');
$tgldelivery = $_POST['tgldelivery'];
$kode_cus = $_POST['kode_cus'];
$nama_cus = $_POST['nama_cus'];
$cus_ref = $_POST['cus_ref'];
$alamat = $_POST['alamat'];
$kode_gudang = $_POST['kode_gudang'];
$kode_project = $_POST['kode_project'];
$remark = $_POST['remark'];
$diskon = $_POST['total_diskon'];
$kontak = $_POST['kontak'];
$cus_curr = $_POST['cus_curr'];
$cus_rate = $_POST['cus_rate'];


$datahead = array();
$datahead['CompanyDB'] = $db;
$datahead['Password'] = $pass;
$datahead['UserName'] = $user;
$datahead['CardCode'] = $kode_cus;
$datahead['CardName'] = $nama_cus;
$datahead['Address2'] = $alamat;
$datahead['Comments'] = $remark;
$datahead['DocDate'] = $tglsales;
$datahead['DocDueDate'] = $tgldelivery;
$datahead['NumAtCard'] = $cus_ref;
$datahead['U_IT_User_Id'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$datahead['U_IT_User_Nama'] = $nmpeg;
$datahead['SalesPersonCode'] = $id_pegawai;
$datahead['DiscountPercent'] = $diskon;
if ($tipe == 2) {
	$datahead['U_IT_Kontak_Person'] = $kontak;
}
$datahead['DocCurrency'] = $cus_curr;
$datahead['DocRate'] = $cus_rate;
if ($jenistrans == 1) {
	$datahead['DocType'] = "dDocument_Items";
} else {
	$datahead['DocType'] = "dDocument_Service";
}


$dataline = array();

for ($i = 0; $i < count($_POST['hidden_kdbrg']); $i++) {


	$kd_brg = $_POST['hidden_kdbrg'][$i];
	$nm_brg = $_POST['hidden_nmbrg'][$i];
	if ($tipe == 1) {
		$pj_brg = $_POST['hidden_pjbrg'][$i];
		$lbr_brg = $_POST['hidden_lbrbrg'][$i];
		$lb_brg = $_POST['hidden_lbbrg'][$i];
	} else {
		$series = $_POST['hidden_series'][$i];
		$uom = $_POST['hidden_uom'][$i];
	}
	$uomserv = $_POST['hidden_uom'][$i];
	$qty = $_POST['hidden_qty'][$i];
	$hrg_sat = $_POST['hidden_hrgsat'][$i];

	$subtotal = $_POST['hidden_subtotal'][$i];


	$dataline1['LineNum'] = $i;

	if ($tipe == 1) {
		$dataline1['U_IDU_Panjang'] = $pj_brg;
		$dataline1['U_IT_Lembar'] = $lbr_brg;
		$dataline1['U_IT_Lebar'] = $lb_brg;
	} else {
		$dataline1['U_IT_Series'] = $series;

	}
	$dataline1['TaxCode'] = "S1+";
	$dataline1['WarehouseCode'] = $kode_gudang;
	$dataline1['ProjectCode'] = $kode_project;

	$dataline1['Currency'] = $cus_curr;
	$dataline1['Rate'] = $cus_rate;
	$dataline1['COGSCostingCode2'] = $dept;
	$dataline1['COGSCostingCode3'] = $cabang;
	if ($jenistrans == 1) {
		$dataline1['ItemCode'] = $kd_brg;
		$dataline1['Quantity'] = $qty;
		if (isset($uom)) {
			$dataline1['U_IT_Uom'] = $uom;
		}
		$dataline1['UnitPrice'] = $hrg_sat;
	} else {
		$dataline1['ItemDescription'] = $nm_brg;
		$dataline1['AccountCode'] = "401010102";

		$dataline1['U_HR_QtyFisik'] = $qty;
		$dataline1['U_HR_UoMSvc'] = $uomserv;

		$dataline1['UnitPrice'] = $subtotal;
		$dataline1['U_IT_No_Product'] = $kd_brg;
	}

	array_push($dataline, $dataline1);

}

$datahead['DocumentLines'] = $dataline;
$data = json_encode($datahead, true);
$detail = json_encode($dataline, true);

$curl = curl_init();
$curl2 = curl_init();

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



if ($trans == 1) {
	$urlsls = "https://172.16.226.2:50000/b1s/v1/Quotations";
} else {
	$urlsls = "https://172.16.226.2:50000/b1s/v1/Orders";
}

curl_setopt_array($curl2, [

	CURLOPT_URL => $urlsls,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_POSTFIELDS => $data,

	CURLOPT_HTTPHEADER => [
		"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
		"Prefer:odata.maxpagesize=10",
		"Content-Type: application/json"
	]
]);

$response2 = curl_exec($curl2);
$err2 = curl_error($curl2);

curl_close($curl2);

$docnum = "";


if ($err2) {
	echo json_encode(array("error" => "cURL Error #: " . $err2));
} else {
	$data2x = json_decode($response2, true);
	$data21 = json_decode($response2);

	// Initialize an empty array to store the response
	$response = array();

	// Check if 'value' is defined and has elements
	if (isset($data21->value) && !empty($data21->value)) {
		$docnum = $data21->value[0]->DocNum ?? ''; // Use null coalescing to avoid undefined property error
	}

	// Check if 'error' is defined
	if (isset($data21->error)) {
		$error1 = $data21->error->code ?? '';
		$error2 = $data21->error->message->value ?? 'Unknown error';

		// Add the error to the response array
		$response['error'] = $error2;
	} else {
		// Handle cases where there is no error
		$response['result'] = 'Success';
		$response['docnum'] = $docnum;
	}

	// Return the final JSON response
	echo json_encode($response);
}


