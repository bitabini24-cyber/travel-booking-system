# TravelLux Database Documentation

## Schema Overview

```
users ──────────┬──── bookings ────── hotels
                │         │
                └──── reviews ────────┘
                           │
                      transactions
```

## Tables

### users
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto increment |
| name | VARCHAR(100) | Full name |
| email | VARCHAR(100) UNIQUE | Login email |
| password | VARCHAR(255) | bcrypt hash |
| phone | VARCHAR(20) | Optional |
| avatar | VARCHAR(255) | Filename in storage/uploads/users/ |
| role | ENUM | `user` or `admin` |
| is_verified | TINYINT | Email verified flag |
| reset_token | VARCHAR(255) | Password reset token |
| created_at | TIMESTAMP | Registration date |

### hotels
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto increment |
| name | VARCHAR(255) | Hotel name |
| location | VARCHAR(255) | Full address |
| city | VARCHAR(100) | City (indexed) |
| country | VARCHAR(100) | Country |
| lat / lng | DECIMAL | GPS coordinates for map |
| price | DECIMAL(10,2) | Price per night (indexed) |
| rating | FLOAT | Average from reviews (indexed) |
| stars | INT | 1-5 star classification |
| image | VARCHAR(255) | Primary image URL |
| gallery | TEXT | JSON array of image URLs |
| amenities | TEXT | Comma-separated list |
| is_featured | TINYINT | Show on homepage |

### bookings
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto increment |
| user_id | INT FK | References users.id |
| hotel_id | INT FK | References hotels.id |
| check_in | DATE | Arrival date |
| check_out | DATE | Departure date |
| guests | INT | Number of guests |
| rooms | INT | Number of rooms |
| total_price | DECIMAL(10,2) | Calculated total |
| status | ENUM | pending/confirmed/cancelled/completed |
| payment_status | ENUM | unpaid/paid/refunded |

### reviews
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto increment |
| user_id | INT FK | References users.id |
| hotel_id | INT FK | References hotels.id |
| rating | INT | 1-5 (CHECK constraint) |
| comment | TEXT | Review text |
| UNIQUE | (user_id, hotel_id) | One review per user per hotel |

### transactions
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto increment |
| booking_id | INT FK | References bookings.id |
| amount | DECIMAL(10,2) | Payment amount |
| method | ENUM | stripe/paypal/card |
| transaction_id | VARCHAR(255) | Gateway transaction ID |
| status | ENUM | pending/success/failed/refunded |

## Key Queries

```sql
-- Search hotels with filters
SELECT * FROM hotels
WHERE city LIKE '%Paris%' AND price <= 500 AND rating >= 4
ORDER BY rating DESC LIMIT 12;

-- Check availability
SELECT COUNT(*) FROM bookings
WHERE hotel_id = 1 AND status != 'cancelled'
AND check_in < '2026-06-10' AND check_out > '2026-06-05';

-- Revenue by month
SELECT DATE_FORMAT(created_at, '%b %Y') as month, SUM(total_price) as revenue
FROM bookings WHERE status = 'confirmed'
GROUP BY DATE_FORMAT(created_at, '%Y-%m');
```
