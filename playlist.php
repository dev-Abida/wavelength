<?php
require 'db.php';

$pageTitle = 'Playlist';
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    $playlist = false;
} else {
    try {
        $stmt = $pdo->prepare("
            SELECT p.playlist_id, p.playlist_name, u.username
            FROM playlists p
            LEFT JOIN users u ON u.user_id = p.user_id
            WHERE p.playlist_id = ?
        ");
        $stmt->execute([$id]);
        $playlist = $stmt->fetch();
    } catch (PDOException $e) {
        $playlist = false;
    }
}

require 'partials/header.php';

if (!$playlist):
?>
    <div class="alert error">Playlist not found.</div>
    <a class="button" href="playlists.php">Back to Playlists</a>
<?php
    require 'partials/footer.php';
    exit;
endif;

try {
    $stmt = $pdo->prepare("
        SELECT
            t.title AS track_title,
            ar.name AS artist_name,
            al.title AS album_title,
            t.duration_seconds,
            t.stream_count,
            pt.added_date
        FROM playlists p
        INNER JOIN playlist_tracks pt ON pt.playlist_id = p.playlist_id
        INNER JOIN tracks t ON t.track_id = pt.track_id
        INNER JOIN albums al ON al.album_id = t.album_id
        INNER JOIN artists ar ON ar.artist_id = al.artist_id
        WHERE p.playlist_id = ?
        ORDER BY pt.added_date ASC, t.track_id ASC
    ");
    $stmt->execute([$id]);
    $tracks = $stmt->fetchAll();

    $sumStmt = $pdo->prepare("
        SELECT COALESCE(SUM(t.duration_seconds), 0)
        FROM playlist_tracks pt
        INNER JOIN tracks t ON t.track_id = pt.track_id
        WHERE pt.playlist_id = ?
    ");
    $sumStmt->execute([$id]);
    $totalSeconds = (int)$sumStmt->fetchColumn();
} catch (PDOException $e) {
    $tracks = [];
    $totalSeconds = 0;
}

$minutes = intdiv($totalSeconds, 60);
$seconds = $totalSeconds % 60;
?>

<h1><?= htmlspecialchars($playlist['playlist_name']) ?></h1>
<p><strong>Owner:</strong> <?= $playlist['username'] === null ? 'Unknown' : htmlspecialchars($playlist['username']) ?></p>
<p><strong>Total duration:</strong> <?= $minutes ?>:<?= str_pad((string)$seconds, 2, '0', STR_PAD_LEFT) ?></p>

<?php if (!$tracks): ?>
    <div class="alert">This playlist is empty.</div>
<?php else: ?>
<table>
    <thead>
        <tr><th>Track</th><th>Artist</th><th>Album</th><th>Duration</th><th>Streams</th><th>Added</th></tr>
    </thead>
    <tbody>
    <?php foreach ($tracks as $track): ?>
        <tr>
            <td><?= htmlspecialchars($track['track_title']) ?></td>
            <td><?= htmlspecialchars($track['artist_name']) ?></td>
            <td><?= htmlspecialchars($track['album_title']) ?></td>
            <td><?= (int)$track['duration_seconds'] ?> sec</td>
            <td><?= number_format((int)$track['stream_count']) ?></td>
            <td><?= htmlspecialchars($track['added_date']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<a class="button secondary" href="playlists.php">Back to Playlists</a>

<?php require 'partials/footer.php'; ?>