<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06/04/2018
 * Time: 12:18
 */

require_once('classes/DocConversion.php');

$upload_dir = 'files';
$file_tmp = $_FILES['FileToUpload']['tmp_name'];
$file_name = $_FILES['FileToUpload']['name'];

if (move_uploaded_file($file_tmp, $upload_dir . '/' . $file_name))
{
    echo 'File uploaded!<br>';
}
else
{
    echo 'File not uploaded!<br>';
}

$docObj = new DocConversion($upload_dir . '/' . $file_name);

echo $docText = $docObj->convertToText();