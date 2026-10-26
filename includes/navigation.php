<?php
// Aktuelle Seite ermitteln
$aktuelleSeite = basename($_SERVER["SCRIPT_NAME"]);
?>

<nav class="app-nav">
    <a href="index.php" class="<?= $aktuelleSeite === "index.php" ? "aktiv" : "" ?>">Startseite</a>
    <a href="jobs.php" class="<?= $aktuelleSeite === "jobs.php" ? "aktiv" : "" ?>">Jobs verwalten</a>
    <a href="arbeitseinsaetze.php" class="<?= $aktuelleSeite === "arbeitseinsaetze.php" ? "aktiv" : "" ?>">Arbeitseinsätze</a>
    <a href="stundenzettel.php" class="<?= $aktuelleSeite === "stundenzettel.php" ? "aktiv" : "" ?>">Stundenzettel</a>
</nav>

