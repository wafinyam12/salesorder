<?php
	
	$db = $_SESSION['posisi_peg'];
  $tipe = $_SESSION['session_id'];
  $tipesales = $_SESSION['session_tipe'];
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

	//Load Delivery Orders
  $ordr = $_GET['no_dln'];
	$endpoint = 'https://172.16.226.2:50000/b1s/v1/DeliveryNotes('.$ordr.')';
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

	
	if ($err2) {
		echo "cURL Error #:" . $err2;
	} else {
		
		$data2x = json_decode($response2,true);
		$data21 = json_decode($response2);
        
    $docentry = $data21->DocEntry;
	$docnum = $data21->DocNum;
    $docstatus = $data21->DocumentStatus;
    $docdate = $data21->DocDate;
    $docduedate = $data21->DocDueDate;
    $cardcode = $data21->CardCode;
    $cardname = $data21->CardName;
    $alamat = $data21->Address2;
    $cus_ref = $data21->NumAtCard;
    $kodegudang = $data21->DocumentLines[0]->WarehouseCode;
    
    $kodeproject = $data21->DocumentLines[0]->ProjectCode;
    
    $remark = $data21->Comments;
    $doctotal = $data21->DocTotal;
    $dispercent = $data21->DiscountPercent;
    $distot = $data21->TotalDiscount;

    $tgldelivery=date_create($docdate);
    
	}
    
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-align-left"></i> Delivery Orders</li>
  </ol>
</nav>

