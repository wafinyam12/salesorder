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

  
  $endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Quotations,SalesPersons,PaymentTermsTypes)';
  
  $var1 = array('$expand' => 'Quotations($select=DocEntry,DocNum,DocDate,DocType,DocumentStatus,CardCode,CardName,NumAtCard,Comments,DocTotal,SalesPersonCode,FederalTaxID,DocTime),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)','$filter' => 'Quotations/SalesPersonCode eq SalesPersons/SalesEmployeeCode and Quotations/PaymentGroupCode eq PaymentTermsTypes/GroupNumber and Quotations/CancelStatus eq \'csNo\' and Quotations/SalesPersonCode eq '.$idpeg.'','$orderby' => 'Quotations/DocEntry desc');
  
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
  $doctipe = "";
  $docstatus = "";
  $tglsales = "";
  $cus_ref = "";
  $kode_cus = "";
  $nama_cus = "";
  $npwp = "";
  $sales = "";
  $remark = "";
  $terms = "";
  $total = "";
  
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
    <li class="breadcrumb-item active" aria-current="page"><a href="?page=entry_datatransport"><i class="fas fa-align-left"></i> Data Transport</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-file-invoice-dollar"></i> Data DN Transport</li>
  </ol>
</nav>
<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>Data DN Transport</h4></div>
		<div class="col-6 text-right">
						           
		</div>
	</div>
	<div class="table-container" id="table-scroll">
		<table id="tbldata_penjualan" class="table table-striped display tabel-data">
			<thead>
		        <tr>
		            <th>DN Quotation</th>
		            <th>Tanggal</th>
		            <th>Tipe</th>
		            <th>Status</th>
					<th>PO Cust</th>
					<th>Kode Cust</th>
					<th>Nama Cust</th>
					<th>NPWP</th>		            
					<th>Remark</th>
					<th>Terms</th>
		            <th>Total</th>
		            <th>Opsi</th>
		        </tr>
		    </thead>
		    <tbody>
		    	<?php 
		    		for ($i=0;$i<count($data2x['value']);$i++){
						$docentry = $data21->value[$i]->Quotations->DocEntry;
						$docnum = $data21->value[$i]->Quotations->DocNum;
						$doctipe = $data21->value[$i]->Quotations->DocType;
						$docstatus = $data21->value[$i]->Quotations->DocumentStatus;
						$tglsales = $data21->value[$i]->Quotations->DocDate;
						$cus_ref = $data21->value[$i]->Quotations->NumAtCard;
						$kode_cus = $data21->value[$i]->Quotations->CardCode;
						$nama_cus = $data21->value[$i]->Quotations->CardName;
						$npwp = $data21->value[$i]->Quotations->FederalTaxID;
						$sales = $data21->value[$i]->SalesPersons->SalesEmployeeName;
						$remark = $data21->value[$i]->Quotations->Comments;
						$terms = $data21->value[$i]->PaymentTermsTypes->PaymentTermsGroupName;
						$total = $data21->value[$i]->Quotations->DocTotal;

						$tgl=date_create($tglsales);
		    	 ?>
		    	 	<tr>
		    	 		<td><?php echo $docnum; ?></td>
		    	 		<td><?php echo date_format($tgl,"d-m-Y"); ?></td>
		    	 		<td><?php if($doctipe == "I"){echo "Item";} else {echo "Service";}?></td>
							<td><?php if($docstatus == "O"){echo "Open";} else {echo "Closed";}?></td>
		    	 		<td><?php echo $cus_ref; ?></td>
						<td><?php echo $kode_cus; ?></td>
						<td><?php echo $nama_cus; ?></td>
						<td><?php echo $npwp; ?></td>
						<td><?php echo $remark; ?></td>
						<td><?php echo $terms; ?></td>
		    	 		<td class="text-right"><?php echo number_format($total,2); ?></td>
			 			<td class="td-opsi">	                        
													
	                        <a href="laporan/?page=nota_quotation&no_pjl=<?php echo $docentry; ?>" target="_blank">
                              <button class="btn-transition btn btn-outline-dark btn-sm" title="cetak" id="tombol_cetak" name="tombol_cetak">
                                  <i class="fas fa-print"></i>
                              </button>
                            </a>                            
		    	 			
	                    </td>
		    	 	</tr>
		    	 <?php } ?>
		    </tbody>
		</table>
	</div>
</div>

