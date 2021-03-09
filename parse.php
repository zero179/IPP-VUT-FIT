<?php
if ($argc > 1) {
    if ($argv[2] == "--help") {
        echo("Usage: parser.php [options] <inputFile");
        exit(0);
    }
}
    ini_set('display_errors', 'stderr');
    $header = false;
    $number = 0;
    while ($line = fgets(STDIN)) {
        if (!$header) {
            if (strtoupper(preg_match("/^(\.IPPcode21)(#.*)|(\s*)/", $line))){
                $header = true;
                echo("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
                echo("<program_language=\"IPPcode21\">\n");
                continue;
            }
            else{
                exit(21);
            }
        }
        if (preg_match("/^$/", $line)){
            continue;
            $number--;
        }
        if (preg_match("/^\s*#.*/", $line)){
            continue;
            $number--;
        }
        if (preg_match("/\s*#.*/", $line)){
            $line=preg_replace("/\s*#.*/", "\n", $line);
        }
        $splitted = explode(' ', trim($line, "\n"));
        $argument_1 = explode('@', $splitted[1], 2);
        $argument_2 = explode ('@',$splitted[2], 2);
        $argument_3 = explode ('@',$splitted[3], 2);
        $number++;
        switch (strtoupper($splitted[0])) {
            case 'DEFVAR':
            case 'POPS':
                writeVar($splitted, $argument_1[1],$number);
                break;
            case 'CALL':
            case 'LABEL':
            case 'JUMP':
                writeLabel($splitted, $argument_1[1],$number);
                break;
            case 'PUSHS':
            case 'WRITE':
            case 'EXIT':
            case 'DPRINT':
                writeSym($splitted, $argument_1[1],$number);
                break;
            case 'MOVE':
            case 'INT2CHAR':
            case 'STRLEN':
            case 'TYPE':
                writeVarSym($splitted, $argument_1[1], $argument_2[1],$number);
                break;
            case 'ADD':
            case 'SUB':
            case 'MUL':
            case 'IDIV':
            case 'LT':
            case 'GT':
            case 'EQ':
            case 'AND':
            case 'OR':
            case 'NOT':
            case 'STRI2INT':
            case 'CONCAT':
            case 'GETCHAR':
            case 'SETCHAR':
                writeVarSymSym($splitted, $argument_1[1], $argument_2[1], $argument_3[1],$number);
                break;
            case 'JUMPIFEQ':
            case 'JUMPIFNEQ':
                writeLabelSymSym($splitted,$argument_1[1], $argument_2[1], $argument_3[1],$number);
                break;
            case 'READ':
                writeVarType($splitted,$argument_1[1], $argument_2[1],$number);
                break;
            case 'CREATEFRAME':
            case 'PUSHFRAME':
            case 'POPFRAME':
            case 'RETURN':
            case 'BREAK':
                writeNone($splitted,$number);
                break;
            default:
                exit(22);
        }
    }
function writeNone($splitted,$number){
    $count = count($splitted, COUNT_NORMAL);
    if($count>1){
        exit(23);
    }
    if (preg_match("/[\r\n]*$/", $splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo("\t</instruction>")."\n";
    }
    else{
        exit(23);
    }
}
function writeVar($splitted, $argument_1,$number){
    $count = count($splitted, COUNT_NORMAL);
    if($count>2){
        exit(23);
    }
    if(preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[1]))
    {
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
        echo("\t</instruction>")."\n";
    }
    else{
        exit(23);
    }
}
function writeLabel($splitted, $argument_1,$number){
    $count = count($splitted, COUNT_NORMAL);
    if($count>2){
        exit(23);
    }
    if (preg_match("/^[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
        echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
        echo ("\t</instruction>") . "\n";
    }
    else{
        exit(23);
    }
}
function writeSym($splitted, $argument_1,$number){
    $count = count($splitted, COUNT_NORMAL);
    if($count>2){
        exit(23);
    }
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/",$splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
        echo ("\t</instruction>")."\n";
    }
    elseif (preg_match("/^bool@(true|false)$/",$splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"bool\">".$argument_1."<\arg1>")."\n";
        echo ("\t</instruction>")."\n";
    }
    elseif (preg_match("/^int@[0-9]*/",$splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"int\">".$argument_1."<\arg1>")."\n";
        echo ("\t</instruction>")."\n";
    }
    elseif (preg_match("/^nil@nil$/",$splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"nil\">".$argument_1."<\arg1>")."\n";
        echo ("\t</instruction>")."\n";
    }
    elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/",$splitted[1])){
        echo("\t<instruction order=\"$number\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"string\">".$argument_1."<\arg1>")."\n";
        echo ("\t</instruction>")."\n";
    }
    else{
        exit(23);
    }
}
function writeVarSym($splitted,$argument_1,$argument_2,$number)
{
    $count = count($splitted, COUNT_NORMAL);
    if($count>3){
        exit(23);
    }
    elseif (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[1])) {
        if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        } elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        } elseif (preg_match("/^int@[0-9]*/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        } elseif (preg_match("/^nil@nil$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        } else {
            exit(23);
        }
    }
}
function writeVarSymSym($splitted,$argument_1,$argument_2,$argument_3,$number){
    $count = count($splitted, COUNT_NORMAL);
    if($count>4){
        exit(23);
    }
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[1])) {
        if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        }
        elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        }
        elseif (preg_match("/^int@[0-9]*/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_1 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_1 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        }
        elseif (preg_match("/^nil@nil$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" .$argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        }
        elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        }
        else {
            exit(23);
        }
    }
    else{
        exit(23);
    }
}
function writeVarType($splitted, $argument_1, $argument_2,$number)
{
    $count = count($splitted, COUNT_NORMAL);
    if($count>3){
        exit(23);
    }
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[1])) {
        if (preg_match("/^int$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"type\">".$splitted[2]."<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        }
        elseif (preg_match("/^bool$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"type\">".$splitted[2]."<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        }
        elseif (preg_match("/^string$/", $splitted[2])) {
            echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$argument_1."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"type\">".$splitted[2]."<\arg2>") . "\n";
            echo ("\t</instruction>") . "\n";
        }
        else {
            exit(23);
        }
    }
    else {
        exit(23);
    }
}
function writeLabelSymSym($splitted,$argument_1,$argument_2,$argument_3,$number)
{
    $count = count($splitted, COUNT_NORMAL);
    if($count>4){
        exit(23);
    }
    if (preg_match("/^[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[1])) {
        if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        } elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        } elseif (preg_match("/^int@[0-9]*/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2. "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        } elseif (preg_match("/^nil@nil$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z\?!\-_\*&%#\$][a-zA-Z0-9\?!\-_\*&%#\$]*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $argument_3. "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" .$argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } elseif (preg_match("/^string@([^\\000-\\040\\043\\134]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"$number\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $argument_1 . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $argument_2 . "<\arg2>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $argument_3 . "<\arg3>") . "\n";
                echo ("\t</instruction>") . "\n";
            } else {
                exit(23);
            }
        } else {
            exit(23);
        }
    } else {
        exit(23);
    }
}
