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

	

	function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'IP tidak dikenali';
        return $ipaddress;
    }
	
	$jml = $_POST['jml'];

	$entry = $_POST['no_entry'];
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
	$terms = $_POST['terms'];
	

	$datahead = array();
        $datahead['CompanyDB'] = $db;
		$datahead['Password'] = $pass;
		$datahead['UserName'] = $user;
		
		$datahead['Address2'] = $alamat;
		$datahead['Comments'] = $remark;
		
		$datahead['DocDueDate'] = $tgldelivery;
		$datahead['NumAtCard'] = $cus_ref;
		$datahead['U_IT_User_Id'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$datahead['U_IT_User_Nama'] = $nmpeg;
        	$datahead['SalesPersonCode'] = $id_pegawai;
			$datahead['DiscountPercent'] = $diskon;
			$datahead['U_IT_Kontak_Person'] = $kontak;
			$datahead['PaymentGroupCode'] = $terms;
			if($jenistrans == "Items"){
				$datahead['DocType'] = "dDocument_Items";
			} else {
				$datahead['DocType'] = "dDocument_Service";
			}

			
	$dataline = array();

	for($i = 0; $i < $jml; $i++) {
		
		$j = $i+1;
		$kd_brg = $_POST['hidden_kdbrg'.$j.''];
		$nm_brg = $_POST['hidden_nmbrg'.$j.''];
		if($tipe == 1){
			
			$pj_brg = str_replace(",","",$_POST['hidden_pjbrg'.$j.'']);
			$lbr_brg = str_replace(",","",$_POST['hidden_lbrbrg'.$j.'']);
		}
		$uom = $_POST['hidden_uom'][$i];
		$qty = str_replace(",","",$_POST['hidden_qty'.$j.'']);
		
		$hrg_sat = str_replace(",","",$_POST['hidden_hrgsat'.$j.'']);
		
		$subtotal = $_POST['hidden_subtotal'.$j.''];

		

		

		$dataline1['LineNum'] = $i;
		$dataline1['ItemCode'] = $kd_brg;
		if($tipe == 1){
			$dataline1['U_IDU_Panjang'] = $pj_brg;
			$dataline1['U_IT_Lembar'] = $lbr_brg;
		}
		
		$dataline1['TaxCode'] = "S1+";
		$dataline1['WarehouseCode'] = $kode_gudang;
		$dataline1['ProjectCode'] = $kode_project;
		$dataline1['UnitPrice'] = $hrg_sat;
		$dataline1['COGSCostingCode2'] = $dept;
        	$dataline1['COGSCostingCode3'] = $cabang;
			$dataline1['BaseType'] = 23;
			$dataline1['BaseEntry'] = $entry;
			$dataline1['BaseLine'] = $i;

			if($jenistrans == "Items"){
				$dataline1['Quantity'] = $qty;
				$dataline1['U_IT_Uom'] = $uom;
				
			} else {
				$dataline1['ItemDescription'] = $nm_brg;
				$dataline1['AccountCode'] = "401010102";
				$dataline1['U_HR_QtyFisik'] = $qty;
				$dataline1['U_HR_UoMSvc'] = $uom;
				
			}

        array_push($dataline,$dataline1);

	}

	$datahead['DocumentLines'] = $dataline;
	$data = json_encode($datahead,true);
	$detail = json_encode($dataline,true);

	$curl = curl_init();
	$curl2 = curl_init();
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
	CURLOPT_POSTFIELDS => '{"CompanyDB": "'.$db.'",
		"Password": "'.$pass.'",
		"UserName": "'.$user.'"}',
	CURLOPT_HTTPHEADER => [
		"Content-Type: application/json"
	],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	$token="";

	if ($err) {
	echo "cURL Error #:" . $err;
	} else {

	$data2 = json_decode($response);
	$token = $data2->SessionId;

	}

	$endpoint = 'https://172.16.226.2:50000/b1s/v1/Quotations('.$entry.')';
	curl_setopt_array($curlcek, [
		CURLOPT_URL => $endpoint,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_POSTFIELDS => json_encode([
			'CompanyDB' => $db,
			'Password' => $pass,
			'UserName' => $user
		]),
		CURLOPT_HTTPHEADER => [
		"Cookie:B1SESSION=".$token."; ROUTEID=.node1",
		"Prefer:odata.maxpagesize=10",
		"Content-Type: application/json"
		]
	]);
	
	$responsecek = curl_exec($curlcek);
	$errcek = curl_error($curlcek);
	
	curl_close($curlcek);
	
  	$cekstatus = "";

	
	if ($errcek) {
		echo "cURL Error #:" . $errcek;
	} else {
		
		$datacekx = json_decode($responsecek,true);
		$datacek1 = json_decode($responsecek);
	    $cekstatus = $datacek1->DocumentStatus;
	}
	

	if($cekstatus == "bost_Open"){
		curl_setopt_array($curl2, [
			CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Orders",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POSTFIELDS => $data,
			
			CURLOPT_HTTPHEADER => [
			"Cookie:B1SESSION=".$token."; ROUTEID=.node1",
			"Prefer:odata.maxpagesize=10",
			"Content-Type: application/json"
			]
		]);
		
		$response2 = curl_exec($curl2);
		$err2 = curl_error($curl2);
		
		curl_close($curl2);
		
		$docnum = "";
		
		if ($err2) {
			echo "cURL Error #:" . $err2;
		} else {
			echo $response2 ;
			$data2x = json_decode($response2,true);
			$data21 = json_decode($response2);
			$docnum = $data21->value[0]->DocNum;
		}

	}

	
 ?>