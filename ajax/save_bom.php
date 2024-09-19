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
    // Get BOM header data
    $treeCode = $_POST['treeCode'];
    $quantity = '1';
    $warehouse = $_POST['warehouse'];

    // Get BOM lines data
    $lineItemCodes = $_POST['itemCode'];
    $lineQuantities = $_POST['lineQuantity'];
    $lineWarehouses = $_POST['lineWarehouse'];

    $bomLines = [];
    for ($i = 0; $i < count($lineItemCodes); $i++) {
        $bomLines[] = [
            'ItemCode' => $lineItemCodes[$i],
            'Quantity' => $lineQuantities[$i],
            'Warehouse' => $lineWarehouses[$i]
        ];
    }
    curl_setopt_array($curl2, [
        CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/ProductTrees('$treeCode')",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            "Cookie : B1SESSION=" . $token . "; ROUTEID=.node1",
            "Accept: application/json"
        ]
    ]);

    $existingData = curl_exec($curl2);
    $err2 = curl_error($curl2);

    if ($err) {
        echo json_encode(['error' => "cURL Error #: " . $err2]);
    } else {
        $existingBOM = json_decode($existingData);
        if (isset($existingBOM->error) || empty($existingBOM)) {
            $data = [
                'TreeCode' => $treeCode,
                'Quantity' => $quantity,
                'Warehouse' => $warehouse,
                'ProductTreeLines' => $bomLines
            ];
            $endpoint = "https://172.16.226.2:50000/b1s/v1/ProductTrees"; // Adjust endpoint as needed

            curl_setopt_array($curl2, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST", // Assuming always POST; change if needed
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "Cookie: B1SESSION=" . $token . "; ROUTEID=.node1",
                    "Prefer: odata.maxpagesize=50000",
                    "Content-Type: application/json"
                ]
            ]);

            $response2 = curl_exec($curl2);
            $err2 = curl_error($curl2);

            // Close cURL session
            curl_close($curl2);

            if ($err2) {
                echo json_encode(['error' => "cURL Error #: " . $err2]);
            } else {
                $data3 = json_decode($response2);
                if (isset($data3->error)) {
                    echo json_encode(['error' => "SAP API ERROR: " . $data3->error->message->value]);
                } else {
                    echo json_encode(['success' => "BOM created successfully!"]);
                }
            }
        } else {
            //data ada, maka akan dilakukan patch untuk update data existing
            $data = [
                'TreeCode' => $treeCode,
                'Quantity' => $quantity,
                'Warehouse' => $warehouse,
                'ProductTreeLines' => $bomLines
            ];
            curl_setopt_array($curl2, [
                CURLOPT_URL => "https://172.16.226.2:50000/b1s/v1/ProductTrees('$treeCode')",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PATCH",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "Cookie: B1SESSION=" . $token . "; ROUTEID=.node1",
                    "Prefer: odata.maxpagesize=50000",
                    "Content-Type: application/json"
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
                    echo json_encode(['success' => "BOM updated successfully!"]);
                }
            }
        }
    }
}
?>