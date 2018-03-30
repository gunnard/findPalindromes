# Scope: 
This goal of this project is to create a command line php script that will read (line by line) from a text file, find all of the palindromes contained within a line and output the results to a file as a jSON encoded string. 

# Use Case:
A hospitality company (Pants inc.) wants to give away a prize each day to any users that have palindromes in their names. Given that they do not want to create an entire new feature to their codebase, they opted for this script to be run via crontab just after midnight every day. At 12:05amthe script will run against a log file containing newly registered users names (dumped from their DB). The resulting output file will be written to a web accesable directory where their VUE.js frontend will be expecting the jSON formated data. This is only for admins to see.

# Usage:
`php findPalindromes.php filename (optional)`

if no filename is given or the file does not exist, the default value for the file will come from `$file`

the output will be a file with a date timestamp i.e.

`output03-30-2018-15:05:24.txt`
