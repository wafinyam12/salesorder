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
	CURLOPT_POSTFIELDS => '{"CompanyDB": "' . $db . '",
	  "Password": "' . $pass . '",
	  "UserName": "' . $user . '"}',
	CURLOPT_HTTPHEADER => [
		"Content-Type: application/json"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$token = "";

if ($err) {
	echo "cURL Error #:" . $err;
} else {

	$data2 = json_decode($response);
	$token = $data2->SessionId;

}

//Load Data Sales Orders
$nosales = $no_pjl;
$endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Orders,Orders/DocumentLines,SalesPersons,PaymentTermsTypes,BusinessPartners)';

$var1 = array('$expand' => 'Orders($select=DocNum,DocDate,DocDueDate,CardCode,CardName,Address,Address2,NumAtCard,Comments,DocTotal,VatSum,TotalDiscount,SalesPersonCode,FederalTaxID,DocTime,DocCurrency,DocRate,VatSumFc,DocTotalFc,TotalDiscountFC),Orders/DocumentLines($select=LineNum,ItemCode,ItemDescription,U_IDU_Panjang,U_IT_Lembar,U_IT_Lebar,Quantity,Price,UnitPrice,LineTotal),BusinessPartners($select=Phone1,Cellular),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)', '$filter' => 'Orders/DocEntry eq Orders/DocumentLines/DocEntry and Orders/CardCode eq BusinessPartners/CardCode and Orders/SalesPersonCode eq SalesPersons/SalesEmployeeCode and Orders/PaymentGroupCode eq PaymentTermsTypes/GroupNumber and Orders/CancelStatus eq \'csNo\' and Orders/DocEntry eq ' . $nosales . '', '$orderby' => 'Orders/DocNum desc');

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
		"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
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

	$data2x = json_decode($response2, true);
	$data21 = json_decode($response2);

}


$endurlx = 'https://172.16.226.2:50000/b1s/v1/Orders';

$var1x = array('$filter' => 'DocEntry eq ' . $nosales . '');

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
		"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
		"Prefer:odata.maxpagesize=10",
		"Content-Type: application/json"
	]
]);

$response3 = curl_exec($curl3);
$err3 = curl_error($curl3);

curl_close($curl3);

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
$cabang = "";
$taxusd = "";
$totalusd = "";
$currency = "";
$rate = "";
$diskonusd = "";

$line = "";
$nm_item = "";
$pj_item = "";
$lbr_item = "";
$lb_item = "";
$qty = "";
$hrg_sat = "";
$tot_hrg = "";
$hrg_sat_usd = "";
$tot_hrg_usd = "";

if ($err3) {
	echo "cURL Error #:" . $err3;
} else {

	$data3x = json_decode($response3, true);
	$data31 = json_decode($response3);

	$total = 0;



}

function terbilang($x)
{
	$angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
	if (!is_numeric($x) || $x < 0) {
		return "Input tidak valid";
	}

	$x = floor($x); // Untuk memastikan $x adalah bilangan bulat
	if ($x < 12)
		return " " . $angka[$x];
	elseif ($x < 20)
		return terbilang($x - 10) . " belas";
	elseif ($x < 100)
		return terbilang($x / 10) . " puluh" . terbilang($x % 10);
	elseif ($x < 200)
		return " seratus" . terbilang($x - 100);
	elseif ($x < 1000)
		return terbilang($x / 100) . " ratus" . terbilang($x % 100);
	elseif ($x < 2000)
		return " seribu" . terbilang($x - 1000);
	elseif ($x < 1000000)
		return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
	elseif ($x < 1000000000)
		return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
	elseif ($x < 1000000000000)
		return terbilang($x / 1000000000) . " milyar" . terbilang($x % 1000000000);
}


?>

