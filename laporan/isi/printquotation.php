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


$nosales = $no_pjl;
$endurl = 'https://172.16.226.2:50000/b1s/v1/$crossjoin(Quotations,Quotations/DocumentLines,SalesPersons,PaymentTermsTypes,BusinessPartners)';

$var1 = array('$expand' => 'Quotations($select=DocNum,DocDate,DocDueDate,DocType,CardCode,CardName,Address,Address2,NumAtCard,Comments,DocTotal,VatSum,TotalDiscount,SalesPersonCode,FederalTaxID,DocTime,DocCurrency,DocRate,VatSumFc,DocTotalFc),Quotations/DocumentLines($select=LineNum,ItemCode,ItemDescription,U_IDU_Panjang,U_IT_Lembar,Quantity,Price,UnitPrice,LineTotal,U_HR_QtyFisik,U_HR_UoMSvc),BusinessPartners($select=Phone1,Cellular),SalesPersons($select=SalesEmployeeCode,SalesEmployeeName),PaymentTermsTypes($select=GroupNumber,PaymentTermsGroupName)', '$filter' => 'Quotations/DocEntry eq Quotations/DocumentLines/DocEntry and Quotations/CardCode eq BusinessPartners/CardCode and Quotations/SalesPersonCode eq SalesPersons/SalesEmployeeCode and Quotations/PaymentGroupCode eq PaymentTermsTypes/GroupNumber and Quotations/CancelStatus eq \'csNo\' and Quotations/DocEntry eq ' . $nosales . '', '$orderby' => 'Quotations/DocNum desc');

$url3 = $endurl . '?' . http_build_query($var1);
curl_setopt_array($curl3, [
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
		"Cookie:B1SESSION=" . $token . "; ROUTEID=.node1",
		"Prefer:odata.maxpagesize=10",
		"Content-Type: application/json"
	]
]);

$response3 = curl_exec($curl3);
$err3 = curl_error($curl3);

curl_close($curl3);

if ($err3) {
	echo "cURL Error #:" . $err3;
} else {

	$data3x = json_decode($response3, true);
	$data31 = json_decode($response3);

}


$endurlx = 'https://172.16.226.2:50000/b1s/v1/Quotations';

$var1x = array('$filter' => 'DocEntry eq ' . $nosales . '');

