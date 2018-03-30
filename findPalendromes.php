<?php 

/*
 * @var string $file default filename to read if none is input at time of excecution
 */
$file = 'words.txt';

/**
 * function findPalindromes()
 *
 * Checks text file for palindromes and returns:
 * 1) Original Line of text
 * 2) List of Palindromes (in descending order of size)
 * 3) Total char count of palindromes per line
 *
 * @param string $file name of file to be checked for Palindromes.
 *
 * @return output file with results
 */
function findPalindromes( $file ) {

    /*
     * @var stream $data requires named resource specified by $file
     * @var timestamp $date used to append to output file
     * @var array $output placeholder for final output 
     * @var string $outputFile file where JSON data will be written
     * @var array $foundPalindromes placeholder for the found palindromes while in loop
     * @var string $testLine the single line being tested
     * @var string $originalLine the unmodified line being tested from file
     * @var string $testString the partial line being tested
     * @var int $charCount the character count of found palindromes on a single line
     * @var int $mainCount main count moving along $testString
     * @var int $stringEnd counter moving from the string end to the beginning
     * @var bool $foundPalindrome returns TRUE|FALSE if palindrome is found or not
     */ 
    $data = fopen($file,"r");
    $date = date('m-d-Y-H:i:s');
    $output = array();
    $outputFile = 'output'.$date.'.txt';
    $foundPalindromes = array();
    $originalLine = '';
    $testLine = '';
    $testString = '';
    $charCount = 0;
    $mainCount = 0;
    $stringEnd = 0;
    $foundPalindrome = FALSE;

    /*
     * while we are not at the end of the file, read it line-by-line and setting each line to $testLine 
     */
    while (!feof($data)) {
        $originalLine = preg_replace( "/\r|\n/", "", fgets($data));
        $testLine = $originalLine;
        if (strlen($testLine) != 0) {

            /* 
             * remove all spaces
             */
            $testLine = str_replace(' ', '', $testLine);

            /*
             * remove anything that is not a letter or number 
             */
            $testLine = preg_replace("/[^a-zA-Z 0-9]+/","", $testLine);

            /*
             * change case to lower
             */
            $testLine = strtolower($testLine);

            /*
             * begin a count going from beginning to end of the entire LINE 
             */
            for ($mainCount=0; $mainCount < strlen($testLine); $mainCount++) {
                /*
                 * begin a count in order to create strings from the test line 
                 */
                for ($stringEnd = strlen($testLine); $stringEnd >=0; $stringEnd--) {
                    /*
                     * Create first chunk to test
                     */
                    $testString = substr($testLine, $mainCount, strlen($testLine) - $stringEnd); 
                    /*
                     * Check if string is > 1 char, reverse it and compare against original string
                     * If the string is a palindrome it gets added to $foundPalindromes and we continue 
                     * to add to the $charCount
                     */
                    if (strlen($testString) > 1) {
                        $reverse = strrev($testString);
                        $foundPalindrome = ($testString == $reverse) ? TRUE : FALSE;
                        if ($foundPalindrome == TRUE) {
                            $foundPalindromes[] = $testString;
                            $charCount += strlen($testString);
                            break;
                        }//if foundPalindrome
                        unset($testString);
                    }//if strlen
                }//for stringEnd
            }//for mainCount

            /*
             * If we found any palindromes, sort them by string size and add the original line, all palindromes and char count 
             * to $output
             */
            if (sizeof($foundPalindromes) > 0) {
                array_multisort(array_map('strlen', $foundPalindromes),SORT_DESC, $foundPalindromes);
                $output[] = array ( 'originalLine' => $originalLine , 'foundPalindromes' => $foundPalindromes , 'totalChars' => $charCount  );
            }

            /*
             * clear Variables
             */
            unset($foundPalindromes);
            unset($charCount);
            unset($foundPalindrome);
        }
    }

    /*
     * close file stream
     */
    fclose($data);

    /*
     * json encode final output array
     */
    $data = json_encode($output, true);
    file_put_contents($outputFile,$data);
}

/*
 * Check if file was given via CLI if not use default file
 */
if (file_exists($argv[1])) {
    $file = $argv[1];
}

/*
 * Run findPalindromes
 */
findPalindromes($file);

?>
