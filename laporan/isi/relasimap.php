<?php

session_start();
include '../koneksi.php';
$no_pjl = @$_GET['no_pjl'];
$db = $_SESSION['posisi_peg'];
$tipe = $_SESSION['session_id'];
$tipesales = $_SESSION['session_tipe'];
$idpeg = $_SESSION['id_peg'];
$nmpeg = $_SESSION['nama_peg'];
$user = $_SESSION['session_user'];
$pass = $_SESSION['session_pass'];


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

  
  $nosales = $no_pjl;
  $endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Orders,Orders/DocumentLines,SalesPersons,PaymentTermsTypes,BusinessPartners)';
  
  $var1 = array('$expand' => 'Orders($select=DocNum,DocDate,DocDueDate,CardCode,CardName,Address,Address2,NumAtCard,Comments,DocTotal,VatSum,TotalDiscount,SalesPersonCode,FederalTaxID,DocTime),Orders/DocumentLines($select=LineNum,ItemCode,ItemDescription,U_IDU_Panjang,U_IT_Lembar,Quantity,Price,UnitPrice,LineTotal),BusinessPartners($select=Phone1,Cellular),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)','$filter' => 'Orders/DocEntry eq Orders/DocumentLines/DocEntry and Orders/CardCode eq BusinessPartners/CardCode and Orders/SalesPersonCode eq SalesPersons/SalesEmployeeCode and Orders/PaymentGroupCode eq PaymentTermsTypes/GroupNumber and Orders/CancelStatus eq \'csNo\' and Orders/DocEntry eq '.$nosales.'','$orderby' => 'Orders/DocNum desc');
  
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
	  "Prefer:odata.maxpagesize=10",
	  "Content-Type: application/json"
	  ]
  ]);
  
  $response2 = curl_exec($curl2);
  $err2 = curl_error($curl2);
  
  curl_close($curl2);

  if ($err2) {
	echo "cURL Error #:" . $err2;
  } else {
	
	$data2x = json_decode($response2,true);
	$data21 = json_decode($response2);
	
  }

  
  $endurlx = 'https://172.16.226.2:50000/b1s/v1/Orders';
  
  $var1x = array('$filter' => 'DocEntry eq '.$nosales.'');
  
  $url2x = $endurlx . '?' . http_build_query($var1x);	
  curl_setopt_array($curl3, [
	  CURLOPT_URL => $url2x,
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
	  "Prefer:odata.maxpagesize=10",
	  "Content-Type: application/json"
	  ]
  ]);
  
  $response3 = curl_exec($curl3);
  $err3 = curl_error($curl3);
  
  curl_close($curl3);
  
  $docentry = "";
  $docnum = "";
  $tglsales = "";
  $tglkirim = "";
  $cus_ref = "";
  $kode_cus = "";
  $nama_cus = "";
  $npwp = "";
  $alamat = "";
  $alamatkirim = "";
  $tlp = "";
  $hp = "";
  $sales = "";
  $remark = "";
  $terms = "";
  $diskon = "";
  $tax = "";
  $total = "";

  $line = "";
  $nm_item = "";
  $pj_item = "0.00";
  $lbr_item = "0.00";
  $qty = "0.00";
  $hrg_sat = "";
  $tot_hrg = "";
  $openqty = "0.00";
  
  if ($err3) {
	  echo "cURL Error #:" . $err3;
  } else {
	  
	  $data3x = json_decode($response3,true);
	  $data31 = json_decode($response3);
	  
	  $total = 0;
	  
	  
	  
  }


?>


