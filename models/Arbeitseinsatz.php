<?php

// Neuen Arbeitseinsatz in der Datenbank speichern
function arbeitseinsatzSpeichern(
    PDO $pdo,
    int $job_id,
    string $datum,
    string $startzeit,
    string $endzeit,
    int $pause,
    float $stundenlohn
): void {
    $sql = "INSERT INTO arbeitseinsaetze
            (job_id, datum, startzeit, endzeit, pause, stundenlohn)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $job_id,
        $datum,
        $startzeit,
        $endzeit,
        $pause,
        $stundenlohn
    ]);
}


 // Gespeicherte Arbeitseinsätze laden und optional filtern
function alleArbeitseinsaetzeLaden(PDO $pdo, string $monat = "alle", int $job_id = 0): array
{
    $sql = "SELECT a.*, j.arbeitgeber, j.bezeichnung
            FROM arbeitseinsaetze a
            JOIN jobs j ON a.job_id = j.job_id
            WHERE 1 = 1";

    $parameter = [];

    // Optional nach Monat filtern
    if ($monat !== "alle") {
        $sql .= " AND a.datum >= ? AND a.datum < ?";
        $monatsbeginn = new DateTimeImmutable($monat . "-01");
        $parameter[] = $monatsbeginn->format("Y-m-d");
        $parameter[] = $monatsbeginn->modify("+1 month")->format("Y-m-d");
    }

    // Optional nach Job filtern
    if ($job_id > 0) {
        $sql .= " AND a.job_id = ?";
        $parameter[] = $job_id;
    }

    $sql .= " ORDER BY a.datum DESC, a.startzeit DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($parameter);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Vorhandenen Arbeitseinsatz aktualisieren
function arbeitseinsatzAktualisieren(
    PDO $pdo,
    int $einsatz_id,
    int $job_id,
    string $datum,
    string $startzeit,
    string $endzeit,
    int $pause,
    float $stundenlohn
): void {
    $sql = "UPDATE arbeitseinsaetze
            SET job_id = ?, datum = ?, startzeit = ?,
                endzeit = ?, pause = ?, stundenlohn = ?
            WHERE einsatz_id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $job_id,
        $datum,
        $startzeit,
        $endzeit,
        $pause,
        $stundenlohn,
        $einsatz_id
    ]);
}

// Arbeitseinsatz aus der Datenbank löschen
function arbeitseinsatzLoeschen(PDO $pdo, int $einsatz_id): void
{
    $sql = "DELETE FROM arbeitseinsaetze WHERE einsatz_id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$einsatz_id]);
}

// Einzelnen Arbeitseinsatz anhand seiner ID laden
function arbeitseinsatzNachIdLaden(PDO $pdo, int $einsatz_id): array|false
{
    $sql = "SELECT * FROM arbeitseinsaetze WHERE einsatz_id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$einsatz_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}