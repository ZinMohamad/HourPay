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
        <section class="dashboard-bereich">
            <h2><?= $einsatz_bearbeiten ? "Arbeitseinsatz bearbeiten" : "Neuen Arbeitseinsatz hinzufügen" ?></h2>  
            <form method="POST" class="einsatz-formular">
                <?php if ($einsatz_bearbeiten): ?>
                    <input type="hidden" name="einsatz_id" value="<?= (int) $einsatz_bearbeiten["einsatz_id"] ?>">
                <?php endif; ?>

                <div class="formular-feld">
                    <label for="job_id">Job</label>
                    <select id="job_id" name="job_id" required>
                        <option value="">Job auswählen</option>
                        <?php foreach ($jobs as $job): ?>
                            <option value="<?= (int) $job["job_id"] ?>"
                                <?= ($einsatz_bearbeiten["job_id"] ?? 0) == $job["job_id"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($job["arbeitgeber"] . " – " . $job["bezeichnung"], ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="formular-zeile">
                    <div class="formular-feld">
                        <label for="datum">Datum</label>                       
                        <input type="date" id="datum" name="datum"
                            min="2000-01-01" max="2099-12-31"
                            value="<?= htmlspecialchars($einsatz_bearbeiten["datum"] ?? "", ENT_QUOTES, "UTF-8") ?>" required>
                    </div>

                    <div class="formular-feld">
                        <label for="pause">Pause (Minuten)</label>
                        <input type="number" id="pause" name="pause" min="0"
                            value="<?= (int) ($einsatz_bearbeiten["pause"] ?? 0) ?>" required>
                    </div>
                </div>

                <div class="formular-zeile">
                    <div class="formular-feld">
                        <label for="startzeit">Startzeit</label>
                        <input type="time" id="startzeit" name="startzeit"
                            value="<?= htmlspecialchars(substr($einsatz_bearbeiten["startzeit"] ?? "", 0, 5), ENT_QUOTES, "UTF-8") ?>" required>
                    </div>

                    <div class="formular-feld">
                        <label for="endzeit">Endzeit</label>
                        <input type="time" id="endzeit" name="endzeit"
                            value="<?= htmlspecialchars(substr($einsatz_bearbeiten["endzeit"] ?? "", 0, 5), ENT_QUOTES, "UTF-8") ?>" required>
                    </div>
                </div>

                <div class="formular-aktionen">
                    <button type="submit" name="<?= $einsatz_bearbeiten ? "aktualisieren" : "speichern" ?>">
                        <?= $einsatz_bearbeiten ? "Änderungen speichern" : "Arbeitseinsatz speichern" ?>
                    </button>

                    <?php if ($einsatz_bearbeiten): ?>
                        <a href="arbeitseinsaetze.php" class="aktion-link">Abbrechen</a>
                    <?php endif; ?>
                </div>
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
            <div class="arbeitseinsaetze-tabelle">
                <table>
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Datum</th>
                            <th>Beginn</th>
                            <th>Ende</th>
                            <th>Pause</th>
                            <th>Stundenlohn</th>
                            <th>Arbeitszeit</th>
                            <th>Verdienst</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>                       
                        <?php foreach ($arbeitseinsaetze as $einsatz): ?>
                            <?php
                            $minuten = berechneArbeitsminuten($einsatz["startzeit"], $einsatz["endzeit"], $einsatz["pause"]);
                            $verdienst = berechneVerdienst($minuten, $einsatz["stundenlohn"]);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($einsatz["arbeitgeber"] . " – " . $einsatz["bezeichnung"], ENT_QUOTES, "UTF-8") ?></td>
                                <td><?= date("d.m.Y", strtotime($einsatz["datum"])) ?></td>
                                <td><?= substr($einsatz["startzeit"], 0, 5) ?></td>
                                <td><?= substr($einsatz["endzeit"], 0, 5) ?></td>
                                <td><?= (int) $einsatz["pause"] ?> Min.</td>
                                <td><?= number_format((float) $einsatz["stundenlohn"], 2, ",", ".") ?> €</td>
                                <td><?= intdiv((int) $minuten, 60) ?> Std. <?= (int) $minuten % 60 ?> Min.</td>
                                <td><?= number_format($verdienst, 2, ",", ".") ?> €</td>
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
