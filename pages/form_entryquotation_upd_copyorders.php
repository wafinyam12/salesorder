<?php
	//session_start();
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
    $curl4 = curl_init();
    $curl4x = curl_init();
    $curl5 = curl_init();
    $curl6 = curl_init();
    $curl7 = curl_init();
    $curl12 = curl_init();
    

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

	//Load Quotations
  $ordr = $_GET['no_ord'];
	$endpoint = 'https://172.16.226.2:50000/b1s/v1/Quotations('.$ordr.')';
	curl_setopt_array($curl2, [
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
	
	$response2 = curl_exec($curl2);
	$err2 = curl_error($curl2);
	
	curl_close($curl2);
	
  $docentry = "";
  $docnum = "";
  $docstatus = "";
  $docdate = "";
  $docduedate = "";
  $cardcode = "";
  $cardname = "";
  $alamat = "";
  $cus_ref = "";
  $kodegudang = "";
  $gudang = "";
  $kodeproject = "";
  $project = "";
  $remark = "";
  $doctotal = "";
  $dispercent = "";
  $distot = "";
  $payterms = "";

	
	if ($err2) {
		echo "cURL Error #:" . $err2;
	} else {
		
		$data2x = json_decode($response2,true);
		$data21 = json_decode($response2);
    $docentry = $data21->DocEntry;
    $doctype = $data21->DocType;
		$docnum = $data21->DocNum;
    $docstatus = $data21->DocumentStatus;
    $docdate = $data21->DocDate;
    $docduedate = $data21->DocDueDate;
    $cardcode = $data21->CardCode;
    $cardname = $data21->CardName;
    $alamat = $data21->Address2;
    $cus_ref = $data21->NumAtCard;
    $kodegudang = $data21->DocumentLines[0]->WarehouseCode;
    //$gudang = $data21->value[0]->DocNum;
    $kodeproject = $data21->DocumentLines[0]->ProjectCode;
    //$project = $data21->value[0]->DocNum;
    $remark = $data21->Comments;
    $doctotal = $data21->DocTotal;
    $dispercent = $data21->DiscountPercent;
    $distot = $data21->TotalDiscount;
    $payterms = $data21->PaymentGroupCode;
    
	}
    
    

	if($tipe == 1){
			
    		if ($tipesales == 1){
		    	$endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
		    	$var1 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode','$filter' => '(ItemsGroupCode eq 100 or ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109 or ItemsGroupCode eq 115) and SalesItem eq \'tYES\' and Valid eq \'tYES\'','$orderby' => 'ItemsGroupCode,ItemCode asc');
		    } else {
		    	$endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Items,U_HR_GRP_PROFILE)';
		    	$var1 = array('$expand' => 'Items($select=ItemCode,ItemName,ItemsGroupCode,U_IT_Profil,U_IT_Tebal,U_HR_AZ,Properties1,Properties2,Properties3,Properties4,Properties5,Properties6,Properties7,Properties8,Properties9,Properties10,Properties11,Properties12,Properties13,Properties14,Properties15,Properties16,Properties17),U_HR_GRP_PROFILE($select=Code,Name,U_GrpName)','$filter' => 'Items/U_IT_Profil eq U_HR_GRP_PROFILE/Code and Items/SalesItem eq \'tYES\' and Items/Valid eq \'tYES\' and Items/ItemsGroupCode eq 101','$orderby' => 'Items/ItemCode asc');
		    }
		    
		    $url3 = $endurl . '?' . http_build_query($var1);	
		    curl_setopt_array($curl4, [
		      CURLOPT_URL => $url3,
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
		    
		    $response4 = curl_exec($curl4);
		    $err4 = curl_error($curl4);
		    
		    curl_close($curl4);

		    $itemcode = "";
		    $itemname = "";
		    $profil = "";
		    $tebal = "";
		    $az = "";
		    $batik = "";
		    $warna = "";
		    $radial = "";
		    $crimping = "";
		    $nokcrimping = "";
		    $upcloser = "";
		    $endcloser = "";
		    $endstopper = "";
		    $pu = "";
		    $pe = "";
		    $flasing = "";
		    $flasing300 = "";
		    $flasing450 = "";
		    $flasing600 = "";
		    $code = "";
		    $nama = "";
		    $grpname = "";
		    $plat300 = "";
		    $plat450 = "";
		    $plat600 = "";

		    if ($err4) {
		    echo "cURL Error #:" . $err4;
		    } else {
		    
		    $data4x = json_decode($response4,true);
		    $data41 = json_decode($response4);
		    
		    }

		    
		    $endurlx = 'https://172.16.226.2:50000/b1s/v1/U_HR_GRP_PRICE';
		    curl_setopt_array($curl4x, [
		      CURLOPT_URL => $endurlx,
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
		      "Prefer:odata.maxpagesize=1000",
		      "Content-Type: application/json"
		      ]
		    ]);
		    
		    $response4x = curl_exec($curl4x);
		    $err4x = curl_error($curl4x);
		    
		    curl_close($curl4x);
		    
		    $kodepl = "";
		    $harga1 = "";
		    $harga2 = "";
		    $harga3 = "";
		    $hargahet = "";
		    $utebal = "";
		    
		    if ($err4x) {
		      echo "cURL Error #:" . $err4x;
		    } else {
		      
		      $data4xx = json_decode($response4x,true);
		      $data4x1 = json_decode($response4x);
		      
		      
		      
		    }

	} else {
		    
		    $endpoint3 = 'https://172.16.226.2:50000/b1s/v1/Items';	
		    $params2 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode,ItemPrices','$filter' => '(ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109) and SalesItem eq \'tYES\' and Valid eq \'tYES\'','$orderby' => 'ItemCode asc');
		    $url3 = $endpoint3 . '?' . http_build_query($params2);	
		    curl_setopt_array($curl4, [
		      CURLOPT_URL => $url3,
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
		      "Prefer:odata.maxpagesize=10000",
		      "Content-Type: application/json"
		      ]
		    ]);
		    
		    $response4 = curl_exec($curl4);
		    $err4 = curl_error($curl4);
		    
		    curl_close($curl4);
		    
		    $itemcode = "";
		    $itemname = "";
		    $kode_pl = "";
		    $hrg_pl = "";
		    $base_pl = "";
		    $price1 = "";
		    $price2 = "";
		    $price3 = "";
		    $price4 = "";
		    
		    if ($err4) {
		      echo "cURL Error #:" . $err4;
		    } else {
		      
		      $data4x = json_decode($response4,true);
		      $data41 = json_decode($response4);
		      
		    }
	  }

    //Load Gudang
    $endpoint5 = 'https://172.16.226.2:50000/b1s/v1/Warehouses';	
	$params4 = array('$select' => 'WarehouseCode,WarehouseName','$filter' => 'startswith(WarehouseCode,\'WRF\') and Inactive eq \'tNO\'','$orderby' => 'WarehouseCode asc');
	$url5 = $endpoint5 . '?' . http_build_query($params4);	
	curl_setopt_array($curl6, [
		CURLOPT_URL => $url5,
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
		"Prefer:odata.maxpagesize=50",
		"Content-Type: application/json"
		]
	]);
	
	$response6 = curl_exec($curl6);
	$err6 = curl_error($curl6);
	
	curl_close($curl6);
	
	$whsecode = "";
	$whsename = "";
	
	if ($err6) {
		echo "cURL Error #:" . $err6;
	} else {
		
		$data6x = json_decode($response6,true);
		$data61 = json_decode($response6);
		
	}

    //Load Project
    $endpoint6 = 'https://172.16.226.2:50000/b1s/v1/Projects';	
	$params5 = array('$select' => 'Code,Name','$filter' => 'Active eq \'tYES\'','$orderby' => 'Code asc');
	$url6 = $endpoint6 . '?' . http_build_query($params5);	
	curl_setopt_array($curl7, [
		CURLOPT_URL => $url6,
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
		"Prefer:odata.maxpagesize=200",
		"Content-Type: application/json"
		]
	]);
	
	$response7 = curl_exec($curl7);
	$err7 = curl_error($curl7);
	
	curl_close($curl7);
	
	$projectcode = "";
	$projectname = "";
	
	if ($err7) {
		echo "cURL Error #:" . $err7;
	} else {
		
		$data7x = json_decode($response7,true);
		$data71 = json_decode($response7);
		
	}

	//Load PaymentTerms
  $endpoint12 = 'https://172.16.226.2:50000/b1s/v1/PaymentTermsTypes';	
	$params12 = array('$select' => 'GroupNumber,PaymentTermsGroupName','$orderby' => 'GroupNumber asc');
	$url12 = $endpoint12 . '?' . http_build_query($params12);	
	curl_setopt_array($curl12, [
		CURLOPT_URL => $url12,
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
		"Prefer:odata.maxpagesize=200",
		"Content-Type: application/json"
		]
	]);
	
	$response12 = curl_exec($curl12);
	$err12 = curl_error($curl12);
	
	curl_close($curl12);
	
	$kodeterms = "";
	$nameterms = "";
	
	if ($err12) {
		echo "cURL Error #:" . $err12;
	} else {
		
		$data12x = json_decode($response12,true);
		$data121 = json_decode($response12);
		
	}
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-align-left"></i> Form Copy SQ to SO</li>
  </ol>
