<?php
require 'db.php';

$pageTitle = 'Delete Track';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$error = null;
$track = false;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT t.track_id, t.title, a.album_id, a.title AS album_title
            FROM tracks t
            LEFT JOIN albums a ON a.album_id = t.album_id
            WHERE t.track_id = ?
        ");
        $stmt->execute([$id]);
        $track = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Unable to load the track.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $track) {
    try {
        $delete = $pdo->prepare("DELETE FROM tracks WHERE track_id = ?");
        $delete->execute([$id]);

        if ($delete->rowCount() > 0) {
            header("Location: index.php");
            exit;
        }

        $error = "Track not found.";
    } catch (PDOException $e) {
        $error = "The track could not be deleted.";
    }
}

require 'partials/header.php';
?>

<h1>Delete Track</h1>

<?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <a class="button" href="index.php">Back to Dashboard</a>
<?php elseif (!$track): ?>
    <div class="alert error">Track not found.</div>
    <a class="button" href="index.php">Back to Dashboard</a>
<?php else: ?>
    <div class="card">
        <p>Are you sure you want to delete
            <strong><?= htmlspecialchars($track['title']) ?></strong>?
        </p>
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button class="button danger" type="submit">Yes, Delete</button>
            <a class="button secondary" href="index.php">Cancel</a>
        </form>
    </div>
<?php endif; ?>

<?php require 'partials/footer.php'; ?>