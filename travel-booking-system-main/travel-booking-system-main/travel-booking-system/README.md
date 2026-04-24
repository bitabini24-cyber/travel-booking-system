# TravelLux - Travel Booking System

A full-stack travel booking platform built with PHP, MySQL, HTML, CSS, and JavaScript.

## Requirements

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache with `mod_rewrite` enabled

## Quick Start

### 1. Database Setup

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p travel_booking < database/seed.sql
```

### 2. Configuration

Edit `config/db.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_user');
define('DB_PASS', 'your_password');
define('DB_NAME', 'travel_booking');
```

Edit `config/app.php`:

```php
// APP_URL is now auto-detected — no manual editing needed.
// It works regardless of your folder name or server path.
```

### 3. Run Tests (optional)

```bash
php tests/auth-test.php
php tests/booking-test.php
```

### 4. Access the Application

Open your web browser and navigate to:

```
http://localhost/travel-booking-system/
```

## Demo Credentials

| Role  | Email               | Password |
| ----- | ------------------- | -------- |
| Admin | admin@travellux.com | password |
| User  | john@example.com    | password |

## Features

- User authentication — register, login, logout, forgot/reset password, email verify
- Hotel search with filters — city, price range, rating, stars, sort
- Interactive Leaflet.js map — price markers, hotel popups
- Real-time booking price calculator — nights × rooms × price + tax breakdown
- Dedicated booking page with guest info form
- Admin dashboard — CRUD hotels, manage bookings/users/reviews
- Analytics page — Chart.js revenue, booking status, user growth charts
- Reviews & ratings — one review per user per hotel, auto-recalculates average
- Responsive mobile-first design with CSS custom properties
- GSAP + AOS animations — parallax hero, staggered cards, counter animations
- Image slider/carousel with touch support
- Global modals — quick view, gallery, confirm dialog
- REST API at `/api/v1/` — hotels, bookings, reviews, users
- Payment-ready structure — Stripe/PayPal integration points in `PaymentService.php`
- Email service — booking confirmation, password reset, welcome emails
- Map service — Nominatim geocoding, Haversine distance, nearby hotels query

## Project Structure

```
travel-booking-system/
├── index.php                  # Homepage entry point
├── .htaccess                  # Security + caching rules
│
├── config/                    # App, DB, constants
├── routes/                    # web.php + api.php route maps
├── public/                    # Clean URL entry point (optional)
│
├── database/
│   ├── schema.sql             # Full table definitions
│   ├── seed.sql               # Sample hotels, users, reviews
│   └── migrations/            # Versioned migration files
│
├── assets/
│   ├── css/                   # style.css, animations.css, responsive.css, variables.css
│   └── js/                    # main.js, ajax.js, booking.js, search.js, map.js, animations.js, slider.js
│
├── includes/                  # header.php, footer.php, navbar.php, sidebar.php, modals.php
│
├── pages/
│   ├── home.php / search.php / hotel-details.php
│   ├── booking.php / checkout.php / confirmation.php
│   ├── user/                  # dashboard, profile, bookings, reviews
│   └── admin/                 # dashboard, hotels, bookings, users, reviews, analytics
│
├── auth/                      # login, register, logout, forgot-password, reset-password, verify
│
├── backend/
│   ├── controllers/           # Auth, User, Hotel, Booking, Review, Payment, Admin
│   ├── models/                # User, Hotel, Booking, Review
│   ├── middleware/            # auth.php, admin.php
│   ├── services/              # PaymentService, EmailService, MapService
│   ├── helpers/               # functions.php, validation.php
│   └── requests/              # booking-request.php, auth-request.php
│
├── api/v1/                    # hotels.php, bookings.php, reviews.php, users.php
│
├── storage/
│   ├── logs/                  # Error logs (auto-created)
│   └── uploads/               # hotels/, users/, reviews/
│
├── tests/                     # auth-test.php, booking-test.php (run with php)
└── docs/                      # API.md, SYSTEM-DESIGN.md, DATABASE.md
```

## REST API

| Method | Endpoint                         | Auth | Description                   |
| ------ | -------------------------------- | ---- | ----------------------------- |
| GET    | `/api/v1/hotels.php`             | No   | List/search hotels            |
| GET    | `/api/v1/hotels.php?id=1`        | No   | Single hotel                  |
| GET    | `/api/v1/bookings.php`           | Yes  | User's bookings               |
| POST   | `/api/v1/bookings.php`           | Yes  | Create booking                |
| GET    | `/api/v1/reviews.php?hotel_id=1` | No   | Hotel reviews                 |
| POST   | `/api/v1/reviews.php`            | Yes  | Submit review                 |
| DELETE | `/api/v1/reviews.php?id=1`       | Yes  | Delete own review             |
| GET    | `/api/v1/users.php`              | Yes  | Own profile (or all if admin) |
| PUT    | `/api/v1/users.php`              | Yes  | Update profile                |

See `docs/API.md` for full request/response details.

## Payment Integration

Update `backend/services/PaymentService.php` with your Stripe secret key:

```php
\Stripe\Stripe::setApiKey('sk_live_...');
```

Add to `config/app.php`:

```php
define('STRIPE_SECRET_KEY', 'sk_live_...');
define('STRIPE_PUBLIC_KEY', 'pk_live_...');
```

## Email Setup

`EmailService.php` uses PHP `mail()` by default. For production, swap the `sendMail()` method body with PHPMailer or an SMTP provider (Mailgun, SendGrid, etc.).

## Further Reading

- `docs/API.md` — REST API reference
- `docs/DATABASE.md` — Schema, table descriptions, key queries
- `docs/SYSTEM-DESIGN.md` — Architecture layers, request lifecycle, security, scalability
