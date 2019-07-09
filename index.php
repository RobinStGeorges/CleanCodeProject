<html>

<head>

    <title>ProjectCleanCode</title>

</head>

 

<?php
$dataManag = new DataManag();
$stringOutput = $dataManag->run();
header('Content-Disposition: attachment; filename="Results.html"');
header('Content-Type: text/plain'); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
header('Content-Length: ' . strlen($stringOutput));
header('Connection: close');
echo $stringOutput;
?>

 

</html>

<?php
class Parser {
    public function getOnePartFromEntry($line1, $line2, $line3, $index) {
        $line1StringVal = "" . $this->verifyIfInputIsBlank($line1[($index * 3) ]) . "" . $this->verifyIfInputIsBlank($line1[($index * 3) + 1]) . "" . $this->verifyIfInputIsBlank($line1[($index * 3) + 2]);
        $line2StringVal = "" . $this->verifyIfInputIsBlank($line2[($index * 3) ]) . "" . $this->verifyIfInputIsBlank($line2[($index * 3) + 1]) . "" . $this->verifyIfInputIsBlank($line2[($index * 3) + 2]);
        $line3StringVal = "" . $this->verifyIfInputIsBlank($line3[($index * 3) ]) . "" . $this->verifyIfInputIsBlank($line3[($index * 3) + 1]) . "" . $this->verifyIfInputIsBlank($line3[($index * 3) + 2]);
        return $line1StringVal . "" . $line2StringVal . "" . $line3StringVal;
    }
    public function verifyIfInputIsBlank($input) {
        return $input == " " ? "b" : $input;
    }
    public function parsePartToString($lines) {
        $arrayLines = str_split($lines);
        for ($g = 0;$g < 9;$g++) {
            if ($arrayLines[0] == "b" && $arrayLines[1] == "b" && $arrayLines[2] == "b") { // 1/4
                if ($arrayLines[3] == "|") {
                    if ($arrayLines[4] == "_" && $arrayLines[5] == "|" && $arrayLines[6] == "b" && $arrayLines[7] == "b" && $arrayLines[8] == "|") {
                        return "4";
                    } else {
                        return "?";
                    }
                } else if ($arrayLines[5] == "|") {
                    if ($arrayLines[6] == "b" && $arrayLines[7] == "b" && $arrayLines[8] == "|") {
                        return "1";
                    } else {
                        return "?";
                    }
                } else {
                    if ($arrayLines[6] == "b" && $arrayLines[7] == "b" && $arrayLines[8] == "b") {
                        return "0";
                    } else {
                        return "?";
                    }
                }
            } else { // 2/3/5/6/7/8/9
                if ($arrayLines[3] == "b" && $arrayLines[4] == "_" && $arrayLines[5] == "|") { // 2/3
                    if ($arrayLines[6] == "|") {
                        if ($arrayLines[7] == "_" && $arrayLines[8] == "b") {
                            return "2";
                        } else {
                            return "?";
                        }
                    } else {
                        if ($arrayLines[7] == "_" && $arrayLines[8] == "|") {
                            return "3";
                        } else {
                            return "?";
                        }
                    }
                } else if ($arrayLines[3] == "|" && $arrayLines[4] == "_" && $arrayLines[5] == "b") { // 5/6
                    if ($arrayLines[6] == "b") {
                        if ($arrayLines[7] == "_" && $arrayLines[8] == "|") {
                            return "5";
                        } else {
                            return "?";
                        }
                    } else {
                        if ($arrayLines[7] == "_" && $arrayLines[8] == "|") {
                            return "6";
                        } else {
                            return "?";
                        }
                    }
                } else if ($arrayLines[3] == "b" && // 7
                $arrayLines[4] == "b" && $arrayLines[5] == "|") {
                    if ($arrayLines[6] == "b" && $arrayLines[7] == "b" && $arrayLines[8] == "|") {
                        return "7";
                    } else {
                        return "?";
                    }
                } else { // 8/9
                    if ($arrayLines[6] == "|") {
                        if ($arrayLines[7] == "_" && $arrayLines[8] == "|") {
                            return "8";
                        } else {
                            return "?";
                        }
                    } else {
                        if ($arrayLines[5] == "|" && $arrayLines[7] == "_" && $arrayLines[8] == "|") {
                            return "9";
                        } else {
                            return "?";
                        }
                    }
                }
            }
        }
    }
}
class Checker {
    public function checkSum($values) {
        $accumulator = 9;
        $accumulatorResult = 0;
        foreach ($values as $value) {
            $accumulatorResult+= $value * $accumulator;
            $accumulator--;
        }
        $resultat = $accumulatorResult % 11 == 0;
        return $resultat;
    }
    public function isMalformed($string) {
        if (strpos($string, "?") !== false) {
            return true;
        }
    }
}
class dataManag {
    public function run() {
        $handle = fopen("inputs.txt", "r");
        $parser = new Parser();
        $checker = new Checker();
        $finalResult = [];
        $finalResultString = "<body>";
        if ($handle) {
            while (($line1 = fgets($handle)) !== false) {
                $line2 = fgets($handle);
                $line3 = fgets($handle);
                $line4 = fgets($handle);
                $line1 = str_split($line1);
                $line2 = str_split($line2);
                $line3 = str_split($line3);
                $line4 = str_split($line4);
                $results = [];
                for ($i = 0;$i < 9;$i++) {
                    $numStringVal = $parser->getOnePartFromEntry($line1, $line2, $line3, $i);
                    $numStringVal = $parser->parsePartToString($numStringVal);
                    array_push($results, $numStringVal); // here's one entry's result
                    
                }
                array_push($finalResult, $results); // adding entry's result to final result
                
            }
            $sizeFinalResult = count($finalResult);
            $finalResultString.= "<h1> Resultats : </h1>"; // displaying resultats
            $finalResultString.= "<table>";
            for ($accSizeFinalResult = 0;$accSizeFinalResult < $sizeFinalResult;$accSizeFinalResult++) {
                $finalResultString.= "<tr><td>";
                $finalResultString.= implode("", $finalResult[$accSizeFinalResult]);
                $malformed = $checker->isMalformed($finalResultString);
                $checkSum = $checker->checkSum($finalResult[$accSizeFinalResult]);
                $finalResultString.= "</td><td>";
                if ($malformed == true) {
                    $finalResultString.= "ILL";
                } else if (!$checkSum) {
                    $finalResultString.= "ERR";
                } else {
                    $finalResultString.= "";
                }
                $finalResultString.= "</td></tr>";
            }
            $finalResultString.= "</table>";
            fclose($handle);
        } else {
            // erreur
            $finalResultString.= "Mauvais fichier";
        }
        return $finalResultString.= "</body>";
    }
}
?>
