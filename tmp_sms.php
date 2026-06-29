<?php
require __DIR__ . "/vendor/autoload.php";
$app = require __DIR__ . "/bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

$key    = DB::table("site_icerik")->where("sayfa","sistem")->where("alan","iletimerkezi_key")->value("deger");
$secret = DB::table("site_icerik")->where("sayfa","sistem")->where("alan","iletimerkezi_secret")->value("deger");
$orig   = DB::table("site_icerik")->where("sayfa","sistem")->where("alan","iletimerkezi_originator")->value("deger");
echo "DB Key:    " . ($key    ? strlen($key)." karakter OK" : "BOŞ") . "\n";
echo "DB Secret: " . ($secret ? strlen($secret)." karakter OK" : "BOŞ") . "\n";
echo "DB Orig:   " . ($orig ?: "BOŞ") . "\n";
if ($key && $secret) {
    echo "Hash:      " . md5($key . "0" . $secret) . "\n";
}
echo "\nSon hatalar:\n";
$logs = DB::table("sms_loglar")->latest()->limit(5)->get(["alici","basarili","hata","created_at"]);
foreach ($logs as $l) {
    echo ($l->basarili ? "[OK]" : "[HATA]") . " " . $l->alici . " => " . ($l->hata ?: "-") . "\n";
}