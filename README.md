# 🚀 RESTful API — Laravel + Sanctum

A token-based authentication API built with **Laravel 13** and **Laravel Sanctum**.  
Designed for mobile apps, single-page applications (SPA), or any third-party integration.

---

## 📋 Table of Contents

- [Tech Stack](#-tech-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Running the Server](#-running-the-server)
- [How Authentication Works](#-how-authentication-works)
- [API Endpoints](#-api-endpoints)
  - [Register](#1-register)
  - [Login](#2-login)
  - [Get Profile](#3-get-profile-me)
  - [Logout](#4-logout)
  - [Logout All Devices](#5-logout-all-devices)
- [Error Responses](#-error-responses)
- [Using with Postman](#-using-with-postman)
- [Using with cURL](#-using-with-curl)
- [Token Expiration](#-token-expiration)
- [Project Structure](#-project-structure)

---

## 🛠 Tech Stack

| Technology | Version |
|------------|---------|
| PHP | ^8.3 |
| Laravel | ^13.x |
| Laravel Sanctum | ^4.x |
| Database | SQLite (default) / MySQL |

---

## 📦 Installation

### Step 1 — Clone the repository

```bash
git clone <repository-url> restfull-api-laravel
cd restfull-api-laravel
```

### Step 2 — Install dependencies

```bash
composer install
```

### Step 3 — Copy the environment file

```bash
cp .env.example .env
```

### Step 4 — Generate the application key

```bash
php artisan key:generate
```

### Step 5 — Run database migrations

```bash
php artisan migrate
```

That's it! Your API is ready to use.

---

## ⚙️ Configuration

Open the `.env` file and update the settings as needed:

```env
APP_NAME=Laravel
APP_ENV=local
APP_URL=http://localhost:8000

# Database — SQLite is used by default (no setup needed)
DB_CONNECTION=sqlite

# To use MySQL instead, uncomment and fill in these lines:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Token lifetime in minutes (1440 = 24 hours)
SANCTUM_TOKEN_EXPIRATION=1440
```

---

## ▶️ Running the Server

```bash
php artisan serve
```

The server will start at: **`http://localhost:8000`**

All API endpoints are accessible under the `/api` prefix:  
**Base URL:** `http://localhost:8000/api`

---

## 🔐 How Authentication Works

This API uses **Bearer Token** authentication provided by Laravel Sanctum.

**Simple flow:**

```
1. Register or Login  →  You receive an access_token
2. Include the token in every protected request header:

   Authorization: Bearer {your_access_token}

3. To end the session, call Logout to invalidate the token
```

> ⚠️ Endpoints marked with 🔒 require a valid token in the `Authorization` header.  
> Without it, the API returns `401 Unauthorized`.

---

## 📡 API Endpoints

### 1. Register

Create a new user account. Returns an access token immediately after registration.

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

**Fields:**

| Field | Type | Required | Rules |
|-------|------|:--------:|-------|
| `name` | string | ✅ | Max 255 characters |
| `email` | string | ✅ | Valid email format, must be unique |
| `password` | string | ✅ | Minimum 8 characters |
| `password_confirmation` | string | ✅ | Must match `password` |

**Success Response — `201 Created`:**
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

Authenticate with your email and password to receive an access token.

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

**Fields:**

| Field | Type | Required | Rules |
|-------|------|:--------:|-------|
| `email` | string | ✅ | Valid email format |
| `password` | string | ✅ | - |

**Success Response — `200 OK`:**
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

> 💡 **Save the `access_token`** — you'll need it to access protected endpoints.

---

### 3. Get Profile (Me)

🔒 Returns the profile of the currently authenticated user.

```
GET /api/auth/me
```

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Success Response — `200 OK`:**
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

🔒 Revoke the current access token. Only affects the current session/device.

```
POST /api/auth/logout
```

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Request Body:** *(not required)*

**Success Response — `200 OK`:**
```json
{
    "message": "Logged out successfully."
}
```

---

### 5. Logout All Devices

🔒 Revoke **all** active tokens for the user. This logs out from every device at once.

```
POST /api/auth/logout-all
```

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Request Body:** *(not required)*

**Success Response — `200 OK`:**
```json
{
    "message": "Logged out from all devices successfully."
}
```

---

## 📋 Endpoint Summary

| Method | Endpoint | Auth Required | Description |
|--------|----------|:------------:|-------------|
| `POST` | `/api/auth/register` | ❌ | Create a new account |
| `POST` | `/api/auth/login` | ❌ | Login and get a token |
| `GET` | `/api/auth/me` | 🔒 | Get current user profile |
| `POST` | `/api/auth/logout` | 🔒 | Logout from this device |
| `POST` | `/api/auth/logout-all` | 🔒 | Logout from all devices |

---

## ❌ Error Responses

### Validation Error — `422 Unprocessable Content`

Returned when request data fails validation rules.

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

### Wrong Credentials — `422 Unprocessable Content`

Returned when email or password is incorrect.

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

### Unauthenticated — `401 Unauthorized`

Returned when the token is missing, expired, or revoked.

```json
{
    "message": "Unauthenticated."
}
```

### Email Already Taken — `422 Unprocessable Content`

Returned when registering with an email that already exists.

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

## 📬 Using with Postman

### Import the Collection

1. Open **Postman**
2. Click **Import** → **Raw text**
3. Paste the JSON below and click **Import**

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

> 💡 **Pro Tip:** After a successful Login, the `access_token` is automatically saved to the `{{token}}` variable via the **Tests** script — no manual copy-pasting needed!

---

## 🖥 Using with cURL

Replace `{access_token}` with the token you received from the Login response.

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

### Logout All Devices
```bash
curl -X POST http://localhost:8000/api/auth/logout-all \
  -H "Authorization: Bearer {access_token}" \
  -H "Accept: application/json"
```

---

## ⏱ Token Expiration

Control how long a token stays valid by editing `.env`:

```env
SANCTUM_TOKEN_EXPIRATION=30      # 30 minutes
SANCTUM_TOKEN_EXPIRATION=60      # 1 hour
SANCTUM_TOKEN_EXPIRATION=1440    # 24 hours (default)
SANCTUM_TOKEN_EXPIRATION=10080   # 7 days
SANCTUM_TOKEN_EXPIRATION=43200   # 30 days
SANCTUM_TOKEN_EXPIRATION=        # Leave empty = tokens never expire
```

After changing `.env`, clear the config cache:
```bash
php artisan config:clear
```

To clean up expired tokens from the database:
```bash
php artisan sanctum:prune-expired --hours=24
```

---

## 🏗 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── AuthController.php   ← Handles register, login, logout, profile
├── Models/
│   └── User.php                 ← User model with API token support
config/
│   └── sanctum.php              ← Sanctum settings (token expiration, etc.)
routes/
│   └── api.php                  ← All API endpoint definitions
database/
│   └── migrations/              ← Database table schemas
```

---

## 📝 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
