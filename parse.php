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
            if(preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1]))
            {
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo("\t</instruction")."\n";
            }
            else {
                echo("pojeb sa\n");
            }
            break;
        case 'MOVE':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[2])){
                    echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                    echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                    echo ("\t\t<arg2 type=\"var\">".$splitted[2]."<\arg2>")."\n";
                    echo ("\t</instruction")."\n";
                }
                elseif (preg_match("/^bool@(true|false)$/",$splitted[2])){
                    echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                    echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                    echo ("\t\t<arg1 type=\"symb\">".$splitted[2]."<\arg1>")."\n";
                    echo ("\t</instruction")."\n";
                }
                elseif (preg_match("/^int@[0-9]{3}/",$splitted[2])){
                    echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                    echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                    echo ("\t\t<arg1 type=\"symb\">".$splitted[2]."<\arg1>")."\n";
                    echo ("\t</instruction")."\n";
                }
                elseif (preg_match("/^nil@nil$/",$splitted[2])){
                    echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                    echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                    echo ("\t\t<arg1 type=\"symb\">".$splitted[2]."<\arg1>")."\n";
                    echo ("\t</instruction")."\n";
                }
                elseif (preg_match("/^string@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/",$splitted[1])){
                    echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                    echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                    echo ("\t\t<arg1 type=\"symb\">".$splitted[2]."<\arg1>")."\n";
                    echo ("\t</instruction")."\n";
                }
                else{
                    echo("pojeb sa11\n");
                }
                break;
            }
            else{
                echo("pojeb sa\n");
            }
            break;

        case 'CREATEFRAME':
            if (preg_match("/[\r\n]*$/", $splitted[1])){
                echo("\t<instruction opcode=\"".$splitted[0]."\">")."\n";
                echo("\t</instruction>")."\n";
            }
            else{
                echo("kokot\n");
                break;
            }
            break;

        case 'PUSHFRAME':
            if (preg_match("/[\r\n]*$/", $splitted[1])){
                echo("\t<instruction opcode=\"".$splitted[0]."\">")."\n";
                echo("\t</instruction>")."\n";
            }
            else{
                echo("kokotko\n");
                break;
            }
            break;

        case 'POPFRAME':
            if (preg_match("/[\r\n]*$/", $splitted[1])){
                echo("\t<instruction opcode=\"".$splitted[0]."\">")."\n";
                echo("\t</instruction>")."\n";
            }
            else{
                echo("kokooooot\n");
                break;
            }
            break;

        case 'CALL':

        case 'RETURN':

        case 'ADD':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^int@[0-9]{3}/", $splitted[2])) {
                    if (preg_match("/^int@[0-9]{3}/", $splitted[3])) {
                        echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                        echo ("\t\t<arg1 type=\"var\">" . $splitted[1] . "<\arg1>") . "\n";
                        echo ("\t\t<arg2 type=\"symb1\">" . $splitted[2] . "<\arg2>") . "\n";
                        echo ("\t\t<arg3 type=\"symb2\">" . $splitted[3] . "<\arg3>") . "\n";
                        echo ("\t</instruction") . "\n";
                    }
                    else{
                        echo("pojebany kokotJ\n");
                    }
                    break;
                }
                else{
                    echo("pojebany kokotH\n");
                }
                break;
            }
            else{
                echo("pojebany kokotK\n");
            }
            break;

        case 'SUB':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^int@[0-9]{3}/", $splitted[2])) {
                    if (preg_match("/^int@[0-9]{3}/", $splitted[3])) {
                        echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                        echo ("\t\t<arg1 type=\"var\">" . $splitted[1] . "<\arg1>") . "\n";
                        echo ("\t\t<arg2 type=\"symb1\">" . $splitted[2] . "<\arg2>") . "\n";
                        echo ("\t\t<arg3 type=\"symb2\">" . $splitted[3] . "<\arg3>") . "\n";
                        echo ("\t</instruction") . "\n";
                    }
                    else{
                        echo("pojebany kokootJ\n");
                    }
                    break;
                }
                else{
                    echo("pojebany kokotH\n");
                }
                break;
            }
            else{
                echo("pojebany kokotK\n");
            }
            break;

        case 'MUL':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^int@[0-9]{3}/", $splitted[2])) {
                    if (preg_match("/^int@[0-9]{3}/", $splitted[3])) {
                        echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                        echo ("\t\t<arg1 type=\"var\">" . $splitted[1] . "<\arg1>") . "\n";
                        echo ("\t\t<arg2 type=\"symb1\">" . $splitted[2] . "<\arg2>") . "\n";
                        echo ("\t\t<arg3 type=\"symb2\">" . $splitted[3] . "<\arg3>") . "\n";
                        echo ("\t</instruction") . "\n";
                    }
                    else{
                        echo("pojebany kokootJ\n");
                    }
                    break;
                }
                else{
                    echo("pojebany kokotH\n");
                }
                break;
            }
            else{
                echo("pojebany kokoutK\n");
            }
            break;

        case 'IDIV':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^int@[0-9]{3}/", $splitted[2])) {
                    if (preg_match("/^int@[0-9]{3}/", $splitted[3])) {
                        echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                        echo ("\t\t<arg1 type=\"var\">" . $splitted[1] . "<\arg1>") . "\n";
                        echo ("\t\t<arg2 type=\"symb1\">" . $splitted[2] . "<\arg2>") . "\n";
                        echo ("\t\t<arg3 type=\"symb2\">" . $splitted[3] . "<\arg3>") . "\n";
                        echo ("\t</instruction") . "\n";
                    }
                    else{
                        echo("pojebany kokootJ\n");
                    }
                    break;
                }
                else{
                    echo("pojebany kokotH\n");
                }
                break;
            }
            else{
                echo("pojebany kokouatK\n");
            }
            break;

        case 'INT2CHAR':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^int@[0-9]{3}/", $splitted[2])) {
                    echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                    echo ("\t\t<arg1 type=\"var\">" . $splitted[1] . "<\arg1>") . "\n";
                    echo ("\t\t<arg2 type=\"symb1\">" . $splitted[2] . "<\arg2>") . "\n";
                }
                else{
                    echo("pojebany kokootJ\n");
                }
                break;
            }
            else{
                echo("pojebany kokouatK\n");
            }
            break;

        case 'STRI2INT':
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
                if (preg_match("/^int@[0-9]{3}/", $splitted[2])) {
                    if (preg_match("/^int@[0-9]{3}/", $splitted[3])) {
                        echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                        echo ("\t\t<arg1 type=\"var\">" . $splitted[1] . "<\arg1>") . "\n";
                        echo ("\t\t<arg2 type=\"symb1\">" . $splitted[2] . "<\arg2>") . "\n";
                        echo ("\t\t<arg3 type=\"symb2\">" . $splitted[3] . "<\arg3>") . "\n";
                        echo ("\t</instruction") . "\n";
                    }
                    else{
                        echo("pojebany kokootJ\n");
                    }
                    break;
                }
                else{
                    echo("pojebany kokotH\n");
                }
                break;
            }
            else{
                echo("pojebanya kokoutK\n");
            }
            break;

        case 'READ':
            
        case 'WRITE':

        case 'PUSHS':
            if (preg_match("/(^GF|^TF|^LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/",$splitted[1])){
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t</instruction")."\n";
            }
            elseif (preg_match("/^bool@(^true|^false)/",$splitted[1])){
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo ("\t\t<arg1 type=\"symb\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t</instruction")."\n";
            }
            elseif (preg_match("/^int@[0-9]{3}/",$splitted[1])){
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo ("\t\t<arg1 type=\"symb\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t</instruction")."\n";
                break;
            }
            elseif (preg_match("/^nil@^nil/",$splitted[1])){
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo ("\t\t<arg1 type=\"symb\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t</instruction")."\n";
            }
            elseif (preg_match("/(^string)@[a-zA-Z][a-zA-Z0-9]*/",$splitted[1])){
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo ("\t\t<arg1 type=\"symb\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t</instruction")."\n";
            }
            elseif (preg_match("/(^string)@[\r\n]*$/",$splitted[1])){
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo ("\t\t<arg1 type=\"symb\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t</instruction")."\n";
            }
            else{
                echo("pojebany kokot\n");
            }
            break;
        case 'POPS':
            if(preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1]))
            {
                echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
                echo("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo("\t</instruction")."\n";
            }
            else {
                echo("pojeeeeeb saaaaaaa\n");
            }
            break;

        case 'BREAK':
            if (preg_match("/[\r\n]*$/", $splitted[1])){
                echo("\t<instruction opcode=\"".$splitted[0]."\">")."\n";
                echo("\t</instruction>")."\n";
            }
            else{
                echo("kokoot\n");
                break;
            }
            break;
    }
}