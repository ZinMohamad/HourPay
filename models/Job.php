<?php

function alleJobsLaden(PDO $pdo): array
{
    $sql = "SELECT * FROM jobs";
    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function jobSpeichern(PDO $pdo, string $arbeitgeber, string $bezeichnung, float $stundenlohn): void
{
    $sql = "INSERT INTO jobs
            (arbeitgeber, bezeichnung, stundenlohn)
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $arbeitgeber,
        $bezeichnung,
        $stundenlohn
    ]);
}


function jobAktualisieren(PDO $pdo, int $job_id, string $arbeitgeber, string $bezeichnung, float $stundenlohn): void
{
    $sql = "UPDATE jobs
            SET arbeitgeber = ?, bezeichnung = ?, stundenlohn = ?
            WHERE job_id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $arbeitgeber,
        $bezeichnung,
        $stundenlohn,
        $job_id
    ]);
}

function jobLoeschen(PDO $pdo, int $job_id): void
{
    $sql = "DELETE FROM jobs WHERE job_id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$job_id]);
}

function jobHatArbeitseinsaetze(PDO $pdo, int $job_id): bool
{
    $sql = "SELECT COUNT(*) FROM arbeitseinsaetze WHERE job_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$job_id]);

    return $stmt->fetchColumn() > 0;
}

function jobNachIdLaden(PDO $pdo, int $job_id): array|false
{
    $sql = "SELECT * FROM jobs WHERE job_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$job_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}