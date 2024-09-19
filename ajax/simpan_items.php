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

$user = "manager";
$pass = "utomo123";

$jenistrans = $_POST['jenistrans'];
$kodebrg = $_POST['kode_brg2'];
$namabrg = $_POST['nama_brg'];
$inventory = $_POST['inventory'];
$sales = $_POST['sales']??null;
$purchasing = $_POST['purchasing'];
$Batik = $_POST['Batik'] ?? null;
$Warna = $_POST['Warna'] ?? null;
$Radial = $_POST['Radial'] ?? null;
$Crimping = $_POST['Crimping'] ?? null;
$NokCrimping = $_POST['NokCrimping'] ?? null;
$UpCloser = $_POST['UpCloser'] ?? null;
$EndCloser = $_POST['EndCloser'] ?? null;
$EndStopper = $_POST['EndStopper'] ?? null;
$pu = $_POST['PU'] ?? null;
$pe = $_POST['PE'] ?? null;
$Flashing = $_POST['Flashing'] ?? null;
$Flashing300 = $_POST['Flashing300'] ?? null;
$Flashing450 = $_POST['Flashing450'] ?? null;
$Flashing600 = $_POST['Flashing600'] ?? null;
$PlatRoll300 = $_POST['PlatRoll300'] ?? null;
$PlatRoll450 = $_POST['PlatRoll450'] ?? null;
$PlatRoll600 = $_POST['PlatRoll600'] ?? null;
$FlashingCustom = $_POST['FlashingCustom'] ?? null;
$kodeaz = $_POST['kodeaz'];
$grade = $_POST['grade'];
$tebal = $_POST['tebal'];
$nama_bahan = $_POST['nama_bahan'];
$kode_prof = $_POST['kode_prof'];
$kodedb = $_POST['kodedb'];
//$nmdb = array("UD_SIMULASI","UD2_SIMULASI","SIMULASI_NEW_UD");


