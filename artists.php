<?php
require 'db.php';

$pageTitle = 'Artists';
$search = trim($_GET['search'] ?? '');
$countries = $_GET['country'] ?? [];
if (!is_array($countries)) {
    $countries = [];
}

$countryStmt = $pdo->query("
    SELECT DISTINCT country
    FROM artists
    WHERE country IS NOT NULL AND country <> ''
    ORDER BY country
");
$countryList = $countryStmt->fetchAll();

$params = [];
$where = [];

if ($search !== '') {
    $where[] = "name LIKE ?";
    $params[] = '%' . $search . '%';
}

$validCountries = [];
foreach ($countryList as $row) {
    $validCountries[] = $row['country'];
}
$countries = array_values(array_intersect($countries, $validCountries));

if ($countries) {
    $placeholders = implode(',', array_fill(0, count($countries), '?'));
    $where[] = "country IN ($placeholders)";
    foreach ($countries as $country) {
        $params[] = $country;
    }
}

$sql = "SELECT artist_id, name, country FROM artists";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artists = $stmt->fetchAll();

require 'partials/header.php';
?>

<h1>Artists</h1>

<form class="filters" method="get">
    <label>
        Search name
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="e.g. Davido">
    </label>

    <fieldset>
        <legend>Country</legend>
        <?php foreach ($countryList as $country): ?>
            <label class="checkbox">
                <input type="checkbox" name="country[]" value="<?= htmlspecialchars($country['country']) ?>"
                    <?= in_array($country['country'], $countries, true) ? 'checked' : '' ?>>
                <?= htmlspecialchars($country['country']) ?>
            </label>
        <?php endforeach; ?>
    </fieldset>

    <button class="button" type="submit">Filter</button>
    <a class="button secondary" href="artists.php">Reset</a>
</form>

<table>
    <thead>
        <tr><th>ID</th><th>Name</th><th>Country</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php foreach ($artists as $artist): ?>
        <tr>
            <td><?= (int)$artist['artist_id'] ?></td>
            <td><?= htmlspecialchars($artist['name']) ?></td>
            <td><?= $artist['country'] === null ? 'Unknown' : htmlspecialchars($artist['country']) ?></td>
            <td class="actions">
                <a href="artist.php?id=<?= (int)$artist['artist_id'] ?>">View</a>
                <a href="edit_artist.php?id=<?= (int)$artist['artist_id'] ?>">Edit</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if (!$artists): ?>
    <p class="empty">No artists found.</p>
<?php endif; ?>

<?php require 'partials/footer.php'; ?>