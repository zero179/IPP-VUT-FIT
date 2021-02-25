<?php
if ($argc > 1) {
    if ($argv[2] == "--help") {
        echo("Usage: parser.php [options] <inputFile");
        exit(0);
    }
}
function writeNone($splitted){
    if (preg_match("/[\r\n]*$/", $splitted[1])){
        echo("\t<instruction opcode=\"".$splitted[0]."\">")."\n";
        echo("\t</instruction>")."\n";
    }
    else{
        echo("kokoot\n");
    }
}
function writeVar($splitted){
    if(preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1]))
    {
        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
        echo("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
        echo("\t</instruction")."\n";
    }
    else{
        echo("pojeb sa\n");
    }
}
function writeLabel($splitted){
    if (preg_match("/[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])){
        echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
        echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
        echo ("\t</instruction") . "\n";
    }
    else{
        echo("pojebany kokotJ\n");
    }
}
function writeSym($splitted){
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/",$splitted[1])){
        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
        echo ("\t</instruction")."\n";
    }
    elseif (preg_match("/^bool@(true|false)$/",$splitted[1])){
        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"bool\">".$splitted[1]."<\arg1>")."\n";
        echo ("\t</instruction")."\n";
    }
    elseif (preg_match("/^int@[0-9]*/",$splitted[1])){
        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"int\">".$splitted[1]."<\arg1>")."\n";
        echo ("\t</instruction")."\n";
    }
    elseif (preg_match("/^nil@nil$/",$splitted[1])){
        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"nil\">".$splitted[1]."<\arg1>")."\n";
        echo ("\t</instruction")."\n";
    }
    elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/",$splitted[1])){
        echo("\t<instruction order=\"kokot\" opcode=".strtoupper($splitted[0]).">\n");
        echo ("\t\t<arg1 type=\"string\">".$splitted[1]."<\arg1>")."\n";
        echo ("\t</instruction")."\n";
    }
    else{
        echo("pojebany kokot\n");
    }
}
function writeVarSym($splitted)
{
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
        if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        } elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        } elseif (preg_match("/^int@[0-9]*/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        } elseif (preg_match("/^nil@nil$/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        } else {
            echo("pojebany kokot\n");
        }
    }
}
function writeVarSymSym($splitted){
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
        if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        }
        elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        }
        elseif (preg_match("/^int@[0-9]*/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        }
        elseif (preg_match("/^nil@nil$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        }
        elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            }
            elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        }
        else {
            echo("pojebany kokot\n");
        }
    }
    else{
        echo("pojebany kokot\n");
    }
}
function writeVarType($splitted)
{
    if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
        if (preg_match("/^int@[0-9]*/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        }
        elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        }
        elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
            echo ("\t\t<arg1 type=\"var\">".$splitted[1]."<\arg1>")."\n";
            echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
            echo ("\t</instruction") . "\n";
        }
        else {
            echo("pojebany kokot\n");
        }
    }
    else {
        echo("pojebany kokot\n");
    }
}
function writeLabelSymSym($splitted)
{
    if (preg_match("/[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[1])) {
        if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"var\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        } elseif (preg_match("/^bool@(true|false)$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"bool\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        } elseif (preg_match("/^int@[0-9]*/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"int\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        } elseif (preg_match("/^nil@nil$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[2])) {
            if (preg_match("/^(GF|TF|LF)@[a-zA-Z?!*%#$&_-][a-zA-Z0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"nil\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"var\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^bool@(true|false)$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"bool\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^int@[0-9]*/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"int\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^nil@nil$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"nil\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } elseif (preg_match("/^string@([\135-\177\041-\042\044-\133]|(\\\\(?=(00[0-9]|0[12][0-9]|03[0-2]|035|092))))*$/", $splitted[3])) {
                echo("\t<instruction order=\"kokot\" opcode=" . strtoupper($splitted[0]) . ">\n");
                echo ("\t\t<arg1 type=\"label\">" . $splitted[1] . "<\arg1>") . "\n";
                echo ("\t\t<arg2 type=\"string\">" . $splitted[2] . "<\arg1>") . "\n";
                echo ("\t\t<arg3 type=\"string\">" . $splitted[3] . "<\arg1>") . "\n";
                echo ("\t</instruction") . "\n";
            } else {
                echo("pojebany kokot\n");
            }
        } else {
            echo("pojebany kokot\n");
        }
    } else {
        echo("pojebany kokot\n");
    }
}
    ini_set('display_errors', 'stderr');
    $header = false;
    while ($line = fgets(STDIN)) {
        if (!$header) {
            if ($line = ".IPPcode21") {
                $header = true;
                echo("<program_language=\"IPPcode21\">\n");
            }
        }
        $splitted = explode(' ', trim($line, "\n"));
        echo ($splitted[0]) . "\n";

        switch (strtoupper($splitted[0])) {
            case 'DEFVAR':
            case 'POPS':
                writeVar($splitted);
                break;
            case 'CALL':
            case 'LABEL':
            case 'JUMP':
                writeLabel($splitted);
                break;
            case 'PUSHS':
            case 'WRITE':
            case 'EXIT':
            case 'DPRINT':
                writeSym($splitted);
                break;
            case 'MOVE':
            case 'INT2CHAR':
            case 'STRLEN':
            case 'TYPE':
                writeVarSym($splitted);
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
            case 'STR2INT':
            case 'CONCAT':
            case 'GETCHAR':
            case 'SETCHAR':
                writeVarSymSym($splitted);
                break;
            case 'JUMPIFEQ':
            case 'JUMPIFNEQ':
                writeLabelSymSym($splitted);
                break;
            case 'READ':
                writeVarType($splitted);
                break;
            case 'CREATEFRAME':
            case 'PUSHFRAME':
            case 'POPFRAME':
            case 'RETURN':
            case 'BREAK':
                writeNone($splitted);
                break;
        }
    }