<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>Detail Delivery Orders</h4></div>
		<div class="col-6 text-right">
			<a href="?page=datadelivery">
				<button class="btn btn-sm btn-info">Data Delivery Order</button>
			</a>
		</div>
	</div>
	<div class="form-container">
		<div class="row" style="padding: 0 12px;">
			<div class="col-md-12 vertical-form">
				

        <form method="post" id="form_penjualan" autocomplete="off">
                <div class="position-relative form-group" style="text-align: left; ">                
                <table width="100%" border="0" style="font-family:arial,tahoma; font-size: 14px;">
                
                    <tr><td width="40%">No Delivery </td><td width="2%"></td><td><input class="" size="12" type="hidden" name="no_entry" value="<?php echo $ordr; ?>" id=""><input class="" size="12" type="text" name="no_order" value="<?php echo $docnum; ?>" id="" disabled=""></td></tr>
                    <tr><td width="40%">Status </td><td width="2%"></td><td><input class="" size="12" type="text" name="st_order" value="<?php if ($docstatus == "bost_Open"){echo "OPEN";}else{echo "CLOSED";} ?>" id="" disabled=""></td></tr>
                    <tr><td width="40%">Tgl Delivery </td><td width="2%"></td><td> <b><input class="" size="10" type="text" name="tgldelivery" value="<?php echo date_format($tgldelivery,"d/m/Y"); ?>" id="tgldelivery" disabled=""></b></td></tr>
                    <tr><td colspan="3"><h6></td></tr>
                    <tr><td width="40%">Customer </td><td width="2%"></td>
                                    <td> <b>
                                    
                                        <input type="text" class="form-control form-control-sm" data-toggle="" data-target="" rows="2" name="kode_cus" id="kode_cus" value="<?php echo $cardcode; ?>" disabled="">
                                        </b> 
                                    </td></tr>
                    <tr><td width="40%">Nama </td><td width="2%"></td><td> <b><textarea class="form-control form-control-sm" name="nama_cus" id="nama_cus" disabled=""><?php echo $cardname; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Cust Ref. No. </td><td width="5%"></td><td> <b><input type="text" class="form-control form-control-sm" name="cus_ref" id="cus_ref" value="<?php echo $cus_ref; ?>" disabled=""></b> </td></tr>
                    <tr><td width="40%">Alamat Kirim </td><td width="5%"></td><td> <b><textarea class="form-control form-control-sm" rows="2" name="alamat" id="alamat" disabled=""><?php echo $alamat; ?></textarea></b> </td></tr>
                    <tr><td colspan="3"><h6></td></tr>
                    <tr><td width="40%">Gudang </td><td width="2%"></td><td> <b>
                        <input type="hidden" class="form-control form-control-sm" name="kode_gudang" id="kode_gudang" value="<?php echo $kodegudang; ?>" disabled="">
                        <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_datagudang" rows="2" name="nama_gudang" id="nama_gudang" disabled=""><?php echo $kodegudang; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Project </td><td width="2%"></td><td> <b>
                        <input type="hidden" class="form-control form-control-sm" name="kode_project" id="kode_project" value="<?php echo $kodeproject; ?>" disabled="">
                        <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_dataproject" rows="2" name="nama_project" id="nama_project" disabled=""><?php echo $kodeproject; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Sales </td><td width="2%"></td><td> <b>
                        
                    <input type="text" class="form-control form-control-sm" value="<?php echo $nmpeg; ?>" name="nama_sales" id="nama_sales" disabled=""></b> </td></tr>    
                    <tr><td width="40%">Remark </td><td width="5%"></td><td> <b><textarea class="form-control form-control-sm" rows="2" name="remark" id="remark" disabled=""><?php echo $remark; ?></textarea></b> </td></tr>
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
                                        <?php } ?>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right">Open Qty</th>                                        
                                        <th class="text-right" width="15%">Harga<br>(non PPN)</th>
                                        <th class="text-right" width="15%">Total<br>(inc PPN)</th>
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
                                          $qty = $data21->DocumentLines[$i]->Quantity;
                                          $openqty = $data21->DocumentLines[$i]->RemainingOpenQuantity;
                                          $hrg_sat = $data21->DocumentLines[$i]->Price;
                                          $subtotal = $data21->DocumentLines[$i]->LineTotal;
                                          $subtotalppn = $data21->DocumentLines[$i]->GrossTotal;
                                          $totalppn = $totalppn + $subtotalppn;
                                          $count = $line+1;
                                          
                                          
                                          ?>
                                          <tr id="row_<?php echo $count; ?>">
                                          <?php if ($tipe == "1") { ?>
                                            <td class="text-left"><?php echo $kd_item; ?></td>
                                            <td class="text-left"><?php echo $nm_item; ?></td>
                                            <td class="text-right"><?php echo number_format($pj_item,2); ?></td>
                                            <td class="text-right"><?php echo number_format($lbr_item,2); ?></td>
                                            <td class="text-right"><?php echo number_format($qty,2); ?></td>
                                          <?php } else { ?>
                                            <td class="text-left"><?php echo $kd_item; ?></td>
                                            <td class="text-left"><?php echo $nm_item; ?></td>
                                            <td class="text-right"><?php //echo $pj_item; ?> </td>
                                            <td class="text-right"><?php //echo $lbr_item; ?> </td>
                                            <td class="text-right"><?php echo number_format($qty,2); ?></td>
                                          <?php } ?>
                                          <td class="text-right"><?php //echo $qty; ?> <?php echo number_format($qty-$openqty,2,".",","); ?></td>                                         
                                          <?php if($tipesales == 1){ ?>
                                            <td class="text-right"><?php echo "Rp ".number_format($hrg_sat,2); ?></td>
                                          <?php } else { ?>
                                            <td class="text-right"><?php echo "Rp ".number_format($hrg_sat,2); ?></td>
                                          <?php } ?>
                                            <td class="text-right"><?php echo "Rp ".number_format($subtotalppn,2); ?></td>
                                          
                                          </tr>
                                        <?php
                                        } 
                                    ?>
                                </tbody>
                                <tfoot>
                                    
                                    <tr class="baris_total" style="">
                                        <td colspan="7" class="text-right" style="font-weight: bold;">Sub Total (exc PPN) : </td><td class="text-right"><?php echo "Rp ".number_format(round(($totalppn/1.11),2),2); ?></td>
                                        
                                    </tr>
                                    <tr class="baris_total" style="">
                                        <td colspan="7" class="text-right" style="font-weight: bold;">Discount : </td><td class="text-right"><?php echo "Rp ".number_format($distot,2); ?></td>
                                        
                                    </tr>
                                    
                                    <tr class="baris_total" style="">
                                        <td colspan="7" class="text-right" style="font-weight: bold;">Total (inc PPN) : </td><td class="text-right"><?php echo "Rp ".number_format($doctotal,2); ?></td>
                                        
                                    </tr>
                                </tfoot>
                                
                            </table>
                            
                    	</div>
                    </div>
                    <div class="text-right tombol-kanan">
                    
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

