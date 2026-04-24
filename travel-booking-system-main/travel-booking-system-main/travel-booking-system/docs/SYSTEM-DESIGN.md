# TravelLux System Design

## Architecture Overview

```
┌─────────────────────────────────────────────────────┐
│                  PRESENTATION LAYER                  │
│         HTML + CSS + JS + GSAP + AOS + Leaflet       │
├─────────────────────────────────────────────────────┤
│                 APPLICATION LAYER                    │
│        PHP Controllers + Services + Middleware       │
├─────────────────────────────────────────────────────┤
│                    DATA LAYER                        │
│              MySQL + PDO + Models                    │
├─────────────────────────────────────────────────────┤
│                    API LAYER                         │
│              JSON REST Endpoints (v1)                │
├─────────────────────────────────────────────────────┤
│                INTEGRATION LAYER                     │
│         Leaflet Maps + Stripe + Email + CDN          │
└─────────────────────────────────────────────────────┘
```

## Request Lifecycle

```
Browser Request
    → .htaccess (rewrite rules)
    → config/app.php (session, constants)
    → config/db.php (PDO connection)
    → backend/middleware/ (auth check)
    → pages/*.php (view)
        → includes/header.php
        → backend/controllers/*.php
            → backend/models/*.php (DB queries)
            → backend/services/*.php (external APIs)
        → includes/footer.php
    → Response to Browser
```

## Security Measures

- PDO prepared statements (SQL injection prevention)
- `password_hash()` / `password_verify()` (bcrypt)
- Session-based authentication with role checks
- `.htaccess` blocks direct access to config/logs
- `htmlspecialchars()` on all output (XSS prevention)
- CSRF protection via session tokens (add to forms)
- Security headers via `.htaccess`

## Caching Strategy

- Static assets: 1 month browser cache (`.htaccess`)
- DB queries: Add Redis/Memcached for production
- Images: Served via Unsplash CDN (no server load)

## Scalability Path

1. Add Redis for session storage
2. Move to Laravel/Symfony for larger teams
3. Separate API server (Node.js/Go) for mobile
4. CDN for static assets (CloudFront/Cloudflare)
5. Read replicas for MySQL at scale
