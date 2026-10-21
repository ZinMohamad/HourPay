<?php

// Controller laden
require_once __DIR__ . "/controllers/ArbeitseinsatzController.php";
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HourPay – Arbeitseinsätze</title>
</head>
<body>

    <?php include __DIR__ . "/includes/navigation.php"; ?>

    <h1>Arbeitseinsätze verwalten</h1>

    <?php if ($einsatz_bearbeiten): ?>

        <h2>Arbeitseinsatz bearbeiten</h2>

        <form method="POST">

            <input type="hidden" name="einsatz_id"
                   value="<?= $einsatz_bearbeiten["einsatz_id"] ?>">

            <label>Job:</label>
            <select name="job_id" required>

                <?php foreach ($jobs as $job): ?>
                    <option value="<?= $job["job_id"] ?>"
                        <?= $job["job_id"] == $einsatz_bearbeiten["job_id"] ? "selected" : "" ?>>

                        <?= htmlspecialchars($job["arbeitgeber"]) ?>
                        – <?= htmlspecialchars($job["bezeichnung"]) ?>

                    </option>
                <?php endforeach; ?>

            </select>

            <label>Datum:</label>
            <input type="date" name="datum"
                   value="<?= htmlspecialchars($einsatz_bearbeiten["datum"]) ?>" required>

            <label>Startzeit:</label>
            <input type="time" name="startzeit"
                   value="<?= htmlspecialchars($einsatz_bearbeiten["startzeit"]) ?>" required>

            <label>Endzeit:</label>
            <input type="time" name="endzeit"
                   value="<?= htmlspecialchars($einsatz_bearbeiten["endzeit"]) ?>" required>

            <label>Pause (Minuten):</label>
            <input type="number" name="pause" min="0"
                   value="<?= htmlspecialchars($einsatz_bearbeiten["pause"]) ?>" required>

            <button type="submit" name="aktualisieren">
                Änderungen speichern
            </button>

            <a href="arbeitseinsaetze.php">Abbrechen</a>

        </form>

    <?php endif; ?>

    <h2>Neuen Arbeitseinsatz hinzufügen</h2>

    <form method="POST">

        <label>Job:</label>
        <select name="job_id" required>
            <option value="">Job auswählen</option>

            <?php foreach ($jobs as $job): ?>
                <option value="<?= $job["job_id"] ?>">
                    <?= htmlspecialchars($job["arbeitgeber"]) ?>
                    – <?= htmlspecialchars($job["bezeichnung"]) ?>
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

        <button type="submit" name="speichern">
            Arbeitseinsatz speichern
        </button>

    </form>

    <h2>Gespeicherte Arbeitseinsätze</h2>

    <form method="GET">

        <label>Monat auswählen:</label>

        <input type="month" name="monat"
            value="<?= htmlspecialchars($monat) ?>">

        <button type="submit">
            Anzeigen
        </button>

            <button type="submit" name="monat" value="alle">
        Alle anzeigen
    </button>

    </form>

    <?php
    // Gesamtsummen vorbereiten
    $gesamtminuten = 0;
    $gesamtverdienst = 0;

    // Summen für jeden Job vorbereiten
    $jobSummen = [];
    
    ?>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Arbeitgeber</th>
            <th>Jobbezeichnung</th>
            <th>Datum</th>
            <th>Startzeit</th>
            <th>Endzeit</th>
            <th>Pause (Min.)</th>
            <th>Stundenlohn (€)</th>
            <th>Aktionen</th>
            <th>Arbeitsstunden</th>
            <th>Verdienst (€)</th>
        </tr>

        <?php foreach ($arbeitseinsaetze as $einsatz): ?>

            <?php
                // Arbeitszeit in Minuten berechnen
                $start = strtotime($einsatz["startzeit"]);
                $ende = strtotime($einsatz["endzeit"]);

                $arbeitsminuten = ($ende - $start) / 60 - $einsatz["pause"];

                // Arbeitsstunden und Verdienst berechnen
                $arbeitsstunden = max(0, $arbeitsminuten / 60);
                $verdienst = $arbeitsstunden * $einsatz["stundenlohn"];

                // Gesamtsummen berechnen
                $gesamtminuten += max(0, $arbeitsminuten);
                $gesamtverdienst += $verdienst;

                
                // Arbeitszeit und Verdienst pro Job zusammenrechnen
                $job_id = $einsatz["job_id"];

                if (!isset($jobSummen[$job_id])) {
                    $jobSummen[$job_id] = [
                        "arbeitgeber" => $einsatz["arbeitgeber"],
                        "bezeichnung" => $einsatz["bezeichnung"],
                        "minuten" => 0,
                        "verdienst" => 0
                    ];
                }

                $jobSummen[$job_id]["minuten"] += max(0, $arbeitsminuten);
                $jobSummen[$job_id]["verdienst"] += $verdienst;

            ?>

            <tr>
                <td><?= $einsatz["einsatz_id"] ?></td>
                <td><?= htmlspecialchars($einsatz["arbeitgeber"]) ?></td>
                <td><?= htmlspecialchars($einsatz["bezeichnung"]) ?></td>
                <td><?= htmlspecialchars($einsatz["datum"]) ?></td>
                <td><?= htmlspecialchars($einsatz["startzeit"]) ?></td>
                <td><?= htmlspecialchars($einsatz["endzeit"]) ?></td>
                <td><?= htmlspecialchars($einsatz["pause"]) ?></td>
                <td><?= htmlspecialchars($einsatz["stundenlohn"]) ?> €</td>

                <td>
                    <form method="POST">
                        <input type="hidden" name="einsatz_id"
                               value="<?= $einsatz["einsatz_id"] ?>">

                        <button type="submit" name="loeschen">
                            Löschen
                        </button>
                    </form>

                    <form method="GET">
                        <input type="hidden" name="bearbeiten"
                               value="<?= $einsatz["einsatz_id"] ?>">

                        <button type="submit">
                            Bearbeiten
                        </button>
                    </form>
                </td>

                <td>
                    <?= floor($arbeitsminuten / 60) ?> Std.
                    <?= $arbeitsminuten % 60 ?> Min.
                </td>
                <td><?= number_format($verdienst, 2, ",", ".") ?> €</td>
            </tr>

        <?php endforeach; ?>

    </table>

    <h2>Gesamtübersicht</h2>

    <p>
        Gesamtarbeitszeit:
        <?= floor($gesamtminuten / 60) ?> Std.
        <?= $gesamtminuten % 60 ?> Min.
    </p>

    <p>
        Gesamtverdienst:
        <?= number_format($gesamtverdienst, 2, ",", ".") ?> €
    </p>

    
<h2>Verdienst nach Jobs</h2>

<table border="1">
    <tr>
        <th>Arbeitgeber</th>
        <th>Jobbezeichnung</th>
        <th>Arbeitszeit</th>
        <th>Verdienst (€)</th>
    </tr>

    <?php foreach ($jobSummen as $job): ?>
        <tr>
            <td><?= htmlspecialchars($job["arbeitgeber"]) ?></td>
            <td><?= htmlspecialchars($job["bezeichnung"]) ?></td>
            <td>
                <?= floor($job["minuten"] / 60) ?> Std.
                <?= $job["minuten"] % 60 ?> Min.
            </td>
            <td>
                <?= number_format($job["verdienst"], 2, ",", ".") ?> €
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
