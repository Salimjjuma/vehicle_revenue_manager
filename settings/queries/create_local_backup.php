<?php

require_once __DIR__ . "/../../fxn/config.php";

$downloadsDir = __DIR__ . '/../../../backups/'; // Ensure this folder is writable.


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ensure the downloads directory exists.
    if (!is_dir($downloadsDir)) {
        mkdir($downloadsDir, 0755, true);
    }

    // Generate a unique filename for the backup file.
    $backupFile = $downloadsDir . '/backup_' . date('Y-m-d_H-i-s') . '.sql';

    try {

        // Fetch all table names from the database.
        $tables = $dbh->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        $backupContent = "";
        foreach ($tables as $table) {
            // Get table creation statement.
            $createStmt = $dbh->query("SHOW CREATE TABLE $table")->fetch(PDO::FETCH_ASSOC);
            $backupContent .= $createStmt['Create Table'] . ";\n\n";

            // Fetch table data.
            $rows = $dbh->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $values = array_map([$dbh, 'quote'], array_values($row));
                $backupContent .= "INSERT INTO $table VALUES(" . implode(',', $values) . ");\n";
            }
            $backupContent .= "\n\n";
        }

        // Save the content to a file.
        file_put_contents($backupFile, $backupContent);

        // Provide a success message and download link.
        echo json_encode([
            'success' => true,
            'message' => 'Backup created successfully!',
            'download_url' => 'downloads/' . basename($backupFile),
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error creating backup: ' . $e->getMessage(),
        ]);
    }
}