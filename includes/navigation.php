<nav class="app-nav">
    <a href="index.php" class="<?= basename($_SERVER["PHP_SELF"]) === "index.php" ? "aktiv" : "" ?>">Startseite</a>
    <a href="jobs.php" class="<?= basename($_SERVER["PHP_SELF"]) === "jobs.php" ? "aktiv" : "" ?>">Jobs verwalten</a>
    <a href="arbeitseinsaetze.php" class="<?= basename($_SERVER["PHP_SELF"]) === "arbeitseinsaetze.php" ? "aktiv" : "" ?>">Arbeitseinsätze</a>
    <a href="stundenzettel.php" class="<?= basename($_SERVER["PHP_SELF"]) === "stundenzettel.php" ? "aktiv" : "" ?>">Stundenzettel</a>
</nav>
