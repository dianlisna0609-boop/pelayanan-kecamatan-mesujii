# Bagan Alir Sistem Informasi (Flowchart)
## Sistem Informasi Pelayanan Masyarakat Kantor Kecamatan Mesuji
**Penulis:** Alfan Ardi Putra (NPM: 21430094)  
**Institusi:** Universitas Muhammadiyah Metro (2025/2026)  
**Metodologi:** Rational Unified Process (RUP)

---

### 1. Flowchart Aliran Sistem Antar-Aktor (Cross-Functional Swimlane)

```mermaid
flowchart TD
    %% SUBGRAPH WARGA
    subgraph Warga ["Warga / Pemohon (Masyarakat)"]
        W_Start(["Mulai"]) --> W_Login["1. Buka Web & Login (NIK/Email)"]
        W_Login --> W_Form["2. Isi Form Izin Keramaian & Upload KTP + Surat Desa"]
        W_Form --> W_Submit["3. Kirim Pengajuan"]
        W_Submit --> W_Wait["Status: Menunggu Verifikasi"]
        W_Tolak["Menerima Catatan Penolakan"] --> W_EndFail(["Revisi Berkas / Selesai"])
        W_Bayar["4. Buka Halaman Tagihan Retribusi (Rp 25.000)"]
        W_Bayar --> W_PilihMetode["5. Pilih Pembayaran Online (QRIS / Bank VA)"]
        W_PilihMetode --> W_BayarAction["6. Bayar via HP / E-Wallet / M-Banking"]
        W_Unduh["7. Unduh & Cetak Surat Izin Resmi (PDF)"] --> W_EndSuccess(["Selesai"])
    end

    %% SUBGRAPH OPERATOR
    subgraph Operator ["Operator Kecamatan (Bpk. Sugianto)"]
        O_Review["Terima Berkas Permohonan Masuk"]
        O_Cek{"Periksa Berkas Lengkap & Valid?"}
        O_Reject["Input Catatan Penolakan & Set Status Ditolak"]
        O_Approve["Klik Terima Berkas & Terbitkan Invoice Tagihan"]
    end

    %% SUBGRAPH GATEWAY
    subgraph Gateway ["Payment Gateway (Midtrans Online)"]
        G_Token["Generate Snap Token & Kode Pembayaran"]
        G_Process["Proses Transaksi Otomatis Real-Time"]
        G_Check{"Status Pembayaran Sukses?"}
        G_Success["Kirim Webhook Callback: Settlement"]
        G_Fail["Menunggu Bayar / Expired"]
    end

    %% SUBGRAPH CAMAT
    subgraph Camat ["Camat Mesuji (Pimpinan)"]
        C_Inbox["Terima Berkas Lunas (Status: Dibayar)"]
        C_Tinjau["Tinjau Rincian Acara & Lokasi"]
        C_TTD["Klik Tanda Tangani & Setujui Surat"]
        C_Terbit["Sistem Terbitkan Nomor Surat Resmi & QR Code Validasi"]
    end

    %% HUBUNGAN ALIRAN PROSES
    W_Submit --> O_Review
    O_Review --> O_Cek
    O_Cek -- "Tidak Lengkap / Ditolak" --> O_Reject
    O_Reject --> W_Tolak
    O_Cek -- "Lengkap & Sah" --> O_Approve
    O_Approve --> G_Token
    G_Token --> W_Bayar
    W_BayarAction --> G_Process
    G_Process --> G_Check
    G_Check -- "Belum / Gagal" --> G_Fail
    G_Fail --> W_Bayar
    G_Check -- "Berhasil (Lunas)" --> G_Success
    G_Success --> C_Inbox
    C_Inbox --> C_Tinjau
    C_Tinjau --> C_TTD
    C_TTD --> C_Terbit
    C_Terbit --> W_Unduh
```

---

### 2. Flowchart Khusus Integrasi Online Payment Gateway (Midtrans)

```mermaid
flowchart TD
    P1(["Warga Buka Checkout Tagihan"]) --> P2["Sistem Request Snap Token ke Midtrans API"]
    P2 --> P3["Pop-up Midtrans Snap Terbuka"]
    P3 --> P4{"Pilih Metode Bayar Online"}
    P4 -- "QRIS" --> P5A["Tampilkan Dynamic QRIS Code"]
    P4 -- "Virtual Account" --> P5B["Tampilkan Nomor Virtual Account Bank BRI/BCA/Mandiri"]
    P5A --> P6["Warga Melakukan Pembayaran di HP"]
    P5B --> P6
    P6 --> P7["Gateway Mengirim HTTP POST Webhook ke /midtrans/callback"]
    P7 --> P8{"Validasi Signature Key SHA-512"}
    P8 -- "Valid" --> P9{"Cek Status Transaksi"}
    P8 -- "Tidak Valid" --> P_Error["Abaikan / Tolak Request"]
    P9 -- "Settlement / Capture" --> P10["Update tabel pembayarans status = settlement"]
    P10 --> P11["Update tabel pengajuan_surats status = dibayar"]
    P11 --> P12(["Surat Siap Diberi Nomor & TTD Camat"])
    P9 -- "Expire / Cancel" --> P13["Update status = expire / cancel"]
```

---

### Cara Download Gambar Flowchart (PNG / SVG):
Buka di browser Anda:  
👉 **http://localhost/pelayanan%20mesuji/public/flowchart.html**  
Lalu klik tombol **"Download Gambar PNG"** atau **"Download Gambar SVG"**.