<?php if ($tipe == 1) { ?>
	<!-- UDMW -->
	<div>
		<?php

		$docnum = $data21->value[0]->Orders->DocNum ?? null;
		$tglsales = $data21->value[0]->Orders->DocDate ?? null;
		$tglkirim = $data21->value[0]->Orders->DocDueDate ?? null;
		$cus_ref = $data21->value[0]->Orders->NumAtCard ?? null;
		$kode_cus = $data21->value[0]->Orders->CardCode ?? null;
		$nama_cus = $data21->value[0]->Orders->CardName ?? null;
		$alamat = $data21->value[0]->Orders->Address ?? null;
		$alamatkirim = $data21->value[0]->Orders->Address2 ?? null;
		$tlp = $data21->value[0]->BusinessPartners->Phone1 ?? null;
		$hp = $data21->value[0]->BusinessPartners->Cellular ?? null;
		$npwp = $data21->value[0]->Orders->FederalTaxID ?? null;
		$sales = $data21->value[0]->SalesPersons->SalesEmployeeName ?? null;

		$remark = $data21->value[0]->Orders->Comments ?? null;
		$terms = $data21->value[0]->PaymentTermsTypes->PaymentTermsGroupName ?? null;
		$diskon = $data21->value[0]->Orders->TotalDiscount ?? null;
		$tax = $data21->value[0]->Orders->VatSum ?? null;
		$total = $data21->value[0]->Orders->DocTotal ?? null;

		?>

		<table width="100%" border=0 style="font-family:arial,tahoma; font-size: 12px;">
			<?php if ($tipesales == 1) { ?>
				<tr>
					<td width="5%" align="center"><img src="../asset/img/logo/logo.jpg" width="80%"></td>
					<td width="40%" colspan="6">
						<font face="arial" size="4"><b>PT. UTOMODECK METAL WORKS</b></font>
					</td>
					<td width="40%" colspan="3">
						<font face="arial" size="3"><b><u>Pesanan Penjualan</u></b></font>
					</td>
				</tr>
			<?php } else {
				if ($db == "NEW_UDMW_LIVE") {
					?>
					<tr>
						<td width="5%" align="center"><img src="../asset/img/logo/logo.jpg" width="80%"></td>
						<td width="40%" colspan="6">
							<font face="arial" size="4"><b>PT. UTOMODECK METAL WORKS</b></font>
						</td>
						<td width="40%" colspan="3">
							<font face="arial" size="3"><b><u>Pesanan Penjualan</u></b></font>
						</td>
					</tr>
				<?php } else{ 
						if($db == "NEW_BOGOR"){


					?>
					<tr>
						<td width="5%" align="center"><img src="../asset/img/logo/jurtap.png" width="90%"></td>
						<td width="40%" colspan="6">
							<font face="arial" size="4"><b>PT. JURAGAN ATAP BOGOR <?php //echo $cabang; ?></b></font>
						</td>
						<td width="40%" colspan="3">
							<font face="arial" size="3"><b><u>Pesanan Penjualan</u></b></font>
						</td>
					</tr>
				<?php }
				}
			}
			?>
			<tr>
				<td width="5%"><b>Tgl. SO</b></td>
				<td width="2%">:</td>
				<td colspan="4"><?php echo date_format(date_create($tglsales), "d M Y"); ?></td>
				<td></td>
				<td rowspan="2" valign="top" width="8%"><b>Kirim ke</b></td>
				<td rowspan="2" valign="top">:</td>
				<td rowspan="3" valign="top"><?php $ex = explode('.', '120000.00');
				echo $alamatkirim; ?></td>
			</tr>
			<tr>
				<td width="5%"><b>No. SO</b></td>
				<td width="2%">:</td>
				<td colspan="4"><?php echo $docnum; ?></td>
				<td></td>
			</tr>
			<tr>
				<td width="5%"><b>Dipesan</b></td>
				<td width="2%">:</td>
				<td width="10%"><?php echo $kode_cus; ?></td>
				<td width="8%"><b>NPWP</b></td>
				<td width="2%">:</td>
				<td><?php echo $npwp; ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="4" rowspan="3" valign="top"><?php echo $nama_cus . "<br>" . $alamat; ?></td>
				<td></td>
				<td width="8%"><b>Telp / HP</b></td>
				<td width="2%">:</td>
				<td><?php echo $tlp . " / " . $hp; ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td width="8%"><b>Tgl. Kirim</b></td>
				<td width="2%">:</td>
				<td><?php echo date_format(date_create($tglkirim), "d M Y"); ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td width="8%"><b>Pay Term</b></td>
				<td width="2%">:</td>
				<td><?php echo $terms; ?></td>
			</tr>
			<tr>
				<td width="5%"><b>Keterangan</b></td>
				<td valign="top" width="2%">:</td>
				<td colspan="8" valign="top"><?php echo $remark; ?></td>
			</tr>
			<tr>
				<td colspan="10" valign="top"><br></td>
			</tr>
		</table>

		<?php //} ?>

		<table width="100%" border=1
			style="border-collapse: collapse; width: 100%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td rowspan="2" align="center"><b>NO.</b></td>
				<td rowspan="2" align="center"><b>DESKRPSI ITEM</b></td>
				<td rowspan="2" align="center"><b>PANJANG</b></td>
				<td colspan="2" align="center"><b>QUANTITY</b></td>
				<td rowspan="2" align="center"><b>HARGA SATUAN</b></td>
				<td rowspan="2" align="center"><b>TOTAL JUMLAH</b></td>
			</tr>
			<tr>
				<td align="center"><b>Lembar</b></td>
				<td align="center"><b>Meter'</b></td>
			</tr>
			<?php
			$sub_tot = 0;
			foreach ($data3x["value"] as $value) {
				foreach ($value["DocumentLines"] as $value2) {

					$line = $value2["LineNum"];
					$nm_item = $value2["ItemDescription"];
					$pj_item = $value2["U_IDU_Panjang"];
					$lbr_item = $value2["U_IT_Lembar"];
					$lb_item = $value2["U_IT_Lebar"]?? null;
					$qty = $value2["Quantity"];
					$hrg_sat = $value2["Price"];
					$tot_hrg = $value2["LineTotal"];

					$sub_tot = $sub_tot + $tot_hrg;
					?>
					<tr>
						<td align="center">
							<font face="arial" size="2"><?php echo $line + 1; ?></font>
						</td>
						<td>
							<font face="arial" size="2"><?php

							if ($lb_item == 0) {
								echo $nm_item;
							} else {
								echo $nm_item, ' ';
								if (floor($lb_item) == $lb_item) {
									echo "(","LEBAR ", number_format($lb_item, 0), ')';
								} else {
									echo "(","LEBAR ", number_format($lb_item, 2), ")";
								}
							}
							?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo number_format($pj_item, 2); ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo $lbr_item; ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo number_format($qty, 2); ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo number_format($hrg_sat, 2); ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo number_format($tot_hrg, 2); ?></font>
						</td>
					</tr>
				<?php }
			} ?>
			<tr>
				<td align="left" colspan="5" rowspan="5" valign="top"><b>Terbilang :</b> <br>
					<?php

					echo ucwords(terbilang($total));

					?>
				</td>
				<td><b>Sub Total</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($sub_tot, 2);
					; ?></b>
				</td>
			</tr>
			<tr>
				<td><b>Discount</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($diskon, 2); ?></b>
				</td>
			</tr>
			<tr>
				<td><b>DPP</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($sub_tot - $diskon, 2);
					; ?></b>
				</td>
			</tr>
			<tr>
				<td><b>PPN</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($tax, 2); ?></b>
				</td>
			</tr>
			<tr>
				<td><b>Grand Total</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($total, 2); ?></b>
				</td>
			</tr>
		</table>
		<br>
		<table width="50%" height="15%" border=1
			style="border-collapse: collapse; width: 50%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td align="center" width="30%" height="1%"><b>MENYETUJUI</b></td>
				<td align="center" width="30%"><b>MENGETAHUI</b></td>
				<td align="center" width="30%"><b>MARKETING</b></td>
			</tr>
			<tr>
				<td align="center" height="30%"></td>
				<td align="center" height="50%"></td>
				<td align="center" valign="bottom" height="50%"><?php echo $sales; ?></td>
			</tr>
		</table>
		<table width="50%" style="font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td align="right"><?php echo date('d-m-Y');
				echo '<script type="text/javascript">
				var d = new Date();
				document.write(" "+("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2)+":"+("0"+d.getSeconds()).slice(-2));
				</script>'; ?>
				</td>
			</tr>
		</table>
	</div>

<?php } else { ?>
	<!-- UJASI -->
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
		$currency = $data21->value[0]->Orders->DocCurrency;
		$rate = $data21->value[0]->Orders->DocRate;
		$taxusd = $data21->value[0]->Orders->VatSumFc;
		$totalusd = $data21->value[0]->Orders->DocTotalFc;
		$diskonusd = $data21->value[0]->Orders->TotalDiscountFC;

		?>

		<table width="100%" border=0 style="font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td width="5%" align="center" colspan="3"><img src="../asset/img/logo/ujasi.jpeg" width="100%"></td>
				<td width="40%" colspan="4">
					<div style="border: 1px solid black; border-radius: 10px;">
						<table width="100%" border=0
							style="border-collapse: collapse; width: 100%; border: 0px solid black; font-family:arial,tahoma; font-size: 12px;">
							<tr>
								<td align="center">
									<font face="arial" size="4"><b>PT. UTOMO JURAGAN ATAP SURYA INDONESIA</b></font><br>
									<div style="border: 1.5px dotted black;"></div>
								</td>
							</tr>
							<tr>
								<td align="center">Jl. Darmo Permai II/56, Kav.5 Pradahkali Kendal - Surabaya 60226 Jawa
									Timur</td>
							</tr>
						</table>
					</div>
				</td>
				<td width="40%" colspan="3" align="center">
					<font face="arial" size="4"><b><u>SALES ORDER</u></b></font>
				</td>
			</tr>
			<tr>
				<td colspan="10" valign="top"><br></td>
			</tr>
			<tr>
				<td width="5%"><b>Order By</b></td>
				<td width="2%">:</td>
				<td colspan="5"><?php echo $nama_cus; ?></td>
				<td rowspan="4" colspan="3" valign="top" width="8%">
					<div style="border: 1px solid black; border-radius: 10px;">
						<table width="100%" border=0
							style="border-collapse: collapse; width: 100%; border: 0px solid black; font-family:arial,tahoma; font-size: 12px;">
							<tr>
								<td align="left" width="50%"> &nbsp;<b>Tgl.
										SO</b><br><br>&nbsp;<?php echo date_format(date_create($tglsales), "d M Y"); ?>
									<div style="border: 1.5px dotted black;"></div>
								</td>
								<td></td>
								<td align="left" width="50%"> &nbsp;<b>Nomer SO</b><br><br>&nbsp;<?php echo $docnum; ?>
									<div style="border: 1.5px dotted black;"></div>
								</td>
							</tr>
							<tr>
								<td align="left"> &nbsp;<b>Terms</b><br><br>&nbsp;<?php echo $terms; ?>
									<div style="border: 1.5px dotted black;"></div>
								</td>
								<td></td>
								<td align="left"> &nbsp;<b>Nomer PO</b><br><br>&nbsp;<?php echo $cus_ref; ?>
									<div style="border: 1.5px dotted black;"></div>
								</td>
								<td></td>
							</tr>
							<tr>
								<td align="left"> &nbsp;<b>Sales</b><br><br>&nbsp;<?php echo $sales; ?></td>
								<td></td>
								<td align="left"> &nbsp;<b>Currency</b><br><br>&nbsp;<?php echo $currency; ?></td>
								<td></td>
							</tr>
						</table>
					</div>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="5" rowspan="3" valign="top"><?php echo $alamat; ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td width="5%"><b>Ship To</b></td>
				<td valign="top" width="2%">:</td>
				<td colspan="5" valign="top"><?php echo $alamatkirim; ?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="10" valign="top"><br></td>
			</tr>
		</table>



		<table width="100%" border=1
			style="border-collapse: collapse; width: 100%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td align="center"><b>NO.</b></td>
				<td align="center"><b>DESKRPSI ITEM</b></td>
				<td align="center"><b>QUANTITY</b></td>
				<td align="center"><b>HARGA SATUAN</b></td>
				<td align="center"><b>TOTAL JUMLAH</b></td>
			</tr>
			<?php
			$sub_tot = 0;
			$sub_tot_usd = 0;
			foreach ($data3x["value"] as $value) {
				foreach ($value["DocumentLines"] as $value2) {


					$line = $value2["LineNum"];
					$nm_item = $value2["ItemDescription"];
					$pj_item = $value2["U_IDU_Panjang"];
					$lbr_item = $value2["U_IT_Lembar"];
					$lb_item = $value2["U_IT_Lebar"];
					$qty = $value2["Quantity"];
					$hrg_sat = $value2["Price"];
					$tot_hrg = $value2["LineTotal"];
					//$hrg_sat_usd = $value2["Price"];
					$tot_hrg_usd = $value2["RowTotalFC"];

					$sub_tot = $sub_tot + $tot_hrg;
					$sub_tot_usd = $sub_tot_usd + $tot_hrg_usd;
					?>
					<tr>
						<td align="center">
							<font face="arial" size="2"><?php echo $line + 1; ?></font>
						</td>
						<td>
							<font face="arial" size="2"><?php echo $nm_item; ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo number_format($qty, 2); ?></font>
						</td>
						<<td align="right">
							<font face="arial" size="2"><?php echo number_format($hrg_sat, 2); ?></font>
							</td>
							<td align="right">
								<font face="arial" size="2">
									<?php if ($currency == "IDR") {
										echo number_format($tot_hrg, 2);
									} else {
										echo number_format($tot_hrg_usd, 2);
									} ?>
								</font>
							</td>
					</tr>
				<?php }
			} ?>
			<tr>
				<td align="left" colspan="3" rowspan="5" valign="top"><br>
					<fieldset style="border: 1px solid black; border-radius: 5px;">
						<legend><b>Terbilang :</b></legend>

						<div>
							<?php

							if ($currency == "IDR") {
								echo ucwords(terbilang($total)) . " Rupiah";
							} else {
								echo ucwords(terbilang($totalusd)) . " Dollars";
							}

							?>
						</div>
					</fieldset>
					<br>
					<fieldset style="border: 1px solid black; border-radius: 5px;">
						<legend><b>Keterangan :</b></legend>

						<div>
							<?php

							echo $remark;

							?>
						</div>
					</fieldset><br>
				</td>
				<td><b>Sub Total</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php if ($currency == "IDR") {
						echo number_format($sub_tot, 2);
					} else {
						echo number_format($sub_tot_usd, 2);
					} ?></b>
				</td>
			</tr>
			<tr>
				<td><b>Discount</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php if ($currency == "IDR") {
						echo number_format($diskon, 2);
					} else {
						echo number_format($diskonusd, 2);
					} ?></b>
				</td>
			</tr>
			<tr>
				<td><b>DPP</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php if ($currency == "IDR") {
						echo number_format($sub_tot - $diskon, 2);
					} else {
						echo number_format($sub_tot_usd - $diskonusd, 2);
					} ?></b>
				</td>
			</tr>
			<tr>
				<td><b>PPN</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php if ($currency == "IDR") {
						echo number_format($tax, 2);
					} else {
						echo number_format($taxusd, 2);
					} ?></b>
				</td>
			</tr>
			<tr>
				<td><b>Total Pesan</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php if ($currency == "IDR") {
						echo number_format($total, 2);
					} else {
						echo number_format($totalusd, 2);
					} ?></b>
				</td>
			</tr>
		</table>
		<br>
		<table width="30%" height="15%" border=1
			style="border-collapse: collapse; width: 30%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td align="center" width="30%" height="1%"><b>PREPARED BY</b></td>
				<td align="center" width="30%"><b>APPROVED BY</b></td>
			</tr>
			<tr>
				<td align="left" valign="bottom" height="30%">Tgl : </td>
				<td align="left" valign="bottom" height="50%">Tgl : </td>
			</tr>
		</table>
		<table width="30%" style="font-family:arial,tahoma; font-size: 12px;">
			<tr>
				<td align="right"><?php echo date('d-m-Y');
				echo '<script type="text/javascript">
				var d = new Date();
				document.write(" "+("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2)+":"+("0"+d.getSeconds()).slice(-2));
				</script>'; ?>
				</td>
			</tr>
		</table>
	</div>

<?php } ?>

<script>
	window.print();
</script>