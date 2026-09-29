<?php
require 'db.php';

$pageTitle = 'Album';
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    require 'partials/header.php';
    echo '<div class="alert error">Album not found.</div>';
    echo '<a class="button" href="albums.php">Back to Albums</a>';
    require 'partials/footer.php';
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT
            a.album_id,
            a.title,
            a.release_year,
            a.genre,
            ar.artist_id,
            ar.name AS artist_name
        FROM albums a
        INNER JOIN artists ar ON ar.artist_id = a.artist_id
        WHERE a.album_id = ?
    ");
    $stmt->execute([$id]);
    $album = $stmt->fetch();

    $trackStmt = $pdo->prepare("
        SELECT
            track_id,
            title,
            duration_seconds,
            stream_count
        FROM tracks
        WHERE album_id = ?
        ORDER BY track_id ASC
    ");
    $trackStmt->execute([$id]);
    $tracks = $trackStmt->fetchAll();

    $totalSeconds = 0;
    $totalStreams = 0;
    foreach ($tracks as $track) {
        $totalSeconds += (int)$track['duration_seconds'];
        $totalStreams += (int)$track['stream_count'];
    }
} catch (PDOException $e) {
    $album = false;
    $tracks = [];
    $totalSeconds = 0;
    $totalStreams = 0;
}

require 'partials/header.php';

if (!$album):
?>
    <div class="alert error">Album not found.</div>
    <a class="button" href="albums.php">Back to Albums</a>
<?php
    require 'partials/footer.php';
    exit;
endif;
?>

<h1><?= htmlspecialchars($album['title']) ?></h1>
<p><strong>Artist:</strong> <a href="artist.php?id=<?= (int)$album['artist_id'] ?>"><?= htmlspecialchars($album['artist_name']) ?></a></p>
<p><strong>Release year:</strong> <?= $album['release_year'] === null ? 'Unknown' : (int)$album['release_year'] ?></p>
<p><strong>Genre:</strong> <?= $album['genre'] === null ? 'Unknown' : htmlspecialchars($album['genre']) ?></p>
<p><strong>Total tracks:</strong> <?= count($tracks) ?></p>
<p><strong>Total duration:</strong> <?= intdiv($totalSeconds, 60) ?> min <?= $totalSeconds % 60 ?> sec</p>
<p><strong>Total streams:</strong> <?= number_format($totalStreams) ?></p>

<h2>Tracklist</h2>
<?php if (!$tracks): ?>
    <div class="alert">This album has no tracks yet.</div>
<?php else: ?>
    <table>
        <thead>
            <tr><th>#</th><th>Track</th><th>Duration</th><th>Streams</th></tr>
        </thead>
        <tbody>
            <?php foreach ($tracks as $index => $track): ?>
                <tr>
                    <td><?= (int)($index + 1) ?></td>
                    <td><?= htmlspecialchars($track['title']) ?></td>
                    <td><?= (int)$track['duration_seconds'] ?> sec</td>
                    <td><?= number_format((int)$track['stream_count']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a class="button secondary" href="albums.php">Back to Albums</a>

<?php require 'partials/footer.php'; ?>