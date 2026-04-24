<?php
/**
 * Map Service - Geocoding and location utilities
 * Uses OpenStreetMap Nominatim (free, no API key needed)
 * Swap geocode() for Google Maps Geocoding API in production
 */
class MapService {

    private const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/search';

    /**
     * Geocode a location string to lat/lng
     * Returns ['lat' => float, 'lng' => float] or null
     */
    public static function geocode(string $location): ?array {
        $url = self::NOMINATIM_URL . '?' . http_build_query([
            'q'      => $location,
            'format' => 'json',
            'limit'  => 1,
        ]);

        $ctx = stream_context_create(['http' => [
            'header'  => "User-Agent: TravelLux/1.0\r\n",
            'timeout' => 5,
        ]]);

        $response = @file_get_contents($url, false, $ctx);
        if (!$response) return null;

        $data = json_decode($response, true);
        if (empty($data[0])) return null;

        return [
            'lat' => (float) $data[0]['lat'],
            'lng' => (float) $data[0]['lon'],
        ];
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     * Returns distance in kilometers
     */
    public static function distance(float $lat1, float $lng1, float $lat2, float $lng2): float {
        $R = 6371; // Earth radius in km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Get nearby hotels within radius (km)
     */
    public static function getNearby($pdo, float $lat, float $lng, float $radiusKm = 50, int $limit = 10): array {
        // Uses Haversine in SQL for performance
        $stmt = $pdo->prepare("
            SELECT *, (
                6371 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(lat)) *
                    COS(RADIANS(lng) - RADIANS(?)) +
                    SIN(RADIANS(?)) * SIN(RADIANS(lat))
                )
            ) AS distance
            FROM hotels
            WHERE lat IS NOT NULL AND lng IS NOT NULL
            HAVING distance <= ?
            ORDER BY distance ASC
            LIMIT ?
        ");
        $stmt->execute([$lat, $lng, $lat, $radiusKm, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Format coordinates for Leaflet.js marker
     */
    public static function toLeafletMarker(array $hotel): array {
        return [
            'id'     => $hotel['id'],
            'lat'    => (float) $hotel['lat'],
            'lng'    => (float) $hotel['lng'],
            'name'   => $hotel['name'],
            'price'  => $hotel['price'],
            'image'  => $hotel['image'],
            'city'   => $hotel['city'],
            'rating' => $hotel['rating'],
        ];
    }
}
