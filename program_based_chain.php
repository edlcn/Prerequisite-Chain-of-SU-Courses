<?php

// $ php -S localhost:8080 examples/02-html.php

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
function strip($a){
    if (str_contains($a,"(")==true){
        $a = str_replace("(","",$a);
    }
    if (str_contains($a,")")==true){
        $a = str_replace(")","",$a);
    }
    return $a;
}

function inputStrip($a){
    if (str_contains($a," ")==true){
        $a = str_replace(" ","",$a);
    }
    return $a;
}

function andOrSetAttribute(&$vertex,$str){
    if (trim($str) == "or"){
        $vertex->setAttribute('graphviz.shape', "circle");
        $vertex->setAttribute('graphviz.color', "blue");
        //$vertex->setAttribute('graphviz.style', "filled");
        $vertex->setAttribute('graphviz.fixedsize', "true");
        $vertex->setAttribute('graphviz.width', 0.2);
        $vertex->setAttribute('graphviz.height', 0.3);
    }
    else {
        $vertex->setAttribute('graphviz.shape', "box");
        $vertex->setAttribute('graphviz.color', "red");
        //$vertex->setAttribute('graphviz.style', "filled");
        $vertex->setAttribute('graphviz.fixedsize', "true");
        $vertex->setAttribute('graphviz.width', 0.4);
        $vertex->setAttribute('graphviz.height', 0.3);
    }

}

require 'vendor\autoload.php';

$courseName = $_POST["coursesUserTook"];
$realcourse = strtoupper($courseName);
$courseName = strtoupper($courseName);
$realcourse = inputStrip($realcourse);
$courseName = inputStrip($courseName);
$file = fopen("out.txt","r");
$preqChain = array();
$wholedata = array();
$placeHolder = 0;
$allP = explode(",",$realcourse);
$ct = 0;


for($x = 0;$x < count($allP);$x++){
    while (!feof($file)){
        $lsc = fgets($file);
        $lscList = explode(":",$lsc);
        if (substr(trim($lscList[0]),0,strlen($allP[$x]))==$allP[$x]){
            array_push($preqChain,trim($lscList[0]));
        }
    }
    fseek($file,0);
}


while(!feof($file) && $placeHolder < count($preqChain)){

    $courseName = $preqChain[$placeHolder];
    $line = fgets($file);
    $lineList = explode(":",$line);
    
    if(trim($lineList[0])==trim($courseName)){
        
        if (strpos(trim($lineList[1]),"&") !== false || strpos(trim($lineList[1]),"|")!== false ){
// data structure = [[mainCourseName,"and",courses that connects to op]]
            if (strpos(trim($lineList[1]),"&") != false && strpos(trim($lineList[1]),"|")== false){
                
                $temp = array();
                array_push($temp,$courseName);
                array_push($temp,"and");
                $nd= explode("&",$lineList[1]);
                for ($x = 0; $x < count($nd);$x++){
                    if (in_array(trim($nd[$x]),$preqChain)== false){
                        array_push($preqChain,trim($nd[$x]));
                    }
                    array_push($temp,trim($nd[$x]));
                }
                array_push($wholedata,$temp);
                $placeHolder++;
                fseek($file,0);
            }
            else if (strpos(trim($lineList[1]),"&") == false && strpos(trim($lineList[1]),"|")!= false){
// data structure = [[mainCourseName,"or",courses that connects to op]]                
                $temp3 = array();
                array_push($temp3,$courseName);
                array_push($temp3,"or");
                $nn = explode("|",$lineList[1]);
                for ($b = 0; $b < count($nn); $b++){

                    if(in_array(trim($nn[$b]),$preqChain)== false){

                        array_push($preqChain,trim($nn[$b]));
                    }
                    array_push($temp3,trim($nn[$b]));
                }
                array_push($wholedata,$temp3);
                $placeHolder++;
                fseek($file,0);
            }
            else { 

                if (substr_count($lineList[1],"(") >= 1){

                    $poss = strpos($lineList[1],"(");
                    $pose = strpos($lineList[1],")");
                    $temp4 = array();

                    if (strpos(substr($lineList[1],$poss,$pose-$poss),"|") == true){
// data structure = [["and",mainCourseName,the courses that are directly connecting to main op,["or",courses that are connecting to main op of this array ]]]
                        
                        array_push($temp4,"and");
                        array_push($temp4,$courseName);
                        $split = explode("&",$lineList[1]);

                        for ($r = 0; $r < count($split);$r++){

                            if (str_contains($split[$r],"(")==true){

                                $innerprths = array();
                                array_push($innerprths,"or");

                                $innersplit = explode("|",$split[$r]);

                                for ($j = 0; $j < count($innersplit);$j++){

                                    $pro = strip($innersplit[$j]);
                                    array_push($innerprths,trim($pro));

                                    if (in_array(trim($pro),$preqChain)==false){

                                        array_push($preqChain,trim($pro));
                                    }
                                }
                                array_push($temp4,$innerprths);
                                
                            }
                            else {

                                if (in_array(trim($split[$r]),$preqChain)==false){

                                    array_push($preqChain,trim($split[$r]));
                                }
                                array_push($temp4,trim($split[$r]));
                                
                            }
                        }
                    }
                    else {
// data structure = [["or",mainCourseName,the courses that are directly connecting to main op,["and",courses that are connecting to main op of this array ]]]
                        array_push($temp4,"or");
                        array_push($temp4,$courseName);
                        $split = explode("|",$lineList[1]);

                        for ($r = 0; $r < count($split);$r++){

                            if (str_contains($split[$r],"(")==true){

                                $innerprths = array();
                                array_push($innerprths,"and");

                                $innersplit = explode("&",$split[$r]);

                                for ($j = 0; $j < count($innersplit);$j++){

                                    $pro = strip($innersplit[$j]);
                                    array_push($innerprths,trim($pro));

                                    if (in_array(trim($pro),$preqChain)==false){

                                        array_push($preqChain,trim($pro));
                                    }
                                }
                                array_push($temp4,$innerprths);
                                
                            }
                            else {

                                if (in_array(trim($split[$r]),$preqChain)==false){

                                    array_push($preqChain,trim($split[$r]));
                                }
                                array_push($temp4,trim($split[$r]));
                                
                            }
                        }
                    }
                    array_push($wholedata,$temp4);
                    $placeHolder++;
                    fseek($file,0);
                }
                

            }
        }
        else if (trim($lineList[1])!="None"){
// data structure = [[maincourse,other course]]

            $temp1 = array();
            array_push($temp1,$courseName);
            array_push($temp1,trim($lineList[1]));
            if (in_array(trim($lineList[1]),$preqChain)== false){

                array_push($preqChain,trim($lineList[1]));
            }
            
            array_push($wholedata,$temp1);
            $placeHolder++;
            fseek($file,0);
        }
        else {
// data structure = [[maincourse]]

            $temp2 = array();
            array_push($temp2,$courseName);
            array_push($wholedata,$temp2);
            $placeHolder++;
            fseek($file,0);
        }
    }
    
    
}
fclose($file);



