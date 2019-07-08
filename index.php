<html>
<head>
    <title>ProjetCleanCode</title>
</head>
<body>
<?php
$datamanag = new DataManag();
$stringOutput = $datamanag->run();
?>
</body>
</html>
<?php

class Parser
{

    public function getOnePartFromEntry($line1, $line2, $line3, $index)
    {
        $line1StringVal = "" .
            $this->verifyIfInputIsBlank($line1[($index * 3)]) . "" .
            $this->verifyIfInputIsBlank($line1[($index * 3) + 1]) . "" .
            $this->verifyIfInputIsBlank($line1[($index * 3) + 2]);
        $line2StringVal = "" .
            $this->verifyIfInputIsBlank($line2[($index * 3)]) . "" .
            $this->verifyIfInputIsBlank($line2[($index * 3) + 1]) . "" .
            $this->verifyIfInputIsBlank($line2[($index * 3) + 2]);
        $line3StringVal = "" .
            $this->verifyIfInputIsBlank($line3[($index * 3)]) . "" .
            $this->verifyIfInputIsBlank($line3[($index * 3) + 1]) . "" .
            $this->verifyIfInputIsBlank($line3[($index * 3) + 2]);
        return $line1StringVal . "" . $line2StringVal . "" . $line3StringVal;
    }

    public function verifyIfInputIsBlank($input)
    {

        return $input == " " ? "b" : $input;

    }

    public function parsePartToInt($lines)
    {
        $arrayLines = str_split($lines);

        for ($g = 0; $g < 9; $g++) {
            if ($arrayLines[0] == "b" &&
                $arrayLines[1] == "b" &&
                $arrayLines[2] == "b") {// 1/4
                if ($arrayLines[3] == "|") {
                    return 4;
                } else if ($arrayLines[5] == "|") {
                    return 1;
                } else {
                    return 0;
                }
            } else { // 2/3/5/6/7/8/9
                if ($arrayLines[3] == "b" &&
                    $arrayLines[4] == "_" &&
                    $arrayLines[5] == "|") {// 2/3
                    if ($arrayLines[6] == "|") {
                        return 2;
                    } else {
                        return 3;
                    }
                } else if ($arrayLines[3] == "|" &&
                    $arrayLines[4] == "_" &&
                    $arrayLines[5] == "b") {// 5/6
                    if ($arrayLines[6] == "b") {
                        return 5;
                    } else {
                        return 6;
                    }
                } else if ($arrayLines[3] == "b" && // 7
                    $arrayLines[4] == "b" &&
                    $arrayLines[5] == "|") {

                    return 7;

                } else {// 8/9

                    if ($arrayLines[6] == "|") {

                        return 8;

                    } else {

                        return 9;

                    }

                }

            }

        }

    }

}


class Checker
{

    public function checkSum($values)
    {

        $accumulator = 9;

        $accumulatorResult = 0;

        foreach ($values as $value) {

            $accumulatorResult += $value * $accumulator;

            $accumulator--;

        }

        $resultat = $accumulatorResult % 11 == 0;

        return $resultat;

    }

}

class dataManag
{

    public function run()
    {

        $handle = fopen("inputs.txt", "r");
        $parser = new Parser();
        $checker = new Checker();
        $finalResult = [];
        $finalResultString = "";

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


                for ($i = 0; $i < 9; $i++) {

                    $numStringVal = $parser->getOnePartFromEntry($line1, $line2, $line3, $i);

                    $numIntVal = $parser->parsePartToInt($numStringVal);
                    array_push($results, $numIntVal); // here's one entry's result
                }
                array_push($finalResult, $results); // adding entry's result to final result

            }

            $sizeFinalResult = count($finalResult);

            echo "<h1> Resultats : </h1>";// displaying resultats

            echo "<table>";

            for ($accSizeFinalResult = 0; $accSizeFinalResult < $sizeFinalResult; $accSizeFinalResult++) {

                echo "<tr><td>";

                echo implode("", $finalResult[$accSizeFinalResult]);

                $checkSum = $checker->checkSum($finalResult[$accSizeFinalResult]);

                echo "</td><td>";

                if (!$checkSum) {

                    echo "ERR";

                } else {

                    echo "";

                }

                echo "</td></tr>";

            }

            echo "</table>";

            fclose($handle);

        } else {

            // erreur

            echo "Mauvais fichier";

        }

    }

}


?>