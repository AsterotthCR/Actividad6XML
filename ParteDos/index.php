<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Upload OFAC List</h1>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file">Choose OFAC consolidated.xml file:</label>
        <input type="file" name="file" id="file" required>
        <input type="submit" value="Upload">
    </form>
</body>
</html>