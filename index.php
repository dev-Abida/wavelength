<?php
require 'db.php';

$pageTitle = 'Dashboard';
$error = null;

try {
    $totalArtists = (int)$pdo->query("SELECT COUNT(*) FROM artists")->fetchColumn();
    $totalAlbums = (int)$pdo->query("SELECT COUNT(*) FROM albums")->fetchColumn();
    $totalTracks = (int)$pdo->query("SELECT COUNT(*) FROM tracks")->fetchColumn();
    $totalStreams = (int)$pdo->query("SELECT COALESCE(SUM(stream_count), 0) FROM tracks")->fetchColumn();
    $averageDuration = (float)$pdo->query("SELECT COALESCE(AVG(duration_seconds), 0) FROM tracks")->fetchColumn();

    $stmt = $pdo->query("
        SELECT title, duration_seconds
        FROM tracks
        WHERE duration_seconds = (SELECT MAX(duration_seconds) FROM tracks)
        ORDER BY track_id
        LIMIT 1
    ");
    $longest = $stmt->fetch();

    $stmt = $pdo->query("
        SELECT title, duration_seconds
        FROM tracks
        WHERE duration_seconds = (SELECT MIN(duration_seconds) FROM tracks)
        ORDER BY track_id
        LIMIT 1
    ");
    $shortest = $stmt->fetch();

    $stmt = $pdo->query("
        SELECT a.title, a.release_year, ar.name AS artist_name
        FROM albums a
        INNER JOIN artists ar ON ar.artist_id = a.artist_id
        ORDER BY a.release_year DESC, a.album_id DESC
        LIMIT 1
    ");
    $recentAlbum = $stmt->fetch();
} catch (PDOException $e) {
    $error = "Unable to load dashboard information.";
}

require 'partials/header.php';
?>

<h1>Music Library Dashboard</h1>

<?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
<?php else: ?>
    <div class="stats">
        <div class="card"><h3>Total Artists</h3><p><?= $totalArtists ?></p></div>
        <div class="card"><h3>Total Albums</h3><p><?= $totalAlbums ?></p></div>
        <div class="card"><h3>Total Tracks</h3><p><?= $totalTracks ?></p></div>
        <div class="card"><h3>Total Streams</h3><p><?= number_format($totalStreams) ?></p></div>
        <div class="card"><h3>Average Duration</h3><p><?= number_format($averageDuration, 2) ?> sec</p></div>
    </div>

    <div class="grid-two">
        <section class="card">
            <h2>Longest Track</h2>
            <?php if ($longest): ?>
                <p><strong><?= htmlspecialchars($longest['title']) ?></strong></p>
                <p><?= (int)$longest['duration_seconds'] ?> seconds</p>
            <?php endif; ?>
        </section>

        <section class="card">
            <h2>Shortest Track</h2>
            <?php if ($shortest): ?>
                <p><strong><?= htmlspecialchars($shortest['title']) ?></strong></p>
                <p><?= (int)$shortest['duration_seconds'] ?> seconds</p>
            <?php endif; ?>
        </section>
    </div>

    <?php if ($recentAlbum): ?>
        <section class="card">
            <h2>Most Recent Album</h2>
            <p><strong><?= htmlspecialchars($recentAlbum['title']) ?></strong>
               by <?= htmlspecialchars($recentAlbum['artist_name']) ?>
               (<?= (int)$recentAlbum['release_year'] ?>)</p>
        </section>
    <?php endif; ?>
<?php endif; ?>

<div class="quick-links">
    <a class="button" href="artists.php">Browse Artists</a>
    <a class="button" href="albums.php">Browse Albums</a>
    <a class="button" href="playlists.php">Browse Playlists</a>
    <a class="button" href="add_track.php">Add Track</a>
</div>

<?php require 'partials/footer.php'; ?>