</nav>

<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>COPY Quotations to Orders</h4></div>
		<div class="col-6 text-right">
			<a href="?page=datapenjualan">
				<button class="btn btn-sm btn-info">Data Sales Quotation</button>
			</a>
		</div>
	</div>
	<div class="form-container">
		<div class="row" style="padding: 0 12px;">
			<div class="col-md-12 vertical-form">
				<h6> (<font color="red">**</font>) Lengkapi form atau dapat merubah data penawaran ini</h6>

        <form method="post" id="form_copyorders" autocomplete="off">
                <div class="position-relative form-group" style="text-align: left; ">                
                <table width="100%" border="0" style="font-family:arial,tahoma; font-size: 14px;">
                
                    <tr><td width="40%">No SQ </td><td width="2%"></td><td><input class="" size="12" type="hidden" name="no_entry" value="<?php echo $ordr; ?>" id=""><input class="" size="12" type="text" name="no_order" value="<?php echo $docnum; ?>" id="" disabled=""></td></tr>
                    <tr><td width="40%">Status SQ </td><td width="2%"></td><td><input class="" size="12" type="text" name="st_order" value="<?php if ($docstatus == "bost_Open"){echo "OPEN";}else{echo "CLOSED";} ?>" id="" disabled=""></td></tr>
                    <tr><td width="40%">Tgl Orders </td><td width="2%"></td><td> <font color="blue"><b><input class="" size="10" type="date" name="tglsales" value="<?php echo date('Y-m-d'); ?>" id="tglsales" disabled=""></b></font></td></tr>
                    <tr><td width="40%">Tgl Delivery <font color="red">**</font> </td><td width="2%"></td><td> <font color="blue"><b><input class="" size="10" type="date" name="tgldelivery" value="<?php echo date('Y-m-d'); ?>" id="tgldelivery"></b></font></td></tr>
                    <tr><td colspan="3"><h6></td></tr>

                    <tr><td width="40%">Jenis Trans</td><td width="2%"></td><td>
                    <input type="text" class="form-control form-control-sm" name="jenistrans" id="jenistrans" value="<?php if($doctype == "dDocument_Service"){ echo "Service"; } else { echo "Items"; }  ?>" readonly>
                    </td></tr>

                    <tr><td width="40%">Customer </td><td width="2%"></td>
                                    <td> <b>
                                    
                                        <input type="text" class="form-control form-control-sm" data-toggle="" data-target="" rows="2" name="kode_cus" id="kode_cus" value="<?php echo $cardcode; ?>" readonly>
                                        </b> 
                                    </td></tr>
                    <tr><td width="40%">Nama </td><td width="2%"></td><td> <b><textarea class="form-control form-control-sm" name="nama_cus" id="nama_cus" readonly><?php echo $cardname; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Payment Terms <font color="red">**</font> </td><td width="5%"></td><td> 
                    	
                    	<select class="form-control form-control-sm" name="terms" id="terms">
                    		<?php 
                    			for ($i=0;$i<count($data12x['value']);$i++){
                    				$kodeterms = $data121->value[$i]->GroupNumber;
														$nameterms = $data121->value[$i]->PaymentTermsGroupName;
														if($payterms == $kodeterms || $kodeterms == '-1' || $kodeterms == '1'){
                    		?>
                    		<option value="<?php echo $kodeterms; ?>" <?php if($payterms == $kodeterms){echo "selected";}?>><?php echo $nameterms; ?></option>
                    		<?php }} ?>
                    	</select>
                    </td></tr>
                    <tr><td width="40%">Cust Ref. No. <font color="red">**</font> </td><td width="5%"></td><td> <b><input type="text" class="form-control form-control-sm" name="cus_ref" id="cus_ref" value="<?php echo $cus_ref; ?>"></b> </td></tr>
                    <tr><td width="40%">Alamat Kirim <font color="red">**</font> </td><td width="5%"></td><td> <b><textarea class="form-control form-control-sm" rows="2" name="alamat" id="alamat" ><?php echo $alamat; ?></textarea></b> </td></tr>
                    <tr><td colspan="3"><h6></td></tr>
                    <tr><td width="40%">Gudang <font color="red">**</font> </td><td width="2%"></td><td> <b>
                        <input type="hidden" class="form-control form-control-sm" name="kode_gudang" id="kode_gudang" value="<?php echo $kodegudang; ?>" readonly>
                        <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_datagudang" rows="2" name="nama_gudang" id="nama_gudang" readonly><?php echo $kodegudang; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Project <font color="red">**</font> </td><td width="2%"></td><td> <b>
                        <input type="hidden" class="form-control form-control-sm" name="kode_project" id="kode_project" value="<?php echo $kodeproject; ?>" readonly>
                        <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_dataproject" rows="2" name="nama_project" id="nama_project" readonly><?php echo $kodeproject; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Sales </td><td width="2%"></td><td> <b>
                        
                    <input type="text" class="form-control form-control-sm" value="<?php echo $nmpeg; ?>" name="nama_sales" id="nama_sales" readonly></b> </td></tr>    
                    <tr><td width="40%">Remark <font color="red">**</font> </td><td width="5%"></td><td> <b><textarea class="form-control form-control-sm" rows="2" name="remark" id="remark"><?php echo $remark; ?></textarea></b> </td></tr>
                </table>
                </div>
                		<div class="row kotak-form-tabel-penjualan">
                    	<div class="col-md-12 kotak-tabel-obat-terjual" id="table-scroll">
                            <table class="table display tabel-data" style="font-family:arial,tahoma; font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th class="text-left">Kode</th>
                                        <th class="text-left">Nama</th>
                                        <?php if ($tipe == "1") { ?>
                                        <th class="text-right">Pjg </th>
                                        <th class="text-right">Lbr </th>
                                        <?php } else { ?>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                    	<?php } ?>
                                        <th class="text-right">UoM</th>
                                        <th class="text-right">Qty</th>                                        
                                        <th class="text-right">Harga<br>(non PPN)</th>
                                        <th class="text-right">Total<br>(inc PPN)</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <?php
                                        $output = "";
                                        $count = 0;
                                        $totalppn = 0;
                                        for ($i=0;$i<count($data2x['DocumentLines']);$i++){
                                          $line = $data21->DocumentLines[$i]->LineNum;	
                                          $kd_item = $data21->DocumentLines[$i]->ItemCode;			
                                          $nm_item = $data21->DocumentLines[$i]->ItemDescription;
                                          $pj_item = $data21->DocumentLines[$i]->U_IDU_Panjang;
                                          $lbr_item = $data21->DocumentLines[$i]->U_IT_Lembar;
                                          //$uom = $data21->DocumentLines[$i]->U_IT_Uom;
                                          $uom = "";
                                          $uomserv = $data21->DocumentLines[$i]->U_HR_UoMSvc;
                                          $qty = $data21->DocumentLines[$i]->Quantity;
                                          $qtyserv = $data21->DocumentLines[$i]->U_HR_QtyFisik;
                                          $openqty = $data21->DocumentLines[$i]->RemainingOpenQuantity;
                                          $hrg_sat = $data21->DocumentLines[$i]->Price;
                                          $subtotal = $data21->DocumentLines[$i]->LineTotal;
                                          $subtotalppn = $data21->DocumentLines[$i]->GrossTotal;
                                          $totalppn = $totalppn + $subtotalppn;
                                          $count = $line+1;
                                                                                   
                                                                                
                                                                                    
                                          ?>
                                          <tr id="row_<?php echo $count; ?>">
                                          <?php if ($tipe == "1") { ?>
                                            <td class="text-left"><?php //echo $count; ?> <textarea style="background-color: #C0C0C0;" name="hidden_kdbrg<?php echo $count; ?>" id="td_kd_obat<?php echo $count; ?>" class="td_kd_obat" value="<?php echo $kd_item; ?>" rows="2" cols="25" readonly><?php echo $kd_item; ?></textarea></td>
                                            <td class="text-left"><?php //echo $nm_item; ?> <textarea style="background-color: #C0C0C0;" name="hidden_nmbrg<?php echo $count; ?>" id="td_nmobat<?php echo $count; ?>" class="td_nmobat" value="<?php //echo $nm_item; ?>" rows="3" cols="40" readonly><?php echo $nm_item; ?></textarea></td>
                                            <td class="text-right"><?php //echo $pj_item; ?> <input type="text" name="hidden_pjbrg<?php echo $count; ?>" id="td_pjobat<?php echo $count; ?>" class="text-right" size="6" value="<?php echo number_format($pj_item,2); ?>"  <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <td class="text-right"><?php //echo $lbr_item; ?> <input type="text" name="hidden_lbrbrg<?php echo $count; ?>" id="td_lbrobat<?php echo $count; ?>" class="text-right" size="6" value="<?php echo number_format($lbr_item,2); ?>" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <?php if($doctype == "dDocument_Service"){ ?>
                                            <td class="text-right"><?php //echo $qty; ?> <input type="text" name="hidden_uom<?php echo $count; ?>" id="td_uomobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo $uomserv; ?>" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <td class="text-right"><?php //echo $qty; ?> <input style="background-color: #C0C0C0;" type="text" name="hidden_qty<?php echo $count; ?>" id="td_jmlobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo number_format($qtyserv,2); ?>" step="0.01" readonly></td>
                                            <?php } else { ?>
                                            <td class="text-right"><?php //echo $qty; ?> <input type="text" name="hidden_uom<?php echo $count; ?>" id="td_uomobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo $uom; ?>" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <td class="text-right"><?php //echo $qty; ?> <input style="background-color: #C0C0C0;" type="text" name="hidden_qty<?php echo $count; ?>" id="td_jmlobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo number_format($qty,2); ?>" step="0.01" readonly></td>
                                            <?php } ?>
                                          <?php } else { ?>
                                            <td class="text-left"><?php //echo $count; ?> <textarea style="background-color: #C0C0C0;" name="hidden_kdbrg<?php echo $count; ?>" id="td_kd_obat<?php echo $count; ?>" class="td_kd_obat" value="<?php echo $kd_item; ?>" rows="2" cols="25" readonly><?php echo $kd_item; ?></textarea></td>
                                            <td class="text-left"><?php //echo $nm_item; ?> <textarea style="background-color: #C0C0C0;" name="hidden_nmbrg<?php echo $count; ?>" id="td_nmobat<?php echo $count; ?>" class="td_nmobat" value="<?php //echo $nm_item; ?>" rows="3" cols="40" readonly><?php echo $nm_item; ?></textarea></td>
                                            <td class="text-right"><?php //echo $pj_item; ?> </td>
                                            <td class="text-right"><?php //echo $lbr_item; ?> </td>
                                            <?php if($doctype == "dDocument_Service"){ ?>
                                            <td class="text-right"><?php //echo $qty; ?> <input type="text" name="hidden_uom<?php echo $count; ?>" id="td_uomobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo $uomserv; ?>" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <td class="text-right"><?php //echo $qty; ?> <input type="number" name="hidden_qty<?php echo $count; ?>" id="td_jmlobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo number_format($qtyserv,2); ?>" step="any" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <?php } else { ?>
                                            <td class="text-right"><?php //echo $qty; ?> <input type="text" name="hidden_uom<?php echo $count; ?>" id="td_uomobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo $uom; ?>" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <td class="text-right"><?php //echo $qty; ?> <input type="number" name="hidden_qty<?php echo $count; ?>" id="td_jmlobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo number_format($qty,2); ?>" step="any" <?php if($doctype == "dDocument_Service"){ echo "style='background-color: #C0C0C0;' readonly"; } ?>></td>
                                            <?php } ?>
                                          <?php } ?>                                          
                                          <?php if($tipesales == 1){ ?>
                                            <td class="text-right"><?php //echo $hrg_sat; ?> <input type="text" name="hidden_hrgsat<?php echo $count; ?>" id="td_satobat<?php echo $count; ?>" class="text-right" size="15" value="<?php echo number_format($hrg_sat,2); ?>"  readonly></td>
                                          <?php } else { ?>
                                          <?php   if($kd_item == "BSPC00001"){ ?>
                                                    <td class="text-right"><?php //echo $hrg_sat; ?> <input type="text" name="hidden_hrgsat<?php echo $count; ?>" id="td_satobat<?php echo $count; ?>" class="text-right" size="15" value="<?php echo number_format($hrg_sat,2); ?>" readonly></td>
                                          <?php   } else { ?>
                                                    <td class="text-right"><?php //echo $hrg_sat; ?> <input type="text" style="background-color: #C0C0C0;" name="hidden_hrgsat<?php echo $count; ?>" id="td_satobat<?php echo $count; ?>" class="text-right" size="15" value="<?php echo number_format($hrg_sat,2); ?>" readonly ></td>
                                          <?php } }?>
                                            <td class="text-right"><?php //echo $subtotal; ?> <input style="background-color: #C0C0C0;" type="text" name="hidden_subtotal<?php echo $count; ?>" id="td_subtotal<?php echo $count; ?>" class="text-right" value="<?php echo number_format($subtotalppn,2); ?>" readonly></td>
                                          
                                          </tr>
                                        <?php
                                        } 
                                    ?>
                                </tbody>
                                <tfoot>
                                    
                                    <tr class="baris_total" style="">
                                        <td colspan="11" class="text-right" style="font-weight: bold;">Sub Total (exc PPN) : <input type="hidden" style="background-color: #C0C0C0;" name="jml" class="text-right" size="8" value="<?php echo $count; ?>" readonly><input type="text" style="background-color: #C0C0C0;" name="hidden_totalpenjualan" id="hidden_totalpenjualan" align="right" size="10" value="<?php echo round(($totalppn/1.11),2); ?>" readonly></td>
                                        <td class="td-opsi">
                                            
                                        </td>
                                    </tr>
                                    <tr class="baris_total" style="">
                                        <td colspan="11" class="text-right" style="font-weight: bold;">Discount :  <input type="text" name="total_diskon" id="total_diskon" align="right" size="4" value="<?php echo $dispercent; ?>" onkeyup="Shitungpercentx()"> % <input type="text" style="background-color: #C0C0C0;" name="hidden_totaldiskon" id="hidden_totaldiskon" align="right" size="10" value="<?php echo $distot; ?>" readonly></td>
                                        <td class="td-opsi">
                                            
                                        </td>
                                    </tr>
                                    
                                    <tr class="baris_total" style="">
                                        <td colspan="11" class="text-right" style="font-weight: bold;">Total (inc PPN) : <input type="text" style="background-color: #C0C0C0;" name="hidden_totalpenjualanppn" id="hidden_totalpenjualanppn" align="right" size="10" value="<?php echo $doctotal; ?>" readonly></td>
                                        <td class="td-opsi">
                                            
                                        </td>
                                    </tr>
                                </tfoot>
                                
                            </table>
                            <div class="baris_total text-right" style="display: none;">
                                
                            </div>
                    	</div>
                    </div>
                    <div class="text-right tombol-kanan">
                    <?php if ($docstatus == "bost_Open"){?>
                      <input type="submit" name="copytoorders" id="copytoorders" class="btn btn-info" value="Copy to Orders">
                    <?php } ?>
                        
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

