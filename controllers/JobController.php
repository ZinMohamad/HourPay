<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Job.php";

// Fehlermeldung für ungültige Eingaben
$fehlermeldung = "";

// Job speichern
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["speichern"])) {

    $arbeitgeber = trim($_POST["arbeitgeber"] ?? "");
    $bezeichnung = trim($_POST["bezeichnung"] ?? "");
    $stundenlohn = $_POST["stundenlohn"] ?? "";

    if ($arbeitgeber != "" && $bezeichnung != ""
        && is_numeric($stundenlohn) && $stundenlohn >= 0) {          
            if (jobExistiertBereits($pdo, $arbeitgeber, $bezeichnung)) {
                $fehlermeldung = "Dieser Job existiert bereits.";
            } else {
                jobSpeichern($pdo, $arbeitgeber, $bezeichnung, (float) $stundenlohn);

                header("Location: jobs.php");
                exit;
            }    
    }
}

// Job aktualisieren
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["aktualisieren"])) {

    $job_id = $_POST["job_id"] ?? 0;
    $arbeitgeber = trim($_POST["arbeitgeber"] ?? "");
    $bezeichnung = trim($_POST["bezeichnung"] ?? "");
    $stundenlohn = $_POST["stundenlohn"] ?? "";

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0
        && $arbeitgeber != "" && $bezeichnung != ""
        && is_numeric($stundenlohn) && $stundenlohn >= 0) {           
            if (jobExistiertBereits($pdo, $arbeitgeber, $bezeichnung, (int) $job_id)) {
                $fehlermeldung = "Ein Job mit diesem Arbeitgeber und dieser Bezeichnung existiert bereits.";
            } else {
                jobAktualisieren($pdo, (int) $job_id, $arbeitgeber, $bezeichnung, (float) $stundenlohn);

                header("Location: jobs.php");
                exit;
            }        
    }
}

// Job löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["loeschen"])) {
    $job_id = $_POST["job_id"] ?? 0;

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0) {

        if (jobHatArbeitseinsaetze($pdo, (int) $job_id)) {
            $fehlermeldung = "Dieser Job kann nicht gelöscht werden, weil noch Arbeitseinsätze vorhanden sind.";
        } else {
            try {
                jobLoeschen($pdo, (int) $job_id);
                header("Location: jobs.php");
                exit;
            } catch (PDOException $e) {
                $fehlermeldung = "Dieser Job konnte nicht gelöscht werden.";
            }
        }
    }
}

// Job anhand seiner ID aus der Datenbank laden, um ihn zu bearbeiten
$job_bearbeiten = null;

if (isset($_GET["bearbeiten"])) {

    $job_id = $_GET["bearbeiten"];

    if (filter_var($job_id, FILTER_VALIDATE_INT) && $job_id > 0) {

        $job_bearbeiten = jobNachIdLaden($pdo, (int) $job_id);
    }
}

// Gespeicherte Jobs über das Model laden
$jobs = alleJobsLaden($pdo);