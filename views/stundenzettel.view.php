
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HourPay – Stundenzettel</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <h1>Stundenzettel</h1>

    <?php include __DIR__ . "/../includes/navigation.php"; ?>

    <h2>Filter</h2>

    <form method="GET">
        <label for="monat">Monat:</label>
        <input
            type="month"
            id="monat"
            name="monat"
            value="<?= htmlspecialchars($monat) ?>"
            required
        >

        <label for="job_id">Job:</label>
        <select id="job_id" name="job_id">
            <option value="0">Alle Jobs</option>

            <?php foreach ($jobs as $job): ?>
                <option
                    value="<?= (int) $job["job_id"] ?>"
                    <?= $job_id == $job["job_id"] ? "selected" : "" ?>
                >
                    <?= htmlspecialchars($job["arbeitgeber"] . " – " . $job["bezeichnung"]) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Anzeigen</button>
    </form>

    <h2>Arbeitseinsätze</h2>

    <?php if (empty($arbeitseinsaetze)): ?>
        <p>Keine Arbeitseinsätze für diese Auswahl vorhanden.</p>
    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Job</th>
                    <th>Beginn</th>
                    <th>Ende</th>
                    <th>Pause</th>
                    <th>Arbeitszeit</th>
                    <th>Verdienst</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arbeitseinsaetze as $einsatz): ?>
                    <?php
                        $arbeitsminuten = berechneArbeitsminuten(
                            $einsatz["startzeit"],
                            $einsatz["endzeit"],
                            $einsatz["pause"]
                        );

                        $verdienst = berechneVerdienst(
                            $arbeitsminuten,
                            $einsatz["stundenlohn"]
                        );
                    ?>

                    <tr>
                        <td><?= date("d.m.Y", strtotime($einsatz["datum"])) ?></td>
                        <td>
                            <?= htmlspecialchars($einsatz["arbeitgeber"] . " – " . $einsatz["bezeichnung"]) ?>
                        </td>
                        <td><?= htmlspecialchars(substr($einsatz["startzeit"], 0, 5)) ?></td>
                        <td><?= htmlspecialchars(substr($einsatz["endzeit"], 0, 5)) ?></td>
                        <td><?= (int) $einsatz["pause"] ?> Min.</td>
                        <td>
                            <?= intdiv((int) $arbeitsminuten, 60) ?> Std.
                            <?= (int) $arbeitsminuten % 60 ?> Min.
                        </td>
                        <td><?= number_format($verdienst, 2, ",", ".") ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <h2>Gesamtsummen</h2>

    <p>
        <strong>Gesamtarbeitszeit:</strong>
        <?= intdiv((int) $gesamtsummen["minuten"], 60) ?> Std.
        <?= (int) $gesamtsummen["minuten"] % 60 ?> Min.
    </p>

    <p>
        <strong>Gesamtverdienst:</strong>
        <?= number_format($gesamtsummen["verdienst"], 2, ",", ".") ?> €
    </p>

</body>
</html>