$url2x = $endurlx . '?' . http_build_query($var1x);
curl_setopt_array($curl2, [
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

$response2 = curl_exec($curl2);
$err2 = curl_error($curl2);

curl_close($curl2);

$doctipe = "";
$docnum = "";
$tglsales = "";
$tglkirim = "";
$cus_ref = "";
$kode_cus = "";
$nama_cus = "";
$kontak = "";
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
$taxusd = "";
$total = "";
$totalusd = "";
$currency = "";
$rate = "";

$line = "";
$nm_item = "";
$pj_item = "";
$lbr_item = "";
$series = "";
$uom = "";
$qty = "";
$hrg_sat = "";
$hrg_sat_usd = "";
$tot_hrg = "";
$tot_hrg_usd = "";
$qty_serv = "";
$uom_serv = "";

if ($err2) {
	echo "cURL Error #:" . $err2;
} else {

	$data2x = json_decode($response2, true);
	$data21 = json_decode($response2);

	$total = 0;


}

//Terbilang Inggris
function us_satuan($angka, $debug)
{
	$a_str['1'] = "one";
	$a_str['2'] = "two";
	$a_str['3'] = "three";
	$a_str['4'] = "four";
	$a_str['5'] = "five";
	$a_str['6'] = "six";
	$a_str['7'] = "seven";
	$a_str['8'] = "eight";
	$a_str['9'] = "nine";
	$terbilang = "";


	$panjang = strlen($angka);
	for ($b = 0; $b < $panjang; $b++) {
		$a_bil[$b] = substr($angka, $panjang - $b - 1, 1);
	}

	if ($panjang > 2) {
		if ($a_bil[2] != "0") {
			$terbilang = " " . $a_str[$a_bil[2]] . " hundred";
		}
	}
	if ($panjang > 1) {
		if ($a_bil[1] == "1") {
			if ($a_bil[0] == "0") {
				$terbilang .= " ten";
			} else if ($a_bil[0] == "1") {
				$terbilang .= " eleven";
			} else if ($a_bil[0] == "2") {
				$terbilang .= " twelve";
			} else if ($a_bil[0] == "3") {
				$terbilang .= " thirteen";
			} else {
				$terbilang .= " " . $a_str[$a_bil[0]] . "teen";
			}
			return $terbilang;
		} else if ($a_bil[1] == "2") {
			$terbilang .= " twenty";
		} else if ($a_bil[1] == "3") {
			$terbilang .= " thirty";
		} else if ($a_bil[1] == "4") {
			$terbilang .= " forty";
		} else if ($a_bil[1] == "5") {
			$terbilang .= " fifty";
		} else {
			$terbilang .= " " . $a_str[$a_bil[1]] . "ty";
		}
	}

	if ($a_bil[0] != "0") {
		$terbilang .= " " . $a_str[$a_bil[0]];
	}
	return $terbilang;
}

function us_terbilang($angka, $debug)
{

	$angka = str_replace(".", ",", $angka);
	$terbilang = "";

	list($angka, $desimal) = explode(",", $angka);
	$panjang = strlen($angka);
	for ($b = 0; $b < $panjang; $b++) {
		$myindex = $panjang - $b - 1;
		$a_bil[$b] = substr($angka, $myindex, 1);
	}
	if ($panjang > 9) {
		$bil = $a_bil[9];
		if ($panjang > 10) {
			$bil = $a_bil[10] . $bil;
		}
		if ($panjang > 11) {
			$bil = $a_bil[11] . $bil;
		}
		if ($bil != "" && $bil != "000") {
			$terbilang .= us_satuan($bil, $debug) . " billion";
		}

	}

	if ($panjang > 6) {
		$bil = $a_bil[6];
		if ($panjang > 7) {
			$bil = $a_bil[7] . $bil;
		}

		if ($panjang > 8) {
			$bil = $a_bil[8] . $bil;
		}
		if ($bil != "" && $bil != "000") {
			$terbilang .= us_satuan($bil, $debug) . " million";
		}

	}

	if ($panjang > 3) {
		$bil = $a_bil[3];
		if ($panjang > 4) {
			$bil = $a_bil[4] . $bil;
		}

		if ($panjang > 5) {
			$bil = $a_bil[5] . $bil;
		}
		if ($bil != "" && $bil != "000") {
			$terbilang .= us_satuan($bil, $debug) . " thousand";
		}

	}

	$bil = $a_bil[0];
	if ($panjang > 1) {
		$bil = $a_bil[1] . $bil;
	}

	if ($panjang > 2) {
		$bil = $a_bil[2] . $bil;
	}
	if ($bil != "" && $bil != "000") {
		$terbilang .= us_satuan($bil, $debug);
	}

	if ($desimal != "") {
		if (strlen($desimal) == 1) {
			$desimal = $desimal . "0";
		} else {
			$desimal = substr($desimal, 0, 2);
		}
		$terbilang = " $terbilang $desimal/100";
	}

	return trim($terbilang);
}

//End Terbilang Inggris

//Terbilang Indonesia  
function terbilang($x)
{
	$angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

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

		$doctipe = $data31->value[0]->Quotations->DocType;
		$docnum = $data31->value[0]->Quotations->DocNum;
		$tglsales = $data31->value[0]->Quotations->DocDate;
		$tglkirim = $data31->value[0]->Quotations->DocDueDate;
		$cus_ref = $data31->value[0]->Quotations->NumAtCard;
		$kode_cus = $data31->value[0]->Quotations->CardCode;
		$nama_cus = $data31->value[0]->Quotations->CardName;

		$alamat = $data31->value[0]->Quotations->Address;
		$alamatkirim = $data31->value[0]->Quotations->Address2;
		$tlp = $data31->value[0]->BusinessPartners->Phone1;
		$hp = $data31->value[0]->BusinessPartners->Cellular;
		$npwp = $data31->value[0]->Quotations->FederalTaxID;
		$sales = $data31->value[0]->SalesPersons->SalesEmployeeName;

		$remark = $data31->value[0]->Quotations->Comments;
		$terms = $data31->value[0]->PaymentTermsTypes->PaymentTermsGroupName;
		$diskon = $data31->value[0]->Quotations->TotalDiscount;
		$tax = $data31->value[0]->Quotations->VatSum;
		$total = $data31->value[0]->Quotations->DocTotal;
		$currency = $data31->value[0]->Quotations->DocCurrency;
		$rate = $data31->value[0]->Quotations->DocRate;
		$taxusd = $data31->value[0]->Quotations->VatSumFc;
		$totalusd = $data31->value[0]->Quotations->DocTotalFc;

		?>

		<style>
			* {
				margin: 0px auto;
			}

			html,
			body {
				height: 100%;
			}

			#container {
				min-height: 100%;
				position: relative;
			}

			.header {
				background: #0cf;
				padding: 10px;
			}

			.content {
				padding: 10px;
			}

			.footer {
				width: 100%;
				height: 50px;
				padding-left: 10px;
				line-height: 50px;
				background: #333;
				color: #fff;
				position: absolute;
				bottom: 0px;
			}
		</style>

		<table width="100%" border=0 style="font-family:arial,tahoma; font-size: 12px;">
			<?php if ($tipesales == 1) { ?>
				<tr>
					<td width="5%" align="center"><img src="../asset/img/logo/logo.jpg" width="80%"></td>
					<td width="40%" colspan="6">
						<font face="arial" size="4"><b>PT. UTOMODECK METAL WORKS</b></font>
					</td>
					<td width="40%" colspan="3">
						<font face="arial" size="3"><b><u>Penawaran Penjualan</u></b></font>
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
							<font face="arial" size="3"><b><u>Penawaran Penjualan</u></b></font>
						</td>
					</tr>
				<?php } else { ?>
					<tr>
						<td width="5%" align="center"><img src="../asset/img/logo/jurtap.png" width="90%"></td>
						<td width="40%" colspan="6">
							<font face="arial" size="4"><b>PT. JURAGAN ATAP <?php //echo $cabang; ?></b></font>
						</td>
						<td width="40%" colspan="3">
							<font face="arial" size="3"><b><u>Penawaran Penjualan</u></b></font>
						</td>
					</tr>
				<?php }
			}
			?>
			<tr>
				<td width="5%"><b>Tgl. SQ</b></td>
				<td width="2%">:</td>
				<td colspan="4"><?php echo date_format(date_create($tglsales), "d M Y"); ?></td>
				<td></td>
				<td rowspan="2" valign="top" width="8%"><b>Kirim ke</b></td>
				<td rowspan="2" valign="top">:</td>
				<td rowspan="3" valign="top"><?php $ex = explode('.', '120000.00');
				echo $alamatkirim; ?></td>
			</tr>
			<tr>
				<td width="5%"><b>No. SQ</b></td>
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
				<td rowspan="2" align="center">
					<b><?php if ($doctipe == "S") {
						echo "UoM Service";
					} else {
						echo "PANJANG";
					} ?></b></td>
				<td colspan="2" align="center"><b>QUANTITY</b></td>
				<td rowspan="2" align="center"><b>HARGA SATUAN</b></td>
				<td rowspan="2" align="center"><b>TOTAL JUMLAH</b></td>
			</tr>
			<tr>
				<td align="center"><b>Lembar</b></td>
				<td align="center"><b><?php if ($doctipe == "S") {
					echo "Service";
				} else {
					echo "Meter'";
				} ?></b></td>
			</tr>
			<?php
			$sub_tot = 0;
			foreach ($data2x["value"] as $value) {
				foreach ($value["DocumentLines"] as $value2) {

					$line = $value2["LineNum"];
					$nm_item = $value2["ItemDescription"];
					$pj_item = $value2["U_IDU_Panjang"];
					$lbr_item = $value2["U_IT_Lembar"];

					$qty = $value2["Quantity"];
					$hrg_sat = $value2["Price"];
					$tot_hrg = $value2["LineTotal"];

					$tot_hrg_usd = $value2["RowTotalFC"];
					$qty_serv = $value2["U_HR_QtyFisik"];
					$uom_serv = $value2["U_HR_UoMSvc"];

					$sub_tot = $sub_tot + $tot_hrg;
					?>
					<tr>
						<td align="center">
							<font face="arial" size="2"><?php echo $line + 1; ?></font>
						</td>
						<td>
							<font face="arial" size="2"><?php echo $nm_item; ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2">
								<?php if ($doctipe == "S") {
									echo $uom_serv;
								} else {
									echo number_format($pj_item, 2);
								} ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2"><?php echo $lbr_item; ?></font>
						</td>
						<td align="right">
							<font face="arial" size="2">
								<?php if ($doctipe == "S") {
									echo number_format($qty_serv, 2);
								} else {
									echo number_format($qty, 2);
								} ?>
							</font>
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
					; ?></b></td>
			</tr>
			<tr>
				<td><b>Discount</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($diskon, 2); ?></b></td>
			</tr>
			<tr>
				<td><b>DPP</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($sub_tot - $diskon, 2);
					; ?></b></td>
			</tr>
			<tr>
				<td><b>PPN</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($tax, 2); ?></b></td>
			</tr>
			<tr>
				<td><b>Grand Total</b></td>
				<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
					<b><?php echo number_format($total, 2); ?></b></td>
			</tr>
		</table>
		<br>
		<table align="left" width="50%" height="15%" border=1
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
		<table width="100%" style="font-family:arial,tahoma; font-size: 12px;">
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

		$docnum = $data21->value[0]->DocNum;
		$tglsales = $data21->value[0]->DocDate;
		$tglkirim = $data21->value[0]->DocDueDate;
		$cus_ref = $data21->value[0]->NumAtCard;
		$kode_cus = $data21->value[0]->CardCode;
		$nama_cus = $data21->value[0]->CardName;
		$kontak = $data21->value[0]->U_IT_Kontak_Person;
		$alamat = $data21->value[0]->Address;
		$alamatkirim = $data21->value[0]->Address2;

		$remark = $data21->value[0]->Comments;
		$diskon = $data21->value[0]->TotalDiscount;
		$tax = $data21->value[0]->VatSum;
		$total = $data21->value[0]->DocTotal;
		$currency = $data21->value[0]->DocCurrency;
		$rate = $data21->value[0]->DocRate;
		$taxusd = $data21->value[0]->VatSumFc;
		$totalusd = $data21->value[0]->DocTotalFc;

		?>

		<!--HEADER FOOTER PRINT-->
		<div id="container">
			<thead>
				<table width="100%" border=0 style="font-family:arial,tahoma; font-size: 12px;">
					<tr>
						<td width="5%" align="center" colspan="7" valign="top"><img
								src="../asset/img/logo/kop atas ujasi.png" width="100%"></td>
					</tr>
					<tr>
						<td colspan="7" valign="top"><br></td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="95%" align="right" colspan="5" valign="top">Surabaya,
							<?php echo date_format(date_create($tglsales), "d M Y"); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="5%"><b>No </b></td>
						<td width="2%">:</td>
						<td colspan="3"> <?php echo $docnum; ?> / US-SL /
							<?php echo date_format(date_create($tglsales), "M"); ?> /
							<?php echo date_format(date_create($tglsales), "Y"); ?></td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="5%"><b>Hal </b></td>
						<td width="2%">:</td>
						<td colspan="3"> Penawaran Harga</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td width="5%"><b>Kepada </b></td>
						<td width="2%" colspan="4"></td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td></td>
						<td colspan="4" rowspan="3" valign="top"><b><?php echo $kontak; ?></b> <br>
							<b><?php echo $nama_cus; ?></b> <br> <b><?php echo $alamatkirim; ?></b> <br> <b>Di Tempat</b>
						</td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td></td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td></td>
						<td width="5%">&nbsp;</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td colspan="6" valign="top"><br>Sehubungan dengan kebutuhan yang telah disampaikan dengan ini kami
							kirimkan penawaran harga sebagai berikut :<br><br></td>
					</tr>
				</table>

			</thead>
		</div>
		<div class="content" align="center">
			<tbody>
				<table width="95%" border=1
					style="border-collapse: collapse; width: 95%; border: 1px solid black; font-family:arial,tahoma; font-size: 12px;">
					<tr>
						<td align="center" width="5%"><b>No.</b></td>
						<td align="center" width="40%"><b>Model</b></td>
						<td align="center" width="10%"><b>Series</b></td>
						<td align="center" width="10%"><b>Qty</b></td>
						<td align="center" width="5%"><b>UoM</b></td>
						<td align="center" width="15%"><b>Unit Price</b></td>
						<td align="center" width="15%"><b>Total Price</b></td>
					</tr>
					<?php
					$sub_tot = 0;
					$sub_tot_usd = 0;
					foreach ($data2x["value"] as $value) {
						foreach ($value["DocumentLines"] as $value2) {


							$line = $value2["LineNum"];
							$nm_item = $value2["ItemDescription"];
							$pj_item = $value2["U_IDU_Panjang"];
							$lbr_item = $value2["U_IT_Lembar"];
							$series = $value2["U_IT_Series"];
							$uom = $value2["U_IT_Uom"];
							$qty = $value2["Quantity"];
							$hrg_sat = $value2["Price"];
							$tot_hrg = $value2["LineTotal"];

							$tot_hrg_usd = $value2["RowTotalFC"];

							$sub_tot = $sub_tot + $tot_hrg;
							$sub_tot_usd = $sub_tot_usd + $tot_hrg_usd;
							?>
							<tr>
								<td align="center">
									<font face="arial" size="2"><?php echo $line + 1; ?>.</font>
								</td>
								<td>
									<font face="arial" size="2"><?php echo $nm_item; ?></font>
								</td>
								<td align="center">
									<font face="arial" size="2"><?php echo $series; ?></font>
								</td>
								<td align="right">
									<font face="arial" size="2"><?php echo number_format($qty, 2); ?></font>
								</td>
								<td align="center">
									<font face="arial" size="2"><?php echo $uom; ?></font>
								</td>
								<td align="right">
									<font face="arial" size="2"><?php echo $currency . " " . number_format($hrg_sat, 2); ?></font>
								</td>
								<td align="right">
									<font face="arial" size="2">
										<?php if ($currency == "IDR") {
											echo $currency . " " . number_format($tot_hrg, 2);
										} else {
											echo $currency . " " . number_format($tot_hrg_usd, 2);
										} ?>
									</font>
								</td>
							</tr>
						<?php }
					} ?>
					<tr>
						<td align="left" colspan="5" rowspan="3" valign="top"><b>Rates :
							</b><?php echo $currency . " " . number_format($rate, 2); ?><br><b>Terbilang : </b>

							<?php

							if ($currency == "IDR") {
								echo ucwords(terbilang($total)) . " Rupiah";
							} else {
								echo ucwords(us_terbilang($totalusd, "0")) . " Dollars";
							}

							?>

							<br>
						</td>
						<td>Sub Total</td>
						<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
							<b><?php if ($currency == "IDR") {
								echo $currency . " " . number_format($sub_tot, 2);
							} else {
								echo $currency . " " . number_format($sub_tot_usd, 2);
							} ?></b>
						</td>
					</tr>
					<tr>
						<td>PPN (11%)</td>
						<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
							<b><?php if ($currency == "IDR") {
								echo $currency . " " . number_format($tax, 2);
							} else {
								echo $currency . " " . number_format($taxusd, 2);
							} ?></b>
						</td>
					</tr>
					<tr>
						<td><b>Total Pesan</b></td>
						<td align="right" style="font-family:arial,tahoma; font-size: 13px;">
							<b><?php if ($currency == "IDR") {
								echo $currency . " " . number_format($total, 2);
							} else {
								echo $currency . " " . number_format($totalusd, 2);
							} ?></b>
						</td>
					</tr>
				</table>
				<br>
				<table width="95%" border=0
					style="border-collapse: collapse; width: 95%; border: 0px solid black; font-family:arial,tahoma; font-size: 10px;">
					<tr>
						<td colspan="3" align="left"><b>Syarat dan Ketentuan :</b></td>

					</tr>
					<tr>
						<td width="2%" align="left" valign="top">&nbsp;&nbsp;</td>
						<td width="68%" align="left" valign="top">
							<?php $newremark = str_replace(".,", ".<br>", $remark);
							echo $newremark; ?></td>
						<td width="30%" rowspan="1" align="center" valign="bottom"
							style="font-family:arial,tahoma; font-size: 12px;">Best Regards,<br><b>PT. Utomo Juragan Atap
								Surya Indonesia</b><br><br>
							<?php if ($tipe == "2") {
								$qttd = "SELECT ttd_peg FROM tbl_ttd WHERE id_peg = '$idpeg'";
								$sttd = mysqli_query($conn, $qttd) or die($conn->error);
								while ($dttd = mysqli_fetch_array($sttd)) {
									$ttdres = $dttd['ttd_peg'];
									echo "<img src='../ttd/$ttdres' width='70%'>";
								}
							}
							?>
							<br><br><u><b><?php echo $nmpeg; ?></b></u><br><b>(Solar Marketing Representative)</b>
						</td>
					</tr>

					<tr>
						<td colspan="3" align="left" style="font-family:arial,tahoma; font-size: 8px;"><br></td>
					</tr>


				</table>
			</tbody>
		</div>

		<style>
			html,
			body {
				margin: 0;
				padding: 0;
				height: 100%;
				font: 13px Arial;
			}

			#wrapper {
				min-height: 100%;
				position: relative;
			}

			#header {
				background: #f0f0f0;
				padding: 5px;
				height: 50px;
				color: #0000ff;
			}

			#body {
				padding-bottom: 40px;
				padding-left: 10px;
			}

			#footer {
				background: #f0f0f0;
				position: absolute;
				bottom: 0;
				width: 100%;
				text-align: center;
				color: #808080;
			}
		</style>
		<div id="footer">
			<p><img src="../asset/img/logo/kop bawah fix.png" width="100%"></p>
		</div>
	</div>
<?php } ?>

<script>
	window.print();
</script>