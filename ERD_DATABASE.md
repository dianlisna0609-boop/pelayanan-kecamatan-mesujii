# Perancangan Entity Relationship Diagram (ERD)
## Sistem Informasi Pelayanan Masyarakat Kantor Kecamatan Mesuji
**Penulis:** Alfan Ardi Putra (NPM: 21430094)  
**Institusi:** Universitas Muhammadiyah Metro (2025/2026)  
**Database:** MySQL (`db_pelayanan_mesuji`)  
**Tingkat Normalisasi:** 1NF, 2NF, 3NF (Valid & Lengkap)

---

### Diagram Relasi Antar Tabel (Mermaid ERD)

```mermaid
erDiagram
    users ||--o{ pengajuan_surats : "1 to N (Warga mengajukan surat)"
    users ||--o| pegawais : "1 to 1 (Akun login pegawai)"
    users ||--o{ beritas : "1 to N (Admin menerbitkan berita)"
    users ||--o{ pembayarans : "1 to N (Warga membayar tagihan)"
    pegawais ||--o{ pengajuan_surats : "1 to N (Pejabat menyetujui surat)"
    pengajuan_surats ||--o| pembayarans : "1 to 1 (Surat memiliki 1 transaksi)"
    users ||--o{ model_has_roles : "memiliki role"
    roles ||--o{ model_has_roles : "ditetapkan"
    roles ||--o{ role_has_permissions : "memiliki izin"
    permissions ||--o{ role_has_permissions : "diberikan"

    users {
        bigint id PK
        string nik UK "16 Digit NIK"
        string name "Nama Lengkap"
        string email UK
        string no_hp "No WhatsApp"
        text alamat "Alamat Rumah"
        string desa "Nama Desa di Mesuji"
        string rt_rw
        string role "admin, operator, camat, warga"
        string password
        timestamp created_at
        timestamp updated_at
    }

    pegawais {
        bigint id PK
        bigint user_id FK "Relasi ke users(id)"
        string nip UK "NIP ASN/PNS"
        string nama "Nama Pegawai"
        string jabatan "Camat / Operator"
        string golongan
        string no_telepon
        string tanda_tangan "File TTD/Stempel"
        timestamp created_at
        timestamp updated_at
    }

    pengajuan_surats {
        bigint id PK
        string nomor_surat UK "Format 300/xxx/KCM-MSJ/2026"
        bigint user_id FK "FK ke users(id) Pemohon"
        bigint disetujui_oleh FK "FK ke pegawais(id) Camat"
        string jenis_surat "Izin Keamanan & Keramaian"
        string nama_acara "Pernikahan, Khitanan, dll"
        dateTime tanggal_mulai
        dateTime tanggal_selesai
        text lokasi_acara
        int estimasi_tamu
        text keperluan
        string file_ktp
        string file_pengantar_desa
        string file_surat_pernyataan
        decimal biaya_retribusi "Rp 25.000"
        string status "menunggu_verifikasi, menunggu_bayar, dibayar, selesai, ditolak"
        text catatan
        string qr_token UK "Token Validasi Online"
        timestamp tanggal_pengajuan
        timestamp tanggal_disetujui
        timestamp created_at
        timestamp updated_at
    }

    pembayarans {
        bigint id PK
        string order_id UK "Invoice ID Gateway"
        bigint pengajuan_surat_id FK "FK ke pengajuan_surats"
        bigint user_id FK "FK ke users(id)"
        decimal jumlah_bayar
        string metode_pembayaran "qris, bca_va, bri_va"
        string status_pembayaran "pending, settlement, expire"
        string snap_token "Midtrans Snap Token"
        timestamp waktu_bayar
        json log_gateway
        timestamp created_at
        timestamp updated_at
    }

    beritas {
        bigint id PK
        bigint user_id FK "FK ke users(id)"
        string judul
        string slug UK
        longtext isi
        string gambar
        date tanggal_terbit
        string status "draft, terbit"
        timestamp created_at
        timestamp updated_at
    }

    roles {
        bigint id PK
        string name "admin, operator, camat, warga"
        string guard_name
        timestamp created_at
        timestamp updated_at
    }

    permissions {
        bigint id PK
        string name
        string guard_name
        timestamp created_at
        timestamp updated_at
    }

    model_has_roles {
        bigint role_id PK,FK
        string model_type PK
        bigint model_id PK,FK
    }

    role_has_permissions {
        bigint permission_id PK,FK
        bigint role_id PK,FK
    }
```

---

### Cara Download Gambar ERD (PNG / SVG):
Buka di browser Anda:
👉 **http://localhost/pelayanan%20mesuji/public/erd.html**  
Lalu klik tombol **"Download Gambar PNG"** atau **"Download Gambar SVG"**.

