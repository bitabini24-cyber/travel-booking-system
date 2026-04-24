# TravelLux REST API Documentation

Base URL: `http://localhost/travel-booking-system/api/v1`

All responses return JSON. Authenticated endpoints require an active PHP session.

---

## Hotels

### GET /hotels.php
List hotels with optional filters.

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| city | string | Filter by city name |
| min_price | number | Minimum price per night |
| max_price | number | Maximum price per night |
| rating | number | Minimum rating (e.g. 4.0) |
| sort | string | `price ASC`, `price DESC`, `rating DESC`, `name ASC` |
| page | int | Page number (default: 1) |
| limit | int | Results per page (max: 50, default: 12) |

**Response:**
```json
{
  "data": [...],
  "meta": { "total": 8, "page": 1, "limit": 12, "pages": 1 }
}
```

### GET /hotels.php?id=1
Get single hotel by ID.

---

## Bookings

### GET /bookings.php
Get current user's bookings. Requires auth.

### POST /bookings.php
Create a new booking. Requires auth.

**Body:**
```json
{
  "hotel_id": 1,
  "check_in": "2026-05-01",
  "check_out": "2026-05-05",
  "guests": 2,
  "rooms": 1,
  "special_requests": "Late check-in"
}
```

**Response:**
```json
{ "success": true, "booking_id": 42, "total": 1280.00 }
```

---

## Reviews

### GET /reviews.php?hotel_id=1
Get reviews for a hotel.

**Response:**
```json
{
  "data": [...],
  "average": 4.7,
  "total": 12
}
```

### POST /reviews.php
Submit a review. Requires auth.

**Body:**
```json
{ "hotel_id": 1, "rating": 5, "comment": "Amazing stay!" }
```

### DELETE /reviews.php?id=5
Delete own review. Requires auth.

---

## Users

### GET /users.php
Get own profile (or all users if admin). Requires auth.

### PUT /users.php
Update own profile. Requires auth.

**Body:**
```json
{ "name": "John Doe", "phone": "+1234567890" }
```

---

## Error Responses

```json
{ "error": "Unauthorized" }        // 401
{ "error": "Hotel not found" }     // 404
{ "error": "Method not allowed" }  // 405
```
