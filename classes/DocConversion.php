<?php
/**
 * Created by PhpStorm.
 * User: Marco
 * Date: 06/04/2018
 * Time: 12:28
 */

class DocConversion
{
    private $fileName;

    public function __construct($filePath)
    {
        $this->fileName = $filePath;
    }

    private function readDoc()
    {
        $fileHandle = fopen($this->fileName, "r");
        $line = @fread($fileHandle, filesize($this->fileName));
        $lines = explode(chr(0x0D),$line);
        $outText = "";
        foreach($lines as $thisLine)
        {
            $pos = strpos($thisLine, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisLine)==0))
            {
            }
            else
            {
                $outText .= $thisLine." ";
            }
        }
        $outText = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outText);
        return $outText;
    }

    private function readDocx()
    {
        $content = '';

        $zip = zip_open($this->fileName);

        if (!$zip || is_numeric($zip)) return false;

        while ($zipEntry = zip_read($zip))
        {
            if (zip_entry_open($zip, $zipEntry) == FALSE) continue;

            if (zip_entry_name($zipEntry) != "word/document.xml") continue;

            $content .= zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));

            zip_entry_close($zipEntry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $stripedContent = strip_tags($content);

        return $stripedContent;
    }

    public function convertToText()
    {

        if(isset($this->fileName) && !file_exists($this->fileName))
        {
            return "File Not exists";
        }

        $fileArray = pathinfo($this->fileName);
        $fileExt  = $fileArray['extension'];
        if($fileExt == "doc" || $fileExt == "docx")
        {
            if($fileExt == "doc")
            {
                return $this->readDoc();
            }
            else if($fileExt == "docx")
            {
                return $this->readDocx();
            }
        }
        else
        {
            return "Invalid File Type";
        }
        return "Generic Error";
    }
}