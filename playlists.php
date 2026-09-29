<?php
require 'db.php';

$pageTitle = 'Playlists';
$search = trim($_GET['search'] ?? '');

$sql = "
    SELECT
        p.playlist_id,
        p.playlist_name,
        u.username,
        COUNT(pt.track_id) AS track_count
    FROM playlists p
    LEFT JOIN users u ON u.user_id = p.user_id
    LEFT JOIN playlist_tracks pt ON pt.playlist_id = p.playlist_id
";
$params = [];

if ($search !== '') {
    $sql .= " WHERE p.playlist_name LIKE ?";
    $params[] = '%' . $search . '%';
}

$sql .= "
    GROUP BY p.playlist_id, p.playlist_name, u.username
    ORDER BY p.playlist_name ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$playlists = $stmt->fetchAll();

require 'partials/header.php';
?>

<h1>Playlists</h1>

<form class="filters" method="get">
    <label>Search playlist
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="e.g. Afrobeats">
    </label>
    <button class="button" type="submit">Search</button>
    <a class="button secondary" href="playlists.php">Reset</a>
</form>

<table>
    <thead><tr><th>Playlist</th><th>Owner</th><th>Tracks</th><th>Action</th></tr></thead>
    <tbody>
    <?php foreach ($playlists as $playlist): ?>
        <tr>
            <td><?= htmlspecialchars($playlist['playlist_name']) ?></td>
            <td><?= $playlist['username'] === null ? 'Unknown' : htmlspecialchars($playlist['username']) ?></td>
            <td><?= (int)$playlist['track_count'] ?></td>
            <td><a href="playlist.php?id=<?= (int)$playlist['playlist_id'] ?>">View</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if (!$playlists): ?><p class="empty">No playlists found.</p><?php endif; ?>

<?php require 'partials/footer.php'; ?>