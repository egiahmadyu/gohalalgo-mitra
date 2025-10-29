<?php
require __DIR__ . '/vendor/autoload.php';

use GohalalgoMitra\MitraClient;

// ✅ Konfigurasi dasar
$baseUrl   = 'https://testing.duft.co.id';
$clientId  = 'ahmad_ppiu';
$clientKey = 'f5ad189f31c199ea944ac560a4e552d3';
$verifySsl = false; // set ke true di production!

// Inisialisasi client
$client = new MitraClient($baseUrl, $clientId, $clientKey, $verifySsl);

echo "\n===============================\n";
echo "1️⃣ GET TOKEN\n";
echo "===============================\n";

// 1️⃣ Get Token
$tokenResponse = $client->getToken();
echo json_encode($tokenResponse, JSON_PRETTY_PRINT) . PHP_EOL;

if (!empty($tokenResponse['error'])) {
    die("❌ Gagal ambil token: " . $tokenResponse['message'] . PHP_EOL);
}

$token = $tokenResponse['data']['token'] ?? null;
if (!$token) {
    die("❌ Token tidak ditemukan di response!\n");
}

$client->setToken($token);
echo "✅ Token berhasil diset!\n";

// ---------------------------------------------------

echo "\n===============================\n";
echo "2️⃣ CREATE PACKAGE\n";
echo "===============================\n";

// 2️⃣ Buat Paket Baru
$paketData = [
    "tipe" => "Umroh",
    "nama_paket" => "Umroh Spesial November 2025",
    "tgl_berangkat" => "2025-11-12",
    "jumlah_hari" => 9,
    "maskapai" => "GARUDA AIRLINES",
    "keberangkatan" => "Bandung",
    "landing" => "Jedda",
    "kodeflight" => "SV 123",
    "hrginfant" => 1000000,
    "hrgquad" => 25750000,
    "hrgtriple" => 26750000,
    "hrgdouble" => 27750000,
    "hrgperlengkapan" => 0,
    "imgpaketumroh" => "https://gohalalgo.com/images/logo-pink.png",
    "itinerary" => "https://pdfobject.com/pdf/sample.pdf",
    "max_slot" => 14,
    "available_slot" => 12,
    "filled_slot" => 2,
    "hotels" => [
        [
            "idhotelmitra" => 1,
            "kota" => "Makkah",
            "nama_hotel" => "Maysan Al Maqam ex Fajr Badea 2",
            "alamat" => "",
            "jarak" => "100 Meter",
            "bintang" => "2"
        ],
        [
            "idhotelmitra" => 2,
            "kota" => "Madinah",
            "nama_hotel" => "Durrat Eiman ex Eiman Taibah",
            "alamat" => "",
            "jarak" => "100 Meter",
            "bintang" => "4"
        ]
    ]
];

$createResponse = $client->createPackage($paketData);
echo json_encode($createResponse, JSON_PRETTY_PRINT) . PHP_EOL;

// Ambil kode_paket untuk operasi berikut
$kodePaket = $createResponse['data']['kode_paket'] ?? null;
if (!$kodePaket) {
    echo "⚠️ Tidak ada kode_paket dikembalikan — pakai kode dummy untuk testing\n";
    $kodePaket = 'TEST12345';
}

// ---------------------------------------------------

echo "\n===============================\n";
echo "3️⃣ UPDATE PACKAGE\n";
echo "===============================\n";

// 3️⃣ Update Paket
$updateData = [
    "kode_paket" => $kodePaket,
    "tipe" => "Umroh",
    "nama_paket" => "Umroh Spesial November 2025",
    "tgl_berangkat" => "2025-11-12",
    "jumlah_hari" => 9,
    "maskapai" => "GARUDA AIRLINES",
    "keberangkatan" => "Bandung",
    "landing" => "Jedda",
    "kodeflight" => "SV 123",
    "hrginfant" => 1000000,
    "hrgquad" => 25750000,
    "hrgtriple" => 26750000,
    "hrgdouble" => 28750000,
    "hrgperlengkapan" => 0,
    "imgpaketumroh" => "https://gohalalgo.com/images/logo-pink.png",
    "itinerary" => "https://pdfobject.com/pdf/sample.pdf",
    "max_slot" => 14,
    "available_slot" => 12,
    "filled_slot" => 2,
    "hotels" => [
        [
            "idhotelmitra" => 1,
            "kota" => "Makkah",
            "nama_hotel" => "Maysan Al Maqam ex Fajr Badea 2",
            "alamat" => "",
            "jarak" => "100 Meter",
            "bintang" => "2"
        ],
        [
            "idhotelmitra" => 2,
            "kota" => "Madinah",
            "nama_hotel" => "Durrat Eiman ex Eiman Taibah",
            "alamat" => "",
            "jarak" => "100 Meter",
            "bintang" => "4"
        ]
    ]
];

$updateResponse = $client->updatePackage($updateData);
echo json_encode($updateResponse, JSON_PRETTY_PRINT) . PHP_EOL;

// ---------------------------------------------------

echo "\n===============================\n";
echo "4️⃣ UPDATE SEAT\n";
echo "===============================\n";

// 4️⃣ Update Seat
$seatData = [
    "kode_paket" => $kodePaket,
    "max_slot" => 16,
    "available_slot" => 13,
    "filled_slot" => 3
];

$seatResponse = $client->updateSeat($seatData);
echo json_encode($seatResponse, JSON_PRETTY_PRINT) . PHP_EOL;

// ---------------------------------------------------

echo "\n===============================\n";
echo "5️⃣ UPLOAD ITINERARY\n";
echo "===============================\n";

// 5️⃣ Upload Itinerary (file PDF)
$itineraryFile = __DIR__ . '/sample.pdf'; // pastikan file ini ada
if (!file_exists($itineraryFile)) {
    echo "⚠️ File itinerary belum ada, buat dummy file...\n";
    file_put_contents($itineraryFile, "Contoh isi itinerary PDF dummy");
}

$uploadResponse = $client->uploadItinerary($kodePaket, $itineraryFile);
echo json_encode($uploadResponse, JSON_PRETTY_PRINT) . PHP_EOL;

echo "\n✅ Semua proses selesai!\n";
