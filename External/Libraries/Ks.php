<?php

class Ks {
    // Şifreleme anahtarı
    private $encryptionKey;

    /**
     * Constructor - Şifreleme anahtarını alır
     *
     * @param string $key Şifreleme anahtarı (256-bit)
     */
    public function __construct($key="2b0f1039663642e8a4dc9a5aaf05d8e2") {
        if (strlen($key) !== 32) {
            throw new Exception("Anahtar uzunluğu 256-bit (32 karakter) olmalıdır.");
        }
        $this->encryptionKey = $key;
    }

    /**
     * Kart Bilgilerini Şifreleme Fonksiyonu
     *
     * @param string $data Şifrelenecek kart bilgisi
     * @return string Şifrelenmiş kart bilgisi
     */
    public function encrypt($data) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')); // 16 byte IV

        // AES-256-CBC ile şifreleme
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);

        // IV'yi şifrelenmiş veriye ekleyerek geri döndür
        return base64_encode($encryptedData . '::' . $iv);
    }

    /**
     * Kart Bilgilerini Çözme Fonksiyonu
     *
     * @param string $encryptedData Şifrelenmiş kart bilgisi
     * @return string Orijinal kart bilgisi
     */
    public function decrypt($encryptedData) {
        // Şifrelenmiş veriyi ve IV'yi ayırma
        list($encryptedData, $iv) = explode('::', base64_decode($encryptedData), 2);

        // AES-256-CBC ile çözme
        return openssl_decrypt($encryptedData, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }
}

/*
// Kullanım Örneği
try {
    // Şifreleme anahtarı 32 karakter (256-bit) olmalıdır
    $encryptionKey = 'bu_cok_guclu_bir_sifreleme_anahtari1234';

    $encryption = new Ks($encryptionKey);
    $kartBilgisi = "1234 5678 9012 3456";

    // Şifreleme
    $encrypted = $encryption->encrypt($kartBilgisi);
    echo "Şifrelenmiş Kart Bilgisi: " . $encrypted . "\n";

    // Çözme
    $decrypted = $encryption->decrypt($encrypted);
    echo "Çözülmüş Kart Bilgisi: " . $decrypted . "\n";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}*/


?>
