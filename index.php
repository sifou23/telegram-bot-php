<?php

$token = "7528048532:AAFOx5w06Ftxc9lFdUMj1nTGb4IGlzk6AMM";
$apiURL = "https://api.telegram.org/bot$token/";

// توليد بيانات فلبينية عشوائية
function generate_pp_data() {
    // تحميل الأسماء من ملف names.txt
    $names = file("names.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $full_name = trim($names[array_rand($names)]);

    // تحميل الدومينات من ملف domains.txt
    $domains = file("domains.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $domain = trim($domains[array_rand($domains)]);

    // تفصيل الاسم إلى أول وثاني (إذا ممكن)
    $name_parts = explode(" ", $full_name, 2);
    $first = $name_parts[0] ?? "Juan";
    $last = $name_parts[1] ?? "DelaCruz";

    // توليد بريد إلكتروني
    $email = strtolower($first . "." . $last . rand(10, 99) . "@" . $domain);

    // توليد باقي البيانات
    $streets = ["Mabini St.", "Rizal Ave.", "Bonifacio Blvd.", "Quezon St.", "Aguinaldo Hwy."];
    $cities = ["Manila", "Cebu City", "Davao City", "Quezon City", "Baguio", "Taguig"];
    $phones = [
        "+63 905" . rand(1000000, 9999999),
        "+63 917" . rand(1000000, 9999999),
        "+63 923" . rand(1000000, 9999999)
    ];

    $birth_year = rand(1980, 2002);
    $birth_month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
    $birth_day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
    $dob = "$birth_year-$birth_month-$birth_day";

    $password_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = substr(str_shuffle(str_repeat($password_chars, 8)), 0, 12);

    $document = "PH" . rand(100000, 999999);

    return [
        "first_name" => $first,
        "last_name" => $last,
        "full_name" => $full_name,
        "email" => $email,
        "phone" => $phones[array_rand($phones)],
        "address" => $streets[array_rand($streets)] . ", " . $cities[array_rand($cities)],
        "dob" => $dob,
        "password" => $password,
        "document" => $document,
    ];
}

// استقبال التحديث
$update = json_decode(file_get_contents("php://input"), true);

if (isset($update["message"]["text"])) {
    $chat_id = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];

    if ($text == "/pp") {
        $data = generate_pp_data();

        $msg  = "👤 Full Name: {$data['full_name']}\n";
        $msg .= "📧 Email: {$data['email']}\n";
        $msg .= "📞 Phone: {$data['phone']}\n";
        $msg .= "🏠 Address: {$data['address']}\n";
        $msg .= "🎂 Date of Birth: {$data['dob']}\n";
        $msg .= "🆔 Document Number: {$data['document']}\n";
        $msg .= "🔐 Password: {$data['password']}\n\n";
        $msg .= "🌍 Country: Philippines\n";
        $msg .= "🚀 Powered by: @JP_DZ";

        file_get_contents($apiURL . "sendMessage?chat_id=$chat_id&text=" . urlencode($msg));
    }
}
?>
