<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 07/04/2018
 * Time: 15:35
 */

/**
 * @param $text
 * @param $word_start
 * @param $word_end
 * @param string $key_word_abstract
 * @return bool|string
 */
function getStringBetweenWords($text, $word_start, $word_end, $key_word_abstract = 'ABSTRACT')
{
    switch([$word_start, $word_end])
    {
        // Get the link of notice
        case [PHP_EOL, PHP_EOL]:
            $word_start = $key_word_abstract;
            $posStart = strpos($text, $word_start) + strlen($word_start) + 1;

            $tempString = substr($text, $posStart);

            // Add \r\n (PHP_EOL) at the end of string if it doesn't exist
            if (substr($tempString, -1) != $word_end)
            {
                $tempString .= $word_end;
            }

            $posStart = strpos($tempString, $word_end);
            $posEnd = strpos($tempString, $word_end, $posStart + strlen($word_end));
            $length = $posEnd - $posStart;

            $textSearched = substr($tempString, $posStart, $length);
            break;

        // Get the abstract of notice
        case [$word_start, PHP_EOL]:
            $posStart = strpos($text, $word_start) + strlen($word_start) + 1;
            $tempString = substr($text, $posStart);
            $posEnd = strpos($tempString, $word_end);
            $length = $posEnd;

            $textSearched = substr($text, $posStart, $length);
            break;

        // Get topic or title of notice
        default:
            $posStart = strpos($text, $word_start) + strlen($word_start) + 1;
            $posEnd = strpos($text, $word_end);
            $length = $posEnd - $posStart;

            $textSearched = substr($text, $posStart, $length);
            break;
    }

    return $textSearched;
}

/**
 * @param $zip_file_path
 */
function downloadFileZip($zip_file_path)
{
    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename="'.basename($zip_file_path).'"');
    header("Content-length: " . filesize($zip_file_path));
    header("Pragma: no-cache");
    header("Expires: 0");

    ob_clean();
    flush();

    readfile($zip_file_path);
}

/**
 * @param $dir
 */
function removeDir($dir)
{
    if (is_dir($dir))
    {
        $objects = scandir($dir);
        foreach ($objects as $object)
        {
            if ($object != "." && $object != "..")
            {
                if (is_dir($dir."/".$object))
                    removeDir($dir."/".$object);
                else
                    unlink($dir."/".$object);
            }
        }
        rmdir($dir);
    }
}
