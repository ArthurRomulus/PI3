<?php
// === translate.php ===
// Backend que recibe texto desde JS y lo traduce usando Azure Translator

header("Content-Type: application/json");

// Configura tus credenciales de Azure
$apiKey = "7hkuEdZNmT8tKyrsAg1WB0lM0tnBJ4SN8umm9qsLsR2CviWokwUKJQQJ99BKACYeBjFXJ3w3AAAbACOGN4M4"; // ⚠️ Reemplaza con tu clave real
$region = "eastus";           // Usa la región que aparece en tu recurso
$endpoint = "https://api.cognitive.microsofttranslator.com/";

$data = json_decode(file_get_contents("php://input"), true);
$texts = $data["texts"] ?? [];
$targetLang = $data["to"] ?? "en";

// Validación básica
if (empty($texts) || !is_array($texts)) {
    echo json_encode(["error" => "No se recibieron textos válidos"]);
    exit;
}

// Llamada a la API de Azure
$url = $endpoint . "translate?api-version=3.0&to=" . urlencode($targetLang);
$options = [
    "http" => [
        "header" => "Content-type: application/json\r\n" .
                    "Ocp-Apim-Subscription-Key: $apiKey\r\n" .
                    "Ocp-Apim-Subscription-Region: $region\r\n",
        "method"  => "POST",
        "content" => json_encode(array_map(fn($t) => ["text" => $t], $texts)),
        "timeout" => 10
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo json_encode(["error" => "Error al conectar con Azure Translator"]);
    exit;
}

$result = json_decode($response, true);
$translations = array_map(fn($item) => $item["translations"][0]["text"] ?? "", $result);

echo json_encode(["translations" => $translations]);
