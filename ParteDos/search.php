<?php
session_start();

if (!isset($_SESSION['uploadedFilePath'])) {
    echo "No XML file uploaded. Please upload the file first.";
    exit;
}

$xmlFile = $_SESSION['uploadedFilePath'];

// Load the XML file
$xml = simplexml_load_file($xmlFile);

// Extract publication date
$publicationDate = $xml->publshInformation->Publish_Date ?? 'Unknown';

// Extract Cuban Entities and Iranian Individuals
$cubanEntities = [];
$iranianIndividuals = [];

foreach ($xml->sdnEntry as $entry) {
    $programList = $entry->programList->program;
    
    // Check for Cuban Entities
    if (in_array('CUBA', (array) $programList)) {
        $cubanEntities[] = (string) $entry->lastName . ', ' . (string) $entry->firstName;
    }
    
    // Check for Iranian Individuals
    if (in_array('IRAN', (array) $programList)) {
        $iranianIndividuals[] = (string) $entry->lastName . ', ' . (string) $entry->firstName;
    }
}

// Handle Search Form
$searchResult = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchTerm'])) {
    $searchTerm = strtolower(trim($_POST['searchTerm']));
    foreach ($xml->sdnEntry as $entry) {
        $name = strtolower($entry->firstName . ' ' . $entry->lastName);
        if (strpos($name, $searchTerm) !== false) {
            $searchResult = "Name found: " . $entry->firstName . ' ' . $entry->lastName . "<br>Alias: " . $entry->akaList->aka[0]->firstName . ' ' . $entry->akaList->aka[0]->lastName . "<br>ID: " . $entry->uid;
            break;
        }
    }

    if (empty($searchResult)) {
        $searchResult = "No results found for '$searchTerm'.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search OFAC List</title>
</head>
<body>
    <h2>OFAC List Information</h2>
    <p><strong>Publication Date:</strong> <?= htmlspecialchars($publicationDate); ?></p>

    <h3>Cuban Entities</h3>
    <ul>
        <?php foreach ($cubanEntities as $entity): ?>
            <li><?= htmlspecialchars($entity); ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>Iranian Individuals</h3>
    <ul>
        <?php foreach ($iranianIndividuals as $individual): ?>
            <li><?= htmlspecialchars($individual); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Search for Sanctioned Individuals/Entities</h2>
    <form action="search.php" method="POST">
        <input type="text" name="searchTerm" placeholder="Enter name to search" required>
        <input type="submit" value="Search">
    </form>

    <p><?= $searchResult; ?></p>
</body>
</html>
