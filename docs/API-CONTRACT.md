# API Contract: API Perpustakaan Terintegrasi

Kontrak ini menjelaskan seluruh endpoint API (PRD bagian 10) sesuai kode di repo VBK4.

- **Base URL:** `http://localhost:8000/api`
- **Format:** JSON (REST)
- **Autentikasi:** token Bearer (Laravel Sanctum). Dapatkan token dari `POST /auth/login`, lalu kirim di setiap request lain.

## Header yang dikirim

| Header | Nilai | Keterangan |
|---|---|---|
| `Accept` | `application/json` | Wajib, supaya error selalu berbentuk JSON |
| `Content-Type` | `application/json` | Untuk request yang memakai body |
| `Authorization` | `Bearer <token>` | Untuk semua endpoint kecuali login |

## Format respons

Sukses:

```json
{ "success": true, "data": { } }
```

Daftar (buku dan riwayat) menambahkan `count`:

```json
{ "success": true, "data": [ ], "count": 3 }
```

Gagal:

```json
{
  "success": false,
  "error": {
    "code": "BOOK_NOT_FOUND",
    "message": "Buku tidak ditemukan."
  }
}
```

Khusus `VALIDATION_ERROR`, ada `error.details` berisi pesan per field.

## Ringkasan endpoint

| Method | Endpoint | Peran | Fungsi |
|---|---|---|---|
| POST | `/auth/login` | Publik | Login, mengembalikan token |
| GET | `/books` | Login | Daftar buku (`?available=`, `?q=`) |
| GET | `/books/{id}` | Login | Detail buku |
| POST | `/loans` | Mahasiswa | Mengajukan peminjaman |
| GET | `/loans` | Mahasiswa | Riwayat milik sendiri (`?status=`) |
| PATCH | `/loans/{id}/approve` | Petugas | Menyetujui pengajuan |
| PATCH | `/loans/{id}/return` | Petugas | Menandai buku dikembalikan |

## Akun uji

Hanya untuk pengujian (dibuat oleh `ApiTestSeeder` dan `AdminSeeder`).

| Peran | Identifier | Password |
|---|---|---|
| Mahasiswa | `2024001` (Alice) | `password123` |
| Petugas | `admin` (kirim `"type": "admin"`) | `admin12345` |

---

## 1. POST `/auth/login`

Mahasiswa login dengan NIM, petugas dengan username.

**Body**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `identifier` | string | Ya | NIM (mahasiswa) atau username (petugas) |
| `password` | string | Ya | |
| `type` | string | Tidak | `student` (default) atau `admin` |

```json
{ "identifier": "2024001", "password": "password123" }
```

**200 OK**

```json
{
  "success": true,
  "data": {
    "token": "1|abcdef...",
    "token_type": "Bearer",
    "user": { "id": 3, "name": "Alice", "nim": "2024001", "username": null, "role": "student" }
  }
}
```

**Error**

| Status | Kode | Penyebab |
|---|---|---|
| 400 | `VALIDATION_ERROR` | Field kosong atau tidak valid |
| 401 | `INVALID_CREDENTIALS` | Password salah atau NIM/username tidak terdaftar (pesan sama) |

---

## 2. GET `/books`

Daftar buku. Perlu token.

**Query (opsional)**

| Parameter | Nilai | Keterangan |
|---|---|---|
| `available` | `true` atau `false` | `true` hanya buku yang masih ada stok |
| `q` | teks | Mencari di judul atau nama kategori |

**200 OK**

```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "title": "The Silent Garden",
      "description": "Demo description for The Silent Garden.",
      "year": 2015,
      "category": { "id": 1, "name": "Fiction" },
      "cover_url": null,
      "total_copies": 2,
      "available_copies": 2,
      "is_available": true
    }
  ],
  "count": 1
}
```

**Error:** 400 `VALIDATION_ERROR` (nilai `available` selain true/false, atau `q` bukan teks); 401 `UNAUTHENTICATED`.

---

## 3. GET `/books/{id}`

Detail satu buku. Perlu token. Respons `data` sama dengan satu item di atas.

**Error:** 404 `BOOK_NOT_FOUND`; 401 `UNAUTHENTICATED`.

---

## 4. POST `/loans`

Mahasiswa mengajukan peminjaman. Status awal `pending`. Stok baru berkurang saat petugas menyetujui.

**Body**

| Field | Tipe | Wajib |
|---|---|---|
| `book_id` | integer | Ya |

```json
{ "book_id": 2 }
```

