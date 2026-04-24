USE travel_booking;

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, role, is_verified) VALUES
('Admin User', 'admin@travellux.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1);

-- Hotels with Unsplash images
INSERT INTO hotels (name, location, city, country, lat, lng, price, rating, stars, image, description, amenities, is_featured) VALUES
('The Grand Parisian', 'Champs-Élysées, Paris', 'Paris', 'France', 48.8698, 2.3078, 320.00, 4.8, 5, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800', 'Luxury 5-star hotel in the heart of Paris with stunning Eiffel Tower views.', 'WiFi,Pool,Spa,Restaurant,Bar,Gym,Parking', 1),
('Santorini Bliss Resort', 'Oia, Santorini', 'Santorini', 'Greece', 36.4618, 25.3753, 450.00, 4.9, 5, 'https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?w=800', 'Iconic white-washed resort with infinity pool overlooking the Aegean Sea.', 'WiFi,Pool,Spa,Restaurant,Bar,Beach', 1),
('Tokyo Skyline Hotel', 'Shinjuku, Tokyo', 'Tokyo', 'Japan', 35.6938, 139.7034, 280.00, 4.7, 4, 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800', 'Modern hotel in vibrant Shinjuku with panoramic city views.', 'WiFi,Gym,Restaurant,Bar,Concierge', 1),
('Maldives Pearl Resort', 'North Malé Atoll', 'Malé', 'Maldives', 4.1755, 73.5093, 850.00, 5.0, 5, 'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=800', 'Overwater bungalows in crystal-clear turquoise waters.', 'WiFi,Pool,Spa,Restaurant,Diving,Beach', 1),
('New York Loft Hotel', 'Manhattan, New York', 'New York', 'USA', 40.7580, -73.9855, 380.00, 4.6, 4, 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800', 'Stylish boutique hotel in the heart of Times Square.', 'WiFi,Gym,Restaurant,Bar,Rooftop', 1),
('Bali Jungle Retreat', 'Ubud, Bali', 'Ubud', 'Indonesia', -8.5069, 115.2625, 195.00, 4.7, 4, 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=800', 'Serene jungle retreat with rice terrace views and traditional Balinese architecture.', 'WiFi,Pool,Spa,Restaurant,Yoga', 1),
('Dubai Sky Tower', 'Downtown Dubai', 'Dubai', 'UAE', 25.1972, 55.2744, 520.00, 4.8, 5, 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800', 'Ultra-luxury tower hotel with Burj Khalifa views and world-class amenities.', 'WiFi,Pool,Spa,Restaurant,Bar,Gym,Concierge', 1),
('Barcelona Beach Hotel', 'Barceloneta, Barcelona', 'Barcelona', 'Spain', 41.3784, 2.1925, 240.00, 4.5, 4, 'https://images.unsplash.com/photo-1583422409516-2895a77efded?w=800', 'Beachfront hotel steps from the Mediterranean with vibrant nightlife nearby.', 'WiFi,Pool,Restaurant,Bar,Beach,Gym', 1);

-- Sample reviews
INSERT INTO reviews (user_id, hotel_id, rating, comment) VALUES
(2, 1, 5, 'Absolutely stunning hotel! The views of Paris are breathtaking.'),
(3, 1, 4, 'Great location and service. Would definitely return.'),
(2, 2, 5, 'The most beautiful place I have ever stayed. Pure paradise!'),
(3, 3, 5, 'Tokyo from above is magical. The hotel staff were incredibly helpful.');
