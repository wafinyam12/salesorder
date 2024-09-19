<?php

$db = $_SESSION['posisi_peg'];
$tipe = $_SESSION['session_id'];
$idpeg = $_SESSION['id_peg'];
$nmpeg = $_SESSION['nama_peg'];
$user = $_SESSION['session_user'];
$pass = $_SESSION['session_pass'];

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

  
  $endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(DeliveryNotes,DeliveryNotes/DocumentLines,Orders,PaymentTermsTypes)'; 
  
  $var1 = array('$expand' => 'DeliveryNotes($select=DocEntry,DocNum,DocDate,CardCode,CardName,Address2,NumAtCard,Comments,DocTotal,SalesPersonCode,FederalTaxID,DocTime),DeliveryNotes/DocumentLines($select=BaseEntry),Orders($select=DocEntry,DocNum,DocDate,CardCode,CardName,NumAtCard,Comments,DocTotal,SalesPersonCode,FederalTaxID,DocTime),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)','$filter' => 'DeliveryNotes/DocEntry eq DeliveryNotes/DocumentLines/DocEntry and DeliveryNotes/DocumentLines/BaseEntry eq Orders/DocEntry and Orders/PaymentGroupCode eq PaymentTermsTypes/GroupNumber and Orders/CancelStatus eq \'csNo\' and DeliveryNotes/CancelStatus eq \'csNo\' and Orders/SalesPersonCode eq '.$idpeg.'','$orderby' => 'DeliveryNotes/DocEntry asc');
  
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
	  "Cookie:B1SESSION=".$token."; ROUTEID=.node1",
	  "Prefer:odata.maxpagesize=3000",
	  "Content-Type: application/json"
	  ]
  ]);
  
  $response2 = curl_exec($curl2);
  $err2 = curl_error($curl2);
  
  curl_close($curl2);
  
  $docentry = "";
  $docnum = "";
  $tgldelivery = "";
  
  $kode_cus = "";
  $nama_cus = "";
  $alamat = "";
  $npwp = "";
  
  $remark = "";
  $terms = "";
  $totalsj = "";
  $doctimesj = "";
  $docnumso = "";
  $tglsales = "";
  $totalso = "";
  $doctimeso = "";
  
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
    <li class="breadcrumb-item active" aria-current="page"><a href="?page=entry_datapenjualan"><i class="fas fa-align-left"></i> Data Sales Orders</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-file-invoice-dollar"></i> Data Delivery</li>
  </ol>
</nav>
<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>Data Delivery</h4></div>
		<div class="col-6 text-right">
			
			<a href="?page=datapenjualan">
				<button class="btn btn-sm btn-info">Data Sales Orders</button>
			</a>
		</div>
	</div>
	<div class="table-container" id="table-scroll">
		<table id="example" class="table table-striped display tabel-data">
			<thead>
		        <tr>
		            <th>Delivery</th>
		            <th>Tanggal</th>
					<th>Time SJ</th>
					
					<th>Kode Cust</th>
					<th>Nama Cust</th>
					<th>Alamat Kirim</th>
					<th>NPWP</th>
		            
					<th>Remark</th>
					<th>Terms</th>
		            <th>Total SJ</th>
					<th>Orders</th>
					<th>Tanggal SO</th>
					<th>Time SO</th>
					<th>Total SO</th>
					
		            <th>Opsi</th>
		        </tr>
		    </thead>
		    <tbody>
		    	<?php 
					$cekentry = array();
		    		for ($i=0;$i<count($data2x['value']);$i++){
						$docentry = $data21->value[$i]->DeliveryNotes->DocEntry;
						$docnum = $data21->value[$i]->DeliveryNotes->DocNum;
						$tgldelivery = $data21->value[$i]->DeliveryNotes->DocDate;
						$doctimesj = $data21->value[$i]->DeliveryNotes->DocTime;
						$kode_cus = $data21->value[$i]->DeliveryNotes->CardCode;
						$nama_cus = $data21->value[$i]->DeliveryNotes->CardName;
						$npwp = $data21->value[$i]->DeliveryNotes->FederalTaxID;
						$alamat = $data21->value[$i]->DeliveryNotes->Address2;
						$remark = $data21->value[$i]->DeliveryNotes->Comments;
						$terms = $data21->value[$i]->PaymentTermsTypes->PaymentTermsGroupName;
						$totalsj = $data21->value[$i]->DeliveryNotes->DocTotal;						
						$docnumso = $data21->value[$i]->Orders->DocNum;
						$tglsales = $data21->value[$i]->Orders->DocDate;
						$totalso = $data21->value[$i]->Orders->DocTotal;
						$doctimeso = $data21->value[$i]->Orders->DocTime;
						
						$tgldelivery=date_create($tgldelivery);
						$tglsales=date_create($tglsales);

						
						if (in_array($docentry, $cekentry)) {
							
						} else {
							$cekentry = array($docentry);
		    	 ?>
		    	 	<tr>
		    	 		<td><?php echo $docnum; ?></td>
		    	 		<td><?php echo date_format($tgldelivery,"d-m-Y"); ?></td>
		    	 		<td><?php echo $doctimesj; ?></td>
						<td><?php echo $kode_cus; ?></td>
						<td><?php echo $nama_cus; ?></td>
						<td><?php echo $alamat; ?></td>
						<td><?php echo $npwp; ?></td>
						<td><?php echo $remark; ?></td>
						<td><?php echo $terms; ?></td>
						<td class="text-right"><?php echo number_format($totalsj,2); ?></td>
						<td><?php echo $docnumso; ?></td>
						<td><?php echo date_format($tglsales,"d-m-Y"); ?></td>
						<td><?php echo $doctimeso; ?></td>
		    	 		<td class="text-right"><?php echo number_format($totalso,2); ?></td>
			 			<td class="td-opsi">
						 	<a href="?page=detail_delivery&no_dln=<?php echo $docentry; ?>">
	                        <button class="btn-transition btn btn-outline-primary btn-sm" title="detail delivery notes" id="tombol_detail" name="tombol_detail">
	                            <i class="fas fa-info-circle"></i>
	                        </button>
	                        
                            </a>
		    	 			
	                    </td>
		    	 	</tr>					
		    	 <?php }} ?>
		    </tbody>
		</table>
	</div>
</div>