**201 Created**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "status": "pending",
    "display_status": "pending",
    "is_overdue": false,
    "days_remaining": null,
    "requested_at": "2026-10-18T06:30:00+00:00",
    "approved_at": null,
    "due_at": null,
    "returned_at": null,
    "decline_reason": null,
    "book": { "id": 2, "title": "The Silent Garden" }
  }
}
```

**Error**

| Status | Kode | Penyebab |
|---|---|---|
| 400 | `VALIDATION_ERROR` | `book_id` kosong atau bukan integer |
| 401 | `UNAUTHENTICATED` | Token tidak ada atau tidak valid |
| 403 | `FORBIDDEN` | Token bukan milik mahasiswa |
| 404 | `BOOK_NOT_FOUND` | Buku tidak ada |
| 409 | `BORROW_LIMIT_EXCEEDED` | Sudah ada 3 pengajuan aktif (`pending` + `approved`) |
| 409 | `ALREADY_REQUESTED` | Buku yang sama masih aktif |
| 409 | `BOOK_UNAVAILABLE` | Stok buku 0 |

Urutan pemeriksaan: batas 3, buku sama, lalu stok.

---

## 5. GET `/loans`

Riwayat peminjaman milik mahasiswa yang login (terbaru di atas).

**Query (opsional):** `status` = `pending`, `approved`, `declined`, atau `returned`.

**200 OK:** `data` berisi daftar objek peminjaman (bentuk seperti pada endpoint 4), plus `count`.

**Error:** 400 `VALIDATION_ERROR` (status tidak dikenal); 401 `UNAUTHENTICATED`; 403 `FORBIDDEN` (token bukan mahasiswa).

### Objek peminjaman

| Field | Keterangan |
|---|---|
| `status` | `pending`, `approved`, `declined`, atau `returned` |
| `display_status` | Sama dengan `status`, kecuali `overdue` bila sudah lewat jatuh tempo |
| `is_overdue` | `true` bila `approved` dan melewati `due_at` |
| `days_remaining` | Sisa hari sampai jatuh tempo; `null` bila belum `approved` |
| `requested_at`, `approved_at`, `due_at`, `returned_at` | Waktu (ISO 8601) atau `null` |
| `decline_reason` | Alasan penolakan (hanya dari panel web) |
| `book` | `{ id, title }` |
| `user` | `{ id, name, nim }`, hanya ada pada respons petugas |

---

## 6. PATCH `/loans/{id}/approve`

Petugas menyetujui pengajuan: stok buku turun 1, `approved_at` terisi, `due_at` = hari persetujuan + 7 hari. Tanpa body.

**200 OK:** objek peminjaman dengan `status: "approved"`.

**Error**

| Status | Kode | Penyebab |
|---|---|---|
| 401 | `UNAUTHENTICATED` | Token tidak ada atau tidak valid |
| 403 | `FORBIDDEN` | Token bukan milik petugas |
| 404 | `LOAN_NOT_FOUND` | Peminjaman tidak ada |
| 409 | `INVALID_LOAN_STATE` | Status bukan `pending` |
| 409 | `BOOK_UNAVAILABLE` | Stok sudah habis saat disetujui |

---

## 7. PATCH `/loans/{id}/return`

Petugas menandai buku dikembalikan: `status: "returned"`, `returned_at` terisi, stok bertambah 1. Tanpa body.

**200 OK:** objek peminjaman dengan `status: "returned"`.

**Error**

| Status | Kode | Penyebab |
|---|---|---|
| 401 | `UNAUTHENTICATED` | Token tidak ada atau tidak valid |
| 403 | `FORBIDDEN` | Token bukan milik petugas |
| 404 | `LOAN_NOT_FOUND` | Peminjaman tidak ada |
| 409 | `ALREADY_RETURNED` | Sudah dikembalikan (stok tidak bertambah dua kali) |
| 409 | `INVALID_LOAN_STATE` | Status bukan `approved` (misalnya masih `pending`) |

---

## Daftar kode error

| Status | Kode | Arti |
|---|---|---|
| 400 | `VALIDATION_ERROR` | Input tidak valid |
| 401 | `INVALID_CREDENTIALS` | Login gagal |
| 401 | `UNAUTHENTICATED` | Token tidak ada atau tidak valid |
| 403 | `FORBIDDEN` | Peran tidak berhak mengakses endpoint |
| 404 | `BOOK_NOT_FOUND` | Buku tidak ada |
| 404 | `LOAN_NOT_FOUND` | Peminjaman tidak ada |
| 404 | `NOT_FOUND` | Endpoint tidak ada |
| 405 | `METHOD_NOT_ALLOWED` | Method tidak diizinkan untuk endpoint ini |
| 409 | `BOOK_UNAVAILABLE` | Stok buku habis |
| 409 | `BORROW_LIMIT_EXCEEDED` | Melebihi batas 3 pengajuan aktif |
| 409 | `ALREADY_REQUESTED` | Buku yang sama masih aktif |
| 409 | `ALREADY_RETURNED` | Sudah dikembalikan |
| 409 | `INVALID_LOAN_STATE` | Status peminjaman tidak sesuai untuk aksi ini |

Catatan: pesan (`message`) pada error 409 berasal dari `LoanService` dan berbahasa Inggris. Yang dipakai untuk pengujian adalah `code`.

## Aturan bisnis

| ID | Aturan |
|---|---|
| BR-01 | Maksimal 3 buku aktif (`pending` atau `approved`) per mahasiswa |
| BR-02 | Buku dengan stok 0 tidak dapat diajukan |
| BR-03 | Jatuh tempo = tanggal persetujuan + 7 hari |
| BR-04 | Satu buku tidak boleh diajukan dua kali selama masih aktif |

## Catatan

- Token Sanctum tidak memiliki masa kedaluwarsa dan belum ada endpoint logout.
- Manajemen buku, kategori, penolakan pengajuan, dan wishlist hanya tersedia di panel web.
