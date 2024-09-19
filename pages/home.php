<?php
	//session_start();
	$db = $_SESSION['posisi_peg'];
	$user = $_SESSION['session_user'];
	$pass = $_SESSION['session_pass'];
	$idpeg = $_SESSION['id_peg'];

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

	//Sum Total Orders
	$endpoint = 'https://172.16.226.2:50000/b1s/v1/Orders';
	
	$params = array('$select' => 'SalesPersonCode,DocTotal,DocDate','$filter' => 'SalesPersonCode eq '.$idpeg.' and CancelStatus eq \'csNo\'');
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
		"Prefer:odata.maxpagesize=2000",
		"Content-Type: application/json"
		]
	]);
	
	$response2 = curl_exec($curl2);
	$err2 = curl_error($curl2);
	
	curl_close($curl2);
	
	$doctotal = "";
	$docdate = "";
	$total = 0;
	$totalbln = 0;
	$totalbln1 = 0;
	$totalbln2 = 0;
	$totalbln3 = 0;
	$totalbln4 = 0;
	$totalbln5 = 0;
	$totalbln6 = 0;
	$totalbln7 = 0;
	$totalbln8 = 0;
	$totalbln9 = 0;
	$totalbln10 = 0;
	$totalbln11 = 0;
	$totalbln12 = 0;
	
	
	if ($err2) {
		echo "cURL Error #:" . $err2;
	} else {
		
		$data2x = json_decode($response2,true);
		$data21 = json_decode($response2);
		
	}

	//Sum Total Orders Per Bulan
	
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-home"></i> Home</li>
  </ol>