$graph = new Fhaculty\Graph\Graph();
$graph->setAttribute('graphviz.graph.bgcolor', 'grey');
//$graph->setAttribute('graphviz.graph.rankdir', 'LR');


$andC =0;
$linkFile = fopen("links.txt","r");
$linkArray = array();
while(!feof($linkFile)){

    $l = fgets($linkFile);
    $llist = explode(" ",$l);

    $linkArray[trim($llist[0])] = trim($llist[1]);
}


for ($x = 0; $x < count($preqChain);$x++){

    $n = $graph->createVertex(trim($preqChain[$x]));
    $n->setAttribute('graphviz.shape', "box");

    if (array_key_exists(trim($preqChain[$x]),$linkArray)){
        $n->setAttribute('graphviz.href', $linkArray[trim($preqChain[$x])]);
        $n->setAttribute('graphviz.target', "blank");
    }
    else {
        $n->setAttribute('graphviz.color', "orange");
    }
    
    
}
fclose($linkFile);

for ($y = 0; $y <count($wholedata);$y++){

    if (count($wholedata[$y])>2 && $wholedata[$y][0]!="and" && $wholedata[$y][0]!="or"){    // handles only or cases or only and cases
        
        $op = $graph->createVertex($wholedata[$y][1]."".strval($andC));
        $op->setAttribute('graphviz.label', $wholedata[$y][1]);
        andOrSetAttribute($op,$wholedata[$y][1]);
        $andC++;

        for ($z =2; $z < count($wholedata[$y]);$z++ ){
            $w = $graph->getVertex($wholedata[$y][$z]);
            $w->createEdgeTo($op);
        }
        $mains =$graph->getVertex($wholedata[$y][0]);
        $op->createEdgeTo($mains);
    }
    else if (count($wholedata[$y]) == 2) {                                                // handles only one preq cases

        $w = $graph->getVertex(trim($wholedata[$y][1]));
        $w1 = $graph->getVertex(trim($wholedata[$y][0]));
        $w->createEdgeTo($w1);
    }
    else if (count($wholedata[$y])>2 && ($wholedata[$y][0]=="and" || $wholedata[$y][0]=="or")){         // handles mixed cases

        
        $op2 = $graph->createVertex($wholedata[$y][0]."".strval($andC));
        $op2->setAttribute('graphviz.label', $wholedata[$y][0]);
        andOrSetAttribute($op2,$wholedata[$y][0]);
        $andC++;

        for ($h = 2; $h<count($wholedata[$y]); $h++){

            if (gettype($wholedata[$y][$h])=="string"){

                $avs =$graph->getVertex($wholedata[$y][$h]);
                $avs->createEdgeTo($op2);
            }
            else {

                $op1 =$graph->createVertex($wholedata[$y][$h][0]."".strval($andC));
                $op1->setAttribute('graphviz.label', $wholedata[$y][$h][0]);
                andOrSetAttribute($op1,$wholedata[$y][$h][0]);
                $andC++;

                for ($o = 1; $o < count($wholedata[$y][$h]);$o++){
                    
                    $ovs = $graph->getVertex($wholedata[$y][$h][$o]);
                    $ovs->createEdgeTo($op1);
                }
                $op1->createEdgeTo($op2);

            }
        }
        $ss=$graph->getVertex(trim($wholedata[$y][1]));
        $op2->createEdgeTo($ss);

    }
}

//$graphviz = new Graphp\GraphViz\GraphViz();
//$graphviz->display($graph);


$graphviz = new Graphp\GraphViz\GraphViz();
$graphviz->setFormat('svg');

echo '<!DOCTYPE html>
<html>
<head>
<title>SUCourseChain</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
<body style="background-color:grey;">

' . $graphviz->createImageHtml($graph) . '

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>
';
?>