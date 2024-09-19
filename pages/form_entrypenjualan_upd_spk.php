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

	//Load Sales Orders
  $ordr = $_GET['no_ord'];
	$endpoint = 'https://172.16.226.2:50000/b1s/v1/Orders('.$ordr.')';
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
    $payterms = $data21->PaymentGroupCode;
    
	}
    
    
	
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-align-left"></i> Form Create Production Order</li>
  </ol>
</nav>

<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>CREATE Production Order</h4></div>
		<div class="col-6 text-right">
			<a href="?page=dataspk">
				<button class="btn btn-sm btn-info">Data Sales Order</button>
			</a>
		</div>
	</div>
	<div class="form-container">
		<div class="row" style="padding: 0 12px;">
			<div class="col-md-12 vertical-form">
				<h6><i class="fas fa-list-alt"></i> Lengkapi form production order dibawah ini.</h6>

        <form method="post" id="form_penjualan" autocomplete="off">
                <div class="position-relative form-group" style="text-align: left; ">                
                <table width="100%" border="0" style="font-family:arial,tahoma; font-size: 14px;">
                
                    <tr><td width="40%">No Trans </td><td width="2%"></td><td><input class="" size="12" type="hidden" name="no_entry" value="<?php echo $ordr; ?>" id=""><input class="" size="12" type="text" name="no_order" value="<?php echo $docnum; ?>" id="" readonly></td></tr>
                    <tr><td width="40%">Status </td><td width="2%"></td><td><input class="" size="12" type="text" name="st_order" value="<?php if ($docstatus == "bost_Open"){echo "OPEN";}else{echo "CLOSED";} ?>" id="" readonly></td></tr>
                    <tr><td width="40%">Tgl Orders </td><td width="2%"></td><td> <font color="blue"><b><input class="" size="10" type="date" name="tglsales" value="<?php echo $docdate; ?>" id="tglsales" readonly></b></font></td></tr>
                    <tr><td width="40%">Tgl Delivery </td><td width="2%"></td><td> <font color="blue"><b><input class="" size="10" type="date" name="tgldelivery" value="<?php echo $docduedate; ?>" id="tgldelivery" readonly></b></font></td></tr>
                    <tr><td colspan="3"><h6></td></tr>
                    <tr><td width="40%">Customer </td><td width="2%"></td>
                                    <td> <b>
                                    
                                        <input type="text" class="form-control form-control-sm" data-toggle="" data-target="" rows="2" name="kode_cus" id="kode_cus" value="<?php echo $cardcode; ?>" readonly>
                                        </b> 
                                    </td></tr>
                    <tr><td width="40%">Nama </td><td width="2%"></td><td> <b><textarea class="form-control form-control-sm" name="nama_cus" id="nama_cus" readonly><?php echo $cardname; ?></textarea></b> </td></tr>                    
                    <tr><td width="40%">Cust Ref. No. </td><td width="5%"></td><td> <b><input type="text" class="form-control form-control-sm" name="cus_ref" id="cus_ref" value="<?php echo $cus_ref; ?>" readonly></b> </td></tr>
                    <tr><td width="40%">Alamat Kirim </td><td width="5%"></td><td> <b><textarea class="form-control form-control-sm" rows="2" name="alamat" id="alamat" readonly><?php echo $alamat; ?></textarea></b> </td></tr>
                    <tr><td colspan="3"><h6></td></tr>
                    <tr><td width="40%">Gudang </td><td width="2%"></td><td> <b>
                        <input type="hidden" class="form-control form-control-sm" name="kode_gudang" id="kode_gudang" value="<?php echo $kodegudang; ?>" readonly>
                        <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_datagudang" rows="2" name="nama_gudang" id="nama_gudang" readonly><?php echo $kodegudang; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Project </td><td width="2%"></td><td> <b>
                        <input type="hidden" class="form-control form-control-sm" name="kode_project" id="kode_project" value="<?php echo $kodeproject; ?>" readonly>
                        <textarea class="form-control form-control-sm" data-toggle="modal" data-target="#modal_dataproject" rows="2" name="nama_project" id="nama_project" readonly><?php echo $kodeproject; ?></textarea></b> </td></tr>
                    <tr><td width="40%">Sales </td><td width="2%"></td><td> <b>
                        
                    <input type="text" class="form-control form-control-sm" value="<?php echo $nmpeg; ?>" name="nama_sales" id="nama_sales" readonly></b> </td></tr>    
                    <tr><td width="40%">Remark </td><td width="5%"></td><td> <b><textarea class="form-control form-control-sm" rows="2" name="remark" id="remark" readonly><?php echo $remark; ?></textarea></b> </td></tr>
                </table>
                </div>
                		<div class="row kotak-form-tabel-penjualan">
                    	<div class="col-md-12 kotak-tabel-obat-terjual" id="table-scroll">
                            <table class="table display tabel-data" style="border-collapse: collapse; width: 100%; border: 0px solid black; font-family:arial,tahoma; font-size: 12px;">
                                <thead style="border-collapse: collapse; width: 100%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
                                    <tr>
                                        <th class="text-left">Kode</th>
                                        <th class="text-left">Nama</th>
                                        <?php if ($tipe == "1") { ?>
                                        <th class="text-right">Pjg</th>
                                        <th class="text-right">Lbr</th>
                                        <?php } else { ?>
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>
                                    	<?php } ?>
                                        <th class="text-right">Qty</th>                                        
                                        <?php if ($tipe == "1") { ?>
                                        <th class="text-center">Nomer SPK</th>
                                        <th class="text-center">BOM</th>
                                        <th class="text-center">Input Produksi</th>
                                        <th class="text-center">Remark Produksi</th>
                                        <?php } else { ?>
                                        <th class="text-right"></th> 
                                        <th class="text-right"></th>
                                        <th class="text-right"></th>                                       
                                    	<?php } ?>
                                        
                                        <th class="text-center">Otomatis</th>
                                    </tr>
                                </thead>
                                <tbody id="" style="border-collapse: collapse; width: 100%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
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
                                          

                                          $curl8 = curl_init();
                                          $curl9 = curl_init();
                                          //Load SPK Production                                          
                                          $endpointspk = 'https://172.16.226.2:50000/b1s/v1/ProductionOrders';
                                          $varspk = array('$filter' => 'ProductionOrderStatus ne \'boposCancelled\' and ProductionOrderOriginEntry eq '.$docentry.' and ItemNo eq \''.$kd_item.'\' and PlannedQuantity eq '.$qty.' and U_IT_Panjang eq '.$pj_item.'','$orderby' => 'AbsoluteEntry asc');
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
                                          
                                          if ($err2spk) {
                                            echo "cURL Error #:" . $err2spk;
                                          } else {
                                            
                                            $data2xspk = json_decode($response2spk,true);
                                            $data21spk = json_decode($response2spk);
                                            
                                            if (count($data2xspk['value']) > 0){
                                              $spknum = $data21spk->value[0]->DocumentNumber;
                                              $nospk = $spknum;
                                            }
                                          
                                          }

                                          //Load BOM Production
                                          $endpointbom = 'https://172.16.226.2:50000/b1s/v1/ProductTrees';
                                          $varbom = array('$filter' => 'TreeType eq \'iProductionTree\' and TreeCode eq \''.$kd_item.'\'');
                                          $urlbom = $endpointbom . '?' . http_build_query($varbom);                                       
                                          curl_setopt_array($curl9, [
                                            CURLOPT_URL => $urlbom,
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
                                          
                                          $response2bom = curl_exec($curl9);
                                          $err2bom = curl_error($curl9);
                                          
                                          curl_close($curl9);
                                          
                                          $bomcode = "";   
                                          $bomname = "";                             
                                          $nobom = "-";
                                          
                                          if ($err2bom) {
                                            echo "cURL Error #:" . $err2bom;
                                          } else {
                                            
                                            $data2xbom = json_decode($response2bom,true);
                                            $data21bom = json_decode($response2bom);
                                            
                                            if (count($data2xbom['value']) > 0){
                                              $bomcode = $data21bom->value[0]->ProductTreeLines[0]->ItemCode;
                                              $bomname = $data21bom->value[0]->ProductTreeLines[0]->ItemName;
                                              $nobom = $bomcode;
                                            }
                                          
                                          }
                                          ?>
                                          <tr id="row_<?php echo $count; ?>" style="border-collapse: collapse; width: 100%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
                                          <?php if ($tipe == "1") { ?>
                                            <td valign="top" class="text-left"> <textarea style="background-color: #C0C0C0;" name="hidden_kdbrg<?php echo $count; ?>" id="td_kd_obat<?php echo $count; ?>" class="td_kd_obat" value="<?php echo $kd_item; ?>" rows="2" cols="25" readonly><?php echo $kd_item; ?></textarea></td>
                                            <td valign="top" class="text-left"> <textarea style="background-color: #C0C0C0;" name="hidden_nmbrg<?php echo $count; ?>" id="td_nmobat<?php echo $count; ?>" class="td_nmobat" value="" rows="3" cols="40" readonly><?php echo $nm_item; ?></textarea></td>
                                            <td valign="top" class="text-right"> <input style="background-color: #C0C0C0;" type="text" name="hidden_pjbrg<?php echo $count; ?>" id="td_pjobat<?php echo $count; ?>" class="text-right" size="6" value="<?php echo number_format($pj_item,2); ?>" readonly></td>
                                            <td valign="top" class="text-right"> <input style="background-color: #C0C0C0;" type="text" name="hidden_lbrbrg<?php echo $count; ?>" id="td_lbrobat<?php echo $count; ?>" class="text-right" size="6" value="<?php echo number_format($lbr_item,2); ?>" readonly></td>
                                            <td valign="top" class="text-right"> <input style="background-color: #C0C0C0;" type="text" name="hidden_qty<?php echo $count; ?>" id="td_jmlobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo number_format($qty,2); ?>" step="0.01" readonly></td>
                                          <?php } else { ?>
                                            <td valign="top" class="text-left"> <textarea style="background-color: #C0C0C0;" name="hidden_kdbrg<?php echo $count; ?>" id="td_kd_obat<?php echo $count; ?>" class="td_kd_obat" value="<?php echo $kd_item; ?>" rows="2" cols="25" readonly><?php echo $kd_item; ?></textarea></td>
                                            <td valign="top" class="text-left"> <textarea style="background-color: #C0C0C0;" name="hidden_nmbrg<?php echo $count; ?>" id="td_nmobat<?php echo $count; ?>" class="td_nmobat" value="" rows="3" cols="40" readonly><?php echo $nm_item; ?></textarea></td>
                                            <td valign="top" class="text-right"></td>
                                            <td valign="top" class="text-right"></td>
                                            <td valign="top" class="text-right"> <input style="background-color: #C0C0C0;" type="number" name="hidden_qty<?php echo $count; ?>" id="td_jmlobat<?php echo $count; ?>" class="text-right" size="8" value="<?php echo number_format($qty,2); ?>" step="any" readonly></td>
                                          <?php } ?>
                                          
                                          <td valign="top" class="text-right"> <?php echo $nospk; ?></td>
                                          <td valign="top" class="text-right"> <?php echo "<font color=green>".$nobom."<br>".$bomname."</font>"; ?></td>
                                          <td valign="top" class="text-right">
                                            <input style="background-color: #C0C0C0;" type="date" name="hidden_tglspk<?php echo $count; ?>" id="td_tglspk<?php echo $count; ?>" class="text-right" size="6" value="<?php echo date('Y-m-d'); ?>" readonly>
                                            <br><br><font color="red">Start Date </font><input type="date" name="hidden_tglstart<?php echo $count; ?>" id="td_tglstart<?php echo $count; ?>" class="text-right" size="6" value="">
                                            <font color="red">Due Date </font><input type="date" name="hidden_tgldue<?php echo $count; ?>" id="td_tgldue<?php echo $count; ?>" class="text-right" size="6" value="">
                                            <br><br>
                                          </td>
                                          <td valign="top" class="text-right"><textarea name="hidden_remark<?php echo $count; ?>" id="td_remark<?php echo $count; ?>" class="td_remark" value="" rows="7" cols="40" ></textarea><br><br></td>
                                          <td valign="top" class="td-opsi">
                                            <select name="spk<?php echo $count; ?>" class="text-left" <?php if($nospk <> "-"){echo "disabled";}?>>
                                              <?php if ($nobom == "-" || $nobom == "" || $nospk <> "-"){?>
                                                <option value="0" selected>No</option>
                                              <?php } else { ?>  
                                              <option value="0" selected>No</option>
                                              <option value="1">Yes</option>
                                              <?php } ?>
                                            </select>
                                            <br><br>
                                          </td>
                                         
                                          </tr>
                                        <?php
                                        } 
                                    ?>
                                </tbody>
                                <tfoot>
                                <input type="hidden" style="background-color: #C0C0C0;" name="jml" class="text-right" size="8" value="<?php echo $count; ?>" readonly>
                                </tfoot>
                                
                            </table>
                            <div class="baris_total text-right" style="display: none;">
                                
                            </div>
                    	</div>
                    </div>
                    <div class="text-right tombol-kanan">
                    
                      <input type="submit" name="create_spk" id="create_spk" class="btn btn-info" value="Create SPK">
                    
                        
                    </div>
                </form>
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
    

    $("#form_penjualan").on("submit", function(event){
        event.preventDefault();
        
        var tglsales = $("#tglsales").val();
        var tgldelivery = $("#tgldelivery").val();
        var kode_cus = $("#kode_cus").val();
        var nama_cus = $("#nama_cus").val();
        
        var cus_ref = $("#cus_ref").val();
        var alamat = $("#alamat").val();
        var kode_gudang = $("#kode_gudang").val();
        var kode_project = $("#kode_project").val();
        var remark = $("#remark").val();
        var idpeg = '<?php echo $idpeg;?>';
        

        if(tgldelivery=="") {
            document.getElementById("tgldelivery").focus();
            Swal.fire(
              'Data Belum Lengkap',
              'maaf, tolong isi tanggal delivery',
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
              title: 'Create SPK Produksi ?',
              text: 'apakah anda ingin membuat SPK Produksi otomatis untuk Sales Order ini ',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya'
            }).then((simpan) => {
              if (simpan.value) {
                var count_data = 0;

                $(".td_kd_obat").each(function(){
                    count_data = count_data + 1;
                });
                if(count_data > 0) {
                    var form_data = $(this).serialize();
                    $.ajax({
                        url: "ajax/create_spk.php",
                        method: "POST",
                        data: form_data,
                        success:function(data) {
                            Swal.fire({
                              title: 'Berhasil',
                              text: 'Data SPK Produksi Berhasil Dibuat',
                              type: 'success',
                              confirmButtonColor: '#3085d6',
                              confirmButtonText: 'OK'
                            }).then((ok) => {
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

