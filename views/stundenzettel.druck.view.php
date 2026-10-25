
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HourPay – Stundenzettel</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 35px;
            font-family: Arial, sans-serif;
            color: #1e293b;
            background: #f1f5f9;
            font-size: 13px;
        }

        .blatt {
            max-width: 210mm;
            margin: auto;
            padding: 20mm;
            background: white;
        }

        .kopf {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
        }

        .untertitel {
            color: #64748b;
            margin-top: 5px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 25px;
        }

        .informationen {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info {
            flex: 1;
        }

        .info-label {
            color: #64748b;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .info-wert {
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: #e2e8f0;
            text-align: left;
            padding: 10px 7px;
            font-size: 11px;
        }

        td {
            padding: 10px 7px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }

        tr {
            break-inside: avoid;
        }

        .rechts {
            text-align: right;
        }

        .summen {
            display: flex;
            justify-content: flex-end;
            gap: 40px;
            border-top: 2px solid #2563eb;
            padding-top: 20px;
        }

        .summe-label {
            color: #64748b;
            font-size: 11px;
        }

        .summe-wert {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }

        .aktionen {
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            cursor: pointer;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .blatt {
                padding: 0;
                max-width: none;
            }

            .aktionen {
                display: none;
            }

            thead {
                display: table-header-group;
            }

            .kopf, .informationen, .summen {
                break-inside: avoid;
            }

            print-color-adjust: exact;
        }
    </style>
</head>
<body>

    <div class="aktionen">
        <button type="button" onclick="window.print()">
            Als PDF speichern / Drucken
        </button>
    </div>

    <main class="blatt">

        <header class="kopf">
            <div>
                <div class="logo">HourPay</div>
                <div class="untertitel">
                    Arbeitszeit- und Verdienstverwaltung
                </div>
            </div>
            <div>STUNDENZETTEL</div>
        </header>

        <h1>Arbeitszeitübersicht</h1>

        <section class="informationen">
            <div class="info">
                <div class="info-label">Zeitraum</div>
                <div class="info-wert">
                    <?= htmlspecialchars(
                        date("m/Y", strtotime($monat . "-01"))
                    ) ?>
                </div>
            </div>

            <div class="info">
                <div class="info-label">Job</div>
                <div class="info-wert">
                    <?php
                    $jobName = "Alle Jobs";

                    foreach ($jobs as $job) {
                        if ((int) $job["job_id"] === $job_id) {
                            $jobName = $job["arbeitgeber"]
                                . " – " . $job["bezeichnung"];
                            break;
                        }
                    }

                    echo htmlspecialchars($jobName);
                    ?>
                </div>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Job</th>
                    <th>Beginn</th>
                    <th>Ende</th>
                    <th>Pause</th>
                    <th>Arbeitszeit</th>
                    <th class="rechts">Verdienst</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($arbeitseinsaetze)): ?>
                    <tr>
                        <td colspan="7">
                            Keine Arbeitseinsätze vorhanden.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($arbeitseinsaetze as $einsatz): ?>
                        <?php
                        $minuten = berechneArbeitsminuten(
                            $einsatz["startzeit"],
                            $einsatz["endzeit"],
                            $einsatz["pause"]
                        );

                        $verdienst = berechneVerdienst(
                            $minuten,
                            $einsatz["stundenlohn"]
                        );
                        ?>
                        <tr>
                            <td>
                                <?= date(
                                    "d.m.Y",
                                    strtotime($einsatz["datum"])
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $einsatz["arbeitgeber"]
                                    . " – "
                                    . $einsatz["bezeichnung"]
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    substr($einsatz["startzeit"], 0, 5)
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    substr($einsatz["endzeit"], 0, 5)
                                ) ?>
                            </td>
                            <td>
                                <?= (int) $einsatz["pause"] ?> Min.
                            </td>
                            <td>
                                <?= intdiv((int) $minuten, 60) ?> Std.
                                <?= (int) $minuten % 60 ?> Min.
                            </td>
                            <td class="rechts">
                                <?= number_format(
                                    $verdienst, 2, ",", "."
                                ) ?> €
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <section class="summen">
            <div>
                <div class="summe-label">Gesamtarbeitszeit</div>
                <div class="summe-wert">
                    <?= intdiv(
                        (int) $gesamtsummen["minuten"], 60
                    ) ?> Std.
                    <?= (int) $gesamtsummen["minuten"] % 60 ?> Min.
                </div>
            </div>

            <div>
                <div class="summe-label">Gesamtverdienst</div>
                <div class="summe-wert">
                    <?= number_format(
                        $gesamtsummen["verdienst"], 2, ",", "."
                    ) ?> €
                </div>
            </div>
        </section>

    </main>

</body>
</html>
