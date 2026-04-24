<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'TravelLux — Discover the World in Luxury' ?></title>
<meta name="description" content="<?= $pageDesc ?? 'Book luxury hotels worldwide with TravelLux. Stunning destinations, best prices, unforgettable experiences.' ?>">
<meta name="app-url" content="<?= APP_URL ?>">
<meta name="theme-color" content="#7C3AED">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">

<!-- Styles -->
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/variables.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/themes.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/background.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/planes.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/animations.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/responsive.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/image-viewer.css">

<!-- Apply saved theme before render to prevent flash -->
<script>
(function(){
    var t = localStorage.getItem('travellux_theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
})();
</script>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<!-- AOS -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">
</head>
<body class="page-enter">
<!-- Background layers -->
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>
<div class="bg-orb bg-orb-4"></div>
<div class="bg-grid"></div>
<div class="bg-noise"></div>
<?php require_once __DIR__ . '/navbar.php'; ?>