if ($kodedb == "0") {
	$query_db = "SELECT * FROM tbl_db ORDER BY id_db ASC";
	$sql_db = mysqli_query($conn, $query_db) or die($conn->error);
	while ($datadb = mysqli_fetch_array($sql_db)) {
		//for($i=0;$i<count($nmdb);$i++){
		$datahead = array();
		$kodenmdb = $datadb['nama_db'];

		$datahead['ItemName'] = $namabrg;
		$datahead['ItemsGroupCode'] = $jenistrans;
		if ($purchasing == "Purchasing") {
			$datahead['PurchaseItem'] = "tYES";
		} else {
			$datahead['PurchaseItem'] = "tNO";
		}
		if ($sales == "Sales") {
			$datahead['SalesItem'] = "tYES";
		} else {
			$datahead['SalesItem'] = "tNO";
		}
		if ($inventory == "Inventory") {
			$datahead['InventoryItem'] = "tYES";
		} else {
			$datahead['InventoryItem'] = "tNO";
		}

		$datahead['Valid'] = "tYES";
		$datahead['Frozen'] = "tNO";
		if ($Batik == "Batik") {
			$datahead['Properties1'] = "tYES";
		} else {
			$datahead['Properties1'] = "tNO";
		}
		if ($Warna == "Warna") {
			$datahead['Properties2'] = "tYES";
		} else {
			$datahead['Properties2'] = "tNO";
		}
		if ($Radial == "Radial") {
			$datahead['Properties3'] = "tYES";
		} else {
			$datahead['Properties3'] = "tNO";
		}
		if ($Crimping == "Crimping") {
			$datahead['Properties4'] = "tYES";
		} else {
			$datahead['Properties4'] = "tNO";
		}
		if ($NokCrimping == "Nok Crimping") {
			$datahead['Properties5'] = "tYES";
		} else {
			$datahead['Properties5'] = "tNO";
		}
		if ($UpCloser == "Up Closer") {
			$datahead['Properties6'] = "tYES";
		} else {
			$datahead['Properties6'] = "tNO";
		}
		if ($EndCloser == "End Closer") {
			$datahead['Properties7'] = "tYES";
		} else {
			$datahead['Properties7'] = "tNO";
		}
		if ($EndStopper == "End Stopper") {
			$datahead['Properties8'] = "tYES";
		} else {
			$datahead['Properties8'] = "tNO";
		}
		if ($pu == "PU") {
			$datahead['Properties9'] = "tYES";
		} else {
			$datahead['Properties9'] = "tNO";
		}
		if ($pe == "PE") {
			$datahead['Properties10'] = "tYES";
		} else {
			$datahead['Properties10'] = "tNO";
		}
		if ($Flashing == "Flashing") {
			$datahead['Properties11'] = "tYES";
		} else {
			$datahead['Properties11'] = "tNO";
		}
		if ($Flashing300 == "Flashing 300") {
			$datahead['Properties12'] = "tYES";
		} else {
			$datahead['Properties12'] = "tNO";
		}
		if ($Flashing450 == "Flashing 450") {
			$datahead['Properties13'] = "tYES";
		} else {
			$datahead['Properties13'] = "tNO";
		}
		if ($Flashing600 == "Flashing 600") {
			$datahead['Properties14'] = "tYES";
		} else {
			$datahead['Properties14'] = "tNO";
		}
		if ($PlatRoll300 == "Plat Roll 300") {
			$datahead['Properties15'] = "tYES";
		} else {
			$datahead['Properties15'] = "tNO";
		}
		if ($PlatRoll450 == "Plat Roll 450") {
			$datahead['Properties16'] = "tYES";
		} else {
			$datahead['Properties16'] = "tNO";
		}
		if ($PlatRoll600 == "Plat Roll 600") {
			$datahead['Properties17'] = "tYES";
		} else {
			$datahead['Properties17'] = "tNO";
		}
		if ($FlashingCustom == "Flashing Custom") {
			$datahead['Properties18'] = "tYES";
		} else {
			$datahead['Properties18'] = "tNO";
		}

		$datahead['U_IT_Grade'] = $grade;
		$datahead['U_IT_Tebal'] = $tebal;
		$datahead['U_IT_Profil'] = $kode_prof;
		$datahead['U_IT_BB'] = $nama_bahan;
		$datahead['U_HR_AZ'] = $kodeaz;


		$curl = curl_init();
		$curlcek = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Login",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $kodenmdb . '",
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


		$endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
		$var1 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode', '$filter' => 'ItemCode eq \'' . $kodebrg . '\'', '$orderby' => 'ItemsGroupCode,ItemCode asc');
		$urlcek = $endurl . '?' . http_build_query($var1);

		curl_setopt_array($curlcek, [
			CURLOPT_URL => $urlcek,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $kodenmdb . '",
				"Password": "' . $pass . '",
				"UserName": "' . $user . '"}',

			CURLOPT_HTTPHEADER => [
				"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
				"Prefer:odata.maxpagesize=10",
				"Content-Type: application/json"
			]
		]);

		$response2cek = curl_exec($curlcek);
		$err2cek = curl_error($curlcek);

		curl_close($curlcek);

		$itemcode = "";

		if ($err2cek) {
			echo "cURL Error #:" . $err2cek;
		} else {

			$data2xcek = json_decode($response2cek, true);
			$data21cek = json_decode($response2cek);
			if (isset($data21cek->value) && !empty($data21cek->value)) {
			$itemcode = $data21cek->value[0]->ItemCode ?? ''; // Menggunakan null coalescing operator untuk menghindari warning
		}
		}


		if ($itemcode != "") {
			$datahead['U_IT_Updated'] = $nmpeg;
			$dataup = json_encode($datahead, true);
			$curl2 = curl_init();

			$urlsls = "https://172.16.226.2:50000/b1s/v1/Items('" . $kodebrg . "')";
			curl_setopt_array($curl2, [
				CURLOPT_URL => $urlsls,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "PATCH",
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POSTFIELDS => $dataup,

				CURLOPT_HTTPHEADER => [
					"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
					"Prefer:odata.maxpagesize=10",
					"Content-Type: application/json"
				]
			]);

			error_reporting(E_ALL & ~E_NOTICE);
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

			// $response2 = curl_exec($curl2);
			// $err2 = curl_error($curl2);

			// curl_close($curl2);

			// $docnum = "";

			// if ($err2) {
			// 	echo "cURL Error #:" . $err2;
			// } else {

			// 	$data2x = json_decode($response2, true);
			// 	$data21 = json_decode($response2);
			// 	$docnum = $data21->value[0]->DocNum;

			// 	$error1 = $data21->error->code;
			// 	$error2 = $data21->error->message->value;
			// 	echo $error2;
			// 	$data = array(
			// 		'result' => $error2
			// 	);
			// 	echo json_encode($data);
			// }
		} else {
			$datahead['ItemCode'] = $kodebrg;
			$datahead['ManageBatchNumbers'] = "tYES";
			$datahead['U_IT_Created'] = $nmpeg;
			$data = json_encode($datahead, true);
			$curl2 = curl_init();

			$urlsls = "https://172.16.226.2:50000/b1s/v1/Items";
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
			error_reporting(E_ALL & ~E_NOTICE);
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

			// $response2 = curl_exec($curl2);
			// $err2 = curl_error($curl2);

			// curl_close($curl2);

			// $docnum = "";

			// if ($err2) {
			// 	echo "cURL Error #:" . $err2;
			// } else {
			// 	echo $response2;
			// 	$data2x = json_decode($response2, true);
			// 	$data21 = json_decode($response2);
			// 	$docnum = $data21->value[0]->DocNum;

			// 	$error1 = $data21->error->code;
			// 	$error2 = $data21->error->message->value;
			// 	echo $error2;
			// 	$data = array(
			// 		'result' => $error2
			// 	);
			// 	echo json_encode($data);
			// }
		}

	}
} else {
	$datahead = array();
	$datahead['ItemName'] = $namabrg;
	$datahead['ItemsGroupCode'] = $jenistrans;
	if ($purchasing == "Purchasing") {
		$datahead['PurchaseItem'] = "tYES";
	} else {
		$datahead['PurchaseItem'] = "tNO";
	}
	if ($sales == "Sales") {
		$datahead['SalesItem'] = "tYES";
	} else {
		$datahead['SalesItem'] = "tNO";
	}
	if ($inventory == "Inventory") {
		$datahead['InventoryItem'] = "tYES";
	} else {
		$datahead['InventoryItem'] = "tNO";
	}

	$datahead['Valid'] = "tYES";
	$datahead['Frozen'] = "tNO";
	if ($Batik == "Batik") {
		$datahead['Properties1'] = "tYES";
	} else {
		$datahead['Properties1'] = "tNO";
	}
	if ($Warna == "Warna") {
		$datahead['Properties2'] = "tYES";
	} else {
		$datahead['Properties2'] = "tNO";
	}
	if ($Radial == "Radial") {
		$datahead['Properties3'] = "tYES";
	} else {
		$datahead['Properties3'] = "tNO";
	}
	if ($Crimping == "Crimping") {
		$datahead['Properties4'] = "tYES";
	} else {
		$datahead['Properties4'] = "tNO";
	}
	if ($NokCrimping == "Nok Crimping") {
		$datahead['Properties5'] = "tYES";
	} else {
		$datahead['Properties5'] = "tNO";
	}
	if ($UpCloser == "Up Closer") {
		$datahead['Properties6'] = "tYES";
	} else {
		$datahead['Properties6'] = "tNO";
	}
	if ($EndCloser == "End Closer") {
		$datahead['Properties7'] = "tYES";
	} else {
		$datahead['Properties7'] = "tNO";
	}
	if ($EndStopper == "End Stopper") {
		$datahead['Properties8'] = "tYES";
	} else {
		$datahead['Properties8'] = "tNO";
	}
	if ($pu == "PU") {
		$datahead['Properties9'] = "tYES";
	} else {
		$datahead['Properties9'] = "tNO";
	}
	if ($pe == "PE") {
		$datahead['Properties10'] = "tYES";
	} else {
		$datahead['Properties10'] = "tNO";
	}
	if ($Flashing == "Flashing") {
		$datahead['Properties11'] = "tYES";
	} else {
		$datahead['Properties11'] = "tNO";
	}
	if ($Flashing300 == "Flashing 300") {
		$datahead['Properties12'] = "tYES";
	} else {
		$datahead['Properties12'] = "tNO";
	}
	if ($Flashing450 == "Flashing 450") {
		$datahead['Properties13'] = "tYES";
	} else {
		$datahead['Properties13'] = "tNO";
	}
	if ($Flashing600 == "Flashing 600") {
		$datahead['Properties14'] = "tYES";
	} else {
		$datahead['Properties14'] = "tNO";
	}
	if ($PlatRoll300 == "Plat Roll 300") {
		$datahead['Properties15'] = "tYES";
	} else {
		$datahead['Properties15'] = "tNO";
	}
	if ($PlatRoll450 == "Plat Roll 450") {
		$datahead['Properties16'] = "tYES";
	} else {
		$datahead['Properties16'] = "tNO";
	}
	if ($PlatRoll600 == "Plat Roll 600") {
		$datahead['Properties17'] = "tYES";
	} else {
		$datahead['Properties17'] = "tNO";
	}
	if ($FlashingCustom == "Flashing Custom") {
		$datahead['Properties18'] = "tYES";
	} else {
		$datahead['Properties18'] = "tNO";
	}

	$datahead['U_IT_Grade'] = $grade;
	$datahead['U_IT_Tebal'] = $tebal;
	$datahead['U_IT_Profil'] = $kode_prof;
	$datahead['U_IT_BB'] = $nama_bahan;
	$datahead['U_HR_AZ'] = $kodeaz;



	$curl = curl_init();
	$curlcek = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Login",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $kodedb . '",
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


	$endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
	$var1 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode', '$filter' => 'ItemCode eq \'' . $kodebrg . '\'', '$orderby' => 'ItemsGroupCode,ItemCode asc');
	$urlcek = $endurl . '?' . http_build_query($var1);

	curl_setopt_array($curlcek, [
		CURLOPT_URL => $urlcek,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $kodedb . '",
				"Password": "' . $pass . '",
				"UserName": "' . $user . '"}',

		CURLOPT_HTTPHEADER => [
			"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
			"Prefer:odata.maxpagesize=10",
			"Content-Type: application/json"
		]
	]);

	$response2cek = curl_exec($curlcek);
	$err2cek = curl_error($curlcek);

	curl_close($curlcek);

	$itemcode = "";

	if ($err2cek) {
		echo "cURL Error #:" . $err2cek;
	} else {

		$data2xcek = json_decode($response2cek, true);
		$data21cek = json_decode($response2cek);
		if (isset($data21cek->value) && !empty($data21cek->value)) {
			$itemcode = $data21cek->value[0]->ItemCode ?? ''; // Menggunakan null coalescing operator untuk menghindari warning
		}
	}


	if ($itemcode != "") {
		$datahead['U_IT_Updated'] = $nmpeg;
		$dataup = json_encode($datahead, true);
		$curl2 = curl_init();

		$urlsls = "https://172.16.226.2:50000/b1s/v1/Items('" . $kodebrg . "')";
		curl_setopt_array($curl2, [
			CURLOPT_URL => $urlsls,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "PATCH",
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POSTFIELDS => $dataup,

			CURLOPT_HTTPHEADER => [
				"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
				"Prefer:odata.maxpagesize=10",
				"Content-Type: application/json"
			]
		]);
		error_reporting(E_ALL & ~E_NOTICE);
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
		// $response2 = curl_exec($curl2);
		// $err2 = curl_error($curl2);

		// curl_close($curl2);

		// $docnum = "";

		// if ($err2) {
		// 	echo "cURL Error #:" . $err2;
		// } else {
		// 	echo $response2;
		// 	$data2x = json_decode($response2, true);
		// 	$data21 = json_decode($response2);
		// 	$docnum = $data21->value[0]->DocNum;

		// 	$error1 = $data21->error->code;
		// 	$error2 = $data21->error->message->value;
		// 	echo $error2;
		// 	$data = array(
		// 		'result' => $error2
		// 	);
		// 	echo json_encode($data);
		// }
	} else {
		$datahead['ItemCode'] = $kodebrg;
		$datahead['ManageBatchNumbers'] = "tYES";
		$datahead['U_IT_Created'] = $nmpeg;
		$data = json_encode($datahead, true);
		$curl2 = curl_init();

		$urlsls = "https://172.16.226.2:50000/b1s/v1/Items";
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
		error_reporting(E_ALL & ~E_NOTICE);
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
		// $response2 = curl_exec($curl2);
		// $err2 = curl_error($curl2);

		// curl_close($curl2);

		// $docnum = "";

		// if ($err2) {
		// 	echo "cURL Error #:" . $err2;
		// } else {

		// 	$data2x = json_decode($response2, true);
		// 	$data21 = json_decode($response2);
		// 	$docnum = $data21->value[0]->DocNum;

		// 	$error1 = $data21->error->code;
		// 	$error2 = $data21->error->message->value;
		// 	echo $error2;
		// 	$data = array(
		// 		'result' => $error2
		// 	);
		// 	echo json_encode($data);
		// }
	}
}
?>