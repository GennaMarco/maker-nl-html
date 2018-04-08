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
 * @return bool|string
 */
function getStringBetweenWords($text, $word_start, $word_end)
{
    switch([$word_start, $word_end])
    {
        // Get the link of notice
        case [PHP_EOL, PHP_EOL]:
            $word_start = 'ABSTRACT';
            $posStart = strpos($text, $word_start) + strlen($word_start) + 1;

            $tempString = substr($text, $posStart);
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
