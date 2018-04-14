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
    '#0655fa',
    '#ff5a10',
    '#41b9e6',
    '#e71401',
    '#55bd5a',
);
const UPLOAD_DIR = 'uploaded_files';
const DOWNLOAD_DIR = 'downloaded_files';
const TEMPLATE_DIR = 'NL_TEMPLATE';
const IMG_DIR = 'img';

$newsletterDir = 'newsletter_'.$_POST['Language'];
$keyWordNotice = $_POST['keyWordNotice'];
$keyWordTopic= $_POST['keyWordTopic'];
$keyWordTitle = $_POST['keyWordTitle'];
$keyWordAbstract = $_POST['keyWordAbstract'];
$file_tmp = $_FILES['FileToUpload']['tmp_name'];
$file_name = $_FILES['FileToUpload']['name'];

$pathToNewsletter = DOWNLOAD_DIR.'/'.$newsletterDir;
$pathToNewsletterImg = DOWNLOAD_DIR.'/'.$newsletterDir.'/'.IMG_DIR;
$pathToNewsletterImgDefault = TEMPLATE_DIR.'/img_default';

if ( !is_dir($pathToNewsletter))
{
    mkdir($pathToNewsletter);
    mkdir($pathToNewsletterImg);
}

if ( is_dir($pathToNewsletterImgDefault))
{
    copy($pathToNewsletterImgDefault.'/facebook.png', $pathToNewsletterImg.'/facebook.png');
    copy($pathToNewsletterImgDefault.'/linkedin.png', $pathToNewsletterImg.'/linkedin.png');
    copy($pathToNewsletterImgDefault.'/youtube.png', $pathToNewsletterImg.'/youtube.png');
}

$file_images_names = array();

foreach ($_FILES['FilesImagesToUpload']['name'] as $i => $name)
{
    $file_images_names[] = $name;

    if (strlen($_FILES['FilesImagesToUpload']['name'][$i]) > 1)
    {
        if (move_uploaded_file($_FILES['FilesImagesToUpload']['tmp_name'][$i], $pathToNewsletter.'/img/'.$name))
        {
            echo 'Image '.$name.' uploaded!<br>';
        }
    }
}

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
$count_notices = preg_match_all('/\b'.$keyWordNotice.'/', $docText, $matches, PREG_OFFSET_CAPTURE);

$doc = new DOMDocument;
$doc->loadHtmlFile( TEMPLATE_DIR.'/'.$newsletterDir.'_TEMP.html');

$parent = $doc->getElementById('id_notices_to_append');

for($i = 0, $index_color = 0; $i < $count_notices; $i++)
{
    // Switch text and image of block's template
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

    $posNotice = $matches[0][$i][1];

    // Overwrite of the document text form the last position (end of notice)
    $docTextFromLineNotice = substr($docText, $posNotice);

    // Get next line from the last position of WORD_NOTICE constant's value
    $posFirstPHP_EOL = strpos($docTextFromLineNotice, PHP_EOL);
    $posSecondPHP_EOL = strpos($docTextFromLineNotice, PHP_EOL, $posFirstPHP_EOL + strlen(PHP_EOL));
    $length =  $posSecondPHP_EOL - $posFirstPHP_EOL;
    $nextLine = substr($docTextFromLineNotice, $posFirstPHP_EOL, $length);

    // Check if a string contains the TOPIC word
    if(strpos($nextLine, $keyWordTopic) !== false )
    {
        // Read of the texts from the document text
        $topic = getStringBetweenWords($docTextFromLineNotice, $keyWordTopic, $keyWordTitle);
        $title = getStringBetweenWords($docTextFromLineNotice, $keyWordTitle, $keyWordAbstract);
        $abstract = trim(getStringBetweenWords($docTextFromLineNotice, $keyWordAbstract, PHP_EOL));
        $link = getStringBetweenWords($docTextFromLineNotice, PHP_EOL, PHP_EOL, $keyWordAbstract);

        // Add point (.) at the end of string if it doesn't exist or if doesn't exist '?' or '!' 
        if (substr($abstract, -1) != '.' && substr($abstract, -1) != '!' && substr($abstract, -1) != '?')
        {
            $abstract .= '.';
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
                                            <td align="left" style="background-color:#ffffff;font-family:Arial;color:'.COLORS_LABELS[$index_color].';font-size:14px;border-width:5px;border-style:solid;border-color:#ffffff;" ><strong>'.$topic.'</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="background-color:#ffffff;font-family:Arial;color:#000001;font-size:21px;border-width:5px;border-style:solid;border-color:#ffffff;line-height:24px;" >'.$title.'</td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="background-color:#ffffff;font-family:Arial;color:#6f6f6e;font-size:15px;border-width:5px;border-style:solid;border-color:#ffffff;line-height:20px;" >
                                                '.$abstract.'
                                                <br><a  href="#" target="_blank" style="color:'.COLORS_LABELS[$index_color].';text-decoration:none;" >'.$link.'</a><br>
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
                                    <img src="'.IMG_DIR.'/'.$file_images_names[$i].'" width="100%" alt="" style="display:block;border-width:0;" />
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

        $index_color++;
    }
    else
    {
        $child = $doc->createCDATASection
        (
            PHP_EOL.PHP_EOL.'<!-- NOTIZIA '.($i + 1).' -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="container600" style="border-width:0;" >
                    <tbody>
                    <tr>
                        <td align="left" style="background-color:#ffffff;font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >
                            <table width="540"  border="0" align="center" cellpadding="0" cellspacing="0" class="container600" style="border-width:0;" >
                                <tbody>
                                <tr>
                                    <td style="font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif;" >
                                        <a href="#" target="_blank">
                                            <img src="'.IMG_DIR.'/'.$file_images_names[$i].'" width="100%" alt="" style="display:block;border-width:0;" />
                                        </a>
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
    }

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

$doc->saveHTMLFile($pathToNewsletter.'/'.$newsletterDir.'.html');
