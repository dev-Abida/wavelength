<?php
require 'db.php';

$pageTitle = 'Artist';
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    $artist = false;
} else {
    try {
        $stmt = $pdo->prepare("SELECT artist_id, name, country FROM artists WHERE artist_id = ?");
        $stmt->execute([$id]);
        $artist = $stmt->fetch();
    } catch (PDOException $e) {
        $artist = false;
    }
}

require 'partials/header.php';

if (!$artist):
?>
    <div class="alert error">Artist not found.</div>
    <a class="button" href="artists.php">Back to Artists</a>
<?php
    require 'partials/footer.php';
    exit;
endif;

try {
    $albumStmt = $pdo->prepare("
        SELECT
            al.album_id,
            al.title,
            al.release_year,
            al.genre,
            COUNT(t.track_id) AS track_count
        FROM artists ar
        LEFT JOIN albums al ON al.artist_id = ar.artist_id
        LEFT JOIN tracks t ON t.album_id = al.album_id
        WHERE ar.artist_id = ?
        GROUP BY al.album_id, al.title, al.release_year, al.genre
        HAVING COUNT(t.track_id) >= 1
        ORDER BY al.release_year DESC, al.title ASC
    ");
    $albumStmt->execute([$id]);
    $albums = $albumStmt->fetchAll();

    $streamStmt = $pdo->prepare("
        SELECT COALESCE(SUM(t.stream_count), 0)
        FROM artists ar
        LEFT JOIN albums al ON al.artist_id = ar.artist_id
        LEFT JOIN tracks t ON t.album_id = al.album_id
        WHERE ar.artist_id = ?
    ");
    $streamStmt->execute([$id]);
    $streams = (int)$streamStmt->fetchColumn();
} catch (PDOException $e) {
    $albums = [];
    $streams = 0;
}
?>

<h1><?= htmlspecialchars($artist['name']) ?></h1>
<p><strong>Country:</strong> <?= $artist['country'] === null ? 'Unknown' : htmlspecialchars($artist['country']) ?></p>
<p><strong>Total streams:</strong> <?= number_format($streams) ?></p>

<h2>Albums</h2>
<table>
    <thead><tr><th>Title</th><th>Year</th><th>Genre</th><th>Tracks</th></tr></thead>
    <tbody>
    <?php foreach ($albums as $album): ?>
        <tr>
            <td><?= htmlspecialchars($album['title']) ?></td>
            <td><?= $album['release_year'] === null ? 'Unknown' : (int)$album['release_year'] ?></td>
            <td><?= $album['genre'] === null ? 'Unknown' : htmlspecialchars($album['genre']) ?></td>
            <td><?= (int)$album['track_count'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if (!$albums): ?>
    <p class="empty">This artist has no albums containing tracks.</p>
<?php endif; ?>

<a class="button secondary" href="artists.php">Back to Artists</a>

<?php require 'partials/footer.php'; ?>