<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HourPay – Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include __DIR__ . "/../includes/header.php"; ?>

    <main class="app-content">
        <h1>Dashboard</h1>
        <p class="seitenbeschreibung">
            Deine Arbeitszeiten und Verdienste im Überblick.
        </p>

        <section class="dashboard-grid">

            <article class="dashboard-karte">
                <h2>Arbeitszeit diesen Monat</h2>
                <p class="dashboard-wert">
                    <?= intdiv((int) $gesamtsummen["minuten"], 60) ?> Std.
                    <?= (int) $gesamtsummen["minuten"] % 60 ?> Min.
                </p>
            </article>

            <article class="dashboard-karte">
                <h2>Verdienst diesen Monat</h2>
                <p class="dashboard-wert">
                    <?= number_format($gesamtsummen["verdienst"], 2, ",", ".") ?> €
                </p>
            </article>

            <article class="dashboard-karte">
                <h2>Verwaltete Jobs</h2>
                <p class="dashboard-wert"><?= $anzahlJobs ?></p>
            </article>

            <article class="dashboard-karte">
                <h2>Arbeitseinsätze diesen Monat</h2>
                <p class="dashboard-wert"><?= $anzahlEinsaetze ?></p>
            </article>

        </section>
    </main>

</body>
</html>
