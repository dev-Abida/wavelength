<?php
require 'db.php';

$pageTitle = 'Edit Artist';
$id = (int)($_GET['id'] ?? $_POST['artist_id'] ?? 0);
$errors = [];
$message = null;

if ($id <= 0) {
    require 'partials/header.php';
    echo '<div class="alert error">Artist not found.</div>';
    echo '<a class="button" href="artists.php">Back to Artists</a>';
    require 'partials/footer.php';
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT artist_id, name, country FROM artists WHERE artist_id = ?");
    $stmt->execute([$id]);
    $artist = $stmt->fetch();
} catch (PDOException $e) {
    $artist = false;
}

if (!$artist) {
    require 'partials/header.php';
    echo '<div class="alert error">Artist not found.</div>';
    echo '<a class="button" href="artists.php">Back to Artists</a>';
    require 'partials/footer.php';
    exit;
}

$name = $artist['name'];
$country = $artist['country'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $countryValue = ($country === '') ? null : $country;

    if ($name === '') {
        $errors[] = "Artist name is required.";
    }

    if (!$errors) {
        try {
            $update = $pdo->prepare("
                UPDATE artists
                SET name = ?, country = ?
                WHERE artist_id = ?
            ");
            $update->execute([$name, $countryValue, $id]);

            $affected = $update->rowCount();
            if ($affected > 0) {
                $message = "Artist updated successfully. Rows affected: " . $affected;
            } else {
                $message = "No changes made, or artist not found.";
            }
        } catch (PDOException $e) {
            $errors[] = "The artist could not be updated.";
        }
    }
}

require 'partials/header.php';
?>

<h1>Edit Artist</h1>

<?php if ($errors): ?>
    <div class="alert error">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($message): ?>
    <div class="alert success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form class="form-card" method="post">
    <input type="hidden" name="artist_id" value="<?= $id ?>">

    <label>Name
        <input type="text" name="name" maxlength="100" required value="<?= htmlspecialchars($name) ?>">
    </label>

    <label>Country
        <input type="text" name="country" maxlength="50" value="<?= htmlspecialchars($country) ?>">
    </label>

    <button class="button" type="submit">Update Artist</button>
    <a class="button secondary" href="artist.php?id=<?= $id ?>">Cancel</a>
</form>

<?php require 'partials/footer.php'; ?>