<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HourPay – Jobs verwalten</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="app-content">
        <h1>Jobs verwalten</h1>
        <p class="seitenbeschreibung">Erstelle und verwalte deine Jobs und Stundenlöhne.</p>

        <?php if (!empty($fehlermeldung)): ?>
            <div class="fehlermeldung" role="alert">
                <?= htmlspecialchars($fehlermeldung, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <section class="dashboard-bereich">
            <h2><?= $job_bearbeiten ? "Job bearbeiten" : "Neuen Job hinzufügen" ?></h2>

            <form method="POST">
                <?php if ($job_bearbeiten): ?>
                    <input type="hidden" name="job_id" value="<?= (int) $job_bearbeiten["job_id"] ?>">
                <?php endif; ?>

                <label for="arbeitgeber">Arbeitgeber:</label>
                <input type="text" id="arbeitgeber" name="arbeitgeber"
                    value="<?= htmlspecialchars($job_bearbeiten["arbeitgeber"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>

                <label for="bezeichnung">Jobbezeichnung:</label>
                <input type="text" id="bezeichnung" name="bezeichnung"
                    value="<?= htmlspecialchars($job_bearbeiten["bezeichnung"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>

                <label for="stundenlohn">Stundenlohn (€):</label>
                <input type="number" id="stundenlohn" name="stundenlohn"
                    value="<?= htmlspecialchars((string) ($job_bearbeiten["stundenlohn"] ?? ""), ENT_QUOTES, "UTF-8") ?>"
                    step="0.01" min="0" required>

                <button type="submit" name="<?= $job_bearbeiten ? "aktualisieren" : "speichern" ?>">
                    <?= $job_bearbeiten ? "Änderungen speichern" : "Job speichern" ?>
                </button>

                <?php if ($job_bearbeiten): ?>
                    <a href="jobs.php" class="aktion-link">Abbrechen</a>
                <?php endif; ?>
            </form>
        </section>

        <section class="dashboard-bereich">
            <h2>Gespeicherte Jobs</h2>

            <?php if (empty($jobs)): ?>
                <p>Es wurden noch keine Jobs angelegt.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Arbeitgeber</th>
                            <th>Jobbezeichnung</th>
                            <th>Stundenlohn</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?= (int) $job["job_id"] ?></td>
                                <td><?= htmlspecialchars($job["arbeitgeber"], ENT_QUOTES, "UTF-8") ?></td>
                                <td><?= htmlspecialchars($job["bezeichnung"], ENT_QUOTES, "UTF-8") ?></td>
                                <td><?= number_format((float) $job["stundenlohn"], 2, ",", ".") ?> €</td>
                                <td>
                                    <div class="tabellen-aktionen">
                                        <form method="GET">
                                            <button type="submit" name="bearbeiten" value="<?= (int) $job["job_id"] ?>">
                                                Bearbeiten
                                            </button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="job_id" value="<?= (int) $job["job_id"] ?>">
                                            <button type="submit" name="loeschen" class="button-gefahr">
                                                Löschen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>