<div>
	<?php	
		
			$docnum = $data21->value[0]->Orders->DocNum;
			$tglsales = $data21->value[0]->Orders->DocDate;
			$tglkirim = $data21->value[0]->Orders->DocDueDate;
			$cus_ref = $data21->value[0]->Orders->NumAtCard;
			$kode_cus = $data21->value[0]->Orders->CardCode;
			$nama_cus = $data21->value[0]->Orders->CardName;
			$alamat = $data21->value[0]->Orders->Address;
  			$alamatkirim = $data21->value[0]->Orders->Address2;
			$tlp = $data21->value[0]->BusinessPartners->Phone1;
			$hp = $data21->value[0]->BusinessPartners->Cellular;
			$npwp = $data21->value[0]->Orders->FederalTaxID;
			$sales = $data21->value[0]->SalesPersons->SalesEmployeeName;
			
			$remark = $data21->value[0]->Orders->Comments;
			$terms = $data21->value[0]->PaymentTermsTypes->PaymentTermsGroupName;
			$diskon = $data21->value[0]->Orders->TotalDiscount;
  			$tax = $data21->value[0]->Orders->VatSum;
			$total = $data21->value[0]->Orders->DocTotal;

	?>

	<!--SCROLL-->
    <style>
      #table-scroll {
        height:450px;
        width:100%;
        overflow:auto;  
        margin-top:2px;
        }
    </style>

	<table width="80%" align=center border=0 style="font-family:arial,tahoma; font-size: 12px;">
		
			<tr><td width="20%" align="left" colspan="2"><font face="arial" size="2">ORDERS : <b><?php echo $docnum; ?></b><br>Tgl. <b><?php echo $tglsales; ?></b></font></td><td width="20%" colspan="2"><font face="arial" size="3"><b><?php echo $kode_cus; ?></b></font></td><td width="60%" colspan="6"><font face="arial" size="3"><b><?php echo $nama_cus; ?></b></font></td></tr>		
		
		<tr><td colspan="10" valign="top"><br></td></tr>
	</table>


	<div class="table-container" id="table-scroll">
	<table width="95%" align=center border=1 style="border-collapse: collapse; width: 95%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
		<tr><td colspan="2" align="center" bgcolor="#F0F8FF" width="35%"><font face="arial" size="2"><b>SALES ORDERS</b></font></td><?php if ($tipe == 1){ ?><td rowspan="2" align="center" bgcolor="#FAEBD7" width="13"><font face="arial" size="2"><b>PRODUCTION ORDERS</b></font><?php } ?></td><td rowspan="2" align="center" bgcolor="#D8BFD8" width="13%"><font face="arial" size="2"><b>DELIVERY NOTES</b></font></td><td rowspan="2" align="center" bgcolor="#EEE8AA" width="13%"><font face="arial" size="2"><b>DELIVERY ORDERS</b></font></td><td rowspan="2" align="center" bgcolor="#B0E0E6" width="13%"><font face="arial" size="2"><b>DOWN PAYMENT</b></font></td><td rowspan="2" align="center" bgcolor="#98FB98" width="13%"><font face="arial" size="2"><b>INVOICE</b></font></td></tr>
		<tr><td align="center" bgcolor="#F0F8FF"><b>No.</b></td><td align="center" bgcolor="#F0F8FF"><b>Deskripsi Item</b></td></tr>
		<?php 
			$sub_tot = 0;
			foreach ($data3x["value"] as $value) {
				foreach ($value["DocumentLines"] as $value2) {
					
				
					$line = $value2["LineNum"];			
					$kode = $value2["ItemCode"];	
					$nm_item = $value2["ItemDescription"];
					$pj_item = $value2["U_IDU_Panjang"];
					$lbr_item = $value2["U_IT_Lembar"];
					$qty = $value2["Quantity"];
					$hrg_sat = $value2["Price"];
					$tot_hrg = $value2["LineTotal"];
					$openqty = $value2["RemainingOpenQuantity"];

					$sub_tot = $sub_tot+$tot_hrg;
		?>
		<tr><td align="center" bgcolor="#F0F8FF" valign="top"><font face="arial" size="2"><?php echo $line+1; ?>.</font></td><td bgcolor="#F0F8FF" valign="top"><?php echo $kode; ?><br><font face="arial" size="2"><?php echo $nm_item; ?></font><br><?php if($tipe == 1){ ?>Panjang : <?php echo number_format($pj_item,2); ?> M<br>Lembar : <?php echo number_format($lbr_item,2); ?> Lbr<br><?php } ?>Qty : <?php echo number_format($qty,2); ?> <?php if($tipe == 1){ ?>M'<?php } ?><br>Terkirim : <?php echo number_format($qty-$openqty,2); ?> <?php if($tipe == 1){ ?>M'<?php } ?><br>Open Qty : <?php if($openqty <= 0){?><font color="green"><?php } else { ?><font color="red"><?php } ?><b><?php echo number_format($openqty,2); ?></b></font> <?php if($tipe == 1){ ?>M'<?php } ?></td>
			<?php if ($tipe == 1){ ?>
			<td align="right" bgcolor="#FAEBD7" valign="top">
				<?php
							$curl8 = curl_init();
																					
                                          $endpointspk = 'https://172.16.226.2:50000/b1s/v1/ProductionOrders';
                                          $varspk = array('$filter' => 'ProductionOrderStatus ne \'boposCancelled\' and ProductionOrderOriginEntry eq '.$nosales.' and ItemNo eq \''.$kode.'\' and PlannedQuantity eq '.$qty.' and U_IT_Panjang eq '.$pj_item.'','$orderby' => 'AbsoluteEntry asc');
                                          $urlspk = $endpointspk . '?' . http_build_query($varspk);                                       
                                          curl_setopt_array($curl8, [
                                            CURLOPT_URL => $urlspk,
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
                                            "Prefer:odata.maxpagesize=100",
                                            "Content-Type: application/json"
                                            ]
                                          ]);
                                          
                                          $response2spk = curl_exec($curl8);
                                          $err2spk = curl_error($curl8);
                                          
                                          curl_close($curl8);
                                          
                                          $spkentry = "";
                                          $spknum = "";
                                          $spkoriginentry = "";
                                          $spkoriginnum = "";
                                          $spkitemcode = "";
                                          $spkpjitem = "";
                                          $nospk = "-";
                                          $docspk = "-";
                                          $duespk = "-";
                                          $issued = "0.00";
                                          $receipt = "0.00";
                                          $gudang = "-";
                                          $stat_spk = "-";
                                          
                                          if ($err2spk) {
                                            echo "cURL Error #:" . $err2spk;
                                          } else {
                                            
                                            $data2xspk = json_decode($response2spk,true);
                                            $data21spk = json_decode($response2spk);
                                            
                                            if (count($data2xspk['value']) > 0){
                                              $spknum = $data21spk->value[0]->DocumentNumber;
                                              $nospk = $spknum;
                                              $docspk = $data21spk->value[0]->PostingDate;
                                              $duespk = $data21spk->value[0]->DueDate;
                                              $issued = $data21spk->value[0]->ProductionOrderLines[0]->IssuedQuantity;
		                                          $receipt = $data21spk->value[0]->CompletedQuantity;
		                                          $gudang = $data21spk->value[0]->Warehouse;
		                                          $stat_spk = $data21spk->value[0]->ProductionOrderStatus;
                                            }
                                          
                                          }              
				?>
				<fieldset style="border: 1px solid black; border-radius: 5px;">
				<legend><b>SPK: <?php echo $nospk; ?></b></legend>

				<div>
				DocDate : <b><?php echo $docspk; ?></b> 
				<br>
				DueDate : <b><?php echo $duespk; ?></b> 
				<br>
				Issued : <b><?php echo number_format($issued,2); ?></b> Kg
				<br>
				Receipt : <b><?php echo number_format($receipt,2); ?></b> M'
				<br>
				Warehouse : <b><?php echo $gudang; ?></b> 
				<br>
				Status : <b><?php echo str_replace("bopos", "", $stat_spk); ?></b> 
				</div>  
				</fieldset>
				<br>
			</td>
			<?php } ?>
			<td align="right" bgcolor="#D8BFD8" valign="top">
				<?php
							$curl91 = curl_init();
																					
                                          $endpointdn = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Drafts,Drafts/DocumentLines)';
                                          $vardn = array('$expand' => 'Drafts($select=DocEntry,DocNum,DocDate,DocDueDate,Address2,CardCode,CardName,NumAtCard,Comments,DocTotal,DocumentStatus,FederalTaxID,DocTime,U_IT_DN_No,U_IT_DN_Tgl_Kirim,U_IT_DN_Aktifitas,U_HR_DN_Sts),Drafts/DocumentLines($select=DocEntry,ItemCode,Quantity,LineNum,BaseLine,BaseEntry)','$filter' => 'Drafts/DocEntry eq Drafts/DocumentLines/DocEntry and Drafts/DocObjectCode eq \'oDeliveryNotes\' and Drafts/DocumentLines/BaseEntry eq '.$nosales.' and Drafts/DocumentLines/ItemCode eq \''.$kode.'\' and Drafts/DocumentLines/BaseLine eq '.$line.'');
                                          $urldn = $endpointdn . '?' . http_build_query($vardn);                                       
                                          curl_setopt_array($curl91, [
                                            CURLOPT_URL => $urldn,
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
                                            "Prefer:odata.maxpagesize=100",
                                            "Content-Type: application/json"
                                            ]
                                          ]);
                                          
                                          $response2dn = curl_exec($curl91);
                                          $err2dn = curl_error($curl91);
                                          
                                          curl_close($curl91);
                                          
                                          $entrydn = "-";
                                          $nodn = "-";
                                          $docdn = "-";
                                          $duedn = "-";                                          
                                          $cusrefdn = "-";
                                          $alamatkirimdn = "-";
                                          $stat_dn = "-";
                                          $nodnit = "-";
                                          $tglkirim = "-";
                                          $jenis = "-";
                                          $stat_kirim = "-";
                                          
                                          if ($err2dn) {
                                            echo "cURL Error #:" . $err2dn;
                                          } else {
                                            
                                            $data2xdn = json_decode($response2dn,true);
                                            $data21dn = json_decode($response2dn);
                                            
                                            if (count($data2xdn['value']) > 0){
                                              $entrydn = $data21dn->value[0]->Drafts->DocEntry;
                                              $nodn = $data21dn->value[0]->Drafts->DocNum;
                                              $docdn = $data21dn->value[0]->Drafts->DocDate;
		                                          $duedn = $data21dn->value[0]->Drafts->DocDueDate;     
		                                          $tglkirim = $data21dn->value[0]->Drafts->U_IT_DN_Tgl_Kirim;                                       
		                                          $cusrefdn = $data21dn->value[0]->Drafts->NumAtCard;
		                                          $alamatkirimdn = $data21dn->value[0]->Drafts->Address2;
		                                          $stat_dn = $data21dn->value[0]->Drafts->DocumentStatus;
                                              $nodnit = $data21dn->value[0]->Drafts->U_IT_DN_No;
                                              $jenis = $data21dn->value[0]->Drafts->U_IT_DN_Aktifitas;
                                              $stat_kirim = $data21dn->value[0]->Drafts->U_HR_DN_Sts;
                                            }
                                          
                                          }              
				?>
				<fieldset style="border: 1px solid black; border-radius: 5px;">
				<legend><b>DN: <?php echo $nodnit; ?></b></legend>

				<div>
        ID : <b><?php echo $entrydn; ?></b> 
				<br>
        No. Doc : <b><?php echo $nodn; ?></b> 
				<br>
				DocDate : <b><?php echo $docdn; ?></b> 
				<br>	
				Tgl. Kirim : <b><?php echo $tglkirim; ?></b> 
				<br>			
				
				Aktifitas : <b><?php echo $jenis; ?></b> 
				<br>
				Status : <b><?php if ($stat_kirim == "P"){ echo "Planned"; } else { echo "Released"; } ?></b> 
				</div>  
				</fieldset>
				<br>
			</td>
			<td align="right" bgcolor="#EEE8AA" valign="top">
				<?php
							$curl9 = curl_init();
																					
                                          $endpointsj = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(DeliveryNotes,DeliveryNotes/DocumentLines)';
                                          $varsj = array('$expand' => 'DeliveryNotes($select=DocEntry,DocNum,DocDate,DocDueDate,Address2,CardCode,CardName,NumAtCard,Comments,DocTotal,DocumentStatus,FederalTaxID,DocTime),DeliveryNotes/DocumentLines($select=DocEntry,ItemCode,Quantity,LineNum,BaseLine,BaseEntry)','$filter' => 'DeliveryNotes/DocEntry eq DeliveryNotes/DocumentLines/DocEntry and DeliveryNotes/CancelStatus eq \'csNo\' and DeliveryNotes/DocumentLines/BaseEntry eq '.$nosales.' and DeliveryNotes/DocumentLines/ItemCode eq \''.$kode.'\' and DeliveryNotes/DocumentLines/BaseLine eq '.$line.'');
                                          $urlsj = $endpointsj . '?' . http_build_query($varsj);                                       
                                          curl_setopt_array($curl9, [
                                            CURLOPT_URL => $urlsj,
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
                                            "Prefer:odata.maxpagesize=100",
                                            "Content-Type: application/json"
                                            ]
                                          ]);
                                          
                                          $response2sj = curl_exec($curl9);
                                          $err2sj = curl_error($curl9);
                                          
                                          curl_close($curl9);
                                          
                                          $entrysj = "-";
                                          $nosj = "-";
                                          $docsj = "-";
                                          $duesj = "-";                                          
                                          $cusref = "-";
                                          $alamatkirim = "-";
                                          $stat_sj = "-";
                                          $noentrysj = array();
                                          
                                          if ($err2sj) {
                                            echo "cURL Error #:" . $err2sj;
                                          } else {
                                            
                                            $data2xsj = json_decode($response2sj,true);
                                            $data21sj = json_decode($response2sj);
                                            
                                            
	                                          for ($isj=0;$isj<count($data2xsj['value']);$isj++){
	                                          		$entrysj = $data21sj->value[$isj]->DeliveryNotes->DocEntry;
	                                              $nosj = $data21sj->value[$isj]->DeliveryNotes->DocNum;
	                                              $docsj = $data21sj->value[$isj]->DeliveryNotes->DocDate;
			                                          $duesj = $data21sj->value[$isj]->DeliveryNotes->DocDueDate;                                         
			                                          $cusref = $data21sj->value[$isj]->DeliveryNotes->NumAtCard;
			                                          $alamatkirim = $data21sj->value[$isj]->DeliveryNotes->Address2;
			                                          $stat_sj = $data21sj->value[$isj]->DeliveryNotes->DocumentStatus;

			                                          $dataline['docentry'] = $entrysj;
																								
			                                          array_push($noentrysj, $dataline);
	                                                       
				?>
				<fieldset style="border: 1px solid black; border-radius: 5px;">
				<legend><b>SJ: <?php echo $nosj; ?></b></legend>

				<div>
				DocDate : <b><?php echo $docsj; ?></b> 
				<br>				
				Cust.Ref : <b><?php echo $cusref; ?></b> 
				<br>
				
				Status : <b><?php echo $stat_sj; ?></b> 
				</div>  
				</fieldset>
				<br>
			<?php }} ?>
			</td>
			<td align="right" bgcolor="#B0E0E6" valign="top">
				<?php
							$curl10 = curl_init();
																					
                                          $endpointdp = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(DownPayments,DownPayments/DocumentLines)';
                                          $var1dp = array('$expand' => 'DownPayments($select=DocEntry,DocNum,DocDate,NumAtCard,DocTotal,DocumentStatus),DownPayments/DocumentLines($select=DocEntry,LineNum)','$filter' => 'DownPayments/DocEntry eq DownPayments/DocumentLines/DocEntry and DownPayments/CancelStatus eq \'csNo\'  and DownPayments/DocumentLines/BaseType eq 17 and DownPayments/DocumentLines/BaseEntry eq '.$nosales.' and DownPayments/DocumentLines/ItemCode eq \''.$kode.'\' and DownPayments/DocumentLines/BaseLine eq '.$line.'');
                                          $urldp = $endpointdp . '?' . http_build_query($var1dp);
                                          curl_setopt_array($curl10, [
                                            CURLOPT_URL => $urldp,
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
                                            "Prefer:odata.maxpagesize=100",
                                            "Content-Type: application/json"
                                            ]
                                          ]);
                                          
                                          $response2dp = curl_exec($curl10);
                                          $err2dp = curl_error($curl10);
                                          
                                          curl_close($curl10);
                                          
                                          $dpentry = "";
                                          $dpnum = "";
                                          $dpbaseentry = "";
                                          $nodp = "-";
                                          $docdp = "-";
                                          $cusref = "-";
                                          $totdp = "0";
                                          $stat_dp = "-";
                                          
                                          if ($err2dp) {
                                            echo "cURL Error #:" . $err2dp;
                                          } else {
                                            
                                            $data2xdp = json_decode($response2dp,true);
                                            $data21dp = json_decode($response2dp);

                                            
                                            if (count($data2xdp['value']) > 0){
                                              $dpnum = $data21dp->value[0]->DownPayments->DocNum;
                                              $nodp = $dpnum;
                                              $docdp = $data21dp->value[0]->DownPayments->DocDate;
                                              $cusref = $data21dp->value[0]->DownPayments->NumAtCard;;
		                                          $totdp = $data21dp->value[0]->DownPayments->DocTotal;;
		                                          $stat_dp = $data21dp->value[0]->DownPayments->DocumentStatus;;
                                                       
				?>
				<fieldset style="border: 1px solid black; border-radius: 5px;">
				<legend><b>DP: <?php echo $nodp; ?></b></legend>

				<div>
				DocDate : <b><?php echo $docdp; ?></b> 
				<br>				
				Cust.Ref : <b><?php echo $cusref; ?></b> 
				<br>
				Total DP : <b>Rp <?php echo number_format($totdp,2); ?></b> 
				<br>
				
				Status : <b><?php echo $stat_dp; ?></b> 
				</div>  
				</fieldset>
				<br>
				<?php }} ?>
			</td>
			<td align="right" bgcolor="#98FB98" valign="top">
				<?php	
							
							for($inv = 0; $inv < count($noentrysj); $inv++) {		
									
							$inventry = $noentrysj[$inv]['docentry'];
							
							$curl11 = curl_init();
																					
                                          $endpointinv = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Invoices,Invoices/DocumentLines)';
                                          $var1inv = array('$expand' => 'Invoices($select=DocEntry,DocNum,DocDate,NumAtCard,DocTotal,DocumentStatus),Invoices/DocumentLines($select=DocEntry,LineNum)','$filter' => 'Invoices/DocEntry eq Invoices/DocumentLines/DocEntry and Invoices/CancelStatus eq \'csNo\'  and Invoices/DocumentLines/BaseType eq 15 and Invoices/DocumentLines/BaseEntry eq '.$inventry.' and Invoices/DocumentLines/ItemCode eq \''.$kode.'\' and Invoices/DocumentLines/BaseLine eq '.$line.'');
                                          $urlinv = $endpointinv . '?' . http_build_query($var1inv);
                                          curl_setopt_array($curl11, [
                                            CURLOPT_URL => $urlinv,
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
                                            "Prefer:odata.maxpagesize=100",
                                            "Content-Type: application/json"
                                            ]
                                          ]);
                                          
                                          $response2inv = curl_exec($curl11);
                                          $err2inv = curl_error($curl11);
                                          
                                          curl_close($curl11);
                                          
                                          $inventry = "";
                                          $invnum = "";
                                          $invbaseentry = "";
                                          $noinv = "-";
                                          $docinv = "-";
                                          $cusrefinv = "-";
                                          $totinv = "0";
                                          $stat_inv = "-";
                                          
                                          if ($err2inv) {
                                            echo "cURL Error #:" . $err2inv;
                                          } else {
                                            
                                            $data2xinv = json_decode($response2inv,true);
                                            $data21inv = json_decode($response2inv);

                                            
                                            if (count($data2xinv['value']) > 0){
                                              $invnum = $data21inv->value[0]->Invoices->DocNum;
                                              $noinv = $invnum;
                                              $docinv = $data21inv->value[0]->Invoices->DocDate;
                                              $cusrefinv = $data21inv->value[0]->Invoices->NumAtCard;;
		                                          $totinv = $data21inv->value[0]->Invoices->DocTotal;;
		                                          $stat_inv = $data21inv->value[0]->Invoices->DocumentStatus;;
                                                       
				?>
				<fieldset style="border: 1px solid black; border-radius: 5px;">
				<legend><b>INV: <?php echo $noinv; ?></b></legend>

				<div>
				DocDate : <b><?php echo $docinv; ?></b> 
				<br>				
				Cust.Ref : <b><?php echo $cusrefinv; ?></b> 
				<br>
				Total INV : <b>Rp <?php echo number_format($totinv,2); ?></b> 
				<br>
				
				Status : <b><?php echo $stat_inv; ?></b> 
				</div>  
				</fieldset>
				<br>
			<?php } } } ?>
			</td>
		</tr>
		<?php } } ?>
		
	</table>
</div>
	
<div>