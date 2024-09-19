<?php
	//session_start();
	$db = $_SESSION['posisi_peg'];
	$user = $_SESSION['session_user'];
	$pass = $_SESSION['session_pass'];
	$tipe = $_SESSION['session_id'];
  	$tipesales = $_SESSION['session_tipe'];
  	$idpeg = $_SESSION['id_peg'];

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

	
	$endpoint = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(BusinessPartners,SalesPersons,PaymentTermsTypes)';
	
	if($tipe == 1){
		$params1 = array('$expand' => 'BusinessPartners($select=CardCode,CardName,Address,FederalTaxID,Phone1,Phone2,Cellular,SalesPersonCode,CreditLimit,CurrentAccountBalance),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)','$filter' => 'BusinessPartners/SalesPersonCode eq SalesPersons/SalesEmployeeCode and BusinessPartners/PayTermsGrpCode eq PaymentTermsTypes/GroupNumber and (contains(BusinessPartners/CardCode,\'C\') or contains(BusinessPartners/CardCode,\'A\')) and BusinessPartners/Valid eq \'tYES\' and BusinessPartners/CardType eq \'C\' and BusinessPartners/SalesPersonCode eq '.$idpeg.'','$orderby' => 'BusinessPartners/CardCode asc');
	} else {
		$params1 = array('$expand' => 'BusinessPartners($select=CardCode,CardName,Address,FederalTaxID,Phone1,Phone2,Cellular,SalesPersonCode,CreditLimit,CurrentAccountBalance),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)','$filter' => 'BusinessPartners/SalesPersonCode eq SalesPersons/SalesEmployeeCode and BusinessPartners/PayTermsGrpCode eq PaymentTermsTypes/GroupNumber and BusinessPartners/Valid eq \'tYES\' and BusinessPartners/CardType eq \'C\' and BusinessPartners/SalesPersonCode eq '.$idpeg.'','$orderby' => 'BusinessPartners/CardCode asc');
	}
	
	$url = $endpoint . '?' . http_build_query($params1);
	
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
	
	$cardcode = "";
	$cardname = "";
	$address = "";
	$salescode = "";
	$npwp = "";
	$tlp1 = "";
	
	$hp = "";
	$payterm = "";
	$credit = "";
	$balance = "";
	
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
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-users"></i> Data Customer</li>
  </ol>
</nav>

<div class="page-content">
	<div class="row">
		<div class="col-6" id="judul"><h4>Data Customer</h4></div>
		
	</div>
	<div class="table-responsive" id="table-scroll">
		<table id="example" class="table table-striped display tabel-data">
			<thead>
		        <tr>
		            <th>Kode</th>
		            <th width="20%">Nama</th>
		            <th width="30%">Alamat</th>
					<th>NPWP</th>
					<th>Telp.</th>
					
		            <th>No. HP</th>
		            <th>Terms</th>
		            <th>Limit</th>
								<th>Piutang</th>
		            <th width="15%">Sales</th>
		            
		        </tr>
		    </thead>
		    <tbody>
		<?php 
			
				for ($i=0;$i<count($data2x['value']);$i++){
					$cardcode = $data21->value[$i]->BusinessPartners->CardCode;
					$cardname = $data21->value[$i]->BusinessPartners->CardName;
					$address = $data21->value[$i]->BusinessPartners->Address;
					$salescode = $data21->value[$i]->SalesPersons->SalesEmployeeName;
					$npwp = $data21->value[$i]->BusinessPartners->FederalTaxID;
					$tlp1 = $data21->value[$i]->BusinessPartners->Phone1;
					
					$hp = $data21->value[$i]->BusinessPartners->Cellular;
					$payterm = $data21->value[$i]->PaymentTermsTypes->PaymentTermsGroupName;
					$credit = $data21->value[$i]->BusinessPartners->CreditLimit;
					$balance = $data21->value[$i]->BusinessPartners->CurrentAccountBalance;
		 ?>
		 		<tr>
		 			<td><?php echo $cardcode; ?></td>
		 			<td><?php echo $cardname; ?></td>
		 			<td width="30%"><?php echo $address; ?></td>
					<td><?php echo $npwp; ?></td>
					<td><?php echo $tlp1; ?></td>
					
		 			<td><?php echo $hp; ?></td>
		 			<td><?php echo $payterm; ?></td>
		 			<td><?php echo number_format($credit,2); ?></td>
					<td><?php echo number_format($balance,2); ?></td>
		 			<td><?php echo $salescode; ?></td>
		 			

		 			
		 		</tr>
		 <?php 
			} 
		 ?>
		    </tbody>
		</table>
	</div>
</div>
