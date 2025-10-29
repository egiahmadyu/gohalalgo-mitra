# Gohalalgo Mitra API Client (Duft API v2)

[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.0-blue.svg)](https://www.php.net/)
[![Composer](https://img.shields.io/badge/Composer-supported-orange.svg)](https://getcomposer.org/)

**Gohalalgo Mitra** adalah library PHP & Laravel SDK untuk integrasi dengan **Duft Mitra API v2**, yang digunakan oleh mitra untuk sinkronisasi data paket Umroh/Haji (create, update, upload itinerary, dsb).

Library ini dibangun dengan [GuzzleHTTP](https://github.com/guzzle/guzzle) dan mendukung konfigurasi SSL verification dari luar.

---

## Fitur

- 🔐 Ambil token otomatis (`get-token`)
- 📦 Buat paket baru (`create-package`)
- 🧾 Update paket (`update-package`)
- 🎫 Update seat (`update-seat`)
- 📤 Upload itinerary (`upload-itenary`)
- ⚙️ Support Laravel `.env` dan auto SSL toggle
- 🧰 Clean PSR-4 autoload & Composer-ready

---

## Instalasi

### Melalui Composer
```bash
composer require egiahmadyu/gohalalgo-mitra
