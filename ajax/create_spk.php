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
	$no_order = $_POST['no_order'];
	$tglsales = date('Y-m-d');
	$tgldelivery = $_POST['tgldelivery'];
	$kode_cus = $_POST['kode_cus'];
	$nama_cus = $_POST['nama_cus'];
	$cus_ref = $_POST['cus_ref'];
	$alamat = $_POST['alamat'];
	$kode_gudang = str_replace("WRF","PRD",$_POST['kode_gudang']);
	$kode_project = $_POST['kode_project'];
	
	$diskon = $_POST['total_diskon'];
	
	
	for($i = 0; $i < $jml; $i++) {		
		$datahead = array();
        		$j = $i+1;
		$spk = $_POST['spk'.$j.''];
		if ($spk == 1){
				$kd_brg = $_POST['hidden_kdbrg'.$j.''];
				$nm_brg = $_POST['hidden_nmbrg'.$j.''];
				if($tipe == 1){
					
					$pj_brg = str_replace(",","",$_POST['hidden_pjbrg'.$j.'']);
					$lbr_brg = str_replace(",","",$_POST['hidden_lbrbrg'.$j.'']);
				}
				$qty = str_replace(",","",$_POST['hidden_qty'.$j.'']);
				
				$tgl_start = $_POST['hidden_tglstart'.$j.''];
				$tgl_due = $_POST['hidden_tgldue'.$j.''];
				$remark = $_POST['hidden_remark'.$j.''];
				

				$datahead['ProductionOrderType'] = "bopotStandard";
				$datahead['Remarks'] = $remark;
				$datahead['PostingDate'] = $tglsales;
				$datahead['Warehouse'] = $kode_gudang;
				$datahead['CustomerCode'] = $kode_cus;
				$datahead['Project'] = $kode_project;	
				$datahead['UserSignature'] = 29;			
				$datahead['ProductionOrderOriginEntry'] = $entry;
				$datahead['U_IT_Id_User'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$datahead['U_IT_Id_Nama'] = $nmpeg;
				
				$datahead['StartDate'] = $tgl_start;
				$datahead['DueDate'] = $tgl_due;

				$datahead['ItemNo'] = $kd_brg;
				$datahead['ProductDescription'] = $nm_brg;
				$datahead['U_IT_Panjang'] = $pj_brg;
				$datahead['PlannedQuantity'] = $qty;
				$datahead['ProductionOrderOriginNumber'] = $no_order;

				
			$data = json_encode($datahead,true);
			

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
			
			
			curl_setopt_array($curl2, [
				CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/ProductionOrders",
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
				
				$data2x = json_decode($response2,true);
				$data21 = json_decode($response2);
				$docnum = $data21->value[0]->DocNum;
			}	
		}
		

	}

	
 ?>