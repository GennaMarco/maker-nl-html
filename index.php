<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06/04/2018
 * Time: 12:16
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Converter</title>
</head>
<body>

    <form action="processing_nl.php" method="post" enctype="multipart/form-data">
        <h1>Choose file</h1>
        <input type="file" name="FileToUpload">
        <input type="submit" value="Convert">
    </form>

</body>
</html>
