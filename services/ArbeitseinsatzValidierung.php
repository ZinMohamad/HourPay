
<?php

// Eingaben eines Arbeitseinsatzes überprüfen
function validiereArbeitseinsatz(
    $job_id,
    $datum,
    $startzeit,
    $endzeit,
    $pause
): bool {

    // Job-ID muss eine positive Ganzzahl sein
    if (
        filter_var(
            $job_id,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        ) === false
    ) {
        return false;
    }

    // Datum muss gültig sein
    if (!is_string($datum)) {
        return false;
    }

    $datumObjekt = DateTimeImmutable::createFromFormat(
        "!Y-m-d",
        $datum
    );

    if (
        $datumObjekt === false ||
        $datumObjekt->format("Y-m-d") !== $datum
    ) {
        return false;
    }

    // Start- und Endzeit müssen gültig sein
    foreach ([$startzeit, $endzeit] as $zeit) {

        if (
            !is_string($zeit) ||
            !preg_match(
                "/^([01][0-9]|2[0-3]):[0-5][0-9]$/",
                $zeit
            )
        ) {
            return false;
        }
    }

    // Endzeit muss nach der Startzeit liegen
    if ($endzeit <= $startzeit) {
        return false;
    }

    // Pause muss eine nicht negative Ganzzahl sein
    if (
        filter_var(
            $pause,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 0]]
        ) === false
    ) {
        return false;
    }

    // Schichtdauer in Minuten berechnen
    $beginn = strtotime($startzeit);
    $ende = strtotime($endzeit);

    $schichtdauer = ($ende - $beginn) / 60;

    // Pause darf nicht länger als die Schicht sein
    if ((int) $pause > $schichtdauer) {
        return false;
    }

    return true;
}