</nav>
<div class="page-content">
	<div>
		<div class="col-12"><h5>Selamat datang <?php echo $_SESSION['nama_peg']; ?></h5></div>
	</div>
	<style>
		h6 {
			font-size: 14px;
		}
	</style>
	<div class="konten-home" style="margin-top: 25px;">
		<div class="row" style="margin-bottom: 18px;">
			<div class="col-lg-6">
				
				<div class="card text-white" style="background-color: #58898c;">
			      <div class="card-body" style="padding: 10px 20px;">
			        <h6 class="card-title">Total Sales Order</h6>
			        <div class="card-text" align="right" style="font-size: 34px; font-weight: lighter;">
					<?php 
						for ($i=0;$i<count($data2x['value']);$i++){
							$doctotal = $data21->value[$i]->DocTotal; 
							$total = $total + $doctotal;
						}

					?>
			        	Rp <?php echo number_format($total,2); ?>					
			        </div>
			        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
			      </div>
			    </div>
			</div>
			<div class="col-lg-6">
				
				<div class="card text-white" style="background-color: #527b87;">
			      <div class="card-body" style="padding: 10px 20px;">
			        <h6 class="card-title">Jumlah Sales Order Bulan ini</h6>
			        <div class="card-text" align="right" style="font-size: 34px; font-weight: lighter;">
					<?php 
						$bln = date('Y-m');
						
						for ($i=0;$i<count($data2x['value']);$i++){
							$doctotal = $data21->value[$i]->DocTotal; 
							$docdate = $data21->value[$i]->DocDate; 
							$blndt = new DateTime($docdate);
							$blnskr = $blndt->format('Y-m');
							if($bln == $blnskr){
								$totalbln = $totalbln + $doctotal;
							}							
						}
						

					?>
						Rp <?php echo number_format($totalbln,2); ?>
			        </div>
			        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
			      </div>
			    </div>
			</div>
		</div>


		<!-- OMSET SALES -->
		<div class="row" style="margin-bottom: 18px;">
		<div class="col-lg-12">
				
				<div class="card text-white" style="background-color: #527b87;">
			      <div class="card-body" style="padding: 10px 20px;">
			        <h6 class="card-title">Jumlah Sales Order per Bulan</h6>
			        <div class="card-text" align="right" style="font-size: 34px; font-weight: lighter;">
					<!-- LOADING -->  
    				<table id="" border="0" class="table table-striped display tabel-data">
                    <thead>
                        <tr>
                            <th bgcolor="white" class="text-center">No</th>
                            <th bgcolor="white" class="text-left">Bulan</th>
                            <th bgcolor="white" class="text-right">Total Order</th>
                            <th bgcolor="white" class="text-center">Opsi</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            
								$thn = date('Y');
								$bln1 = date('Y-m',strtotime($thn.'-01'));
								$bln2 = date('Y-m',strtotime($thn.'-02'));
								$bln3 = date('Y-m',strtotime($thn.'-03'));
								$bln4 = date('Y-m',strtotime($thn.'-04'));
								$bln5 = date('Y-m',strtotime($thn.'-05'));
								$bln6 = date('Y-m',strtotime($thn.'-06'));
								$bln7 = date('Y-m',strtotime($thn.'-07'));
								$bln8 = date('Y-m',strtotime($thn.'-08'));
								$bln9 = date('Y-m',strtotime($thn.'-09'));
								$bln10 = date('Y-m',strtotime($thn.'-10'));
								$bln11 = date('Y-m',strtotime($thn.'-11'));
								$bln12 = date('Y-m',strtotime($thn.'-12'));
								
								for ($i=0;$i<count($data2x['value']);$i++){
									$doctotal = $data21->value[$i]->DocTotal; 
									$docdate = $data21->value[$i]->DocDate; 
									$blndt = new DateTime($docdate);
									$blnskr = $blndt->format('Y-m');
									if($bln1 == $blnskr){
										$totalbln1 = $totalbln1 + $doctotal;
									} elseif($bln2 == $blnskr)  {
										$totalbln2 = $totalbln2 + $doctotal;
									} elseif($bln3 == $blnskr)  {
										$totalbln3 = $totalbln3 + $doctotal;
									} elseif($bln4 == $blnskr)  {
										$totalbln4 = $totalbln4 + $doctotal;
									} elseif($bln5 == $blnskr)  {
										$totalbln5 = $totalbln5 + $doctotal;
									} elseif($bln6 == $blnskr)  {
										$totalbln6 = $totalbln6 + $doctotal;
									} elseif($bln7 == $blnskr)  {
										$totalbln7 = $totalbln7 + $doctotal;
									} elseif($bln8 == $blnskr)  {
										$totalbln8 = $totalbln8 + $doctotal;
									} elseif($bln9 == $blnskr)  {
										$totalbln9 = $totalbln9 + $doctotal;
									} elseif($bln10 == $blnskr)  {
										$totalbln10 = $totalbln10 + $doctotal;
									} elseif($bln11 == $blnskr)  {
										$totalbln11 = $totalbln11 + $doctotal;
									} else  {
										$totalbln12 = $totalbln12 + $doctotal;
									}							
								}
							$nmbln = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
							for ($i=0;$i<12;$i++){
								
								$bl = $i+1;
								$bl2 = "";
								if (strlen($bl) == 1){
									$bl2 = "0".$bl;
								} else {
									$bl2 = $bl;
								}
                         ?>
                            <tr>
                                <td bgcolor="white" class="text-center"><font face="tahoma" color="black"><?php echo $i+1; ?>.</font></td>
                                <td bgcolor="white" class="text-left"><font face="tahoma" color="black"><?php echo $nmbln[$i]." ".$thn; ?></font></td> 
								<?php 
								if($i == 0){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln1,2)."</font></td>";
								} elseif($i == 1){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln2,2)."</font></td>";
								} elseif($i == 2){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln3,2)."</font></td>";
								} elseif($i == 3){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln4,2)."</font></td>";
								} elseif($i == 4){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln5,2)."</font></td>";
								} elseif($i == 5){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln6,2)."</font></td>";
								} elseif($i == 6){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln7,2)."</font></td>";
								} elseif($i == 7){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln8,2)."</font></td>";
								} elseif($i == 8){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln9,2)."</font></td>";
								} elseif($i == 9){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln10,2)."</font></td>";
								} elseif($i == 10){
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln11,2)."</font></td>";
								} else{
									echo "<td bgcolor='white' class='text-right'><font face='tahoma' color='black'>Rp ".number_format($totalbln12,2)."</font></td>";
								}
								?>                               
                                
                                <td bgcolor="white" class="text-center">
									
									<a href="?page=detail_datapenjualan_bln&bln=<?php echo $thn."-".$bl2; ?>&ref=<?php echo $nmbln[$i]." ".$thn; ?>">
									<button class="btn-transition btn btn-outline-primary btn-sm" title="Lihat Detail" name="tombol_detail">
										<i class="fas fa-info-circle"></i>
									</button>
									</a>
								</td>
                                
                            </tr> 
                                     
                         <?php } ?>
                    </tbody>
                </table>
			        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
			      </div>
			    </div>
			</div>
		

		</div>
		</div>


		<!-- GRAPH OMSET SALES -->
		<div class="row" style="margin-bottom: 18px;">
		<div class="col-lg-12">
				
				<div class="card text-white" style="background-color: #527b87;">
			      <div class="card-body" style="padding: 10px 20px;">
			        <h6 class="card-title">Comparison Sales Order per Bulan</h6>
			        <div class="card-text" align="right" style="font-size: 34px; font-weight: lighter;">
				      <!-- LOADING -->  
	    				<div id="loader" class="center"></div>
							<div class="chart">						
							<div id ="mygraph"></div>
							</div>
						</div>
			        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
			      </div>
			    </div>
		</div>
		</div>
		<!-- GRAPH OMSET SALES -->

		
	</div>
</div>
