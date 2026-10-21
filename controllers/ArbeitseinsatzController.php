<?php

// Datenbankverbindung laden
require_once __DIR__ . "/../config/database.php";

// Models laden
require_once __DIR__ . "/../models/Arbeitseinsatz.php";
require_once __DIR__ . "/../models/Job.php";

// Ausgewählten Monat aus der URL auslesen
$monat = $_GET["monat"] ?? date("Y-m");

// Monatsangabe überprüfen
if ($monat != "alle" && !preg_match("/^\d{4}-(0[1-9]|1[0-2])$/", $monat)) {
    $monat = date("Y-m");
}

// Arbeitseinsatz speichern
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["speichern"])) {

    $job_id = $_POST["job_id"] ?? 0;
    $datum = $_POST["datum"] ?? "";
    $startzeit = $_POST["startzeit"] ?? "";
    $endzeit = $_POST["endzeit"] ?? "";
    $pause = $_POST["pause"] ?? 0;

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0
        && $datum != "" && $startzeit != "" && $endzeit != ""
        && filter_var($pause, FILTER_VALIDATE_INT) !== false
        && $pause >= 0
        && $endzeit > $startzeit) {

        // Ausgewählten Job über das Model laden
        $job = jobNachIdLaden($pdo, (int) $job_id);

        if ($job) {

            // Arbeitseinsatz über das Model speichern
            arbeitseinsatzSpeichern(
                $pdo,
                (int) $job_id,
                $datum,
                $startzeit,
                $endzeit,
                (int) $pause,
                (float) $job["stundenlohn"]
            );

            header("Location: arbeitseinsaetze.php");
            exit;
        }
    }
}

// Arbeitseinsatz aktualisieren
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aktualisieren"])) {

    $einsatz_id = $_POST["einsatz_id"] ?? 0;
    $job_id = $_POST["job_id"] ?? 0;
    $datum = $_POST["datum"] ?? "";
    $startzeit = $_POST["startzeit"] ?? "";
    $endzeit = $_POST["endzeit"] ?? "";
    $pause = $_POST["pause"] ?? 0;

    if (filter_var($einsatz_id, FILTER_VALIDATE_INT) && $einsatz_id > 0
        && filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0
        && $datum != "" && $startzeit != "" && $endzeit != ""
        && filter_var($pause, FILTER_VALIDATE_INT) !== false
        && $pause >= 0
        && $endzeit > $startzeit) {

        // Ausgewählten Job über das Model laden
        $job = jobNachIdLaden($pdo, (int) $job_id);

        if ($job) {

        // Arbeitseinsatz über das Model aktualisieren
        arbeitseinsatzAktualisieren(
            $pdo,
            (int) $einsatz_id,
            (int) $job_id,
            $datum,
            $startzeit,
            $endzeit,
            (int) $pause,
            (float) $job["stundenlohn"]
        );

            header("Location: arbeitseinsaetze.php");
            exit;
        }
    }
}

// Arbeitseinsatz zum Bearbeiten laden
$einsatz_bearbeiten = null;

if (isset($_GET["bearbeiten"])) {

    $einsatz_id = $_GET["bearbeiten"];

    if (filter_var($einsatz_id, FILTER_VALIDATE_INT) && $einsatz_id > 0) {

        // Arbeitseinsatz über das Model laden
        $einsatz_bearbeiten = arbeitseinsatzNachIdLaden(
            $pdo,
            (int) $einsatz_id
        );
    }
}

// Alle vorhandenen Jobs über das Job-Model laden
$jobs = alleJobsLaden($pdo);

// Gespeicherte Arbeitseinsätze über das Model laden
$arbeitseinsaetze = alleArbeitseinsaetzeLaden($pdo, $monat);

// Arbeitseinsatz löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loeschen"])) {

    $einsatz_id = $_POST["einsatz_id"] ?? 0;

    if (filter_var($einsatz_id, FILTER_VALIDATE_INT) && $einsatz_id > 0) {

        // Arbeitseinsatz über das Model löschen
        arbeitseinsatzLoeschen($pdo, (int) $einsatz_id);

        header("Location: arbeitseinsaetze.php");
        exit;
    }
}