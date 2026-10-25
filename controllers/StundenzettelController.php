
<?php

// Datenbankverbindung laden
require_once __DIR__ . "/../config/database.php";

// Models laden
require_once __DIR__ . "/../models/Job.php";
require_once __DIR__ . "/../models/Arbeitseinsatz.php";

// Service für Berechnungen laden
require_once __DIR__ . "/../services/ArbeitszeitService.php";

// Ausgewählten Monat ermitteln
$monat = $_GET["monat"] ?? date("Y-m");

// Monat überprüfen
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $monat)) {
    $monat = date("Y-m");
}

// Ausgewählten Job ermitteln
$job_id = filter_var(
    $_GET["job_id"] ?? 0,
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 0]]
);

if ($job_id === false) {
    $job_id = 0;
}

// Jobs für die Auswahl laden
$jobs = alleJobsLaden($pdo);

// Arbeitseinsätze nach Monat und Job laden
$arbeitseinsaetze = alleArbeitseinsaetzeLaden($pdo, $monat, $job_id);

// Gesamtsummen berechnen
$gesamtsummen = berechneGesamtsummen($arbeitseinsaetze);
