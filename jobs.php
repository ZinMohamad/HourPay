<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $arbeitgeber = trim($_POST["arbeitgeber"] ?? "");
    $bezeichnung = trim($_POST["bezeichnung"] ?? "");
    $stundenlohn = $_POST["stundenlohn"] ?? "";

    if ($arbeitgeber != "" && $bezeichnung != ""
        && is_numeric($stundenlohn) && $stundenlohn >= 0) {

        $sql = "INSERT INTO jobs
                (arbeitgeber, bezeichnung, stundenlohn)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $arbeitgeber,
            $bezeichnung,
            $stundenlohn
        ]);

        echo "Job erfolgreich gespeichert!";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HourPay – Jobs</title>
</head>
<body>

    <h1>Jobs verwalten</h1>

    <h2>Neuen Job hinzufügen</h2>

    <form method="POST">
        <label>Arbeitgeber:</label>
        <input type="text" name="arbeitgeber" required>

        <label>Jobbezeichnung:</label>
        <input type="text" name="bezeichnung" required>

        <label>Stundenlohn (€):</label>
        <input type="number" name="stundenlohn"
               step="0.01" min="0" required>

        <button type="submit">Job speichern</button>
    </form>

</body>
</html>