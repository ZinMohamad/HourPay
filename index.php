<?php

// Datenbankverbindung laden
require_once __DIR__ . "/config/database.php";

// Vorhandene Models und Service laden
require_once __DIR__ . "/models/Job.php";
require_once __DIR__ . "/models/Arbeitseinsatz.php";
require_once __DIR__ . "/services/ArbeitszeitService.php";

// Aktuellen Monat bestimmen
$monat = date("Y-m");

// Jobs und Arbeitseinsätze laden
$jobs = alleJobsLaden($pdo);
$arbeitseinsaetze = alleArbeitseinsaetzeLaden($pdo, $monat);

// Kennzahlen für das Dashboard berechnen
$gesamtsummen = berechneGesamtsummen($arbeitseinsaetze);
$anzahlJobs = count($jobs);
$anzahlEinsaetze = count($arbeitseinsaetze);

// HTML-Oberfläche der Startseite laden
require_once __DIR__ . "/views/index.view.php";

