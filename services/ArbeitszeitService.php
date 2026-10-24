<?php

function berechneArbeitsminuten($startzeit, $endzeit, $pause)
{
    $start = strtotime($startzeit);
    $ende = strtotime($endzeit);

    $arbeitsminuten = ($ende - $start) / 60 - $pause;

    return max(0, $arbeitsminuten);
}


function berechneVerdienst($arbeitsminuten, $stundenlohn)
{
    $arbeitsstunden = $arbeitsminuten / 60;

    return $arbeitsstunden * $stundenlohn;
}


function berechneGesamtsummen($arbeitseinsaetze)
{
    $gesamtminuten = 0;
    $gesamtverdienst = 0;

    foreach ($arbeitseinsaetze as $einsatz) {

        $arbeitsminuten = berechneArbeitsminuten(
            $einsatz["startzeit"],
            $einsatz["endzeit"],
            $einsatz["pause"]
        );

        $verdienst = berechneVerdienst(
            $arbeitsminuten,
            $einsatz["stundenlohn"]
        );

        $gesamtminuten += $arbeitsminuten;
        $gesamtverdienst += $verdienst;
    }

    return [
        "minuten" => $gesamtminuten,
        "verdienst" => $gesamtverdienst
    ];
}


function berechneJobSummen($arbeitseinsaetze)
{
    $jobSummen = [];

    foreach ($arbeitseinsaetze as $einsatz) {

        $arbeitsminuten = berechneArbeitsminuten(
            $einsatz["startzeit"],
            $einsatz["endzeit"],
            $einsatz["pause"]
        );

        $verdienst = berechneVerdienst(
            $arbeitsminuten,
            $einsatz["stundenlohn"]
        );

        $job_id = $einsatz["job_id"];

        if (!isset($jobSummen[$job_id])) {
            $jobSummen[$job_id] = [
                "arbeitgeber" => $einsatz["arbeitgeber"],
                "bezeichnung" => $einsatz["bezeichnung"],
                "minuten" => 0,
                "verdienst" => 0
            ];
        }

        $jobSummen[$job_id]["minuten"] += $arbeitsminuten;
        $jobSummen[$job_id]["verdienst"] += $verdienst;
    }

    return $jobSummen;
}

