<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HourPay – Jobs</title>
</head>
<body>

    <?php include __DIR__ . "/../includes/navigation.php"; ?>

    <h1>Jobs verwalten</h1>

    <?php if (!empty($fehlermeldung)): ?>
    <p style="color: red;">
        <?= htmlspecialchars($fehlermeldung) ?>
    </p>
    <?php endif; ?>

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