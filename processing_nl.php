<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06/04/2018
 * Time: 12:18
 */

require_once('classes/DocConversion.php');
require_once('functions.php');

const COLORS_LABELS = array
(
    '#0655fa',
    '#ff5a10',
    '#41b9e6',
    '#e71401',
    '#55bd5a',
);
const UPLOAD_DIR = 'uploaded_files';
const DOWNLOAD_DIR = 'downloaded_files';
const WORD_NOTICE = 'NOTIZIA';
const TEMPLATE_DIR = 'NL_TEMPLATE';

$file_tmp = $_FILES['FileToUpload']['tmp_name'];
$file_name = $_FILES['FileToUpload']['name'];

if (move_uploaded_file($file_tmp, UPLOAD_DIR . '/' . $file_name))
{
    echo 'File uploaded!';
}
else
{
    echo 'File not uploaded!';
}

$docObj = new DocConversion(UPLOAD_DIR . '/' . $file_name);
$docText = $docObj->convertToText();

// Count number of notices in the string
$count_notices = preg_match_all('/\b'.WORD_NOTICE.'/', $docText);

$doc = new DOMDocument;
$doc->loadHtmlFile( TEMPLATE_DIR.'/newsletter_TEMP.html');

$parent = $doc->getElementById('id_notices_to_append');

for($i = 0; $i < $count_notices; $i++)
{
    // Read of the texts from the document text
    $topic = getStringBetweenWords($docText, "TOPIC", "TITOLO");
    $title = getStringBetweenWords($docText, "TITOLO", "ABSTRACT");
    $abstract = trim(getStringBetweenWords($docText, "ABSTRACT", PHP_EOL));
    $link = getStringBetweenWords($docText, PHP_EOL, PHP_EOL);

    // Overwrite of the document text form the last position of link (end of notice)
    $posLink = strpos($docText, $link);
    $docText = substr($docText, $posLink);

    if (substr($abstract, -1) != '.')
    {
        $abstract .= '.';
    }

    if($i % 2 == 0)
    {
        $alignUp = 'left';
        $alignDown = 'right';
    }
    else
    {
        $alignUp = 'right';
        $alignDown = 'left';
    }

    $child = $doc->createCDATASection
    (
        PHP_EOL.PHP_EOL.'<!-- NOTIZIA '.($i + 1).'-->
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="container600" style="border-width:0;" >
                        <tbody>
                        <tr>
                            <td align="center" style="background-color:#ffffff;font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >
                                <table width="258" border="0" align="'.$alignUp.'" cellpadding="0" cellspacing="0" class="container600" style="border-width:0;" >
                                    <tbody>
                                    <tr>
                                        <td style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-width:0;" >
                                                <tbody>
                                                <tr>
                                                    <td align="left" style="background-color:#ffffff;font-family:Arial;color:'.COLORS_LABELS[$i].';font-size:14px;border-width:5px;border-style:solid;border-color:#ffffff;" ><strong>'.$topic.'</strong></td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color:#ffffff;font-family:Arial;color:#000001;font-size:21px;border-width:5px;border-style:solid;border-color:#ffffff;line-height:24px;" >'.$title.'</td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="background-color:#ffffff;font-family:Arial;color:#6f6f6e;font-size:15px;border-width:5px;border-style:solid;border-color:#ffffff;line-height:20px;" >
                                                        '.$abstract.'
                                                        <br><a  href="#" target="_blank" style="color:'.COLORS_LABELS[$i].';text-decoration:none;" >'.$link.'</a><br>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="258" border="0" align="'.$alignDown.'" cellpadding="0" cellspacing="0" class="container600" style="border-width:0;" >
                                    <tbody>
                                    <tr>
                                        <td style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >
                                            <img src="img/1_rinnovabili.jpg" width="100%" alt="" style="display:block;border-width:0;" />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="container600" style="border-width:0;" >
                        <tbody>
                        <tr>
                            <td width="100%" style="background-color:#eeeeee;font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>'
    );

    $parent->appendChild($child);
}

$marginEnd = $doc->createCDATASection
(
    '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="container600" style="border-width:0;" >
                                    <tbody>
                                    <tr>
                                        <td width="100%" height="40" style="background-color:#eeeeee;font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>'
);

$parent->appendChild($marginEnd);

$doc->saveHTMLFile(DOWNLOAD_DIR.'/newsletter_ITA.html');
