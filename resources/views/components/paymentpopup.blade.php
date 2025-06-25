
<div id="paymentModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Pilih Metode Pembayaran</h2>
            <button class="close-button" onclick="closePaymentModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="payment-methods">
                <!-- Bank Transfer -->
                <div class="payment-method" onclick="togglePaymentMethod(this)">
                    <div class="payment-header">
                        <div class="payment-icon bank-transfer">🏦</div>
                        <div>
                            <div class="payment-name">Transfer Bank</div>
                            <div class="payment-description">Transfer melalui ATM, Internet Banking, atau Mobile Banking</div>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <div class="instruction-title">Cara Transfer Bank:</div>
                        <ol class="instruction-steps">
                            <li>Buka aplikasi mobile banking atau kunjungi ATM terdekat</li>
                            <li>Pilih menu Transfer ke Rekening Lain</li>
                            <li>Masukkan nomor rekening tujuan</li>
                            <li>Masukkan jumlah pembayaran sesuai total pesanan</li>
                            <li>Konfirmasi transfer dan simpan bukti pembayaran</li>
                            <li>Kirim bukti transfer melalui WhatsApp atau upload di aplikasi</li>
                        </ol>
                        <div class="account-info">
                            <strong>Rekening Tujuan:</strong><br>
                            BCA: 1234567890<br>
                            BNI: 0987654321<br>
                            Mandiri: 1122334455<br>
                            <strong>a.n. JawDelivery Indonesia</strong>
                        </div>
                    </div>
                </div>

                <!-- QRIS -->
                <div class="payment-method" onclick="togglePaymentMethod(this)">
                    <div class="payment-header">
                        <div class="payment-icon qris">QR</div>
                        <div>
                            <div class="payment-name">QRIS</div>
                            <div class="payment-description">Scan QR Code dengan aplikasi e-wallet atau mobile banking</div>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <div class="instruction-title">Cara Bayar dengan QRIS:</div>
                        <ol class="instruction-steps">
                            <li>Buka aplikasi e-wallet atau mobile banking favorit Anda</li>
                            <li>Pilih fitur Scan QR atau Bayar dengan QR</li>
                            <li>Arahkan kamera ke QR Code yang tersedia</li>
                            <li>Pastikan nominal pembayaran sesuai dengan total pesanan</li>
                            <li>Masukkan PIN untuk konfirmasi pembayaran</li>
                            <li>Simpan bukti pembayaran yang berhasil</li>
                        </ol>
                        <div class="account-info">
                            <strong>QR Code akan ditampilkan setelah konfirmasi pesanan</strong><br>
                            Berlaku untuk semua aplikasi e-wallet dan mobile banking
                        </div>
                    </div>
                </div>

                <!-- GoPay -->
                <div class="payment-method" onclick="togglePaymentMethod(this)">
                    <div class="payment-header">
                        <div class="payment-icon gopay">GP</div>
                        <div>
                            <div class="payment-name">GoPay</div>
                            <div class="payment-description">Bayar langsung dengan saldo GoPay Anda</div>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <div class="instruction-title">Cara Bayar dengan GoPay:</div>
                        <ol class="instruction-steps">
                            <li>Pastikan saldo GoPay Anda mencukupi</li>
                            <li>Klik tombol "Bayar dengan GoPay"</li>
                            <li>Anda akan diarahkan ke aplikasi Gojek</li>
                            <li>Konfirmasi pembayaran di aplikasi Gojek</li>
                            <li>Masukkan PIN GoPay Anda</li>
                            <li>Pembayaran berhasil, Anda akan kembali ke aplikasi</li>
                        </ol>
                        <div class="account-info">
                            <strong>Pastikan aplikasi Gojek sudah terinstall</strong><br>
                            Pembayaran akan diproses secara otomatis
                        </div>
                    </div>
                </div>

                <!-- ShopeePay -->
                <div class="payment-method" onclick="togglePaymentMethod(this)">
                    <div class="payment-header">
                        <div class="payment-icon shopeepay">SP</div>
                        <div>
                            <div class="payment-name">ShopeePay</div>
                            <div class="payment-description">Bayar dengan saldo ShopeePay atau kartu yang terdaftar</div>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <div class="instruction-title">Cara Bayar dengan ShopeePay:</div>
                        <ol class="instruction-steps">
                            <li>Pastikan aplikasi Shopee sudah terinstall dan login</li>
                            <li>Klik tombol "Bayar dengan ShopeePay"</li>
                            <li>Anda akan diarahkan ke aplikasi Shopee</li>
                            <li>Pilih metode pembayaran (Saldo ShopeePay atau Kartu)</li>
                            <li>Konfirmasi pembayaran dengan PIN atau biometrik</li>
                            <li>Tunggu notifikasi pembayaran berhasil</li>
                        </ol>
                        <div class="account-info">
                            <strong>Bisa menggunakan saldo ShopeePay atau kartu kredit/debit</strong><br>
                            Dapatkan cashback sesuai promo yang berlaku
                        </div>
                    </div>
                </div>

                <!-- DANA -->
                <div class="payment-method" onclick="togglePaymentMethod(this)">
                    <div class="payment-header">
                        <div class="payment-icon dana">DA</div>
                        <div>
                            <div class="payment-name">DANA</div>
                            <div class="payment-description">Bayar praktis dengan saldo DANA Anda</div>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <div class="instruction-title">Cara Bayar dengan DANA:</div>
                        <ol class="instruction-steps">
                            <li>Pastikan saldo DANA Anda mencukupi</li>
                            <li>Klik tombol "Bayar dengan DANA"</li>
                            <li>Anda akan diarahkan ke aplikasi DANA</li>
                            <li>Periksa detail pembayaran</li>
                            <li>Konfirmasi dengan PIN DANA atau biometrik</li>
                            <li>Pembayaran selesai, simpan bukti transaksi</li>
                        </ol>
                        <div class="account-info">
                            <strong>Pastikan aplikasi DANA sudah terinstall</strong><br>
                            Nikmati berbagai promo dan cashback DANA
                        </div>
                    </div>
                </div>

                <!-- Cash -->
                <div class="payment-method" onclick="togglePaymentMethod(this)">
                    <div class="payment-header">
                        <div class="payment-icon cash">💵</div>
                        <div>
                            <div class="payment-name">Bayar Tunai (COD)</div>
                            <div class="payment-description">Bayar langsung saat pesanan tiba di lokasi Anda</div>
                        </div>
                    </div>
                    <div class="payment-instructions">
                        <div class="instruction-title">Cara Bayar Tunai (COD):</div>
                        <ol class="instruction-steps">
                            <li>Pilih metode "Bayar Tunai" saat checkout</li>
                            <li>Konfirmasi pesanan Anda</li>
                            <li>Tunggu kurir tiba di lokasi pengiriman</li>
                            <li>Periksa pesanan sebelum melakukan pembayaran</li>
                            <li>Bayar sesuai total yang tertera kepada kurir</li>
                            <li>Terima struk pembayaran dari kurir</li>
                        </ol>
                        <div class="account-info">
                            <strong>Siapkan uang pas untuk mempermudah transaksi</strong><br>
                            Pembayaran hanya diterima dalam bentuk uang tunai Rupiah
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal-footer">
        <button id="pilihButton" class="pilih-button" onclick="confirmPaymentMethod()">
            Pilih Metode Pembayaran
        </button>
    </div>
    </div>
</div>
