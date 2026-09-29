<?php
require 'db.php';

$pageTitle = 'Add Track';
$errors = [];
$success = null;
$title = trim($_POST['title'] ?? '');
$albumId = (int)($_POST['album_id'] ?? 0);
$duration = (int)($_POST['duration_seconds'] ?? 0);
$streamCount = (int)($_POST['stream_count'] ?? 0);

try {
    $albumStmt = $pdo->query("SELECT album_id, title FROM albums ORDER BY title ASC");
    $albums = $albumStmt->fetchAll();
} catch (PDOException $e) {
    $albums = [];
    $errors[] = 'Unable to load albums right now.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $albumId = (int)($_POST['album_id'] ?? 0);
    $duration = (int)($_POST['duration_seconds'] ?? 0);
    $streamCount = (int)($_POST['stream_count'] ?? 0);

    if ($title === '') {
        $errors[] = 'Track title is required.';
    }

    if ($albumId <= 0) {
        $errors[] = 'Please choose an album.';
    }

    if ($duration <= 0) {
        $errors[] = 'Duration must be greater than 0 seconds.';
    }

    if ($streamCount < 0) {
        $errors[] = 'Stream count cannot be negative.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO tracks (album_id, title, duration_seconds, stream_count) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$albumId, $title, $duration, $streamCount]);
            $success = 'Track added successfully.';
            $title = '';
            $albumId = 0;
            $duration = 0;
            $streamCount = 0;
        } catch (PDOException $e) {
            $errors[] = 'The track could not be added.';
        }
    }
}

require 'partials/header.php';
?>

<div class="page-intro">
    <div>
        <span class="eyebrow">Library</span>
        <h1>Add Track</h1>
    </div>
    <p>Add a new song to your catalog and keep your music collection up to date.</p>
</div>

<?php if ($errors): ?>
    <div class="alert error">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form class="form-card" method="post">
    <div class="form-grid">
        <label class="field">
            <span>Track title</span>
            <input type="text" name="title" maxlength="150" required value="<?= htmlspecialchars($title) ?>" placeholder="e.g. Midnight Echo">
        </label>

        <label class="field">
            <span>Album</span>
            <select name="album_id" required>
                <option value="">Select an album</option>
                <?php foreach ($albums as $album): ?>
                    <option value="<?= (int)$album['album_id'] ?>" <?= $albumId === (int)$album['album_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($album['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="field">
            <span>Duration (seconds)</span>
            <input type="number" name="duration_seconds" min="1" required value="<?= htmlspecialchars((string)$duration) ?>" placeholder="210">
        </label>

        <label class="field">
            <span>Stream count</span>
            <input type="number" name="stream_count" min="0" value="<?= htmlspecialchars((string)$streamCount) ?>" placeholder="12000">
        </label>
    </div>

    <div class="form-actions">
        <button class="button button-primary" type="submit">Save Track</button>
        <a class="button secondary" href="index.php">Back to Dashboard</a>
    </div>
</form>

<?php require 'partials/footer.php'; ?>