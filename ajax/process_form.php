<?php
session_start();

$db = $_SESSION['posisi_peg'];
$user = $_SESSION['session_user'];
$pass = $_SESSION['session_pass'];
$tipesales = $_SESSION['session_tipe'];

$curl = curl_init();
$curl2 = curl_init();

// Login request
curl_setopt_array($curl, [
    CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/Login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POSTFIELDS => json_encode([
        'CompanyDB' => $db,
        'Password' => $pass,
        'UserName' => $user
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

$token = "";

if ($err) {
    echo json_encode(['error' => "cURL Error #: " . $err]);
    exit;
} else {
    $data2 = json_decode($response);
    $token = $data2->SessionId;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get BP data
    
    $cardType = $_POST['cardType'];
    $cardName = $_POST['cardName'];
    $group = $_POST['group'];
    $kodesales = $_POST['kode_sales'];
    $payment = $_POST['payment'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $county = $_POST['county'];
    $nomorHP = $_POST['nomorHP'];
    $federaltax = $_POST['faderalTax']??null;
    $createDate = date('Y-m-d'); // Generate current date
    $createTime = date('H:i:s'); // Generate current time

    $bpAddress =[];

    $bpAddress[] = [
        'AddressName' => 'BILL TO',
        'Street'=> $address1,
        'City' => $city,
        'County'=> $county,
        'State'=> $state,
        'AddressType' => 'bo_BillTo',
        'CreateDate' => $createDate,
        'CreateTime' => $createTime,
    ];
    $bpAddress[] = [
        'AddressName' => 'SHIP TO',
        'Street'=> $address2,
        'City' => $city,
        'County'=> $county,
        'State'=> $state,
        'FederalTaxID'=> $federaltax,
        'AddressType' => 'bo_ShipTo',
        'CreateDate' => $createDate,
        'CreateTime' => $createTime,
    ];

    $ContactEmployees =[];

    $ContactEmployees[] = [
        'Name' => $cardName,
        'Address' => $address1,
        'MobilePhone'=> $nomorHP,
    ];

    $endpoint = "https://172.16.226.2:50000/b1s/v1/BusinessPartners";
    $data = [
        "Series" => '103',
        "CardType" => $cardType,
        "CardName" => $cardName,
        "GroupCode" => $group,
        "SalesPersonCode" => $kodesales,
        "Cellular"=> $nomorHP,
        "PayTermsGrpCode" => $payment,
        "FederalTaxID" => $federaltax,
        "BPAddresses" => $bpAddress,
        "ContactEmployees" => $ContactEmployees,
    ];
    curl_setopt_array($curl2, [
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            "Cookie: B1SESSION=" . $token . "; ROUTEID=.node1",
            "Prefer: odata.maxpagesize=1000", // Hilangkan spasi setelah "Cookie"
            "Accept: application/json"
        ]
    ]);


    $response2 = curl_exec($curl2);
    $err3 = curl_error($curl2);
    curl_close($curl2);
    if ($err3) {
        echo json_encode(['error' => "cURL Error #:" . $err3]);
    } else {
        $data3 = json_decode($response2);
        if (isset($data3->error)) {
            echo json_encode(['error' => "SAP API ERROR: " . $data3->error->message->value]);
        } else {
            echo json_encode(['success' => "Bussines Partner Berhasil DiTambah!"]);
        }
    }
}


?>