<!-- Modal Gudang -->
<div class="modal fade" id="modal_datagudang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Gudang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-striped display tabel-data">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
        <?php 

                for ($i=0;$i<count($data6x['value']);$i++){
					$whsecode = $data61->value[$i]->WarehouseCode;
					$whsename = $data61->value[$i]->WarehouseName;
				
         ?>
                <tr>
                    <td><?php echo $whsecode; ?></td>
                    <td><?php echo $whsename; ?></td>                  
                    <td class="td-opsi">                        
                        <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihgudang" name="tombol_pilihgudang" data-dismiss="modal"
                            data-kodegudang="<?php echo $whsecode; ?>"
                            data-namagudang="<?php echo $whsename; ?>"                            
                        > <b>pilih</b>
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

<!-- Modal Project -->
<div class="modal fade" id="modal_dataproject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-striped display tabel-data">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
        <?php 

                for ($i=0;$i<count($data7x['value']);$i++){
					        $projectcode = $data71->value[$i]->Code;
					        $projectname = $data71->value[$i]->Name;
				
         ?>
                <tr>
                    <td><?php echo $projectcode; ?></td>
                    <td><?php echo $projectname; ?></td>                  
                    <td class="td-opsi">                        
                        <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihproject" name="tombol_pilihproject" data-dismiss="modal"
                            data-kodeproject="<?php echo $projectcode; ?>"
                            data-namaproject="<?php echo $projectname; ?>"                            
                        > <b>pilih</b>
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


<!-- Modal Barang -->
<div class="modal fade" id="modal_dataobat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Barang Baris ke- <input type="text" name="idrow" id="rows" size="2" disabled=""></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="table-scroll">
        <table id="tabel_dataobat" class="table table-striped display tabel-data">
            <thead>                
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <?php if($tipesales == 2){ ?>
                    <th>Harga1</th>
                    <th>Harga2</th>
                    <th>Harga3</th>
                    <th>Harga HET</th>
                    <?php } else { ?>
                    
                    <th>Opsi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
              
        <?php 

        		$curl = curl_init();
        		$curl4 = curl_init();
        		$curl4x = curl_init();
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

            if($tipe == 1){
						
			    		if ($tipesales == 1){
					    	$endurl = 'https://172.16.226.2:50000/b1s/v1/Items';
					    	$var1 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode','$filter' => '(ItemsGroupCode eq 100 or ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109 or ItemsGroupCode eq 115) and SalesItem eq \'tYES\' and Valid eq \'tYES\'','$orderby' => 'ItemsGroupCode,ItemCode asc');
					    } else {
					    	$endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Items,U_HR_GRP_PROFILE)';
					    	$var1 = array('$expand' => 'Items($select=ItemCode,ItemName,ItemsGroupCode,U_IT_Profil,U_IT_Tebal,U_HR_AZ,Properties1,Properties2,Properties3,Properties4,Properties5,Properties6,Properties7,Properties8,Properties9,Properties10,Properties11,Properties12,Properties13,Properties14,Properties15,Properties16,Properties17),U_HR_GRP_PROFILE($select=Code,Name,U_GrpName)','$filter' => 'Items/U_IT_Profil eq U_HR_GRP_PROFILE/Code and Items/SalesItem eq \'tYES\' and Items/Valid eq \'tYES\' and Items/ItemsGroupCode eq 101','$orderby' => 'Items/ItemCode asc');
					    }
					    
					    $url3 = $endurl . '?' . http_build_query($var1);	
					    curl_setopt_array($curl4, [
					      CURLOPT_URL => $url3,
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
					    
					    $response4 = curl_exec($curl4);
					    $err4 = curl_error($curl4);
					    
					    curl_close($curl4);

					    $itemcode = "";
					    $itemname = "";
					    $profil = "";
					    $tebal = "";
					    $az = "";
					    $batik = "";
					    $warna = "";
					    $radial = "";
					    $crimping = "";
					    $nokcrimping = "";
					    $upcloser = "";
					    $endcloser = "";
					    $endstopper = "";
					    $pu = "";
					    $pe = "";
					    $flasing = "";
					    $flasing300 = "";
					    $flasing450 = "";
					    $flasing600 = "";
					    $code = "";
					    $nama = "";
					    $grpname = "";
					    $plat300 = "";
					    $plat450 = "";
					    $plat600 = "";

					    if ($err4) {
					    echo "cURL Error #:" . $err4;
					    } else {
					    
					    $data4x = json_decode($response4,true);
					    $data41 = json_decode($response4);
					    
					    }

					    
					    $endurlx = 'https://172.16.226.2:50000/b1s/v1/U_HR_GRP_PRICE';
					    curl_setopt_array($curl4x, [
					      CURLOPT_URL => $endurlx,
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
					      "Prefer:odata.maxpagesize=1000",
					      "Content-Type: application/json"
					      ]
					    ]);
					    
					    $response4x = curl_exec($curl4x);
					    $err4x = curl_error($curl4x);
					    
					    curl_close($curl4x);
					    
					    $kodepl = "";
					    $harga1 = "";
					    $harga2 = "";
					    $harga3 = "";
					    $hargahet = "";
					    $utebal = "";
					    
					    if ($err4x) {
					      echo "cURL Error #:" . $err4x;
					    } else {
					      
					      $data4xx = json_decode($response4x,true);
					      $data4x1 = json_decode($response4x);
					      
					      
					      
					    }

				} else {
					    
					    $endpoint3 = 'https://172.16.226.2:50000/b1s/v1/Items';	
					    $params2 = array('$select' => 'ItemCode,ItemName,ForeignName,ItemsGroupCode,ItemPrices','$filter' => '(ItemsGroupCode eq 101 or ItemsGroupCode eq 102 or ItemsGroupCode eq 109) and SalesItem eq \'tYES\' and Valid eq \'tYES\'','$orderby' => 'ItemCode asc');
					    $url3 = $endpoint3 . '?' . http_build_query($params2);	
					    curl_setopt_array($curl4, [
					      CURLOPT_URL => $url3,
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
					      "Prefer:odata.maxpagesize=10000",
					      "Content-Type: application/json"
					      ]
					    ]);
					    
					    $response4 = curl_exec($curl4);
					    $err4 = curl_error($curl4);
					    
					    curl_close($curl4);
					    
					    $itemcode = "";
					    $itemname = "";
					    $kode_pl = "";
					    $hrg_pl = "";
					    $base_pl = "";
					    $price1 = "";
					    $price2 = "";
					    $price3 = "";
					    $price4 = "";
					    
					    if ($err4) {
					      echo "cURL Error #:" . $err4;
					    } else {
					      
					      $data4x = json_decode($response4,true);
					      $data41 = json_decode($response4);
					      
					    }
				  }




            for ($i=0;$i<count($data4x['value']);$i++){
            	if ($tipe == 2){
		    				$itemcode = $data41->value[$i]->ItemCode;
		            $itemname = $data41->value[$i]->ItemName;
		            $itemname = str_replace("\"","",$itemname);
		    			} else {
		    				if($tipesales == 2){
					    		$itemcode = $data41->value[$i]->Items->ItemCode;
			            $itemname = $data41->value[$i]->Items->ItemName;
			            $itemname = str_replace("\"","",$itemname);
			            $profil = $data41->value[$i]->Items->U_IT_Profil;
			            $tebal = $data41->value[$i]->Items->U_IT_Tebal;
			            $az = $data41->value[$i]->Items->U_HR_AZ;
			            $batik = $data41->value[$i]->Items->Properties1;
			            $warna = $data41->value[$i]->Items->Properties2;
			            $radial = $data41->value[$i]->Items->Properties3;
			            $crimping = $data41->value[$i]->Items->Properties4;
			            $nokcrimping = $data41->value[$i]->Items->Properties5;
			            $upcloser = $data41->value[$i]->Items->Properties6;
			            $endcloser = $data41->value[$i]->Items->Properties7;
			            $endstopper = $data41->value[$i]->Items->Properties8;
			            $pu = $data41->value[$i]->Items->Properties9;
			            $pe = $data41->value[$i]->Items->Properties10;
			            $flasing = $data41->value[$i]->Items->Properties11;
			            $flasing300 = $data41->value[$i]->Items->Properties12;
			            $flasing450 = $data41->value[$i]->Items->Properties13;
			            $flasing600 = $data41->value[$i]->Items->Properties14;
			            $plat300 = $data41->value[$i]->Items->Properties15;
			            $plat450 = $data41->value[$i]->Items->Properties16;
			            $plat600 = $data41->value[$i]->Items->Properties17;
			            $code = $data41->value[$i]->U_HR_GRP_PROFILE->Code;
			            $nama = $data41->value[$i]->U_HR_GRP_PROFILE->Name;
			            $grpname = $data41->value[$i]->U_HR_GRP_PROFILE->U_GrpName;

			            if($warna == 'Y'){$jenis = "WARNA";}else{$jenis = "NATUR";}
			            $const = number_format((($tebal*100)/100),2);
			            $kodetebal = str_replace(".","",(string)$const);
			            $pricekode = $grpname.'-'.$jenis.'#'.$az.'#'.$kodetebal;
			            

			            $harga1 = "";
			            $harga2 = "";
			            $harga3 = "";
			            $hargahet = "";

			            $harga1x = "";
			            $harga2x = "";
			            $harga3x = "";
			            $hargahetx = "";

			            $harga1xx = "";
			            $harga2xx = "";
			            $harga3xx = "";
			            $hargahetxx = "";

			            for ($j=0;$j<count($data4xx['value']);$j++){                                      				
								      $kodepl = $data4x1->value[$j]->Code;
			                if ($pricekode == $kodepl){
			                        $harga1 = $data4x1->value[$j]->U_Harga1;
			                        $harga2 = $data4x1->value[$j]->U_Harga2;
			                        $harga3 = $data4x1->value[$j]->U_Harga3;
			                        $hargahet = $data4x1->value[$j]->U_HET;
			                        

			                        $harga1x = (float)$harga1;
			                        $harga2x = (float)$harga2;
			                        $harga3x = (float)$harga3;
			                        $hargahetx = (float)$hargahet;

			                        $harga1xx = (float)$harga1;
			                        $harga2xx = (float)$harga2;
			                        $harga3xx = (float)$harga3;
			                        $hargahetxx = (float)$hargahet;
			                

				              if ($batik == 'Y'){
				                  $harga1xx = (int)$harga1x+1000;
				                  $harga2xx = (int)$harga2x+1000;
				                  $harga3xx = (int)$harga3x+1000;
				                  $hargahetxx = (int)$hargahetx+1000;
				              }
				              if ($radial == 'Y'){
				                  $harga1xx = (int)$harga1x+20000;
				                  $harga2xx = (int)$harga2x+20000;
				                  $harga3xx = (int)$harga3x+20000;
				                  $hargahetxx = (int)$hargahetx+20000;
				              }
				              if ($crimping == 'Y'){
				                  $harga1xx = (int)$harga1x+20000;
				                  $harga2xx = (int)$harga2x+20000;
				                  $harga3xx = (int)$harga3x+20000;
				                  $hargahetxx = (int)$hargahetx+20000;
				              }
				              if ($pu == 'Y'){
				                  $harga1xx = (int)$harga1x+170000;
				                  $harga2xx = (int)$harga2x+170000;
				                  $harga3xx = (int)$harga3x+170000;
				                  $hargahetxx = (int)$hargahetx+170000;
				              }
				              if ($pe == 'Y'){
				                  $harga1xx = (int)$harga1x+50000;
				                  $harga2xx = (int)$harga2x+50000;
				                  $harga3xx = (int)$harga3x+50000;
				                  $hargahetxx = (int)$hargahetx+50000;
				              }
				              
				              if ($flasing == 'Y' || $nokcrimping == 'Y'){                  
				                  $harga1xx = round((int)$harga1x*1.3);
				                  $harga2xx = round((int)$harga2x*1.3);
				                  $harga3xx = round((int)$harga3x*1.3);
				                  $hargahetxx = round((int)$hargahetx*1.3);
				              }
				              if ($flasing450 == 'Y' || $flasing600 == 'Y' || $plat450 == 'Y' || $plat600 == 'Y'){
				                  $harga1xx = round((int)$harga1x/2);
				                  $harga2xx = round((int)$harga2x/2);
				                  $harga3xx = round((int)$harga3x/2);
				                  $hargahetxx = round((int)$hargahetx/2);
				              }
				              if ($flasing300 == 'Y' || $flasing300 == 'Y'){
				                  $harga1xx = round((int)$harga1x/3);
				                  $harga2xx = round((int)$harga2x/3);
				                  $harga3xx = round((int)$harga3x/3);
				                  $hargahetxx = round((int)$hargahetx/3);
				              }
				          }

				        }
				    } else {
			            $itemcode = $data41->value[$i]->ItemCode;
			            $itemname = $data41->value[$i]->ItemName;
			            $itemname = str_replace("\"","",$itemname);
	            	}
	                    
	            }


                
                    
			
         ?>
                <tr>
                    <td><?php echo $itemcode; ?></td>
                    <td><?php echo $itemname; ?></td>
                    <?php if($tipesales == 2){ ?>
                    <td> 
                    
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat1" name="tombol_pilihobat1" data-dismiss="modal"
                            data-kode="<?php echo $itemcode; ?>"
                            data-nama="<?php echo $itemname; ?>"
                            data-harga="<?php echo round(((int)$harga1xx/1.11),2); ?>"
                        > <?php if(($harga1 <> null)){echo number_format(((int)$harga1xx/1.11),2);}else{echo "0";} ?>
                    </button>
                    </td>
                    <td>
                    
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat2" name="tombol_pilihobat2" data-dismiss="modal"
                            data-kode="<?php echo $itemcode; ?>"
                            data-nama="<?php echo $itemname; ?>"
                            data-harga="<?php echo round(((int)$harga2xx/1.11)); ?>"
                        > <?php if(($harga2 <> null)){echo number_format(((int)$harga2xx/1.11),2);}else{echo "0";} ?>
                    </button>
                    </td>
                    <td>
                    
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat3" name="tombol_pilihobat3" data-dismiss="modal"
                            data-kode="<?php echo $itemcode; ?>"
                            data-nama="<?php echo $itemname; ?>"
                            data-harga="<?php echo round(((int)$harga3xx/1.11)); ?>"
                        > <?php if(($harga3 <> null)){echo number_format(((int)$harga3xx/1.11),2);}else{echo "0";} ?>
                    </button>
                    </td>
                    <td>
                    
                    <button class="btn-transition btn btn-outline-dark btn-sm " title="pilih" id="tombol_pilihobat4" name="tombol_pilihobat4" data-dismiss="modal"
                            data-kode="<?php echo $itemcode; ?>"
                            data-nama="<?php echo $itemname; ?>"
                            data-harga="<?php echo round(((int)$hargahetxx/1.11)); ?>"
                        > <?php if(($hargahet <> null)){echo number_format(((int)$hargahetxx/1.11),2);}else{echo "0";} ?>
                    </button>
                    </td>
                    <?php } else { ?>
                    <td class="td-opsi">                        
                        <button class="btn-transition btn btn-outline-dark btn-sm" title="pilih" id="tombol_pilihobat" name="tombol_pilihobat" data-dismiss="modal"
                            data-kode="<?php echo $itemcode; ?>"
                            data-nama="<?php echo $itemname; ?>"
                            data-harga="<?php echo $data['hrg_obat']; ?>"
                            data-satuan="<?php echo $data['sat_obat']; ?>"
                            data-stok="<?php echo $data['stok']; ?>"
                            data-exp="<?php echo $data['tgl_exp']; ?>"
                        > <b>pilih</b>
                        </button>
                    </td>
                    <?php } ?>
                </tr>
         <?php 
     			//}
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

<script>
$(document).ready(function() {
    
    var count = 0;
    var total_penjualan = 0;

        var kodebrg = new Array();
        var namabrg = new Array();
        var pjbrg = new Array();
        var lbrbrg = new Array();
        var qtybrg = new Array();
        var hrgbrg = new Array();
        var totbrg = new Array();

    $('.datepicker').datepicker({
        format : "yyyy-mm-dd",
        orientation: "bottom left",
        todayBtn: "linked",
        autoclose: true,
        language: "id",
        todayHighlight: true
    });


    $("button[name='ubah']").click(function() {
        var row_id = $(this).attr("id");
        
        $("#rows").val(row_id);        
        
    });

    $("button[name='tombol_pilihobat']").click(function() {
        var kode = $(this).data('kode');
        var nama = $(this).data('nama');
        var row_id = document.getElementById('rows').value;
                
        $("#td_nmobat"+row_id).val(nama);
        $("#td_kd_obat"+row_id).val(kode);
        $("#td_satobat"+row_id).val(harga);
        
        
    });

    $("button[name='tombol_pilihobat1']").click(function() {
        var kode = $(this).data('kode');
        var nama = $(this).data('nama');
        var harga = $(this).data('harga');
        
        
        var row_id = document.getElementById('rows').value;
        
        $("#td_nmobat"+row_id).val(nama);
        $("#td_kd_obat"+row_id).val(kode);
        $("#td_satobat"+row_id).val(harga);
        
        
        
    });
    $("button[name='tombol_pilihobat2']").click(function() {
        var kode = $(this).data('kode');
        var nama = $(this).data('nama');
        var harga = $(this).data('harga');
        
        var row_id = document.getElementById('rows').value;
        
        
        $("#td_nmobat"+row_id).val(nama);
        $("#td_kd_obat"+row_id).val(kode);
        $("#td_satobat"+row_id).val(harga);
        
        
    });
    $("button[name='tombol_pilihobat3']").click(function() {
        var kode = $(this).data('kode');
        var nama = $(this).data('nama');
        var harga = $(this).data('harga');
        
        var row_id = document.getElementById('rows').value;
        
        $("#td_nmobat"+row_id).val(nama);
        $("#td_kd_obat"+row_id).val(kode);
        $("#td_satobat"+row_id).val(harga);
        
        
    });
    $("button[name='tombol_pilihobat4']").click(function() {
        var kode = $(this).data('kode');
        var nama = $(this).data('nama');
        var harga = $(this).data('harga');
        
        var row_id = document.getElementById('rows').value;
        
        $("#td_nmobat"+row_id).val(nama);
        $("#td_kd_obat"+row_id).val(kode);
        $("#td_satobat"+row_id).val(harga);
        
        
    });

    $("button[name='tombol_pilihcus']").click(function() {
        var kodecus = $(this).data('kodecus');
        var namacus = $(this).data('namacus');
        var alamat = $(this).data('alamat');
        

        $("#kode_cus").val(kodecus);
        $("#nama_cus").val(namacus);
        $("#alamat").val(alamat);
        
    });

    $("button[name='tombol_pilihgudang']").click(function() {
        var kodegudang = $(this).data('kodegudang');
        var namagudang = $(this).data('namagudang');
        

        $("#kode_gudang").val(kodegudang);
        $("#nama_gudang").val(namagudang);
        
    });

    $("button[name='tombol_pilihproject']").click(function() {
        var kodeproject = $(this).data('kodeproject');
        var namaproject = $(this).data('namaproject');
        

        $("#kode_project").val(kodeproject);
        $("#nama_project").val(namaproject);
        
    });

    

    $("#kode_obat").keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            alert();
        }
    })

    $("#kode_cus").keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            alert();
        }
    })

    $("#kode_gudang").keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            alert();
        }
    })

    $("#kode_project").keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            alert();
        }
    })

    $("#kode_sales").keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            alert();
        }
    })

    

    $(document).on("click", ".input_diskon", function() {
      
      var diskon = document.getElementById('hidden_totaldiskon').value;
      var dispercent = document.getElementById('total_diskon').value;

        dispercnt = Number((diskon/total_penjualan)*100);
        dishrg = Number((dispercent*total_penjualan)/100);
        total_penjualan = Number(total_penjualan-subtotal);
        total_penjualanx = Number(total_penjualan-diskon);
        
        
        $("#total_diskon").val(dispercnt);
        $("#hidden_totaldiskon").val(dishrg);
        $("#total_penjualan").text(total_penjualan);
        $("#hidden_totalpenjualan").val(total_penjualan);
        $("#hidden_totalpenjualanppn").val(total_penjualanx);
    });

    

    

    $("#form_copyorders").on("submit", function(event){
        event.preventDefault();
        
        var jenistrans = $("#jenistrans").val();
        var tglsales = $("#tglsales").val();
        var tgldelivery = $("#tgldelivery").val();
        var kode_cus = $("#kode_cus").val();
        var nama_cus = $("#nama_cus").val();
        var terms = $("#terms").val();
        var cus_ref = $("#cus_ref").val();
        var alamat = $("#alamat").val();
        var kode_gudang = $("#kode_gudang").val();
        var kode_project = $("#kode_project").val();
        var remark = $("#remark").val();
        var idpeg = '<?php echo $idpeg;?>';
        var total_diskon = document.getElementById('total_diskon').value;
        var kontak = $("#kontak").val();

        if(tgldelivery=="") {
            document.getElementById("tgldelivery").focus();
            Swal.fire(
              'Data Belum Lengkap',
              'maaf, tolong isi tanggal valid until',
              'warning'
            )
        } else 
        if(kode_cus==""){
            document.getElementById("kode_cus").focus();
            Swal.fire(
              'Data Belum Lengkap',
              'maaf, tolong isi nama customer',
              'warning'
            )
        } else if(alamat==""){
            document.getElementById("alamat").focus();
            Swal.fire(
              'Data Belum Lengkap',
              'maaf, anda belum mengisi alamat kirim',
              'warning'
            )
        } else if(kode_gudang==""){
            document.getElementById("kode_gudang").focus();
            Swal.fire(
              'Data Belum Lengkap',
              'maaf, anda belum mengisi gudang',
              'warning'
            )
        } else if(kode_project==""){
            document.getElementById("kode_project").focus();
            Swal.fire(
              'Data Belum Lengkap',
              'maaf, anda belum mengisi project',
              'warning'
            )
        } 
        
        else {
            Swal.fire({
              title: 'Copy To Orders ?',
              text: 'apakah anda yakin ingin copy quotations ke orders ',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya'
            }).then((simpan) => {
              $(this).find(':input[type=submit]').attr('disabled','disabled');
              if (simpan.value) {
                var count_data = 0;

                $(".td_kd_obat").each(function(){
                    count_data = count_data + 1;
                });                
                if(count_data > 0) {
                    var form_data = $(this).serialize();
                    $.ajax({
                        url: "ajax/copy_quotation_orders.php",
                        method: "POST",
                        data: form_data,
                        success:function(data) {
                            Swal.fire({
                              title: 'Copy to Orders Berhasil',
                              text: 'Data SQ Berhasil Dicopy ke Orders',
                              type: 'success',
                              confirmButtonColor: '#3085d6',
                              confirmButtonText: 'OK'
                            }).then((ok) => {
                              document.getElementById("copytoorders").removeAttribute("disabled"); 
                              if (ok.value) {
                                
                                window.open("laporan/?page=nota_penjualan&no_pjl="+no_penjualan);
                                location.reload();
                              }
                            })
                        }
                    })
                } else {
                    alert();
                }
              }
            })
        }
    });
});
</script>

