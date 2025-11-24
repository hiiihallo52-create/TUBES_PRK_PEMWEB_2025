# FINAL PROJECT

## Praktikum Pemrograman Web

Laboratorium Teknik Komputer – Universitas Lampung

Repositori ini digunakan sebagai **Tempat pengumpulan Tugas Besar Praktikum Pemrograman Web**.

Setiap kelompok wajib mengikuti seluruh aturan dan struktur folder dalam dokumen ini.

---

## 1. Mekanisme Pengumpulan

1. Lakukan **fork** repository ini ke akun GitHub ketua kelompok.
2. Di dalam repository hasil fork, buat folder kelompok dengan format:

```bash
kelompok/kelompok_XX/
```

Contoh untuk kelompok 4:

```

kelompok/kelompok_04/

```

3. Di dalam folder kelompok tersebut, buat struktur wajib:

```

kelompok_04/
└── src/
    └── index.html
    └── style.css
    └── script.js
    *README.md
```

> \*README.md ini berisi tentang dokumentasi proyek dan anggota kelompok

4. Push seluruh pekerjaan ke repository fork.

5. Buat **Pull Request (PR)** ke repository utama dengan format judul:

```
[Kelompok-04] Nama Proyek

```

6. PR akan dicek otomatis oleh **CI** untuk memastikan:

- Struktur folder benar
- File wajib tersedia
- Tidak menyentuh folder kelompok lain
- Tidak mengubah file di luar direktori yang diizinkan

---

## 2. Tema Final Project (pilih satu)

Berdasarkan dokumen resmi Final Project Praktikum Pemrograman Web:

1. **Good Governance**
   Sistem layanan publik, perizinan, layanan sosial, pajak, dan transparansi pemerintahan.

2. **Innovation in Health**
   Telemedicine, rekam medis elektronik, jadwal dokter, klaim asuransi kesehatan, monitoring kesehatan.

3. **Innovation in Education**
   E-learning, platform pembelajaran interaktif, aplikasi edukasi anak.

4. **Digital Transformation for SMEs (UMKM)**
   Marketplace lokal, POS, inventori, kasir digital, promosi produk UMKM.

5. **Community & Organization Management**
   Sistem keanggotaan, event management, donasi, forum, voting, koordinasi kegiatan.

6. **Smart City & Environment**
   Pelaporan infrastruktur, kualitas udara, manajemen sampah, parkir pintar, transportasi publik.

---

## 3. Ketentuan Umum

### Persyaratan Teknis

- **Frontend:**
  HTML5, CSS3 (Native/Bootstrap/Tailwind), JavaScript Native
  _(Tidak diperbolehkan menggunakan framework JS seperti React/Vue/Angular)_

- **Backend:**
  PHP Native _(tanpa framework seperti Laravel/CodeIgniter)_

- **Database:**
  MySQL
  Wajib menyediakan:
- File SQL schema
- ERD (Entity Relationship Diagram)

- **Version Control:**
  Git & GitHub

---

## 4. Fitur Wajib

### 1. User Management

- Registrasi akun
- Login
- Logout
- Manajemen role / hak akses

### 2. Transaksi atau Layanan Utama Sesuai Tema

Contoh:

- CRUD data layanan
- Pemrosesan transaksi
- Pengajuan dan pelaporan data
- Validasi dan manajemen alur layanan

---

## 5. Deliverables (Wajib Dikumpulkan)

Setiap kelompok harus mengumpulkan:

1. **Repository GitHub** berisi:

- Seluruh source code dalam folder `src/`
- Struktur folder sesuai ketentuan

2. **File SQL** (schema database)

3. **ERD** lengkap (format bebas: PNG/JPG/PDF)

4. **Screenshot tampilan aplikasi**

5. **README.md** pada folder kelompok berisi:

- Cara instalasi
- Cara menjalankan aplikasi
- Dokumentasi singkat fitur

6. **Presentasi & Demo Final Project**

---

## 6. Struktur Repo

```
TUBES_PRK_PEMWEB_2025/
│
├── README.md
├── .github/
│ └── workflows/
│     └── format-check.yml # CI untuk validasi PR
│
└── kelompok/
    └── kelompok_01/ # contoh (opsional)

```

Setiap kelompok hanya boleh mengubah folder mereka sendiri.

---

## 7. Aturan Tambahan

- Tidak boleh mengubah folder milik kelompok lain.
- Tidak boleh membuat folder di luar `kelompok/`.
- Tidak boleh mengganti nama folder kelompok setelah dibuat.
- PR dengan struktur salah akan otomatis ditolak oleh CI.

---

## 8. Kontak Resmi

Silakan hubungi asisten praktikum bila terjadi kendala teknis terkait mekanisme pengumpulan atau CI.

---

Selamat mengerjakan.
Gunakan Git dengan baik, commit secara bertahap, dan kerjakan dengan rapi.

> Laboratorium Teknik Komputer – Universitas Lampung
