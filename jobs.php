<?php

require_once "config/database.php";

// Job speichern
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["speichern"])) {

    $arbeitgeber = trim($_POST["arbeitgeber"] ?? "");
    $bezeichnung = trim($_POST["bezeichnung"] ?? "");
    $stundenlohn = $_POST["stundenlohn"] ?? "";

    if ($arbeitgeber != "" && $bezeichnung != ""
        && is_numeric($stundenlohn) && $stundenlohn >= 0) {

        $sql = "INSERT INTO jobs
                (arbeitgeber, bezeichnung, stundenlohn)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $arbeitgeber,
            $bezeichnung,
            $stundenlohn
        ]);

        header("Location: jobs.php");
        exit;
    }
}

// Job aktualisieren
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aktualisieren"])) {

    $job_id = $_POST["job_id"] ?? 0;
    $arbeitgeber = trim($_POST["arbeitgeber"] ?? "");
    $bezeichnung = trim($_POST["bezeichnung"] ?? "");
    $stundenlohn = $_POST["stundenlohn"] ?? "";

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0
        && $arbeitgeber != "" && $bezeichnung != ""
        && is_numeric($stundenlohn) && $stundenlohn >= 0) {

        $sql = "UPDATE jobs
                SET arbeitgeber = ?, bezeichnung = ?, stundenlohn = ?
                WHERE job_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $arbeitgeber,
            $bezeichnung,
            $stundenlohn,
            $job_id
        ]);

        header("Location: jobs.php");
        exit;
    }
}

// Job löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loeschen"])) {

    $job_id = $_POST["job_id"] ?? 0;

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0) {

        $sql = "DELETE FROM jobs WHERE job_id = ?";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$job_id]);

            header("Location: jobs.php");
            exit;

        } catch (PDOException $e) {
            echo "Dieser Job konnte nicht gelöscht werden.";
        }
    }
}

$job_bearbeiten = null;

if (isset($_GET["bearbeiten"])) {

    $job_id = $_GET["bearbeiten"];

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0) {

        $sql = "SELECT * FROM jobs WHERE job_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$job_id]);

        $job_bearbeiten = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Gespeicherte Jobs auslesen
$sql = "SELECT * FROM jobs";
$stmt = $pdo->query($sql);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HourPay – Jobs</title>
</head>
<body>

    <h1>Jobs verwalten</h1>

    <h2>Neuen Job hinzufügen</h2>

    <form method="POST">
        <label>Arbeitgeber:</label>
        <input type="text" name="arbeitgeber" required>

        <label>Jobbezeichnung:</label>
        <input type="text" name="bezeichnung" required>

        <label>Stundenlohn (€):</label>
        <input type="number" name="stundenlohn"
               step="0.01" min="0" required>

        <button type="submit" name="speichern">Job speichern</button>
    </form>

     <?php if ($job_bearbeiten): ?>

        <h2>Job bearbeiten</h2>

        <form method="POST">

            <input type="hidden" name="job_id"
                   value="<?= $job_bearbeiten["job_id"] ?>">

            <label>Arbeitgeber:</label>
            <input type="text" name="arbeitgeber"
                   value="<?= htmlspecialchars($job_bearbeiten["arbeitgeber"]) ?>"
                   required>

            <label>Jobbezeichnung:</label>
            <input type="text" name="bezeichnung"
                   value="<?= htmlspecialchars($job_bearbeiten["bezeichnung"]) ?>"
                   required>

            <label>Stundenlohn (€):</label>
            <input type="number" name="stundenlohn"
                   value="<?= htmlspecialchars($job_bearbeiten["stundenlohn"]) ?>"
                   step="0.01" min="0" required>

            <button type="submit" name="aktualisieren">
                Änderungen speichern
            </button>

        </form>

    <?php endif; ?>

    <h2>Gespeicherte Jobs</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Arbeitgeber</th>
            <th>Jobbezeichnung</th>
            <th>Stundenlohn</th>
            <th>Aktionen</th>
        </tr>

        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?= $job["job_id"] ?></td>
                <td><?= htmlspecialchars($job["arbeitgeber"]) ?></td>
                <td><?= htmlspecialchars($job["bezeichnung"]) ?></td>
                <td><?= htmlspecialchars($job["stundenlohn"]) ?> €</td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="job_id"
                               value="<?= $job["job_id"] ?>">

                        <button type="submit" name="loeschen">
                            Löschen
                        </button>
                    </form>

                    <form method="GET">
                        <input type="hidden" name="bearbeiten"
                        value="<?= $job["job_id"] ?>">

                        <button type="submit">
                            Bearbeiten
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>