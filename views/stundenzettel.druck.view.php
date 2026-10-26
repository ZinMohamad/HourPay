<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HourPay – Stundenzettel</title>
    <link rel="stylesheet" href="assets/css/stundenzettel-druck.css">
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
                    <?= htmlspecialchars($jobName, ENT_QUOTES, "UTF-8") ?>
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
