<?php
$pageTitle = $pageTitle ?? 'Wavelength';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Wavelength</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="brand" aria-label="Wavelength home">
                <span class="brand-mark">WL</span>
                <span class="brand-text">Wavelength</span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="index.php" class="nav-link active">Home</a>
                <a href="artists.php" class="nav-link">Artists</a>
                <a href="albums.php" class="nav-link">Albums</a>
                <a href="playlists.php" class="nav-link">Playlists</a>
            </nav>

            <div class="header-actions">
                <a href="playlists.php" class="header-pill">My Mixes</a>
                <a href="add_track.php" class="button button-primary">Add Track</a>
            </div>
        </div>
    </header>

    <main class="page-shell">
        <div class="container">
