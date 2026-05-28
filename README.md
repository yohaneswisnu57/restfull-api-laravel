# 🚀 RESTful API — Laravel + Sanctum

API autentikasi berbasis token menggunakan **Laravel 13** dan **Laravel Sanctum**.  
Dibangun dengan PHP 8.4 dan siap digunakan untuk aplikasi mobile, SPA, atau integrasi pihak ketiga.

---

## 📋 Daftar Isi

- [Teknologi](#-teknologi)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Server](#-menjalankan-server)
- [Autentikasi](#-autentikasi)
- [Endpoint API](#-endpoint-api)
  - [Register](#1-register)
  - [Login](#2-login)
  - [Get Profile (Me)](#3-get-profile-me)
  - [Logout](#4-logout)
  - [Logout Semua Device](#5-logout-semua-device)
- [Response Error](#-response-error)
- [Penggunaan dengan Postman](#-penggunaan-dengan-postman)
- [Penggunaan dengan cURL](#-penggunaan-dengan-curl)
- [Konfigurasi Token Expiration](#-konfigurasi-token-expiration)

---

## 🛠 Teknologi

| Teknologi | Versi |
|-----------|-------|
| PHP | ^8.3 |
| Laravel | ^13.x |
| Laravel Sanctum | ^4.x |
| Database | SQLite (default) / MySQL |

---

## 📦 Instalasi

### 1. Clone atau download project

```bash
git clone <url-repository> restfull-api-laravel
cd restfull-api-laravel
```

### 2. Install dependency

```bash
composer install
```

### 3. Salin file environment

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Jalankan migrasi database

```bash
php artisan migrate
```

---

## ⚙️ Konfigurasi

Edit file `.env` sesuai kebutuhan:

```env
APP_NAME=Laravel
APP_ENV=local
APP_URL=http://localhost:8000

# Database (default: SQLite)
DB_CONNECTION=sqlite

# Atau gunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nama_database
# DB_USERNAME=root
# DB_PASSWORD=

# Durasi token (dalam menit)
# null atau kosong = token tidak pernah expired
SANCTUM_TOKEN_EXPIRATION=1440   # 24 jam
```

---

## ▶️ Menjalankan Server

```bash
php artisan serve
```

Server akan berjalan di: **`http://localhost:8000`**

Semua endpoint API dapat diakses dengan prefix `/api`:  
**Base URL:** `http://localhost:8000/api`

---

## 🔐 Autentikasi

API ini menggunakan **Bearer Token** dari Laravel Sanctum.

**Alur penggunaan:**
1. Daftar akun baru → `POST /api/auth/register`
2. Login → `POST /api/auth/login` → dapatkan `access_token`
3. Gunakan token di header setiap request yang membutuhkan autentikasi:

```
Authorization: Bearer {access_token}
```

> ⚠️ Endpoint yang membutuhkan autentikasi akan mengembalikan `401 Unauthorized` jika token tidak disertakan atau tidak valid.

---

## 📡 Endpoint API

### 1. Register

Mendaftarkan pengguna baru dan langsung mengembalikan token akses.

```
POST /api/auth/register
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Field Validation:**

| Field | Tipe | Wajib | Aturan |
|-------|------|:-----:|--------|
| `name` | string | ✅ | Maksimal 255 karakter |
| `email` | string | ✅ | Format email valid, unik |
| `password` | string | ✅ | Minimal 8 karakter |
| `password_confirmation` | string | ✅ | Harus sama dengan `password` |

**Response Sukses `201 Created`:**
```json
{
    "message": "User registered successfully.",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2026-05-28T07:28:00.000000Z",
        "updated_at": "2026-05-28T07:28:00.000000Z"
    },
    "access_token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz1234567890",
    "token_type": "Bearer"
}
```

---

### 2. Login

Login dengan email dan password untuk mendapatkan token akses.

```
POST /api/auth/login
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Field Validation:**

| Field | Tipe | Wajib | Aturan |
|-------|------|:-----:|--------|
| `email` | string | ✅ | Format email valid |
| `password` | string | ✅ | - |

**Response Sukses `200 OK`:**
```json
{
    "message": "Login successful.",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2026-05-28T07:28:00.000000Z",
        "updated_at": "2026-05-28T07:28:00.000000Z"
    },
    "access_token": "2|AbCdEfGhIjKlMnOpQrStUvWxYz1234567890",
    "token_type": "Bearer",
    "expires_at": "2026-05-29 07:28:00"
}
```

> 💡 Simpan `access_token` ini untuk digunakan di request selanjutnya.

---

### 3. Get Profile (Me)

Mengambil data profil pengguna yang sedang login.

```
GET /api/auth/me
```

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Response Sukses `200 OK`:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2026-05-28T07:28:00.000000Z",
        "updated_at": "2026-05-28T07:28:00.000000Z"
    }
}
```

---

### 4. Logout

Mencabut token saat ini (logout dari device/sesi ini saja).

```
POST /api/auth/logout
```

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Request Body:** *(tidak diperlukan)*

**Response Sukses `200 OK`:**
```json
{
    "message": "Logged out successfully."
}
```

---

### 5. Logout Semua Device

Mencabut **semua** token aktif milik pengguna (logout dari semua device sekaligus).

```
POST /api/auth/logout-all
```

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Request Body:** *(tidak diperlukan)*

**Response Sukses `200 OK`:**
```json
{
    "message": "Logged out from all devices successfully."
}
```

---

## ❌ Response Error

### Validation Error `422 Unprocessable Content`

Terjadi ketika request body tidak memenuhi validasi.

```json
{
    "message": "The email field must be a valid email address.",
    "errors": {
        "email": [
            "The email field must be a valid email address."
        ],
        "password": [
            "The password field must be at least 8 characters."
        ]
    }
}
```

### Credentials Salah `422 Unprocessable Content`

```json
{
    "message": "The provided credentials are incorrect.",
    "errors": {
        "email": [
            "The provided credentials are incorrect."
        ]
    }
}
```

### Tidak Terautentikasi `401 Unauthorized`

Terjadi ketika token tidak disertakan atau sudah expired/dicabut.

```json
{
    "message": "Unauthenticated."
}
```

### Email Sudah Terdaftar `422 Unprocessable Content`

```json
{
    "message": "The email has already been taken.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

---

## 🗂 Ringkasan Endpoint

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|:----:|-----------|
| `POST` | `/api/auth/register` | ❌ | Daftar akun baru |
| `POST` | `/api/auth/login` | ❌ | Login & dapatkan token |
| `GET` | `/api/auth/me` | ✅ | Lihat profil user |
| `POST` | `/api/auth/logout` | ✅ | Logout device ini |
| `POST` | `/api/auth/logout-all` | ✅ | Logout semua device |

---

## 📬 Penggunaan dengan Postman

### Import Collection

1. Buka **Postman**
2. Klik **Import** → **Raw text**
3. Paste JSON berikut:

```json
{
    "info": {
        "name": "RESTful API Laravel",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "variable": [
        { "key": "base_url", "value": "http://localhost:8000/api" },
        { "key": "token", "value": "" }
    ],
    "item": [
        {
            "name": "Register",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/register",
                "header": [
                    { "key": "Content-Type", "value": "application/json" },
                    { "key": "Accept", "value": "application/json" }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\"name\":\"John Doe\",\"email\":\"john@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
                }
            }
        },
        {
            "name": "Login",
            "event": [
                {
                    "listen": "test",
                    "script": {
                        "exec": ["const res = pm.response.json(); pm.collectionVariables.set('token', res.access_token);"]
                    }
                }
            ],
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/login",
                "header": [
                    { "key": "Content-Type", "value": "application/json" },
                    { "key": "Accept", "value": "application/json" }
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\"email\":\"john@example.com\",\"password\":\"password123\"}"
                }
            }
        },
        {
            "name": "Me (Profile)",
            "request": {
                "method": "GET",
                "url": "{{base_url}}/auth/me",
                "header": [
                    { "key": "Authorization", "value": "Bearer {{token}}" },
                    { "key": "Accept", "value": "application/json" }
                ]
            }
        },
        {
            "name": "Logout",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/logout",
                "header": [
                    { "key": "Authorization", "value": "Bearer {{token}}" },
                    { "key": "Accept", "value": "application/json" }
                ]
            }
        },
        {
            "name": "Logout All Devices",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/auth/logout-all",
                "header": [
                    { "key": "Authorization", "value": "Bearer {{token}}" },
                    { "key": "Accept", "value": "application/json" }
                ]
            }
        }
    ]
}
```

> 💡 **Tip Postman:** Setelah Login berhasil, token otomatis tersimpan ke variable `{{token}}` berkat script pada tab **Tests** di request Login.

---

## 🖥 Penggunaan dengan cURL

### Register
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get Profile
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer {access_token}" \
  -H "Accept: application/json"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer {access_token}" \
  -H "Accept: application/json"
```

### Logout Semua Device
```bash
curl -X POST http://localhost:8000/api/auth/logout-all \
  -H "Authorization: Bearer {access_token}" \
  -H "Accept: application/json"
```

> Ganti `{access_token}` dengan token yang didapat dari response Login.

---

## ⏱ Konfigurasi Token Expiration

Atur masa berlaku token di file `.env`:

```env
# Contoh pilihan durasi (satuan: menit):
SANCTUM_TOKEN_EXPIRATION=30      # 30 menit
SANCTUM_TOKEN_EXPIRATION=60      # 1 jam
SANCTUM_TOKEN_EXPIRATION=1440    # 24 jam (default)
SANCTUM_TOKEN_EXPIRATION=10080   # 7 hari
SANCTUM_TOKEN_EXPIRATION=43200   # 30 hari
SANCTUM_TOKEN_EXPIRATION=        # Kosong = tidak pernah expired
```

Setelah mengubah `.env`, jalankan:
```bash
php artisan config:clear
```

Untuk membersihkan token yang sudah expired dari database:
```bash
php artisan sanctum:prune-expired --hours=24
```

---

## 🏗 Struktur Project

```
app/
├── Http/
│   └── Controllers/
│       └── AuthController.php   # Logic autentikasi
├── Models/
│   └── User.php                 # Model user dengan HasApiTokens
config/
│   └── sanctum.php              # Konfigurasi Sanctum
routes/
│   └── api.php                  # Definisi endpoint API
database/
│   └── migrations/              # Skema tabel database
```

---

## 📝 Lisensi

Project ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
