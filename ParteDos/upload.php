<?php
session_start();

// File upload handling
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlFile']) && $_FILES['xmlFile']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['xmlFile']['tmp_name'];
    $fileName = $_FILES['xmlFile']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExtension === 'xml') {
        $uploadFileDir = './uploaded_files/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true); // Create directory if it doesn't exist
        }

        $destination = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $_SESSION['uploadedFilePath'] = $destination;
            echo "File uploaded successfully.";
            echo "<br><a href='search.php'>Go to Search Tool</a>";
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "Only XML files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload XML</title>
</head>
<body>
    <h2>Upload OFAC XML File</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="xmlFile" accept=".xml" required>
        <input type="submit" value="Upload">
    </form>
</body>
</html>
