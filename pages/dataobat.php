<?php
	//session_start();
	$db = $_SESSION['posisi_peg'];
	$user = $_SESSION['session_user'];
	$pass = $_SESSION['session_pass'];
	$tipesales = $_SESSION['session_tipe'];

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


	$endpoint = 'https://172.16.226.2:50000/b1s/v1/Items';
	$cari = "";
	if($tipesales == 1){
		$params = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode','$filter' => '(ItemsGroupCode eq 100 or ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109 or ItemsGroupCode eq 115) and SalesItem eq \'tYES\' and Valid eq \'tYES\'','$orderby' => 'ItemsGroupCode,ItemCode asc');
	} else {
		$params = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode','$filter' => '(ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109 or ItemsGroupCode eq 115) and Valid eq \'tYES\'','$orderby' => 'ItemsGroupCode,ItemCode asc');
	}
	
	$url = $endpoint . '?' . http_build_query($params);
	
	curl_setopt_array($curl2, [
		CURLOPT_URL => $url,
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
		"Cookie:B1SESSION=".$token."; ROUTEID=.node1",
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
		
		$data2x = json_decode($response2,true);
		$data21 = json_decode($response2);
		
	}
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-capsules"></i> Data Barang</li>
  </ol>
</nav>
<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>Data Barang</h4></div>
		<div class="col-6 text-right">
			
		</div>
	</div>
	<div class="table-container">
		<table id="tabel_dataobat" class="table table-striped display tabel-data">
			<thead>
		        <tr>
		            <th>Kode</th>
		            <th>Nama Barang</th>
		            
		        </tr>
		    </thead>
		    <tbody>
		<?php 
			
				for ($i=0;$i<count($data2x['value']);$i++){
					$itemcode = $data21->value[$i]->ItemCode;
					$itemname = $data21->value[$i]->ItemName;
					
		 ?>
		 		<tr>
		 			<td><?php echo $itemcode; ?></td>
		 			<td><?php echo $itemname; ?></td>
		 			
		 		</tr>
		 <?php 
			} 
		 ?>
		    </tbody>
		</table>
	</div>
</div>