<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HourPay – Arbeitseinsätze</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="app-content">
        <h1>Arbeitseinsätze verwalten</h1>
        <p class="seitenbeschreibung">Verwalte deine Arbeitszeiten und Verdienste.</p>

        <?php if (!empty($fehlermeldung)): ?>
            <div class="fehlermeldung" role="alert">
                <strong>Fehler:</strong>
                <?= htmlspecialchars($fehlermeldung, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <?php if ($einsatz_bearbeiten): ?>
            <section class="dashboard-bereich">
                <h2>Arbeitseinsatz bearbeiten</h2>
                <form method="POST">
                    <input type="hidden" name="einsatz_id" value="<?= (int) $einsatz_bearbeiten["einsatz_id"] ?>">

                    <label>Job:</label>
                    <select name="job_id" required>
                        <?php foreach ($jobs as $job): ?>
                            <option value="<?= (int) $job["job_id"] ?>" <?= $job["job_id"] == $einsatz_bearbeiten["job_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($job["arbeitgeber"] . " – " . $job["bezeichnung"], ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Datum:</label>
                    <input type="date" name="datum" value="<?= htmlspecialchars($einsatz_bearbeiten["datum"], ENT_QUOTES, "UTF-8") ?>" required>

                    <label>Startzeit:</label>
                    <input type="time" name="startzeit" value="<?= htmlspecialchars($einsatz_bearbeiten["startzeit"], ENT_QUOTES, "UTF-8") ?>" required>

                    <label>Endzeit:</label>
                    <input type="time" name="endzeit" value="<?= htmlspecialchars($einsatz_bearbeiten["endzeit"], ENT_QUOTES, "UTF-8") ?>" required>

                    <label>Pause (Minuten):</label>
                    <input type="number" name="pause" min="0" value="<?= (int) $einsatz_bearbeiten["pause"] ?>" required>

                    <button type="submit" name="aktualisieren">Änderungen speichern</button>
                    <a href="arbeitseinsaetze.php" class="aktion-link">Abbrechen</a>
                </form>
            </section>
        <?php endif; ?>

        <section class="dashboard-bereich">
            <h2>Neuen Arbeitseinsatz hinzufügen</h2>
            <form method="POST">
                <label>Job:</label>
                <select name="job_id" required>
                    <option value="">Job auswählen</option>
                    <?php foreach ($jobs as $job): ?>
                        <option value="<?= (int) $job["job_id"] ?>">
                            <?= htmlspecialchars($job["arbeitgeber"] . " – " . $job["bezeichnung"], ENT_QUOTES, "UTF-8") ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Datum:</label>
                <input type="date" name="datum" required>

                <label>Startzeit:</label>
                <input type="time" name="startzeit" required>

                <label>Endzeit:</label>
                <input type="time" name="endzeit" required>

                <label>Pause (Minuten):</label>
                <input type="number" name="pause" min="0" value="0" required>

                <button type="submit" name="speichern">Arbeitseinsatz speichern</button>
            </form>
        </section>

        <section class="dashboard-bereich">
            <h2>Gespeicherte Arbeitseinsätze</h2>
            <form method="GET">
                <label>Monat auswählen:</label>
                <input type="month" name="monat" value="<?= $monat === "alle" ? "" : htmlspecialchars($monat, ENT_QUOTES, "UTF-8") ?>">
                <button type="submit">Anzeigen</button>
                <button type="submit" name="monat" value="alle">Alle anzeigen</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Arbeitgeber</th>
                        <th>Jobbezeichnung</th>
                        <th>Datum</th>
                        <th>Startzeit</th>
                        <th>Endzeit</th>
                        <th>Pause</th>
                        <th>Stundenlohn</th>
                        <th>Aktionen</th>
                        <th>Arbeitszeit</th>
                        <th>Verdienst</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arbeitseinsaetze as $einsatz): ?>
                        <?php
                        $minuten = berechneArbeitsminuten($einsatz["startzeit"], $einsatz["endzeit"], $einsatz["pause"]);
                        $verdienst = berechneVerdienst($minuten, $einsatz["stundenlohn"]);
                        ?>
                        <tr>
                            <td><?= (int) $einsatz["einsatz_id"] ?></td>
                            <td><?= htmlspecialchars($einsatz["arbeitgeber"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($einsatz["bezeichnung"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($einsatz["datum"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($einsatz["startzeit"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars($einsatz["endzeit"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= (int) $einsatz["pause"] ?> Min.</td>
                            <td><?= number_format((float) $einsatz["stundenlohn"], 2, ",", ".") ?> €</td>
                            <td>
                                <div class="tabellen-aktionen">
                                    <form method="GET">
                                        <input type="hidden" name="bearbeiten" value="<?= (int) $einsatz["einsatz_id"] ?>">
                                        <button type="submit">Bearbeiten</button>
                                    </form>
                                    <form method="POST">
                                        <input type="hidden" name="einsatz_id" value="<?= (int) $einsatz["einsatz_id"] ?>">
                                        <button type="submit" name="loeschen" class="button-gefahr">Löschen</button>
                                    </form>
                                </div>
                            </td>
                            <td><?= intdiv($minuten, 60) ?> Std. <?= $minuten % 60 ?> Min.</td>
                            <td><?= number_format($verdienst, 2, ",", ".") ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>
