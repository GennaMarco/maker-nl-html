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
        <h1>Choose file <input type="file" name="FileToUpload"></h1>
        <h1>Choose directory images <input type="file" name="FilesImagesToUpload[]" webkitdirectory directory multiple></h1>
        <h1>Choose language
            <select name="Language">
                <option value="ITA">ITA</option>
                <option value="ENG">ENG</option>
                <option value="ESP">ESP</option>
            </select>
        </h1>
        <h1>Choose key word of notice
            <select name="keyWordNotice">
                <option value="NOTIZIA">NOTIZIA</option>
                <option value="NEWS ITEM">NEWS ITEM</option>
                <option value="NOTICIA">NOTICIA</option>
            </select>
        </h1>
        <h1>Choose key word for topic
            <select name="keyWordTopic">
                <option value="TOPIC">TOPIC</option>
                <option value="TEMA">TEMA</option>
            </select>
        </h1>
        <h1>Choose key word for title
            <select name="keyWordTitle">
                <option value="TITOLO">TITOLO</option>
                <option value="TITLE">TITLE</option>
                <option value="TÍTULO">TÍTULO</option>
            </select>
        </h1>
        <h1>Choose key word for abstract
            <select name="keyWordAbstract">
                <option value="ABSTRACT">ABSTRACT</option>
                <option value="RESUMEN">RESUMEN</option>
            </select>
        </h1>
        <input type="submit" value="Convert">
    </form>

</body>
</html>
