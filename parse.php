<?php
ini_set('display_errors', 'stderr');
$header = false;
while($line=fgets(STDIN))
{
    if(!$header)
    {
        if($line=".IPPcode21")
        {
            $header = true;
            echo("<program_language=\"IPPcode21\">\n");
        }
    }
    $splitted = explode(' ', trim($line, "\n"));
    echo($splitted[0])."\n";
    switch(strtoupper($splitted[0]))
    {
        case 'DEFVAR':
            if($argc>1){
                echo("kokot");
            }
            else{
                if(preg_match("/(GF|TF|LF)@[a-zA-Z][a-zA-Z0-9]*/", $splitted[1]))
                {
                    echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                    echo("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                    echo("\t</instruction")."\n";
                }
                else{
                    echo("kokot");
                }
                break;
            }
        case 'MOVE':
            if ($argc=2) {
                if (preg_match("/(GF|TF|LF)@[a-zA-Z][a-zA-Z0-9]*/", $splitted[1])) {
                    if (preg_match("/(GF|TF|LF)@[a-zA-Z][a-zA-Z0-9]*/", $splitted[2])){
                        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                        echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                        echo ("\t\t<arg2 type=\"var\">".$splitted[2]."<\arg2>")."\n";
                        echo ("\t</instruction")."\n";
                    }
                    else{
                        echo("kokotko");
                    }
                } else {
                    echo("kokot1");
                }
                break;
            }
            else{
                echo("kokot2");
            }
            break;
        case 'BREAK':
            echo("\t<instruction opcode=\"".$splitted[0]."\">")."\n";
            echo("\t</instruction>")."\n";
            break;
    }
}