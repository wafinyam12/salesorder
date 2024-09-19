<?php

    session_start();
    $db = $_SESSION['posisi_peg'];
	$user = $_SESSION['session_user'];
	$pass = $_SESSION['session_pass'];
	$idpeg = $_SESSION['id_peg'];

    
	$curl = curl_init();
	$curl2 = curl_init();
    $curl21 = curl_init();
    $curl22 = curl_init();

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
		"Prefer:odata.maxpagesize=50000",
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
	
	
	if ($err2) {
		echo "cURL Error #:" . $err2;
	} else {
		
		$data2x = json_decode($response2,true);
		$data21 = json_decode($response2);
		
	}

    //Sum Total Invoice
	$endpoint2 = 'https://172.16.226.2:50000/b1s/v1/Invoices';
	
	$params2 = array('$select' => 'SalesPersonCode,DocTotal,DocDate','$filter' => 'SalesPersonCode eq '.$idpeg.' and CancelStatus eq \'csNo\'');
	$url2 = $endpoint2 . '?' . http_build_query($params2);
	
	curl_setopt_array($curl21, [
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
		"Prefer:odata.maxpagesize=50000",
		"Content-Type: application/json"
		]
	]);
	
	$response21 = curl_exec($curl21);
	$err21 = curl_error($curl21);
	
	curl_close($curl21);
	
	$doctotal2 = "";
	$docdate2 = "";
	$total2 = 0;
	$totalinvbln = 0;
	
	
	if ($err21) {
		echo "cURL Error #:" . $err21;
	} else {
		
		$data21x = json_decode($response21,true);
		$data211 = json_decode($response21);
		
	}

    //Sum Total Down Payments
	$endpoint21 = 'https://172.16.226.2:50000/b1s/v1/DownPayments';
	
	$params21 = array('$select' => 'SalesPersonCode,DocTotal,DocDate','$filter' => 'SalesPersonCode eq '.$idpeg.' and CancelStatus eq \'csNo\'');
	$url21 = $endpoint21 . '?' . http_build_query($params21);
	
	curl_setopt_array($curl22, [
		CURLOPT_URL => $url21,
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
	
	$response22 = curl_exec($curl22);
	$err22 = curl_error($curl22);
	
	curl_close($curl22);
	
	$doctotal22 = "";
	$docdate22 = "";
	$total22 = 0;
	$totaldpbln = 0;
	
	
	if ($err22) {
		echo "cURL Error #:" . $err22;
	} else {
		
		$data22x = json_decode($response22,true);
		$data212 = json_decode($response22);
		
	}

    $thn = date ('Y');
    $bulan = array("01","02","03","04","05","06","07","08","09","10","11","12");
    $rows = array();
    $rows1 = array();
    $rows2 = array();
    $rows['name'] = 'Sales Order';
    $rows1['name'] = 'Down Payment';
    $rows2['name'] = 'Invoice';

    for ($ib=0;$ib<count($bulan);$ib++){
    $tglbln = $bulan[$ib]."/"."01"."/".$thn;
    $convert = strtotime($tglbln);    
    $blnformat = date('Y-m',$convert);    
    

    $bln = date('Y-m');
    $totalbln = 0;
    $totalinvbln = 0;
    $totaldpbln = 0;
    
    for ($i=0;$i<count($data2x['value']);$i++){
        $doctotal = $data21->value[$i]->DocTotal; 
        $docdate = $data21->value[$i]->DocDate; 
        $blndt = new DateTime($docdate);
        $blnskr = $blndt->format('Y-m');
        
        if($blnformat == $blnskr){
            $totalbln = $totalbln + $doctotal;            
        }							
    }
    for ($ii=0;$ii<count($data21x['value']);$ii++){
        $doctotal2 = $data211->value[$ii]->DocTotal; 
        $docdate2 = $data211->value[$ii]->DocDate; 
        $blndt2 = new DateTime($docdate2);
        $blnskr2 = $blndt2->format('Y-m');
        
        if($blnformat == $blnskr2){
            $totalinvbln = $totalinvbln + $doctotal2;            
        }							
    }
    for ($iii=0;$iii<count($data22x['value']);$iii++){
        $doctotal22 = $data212->value[$iii]->DocTotal; 
        $docdate22 = $data212->value[$iii]->DocDate; 
        $blndt22 = new DateTime($docdate22);
        $blnskr22 = $blndt->format('Y-m');
        
        if($blnformat == $blnskr22){
            $totaldpbln = $totaldpbln + $doctotal22;            
        }							
    } 
    $rows['data'][] = round($totalbln,2);
    $rows1['data'][] = round($totaldpbln,2);
    $rows2['data'][] = round($totalinvbln,2);
    }
    

$result = array();
array_push($result,$rows);
array_push($result,$rows1);
array_push($result,$rows2);

print json_encode($result, JSON_NUMERIC_CHECK);


?> 