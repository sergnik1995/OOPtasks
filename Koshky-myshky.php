<?php
 
error_reporting(-1);

const FIELD_SIZE = 10;

abstract class Animal
{
    public $name;
    public $posX;
    public $posY;
    public $decisions;
    function stay()
    {}
    function goUp(){
        $this->posY++;
        $this->decisions->posY++;
    }
    function goDown(){
        $this->posY--;
        $this->decisions->posY--;
    }
    function goRight(){
        $this->posX++;
        $this->decisions->posX++;
    }
    function goLeft(){
        $this->posX--;
        $this->decisions->posX--;
    }
}

class Cat extends Animal
{
    public $name = 'cat';
    public $sleep;
    public $decisions;
    public $turnsToWakeUp;
    function __construct(int $a, int $b){
        $this->posX = $a;
        $this->posY = $b;
        $this->sleep = 0;
        $this->turnsToWakeUp = 0;
        $this->decisions = new CatDecisions($a, $b);
    }
    function goUpRight(){
        $this->posX++;
        $this->posY++;
        $this->decisions->posX++;
        $this->decisions->posY++;
    }
    function goUpLeft(){
        $this->posX--;
        $this->posY++;
        $this->decisions->posX--;
        $this->decisions->posY++;
    }
    function goDownRight(){
        $this->posX++;
        $this->posY--;
        $this->decisions->posX++;
        $this->decisions->posY--;
    }
    function goDownLeft(){
        $this->posX--;
        $this->posY--;
        $this->decisions->posX--;
        $this->decisions->posY--;
    }
}

class Mouse extends Animal
{
    public $name = "mouse";
    public $decisions;
    function __construct(int $a, int $b){
        $this->posX = $a;
        $this->posY = $b;
        $this->decisions = new MouseDecisions($a, $b);
    }
    public function checkMouseAlone($animals) {
        $mousesAround = 0;
        foreach($animals as $animal) {
            if($animal->name == "mouse" && ((abs($animal->posX - $this->posX) <= 2) && (abs($animal->posY - $this->posY) <= 2))){
                if(($this->posX == $animal->posX) && ($this->posY == $animal->posY)) {}
                else {
                    $mousesAround++;
                }
            }
            if($mousesAround >= 2) return 0;
        }
        return 1;
    }
}

abstract class Decisions
{
    public $posX;
    public $posY;
    function __construct($a, $b){
        $this->posX = $a;
        $this->posY = $b;
    }
}

class CatDecisions extends Decisions
{
    function goUp($animals) {
        $result = 0;
        if($this->posY == FIELD_SIZE){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY+1)) && ($animal->posX == $this->posX) && ($animal->checkMouseAlone($animals) == 1)){
                    return 100;
                }
                else if(($animal->posY == ($this->posY+1)) && ($animal->posX == $this->posX) && ($animal->checkMouseAlone($animals) == 0)){
                    return -1;
                }
                else if(($animal->posY - $this->posY <= 3) && ($animal->posX == $this->posX)){
                    if(($animal->posY - $this->posY) < 0){}
                    else $result += 5;
                }
                else if(($animal->posY > $this->posY) && ($animal->posX == $this->posX)){
                    $result += 2;
                }
                else if($animal->posY > $this->posY){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == ($this->posY + 1)) && ($animal->posX == $this->posX)){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goDown($animals) {
        $result = 0;
        if($this->posY == 0){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY-1)) && ($animal->posX == $this->posX) && ($animal->checkMouseAlone($animals) == 1)){
                    return 100;
                }
                else if(($animal->posY == ($this->posY-1)) && ($animal->posX == $this->posX) && ($animal->checkMouseAlone($animals) == 0)){
                    return -1;
                }
                else if(($this->posY - $animal->posY <= 3) && ($animal->posX == $this->posX)){
                    if(($this->posY - $animal->posY) < 0){}
                    else $result += 5;
                }
                else if(($animal->posY < $this->posY) && ($animal->posX == $this->posX)){
                    $result += 2;
                }
                else if($animal->posY < $this->posY){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == ($this->posY - 1)) && ($animal->posX == $this->posX)){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goRight($animals) {
        $result = 0;
        if($this->posX == FIELD_SIZE){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == $this->posY) && ($animal->posX == ($this->posX + 1)) && ($animal->checkMouseAlone($animals) == 1)){
                    return 100;
                }
                else if(($animal->posY == $this->posY) && ($animal->posX == ($this->posX + 1)) && ($animal->checkMouseAlone($animals) == 0)){
                    return -1;
                }
                else if(($animal->posX - $this->posX) <= 3 && ($animal->posY == $this->posY)){
                    if(($animal->posX - $this->posX) < 0){}
                    else $result += 5;
                }
                else if(($animal->posX > $this->posX) && ($animal->posY == $this->posY)){
                    $result += 2;
                }
                else if($animal->posX > $this->posX){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == $this->posY) && ($animal->posX == ($this->posX + 1))){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goLeft($animals) {
        $result = 0;
        if($this->posX == 0){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == $this->posY) && ($animal->posX == ($this->posX- 1)) && ($animal->checkMouseAlone($animals) == 1)){
                    return 100;
                }
                else if(($animal->posY == $this->posY) && ($animal->posX == ($this->posX - 1)) && ($animal->checkMouseAlone($animals) == 0)){
                    return -1;
                }
                else if(($this->posX - $animal->posX) <= 3 && ($animal->posY == $this->posY)){
                    if(($this->posX - $animal->posX) < 0){}
                    else $result += 5;
                }
                else if(($animal->posX > $this->posX) && ($animal->posY == $this->posY)){
                    $result += 2;
                }
                else if($animal->posY > $this->posY){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == $this->posY) && ($animal->posX == ($this->posX - 1))){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goUpRight($animals) {
        $result = 0;
        if(($this->posX == FIELD_SIZE) or ($this->posY == FIELD_SIZE)){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY + 1)) && ($animal->posX == ($this->posX + 1))){
                    if($animal->checkMouseAlone($animals) == 1){
                        return 200;
                    }
                    else if($animal->checkMouseAlone($animals) == 0){
                        return -1;
                    }
                }
                else if((($animal->posX - $this->posX) <= 3) && (($animal->posY - $this->posY) <= 3)){
                    if((($animal->posX - $this->posX) < 0) or (($animal->posY - $this->posY) < 0)){}
                    else $result += 5;
                }
                else if(($animal->posX > $this->posX) && ($animal->posY > $this->posY)){
                    $result += 2;
                }
                else if(($animal->posY > $this->posY) or ($animal->posX > $this->posX)){
                    $result += 1;
                }
            }
            else if($animal->name == "cat") {
                if(($animal->posY == ($this->posY + 1)) && ($animal->posX == ($this->posX + 1))){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goUpLeft($animals) {
        $result = 0;
        if(($this->posX == 0) or ($this->posY == FIELD_SIZE)){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY + 1)) && ($animal->posX == ($this->posX - 1))){
                    if($animal->checkMouseAlone($animals) == 1){
                        return 200;
                    }
                    else{
                        return -1;
                    }
                }
                else if((($this->posX - $animal->posX) <= 3) && (($animal->posY - $this->posY) <= 3)){
                    if((($this->posX - $animal->posX) < 0) or (($animal->posY - $this->posY) < 0)){}
                    else $result += 5;
                }
                else if(($animal->posX < $this->posX) && ($animal->posY > $this->posY)){
                    $result += 2;
                }
                else if(($animal->posY < $this->posY) or ($animal->posX > $this->posX)){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == ($this->posY + 1)) && ($animal->posX == ($this->posX - 1))){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goDownRight($animals) {
        $result = 0;
        if(($this->posX == FIELD_SIZE) or ($this->posY == 0)){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY - 1)) && ($animal->posX == ($this->posX + 1))){
                    if($animal->checkMouseAlone($animals) == 1){
                        return 200;
                    }
                    else{
                        return -1;
                    }
                }
                else if((($animal->posX - $this->posX) <= 3) && (($this->posY - $animal->posY) <= 3)){
                    if((($animal->posX - $this->posX) < 0) or (($this->posY - $animal->posY) < 0)){}
                    else $result += 5;
                }
                else if(($animal->posX > $this->posX) && ($animal->posY < $this->posY)){
                    $result += 2;
                }
                else if(($animal->posY < $this->posY) or ($animal->posX > $this->posX)){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == ($this->posY - 1)) && ($animal->posX == ($this->posX + 1))){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goDownLeft($animals) {
        $result = 0;
        if(($this->posX == 0) or ($this->posY == 0)){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY - 1)) && ($animal->posX == ($this->posX - 1))){
                    if($animal->checkMouseAlone($animals) == 1){
                        return 200;
                    }
                    else{
                        return -1;
                    }
                }
                else if((($this->posX - $animal->posX) <= 3) && (($this->posY - $animal->posY) <= 3)){
                    if((($this->posX - $animal->posX) < 0) or (($this->posY - $animal->posY) < 0)){}
                    else $result += 5;
                }
                else if(($animal->posX < $this->posX) && ($animal->posY < $this->posY)){
                    $result += 2;
                }
                else if(($animal->posY < $this->posY) or ($animal->posX < $this->posX)){
                    $result += 1;
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY == ($this->posY - 1)) && ($animal->posX == ($this->posX - 1))){
                    return -1;
                }
            }
        }
        return $result;
    }
}

class MouseDecisions extends Decisions
{
    function goUp($animals) {
        $result = 0;
        if($this->posY == FIELD_SIZE){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY + 1)) && (abs($animal->posY - $this->posY) <= 9)){
                    if($animal->posX == $this->posX){
                        return -1;
                    }
                    else {
                        $result += 1;
                    }
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY > $this->posY) && (abs($animal->posY - $this->posY) <= 9)){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goDown($animals) {
        $result = 0;
        if($this->posY == 0){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posY == ($this->posY - 1)) && (abs($animal->posY - $this->posY) <= 9)){
                    if($animal->posX == $this->posX){
                        return -1;
                    }
                    else {
                        $result += 1;
                    }
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posY < $this->posY) && (abs($animal->posY - $this->posY) <= 9)){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goRight($animals) {
        $result = 0;
        if($this->posX == FIELD_SIZE){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posX == ($this->posX + 1)) && (abs($animal->posX - $this->posX) <= 9)){
                    if($animal->posY == $this->posY){
                        return -1;
                    }
                    else {
                        $result += 1;
                    }
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posX > $this->posX) && (abs($animal->posX - $this->posX) <= 9)){
                    return -1;
                }
            }
        }
        return $result;
    }
    function goLeft($animals) {
        $result = 0;
        if($this->posX == 0){
            return -1;
        }
        foreach($animals as $animal){
            if($animal->name == "mouse") {
                if(($animal->posX < $this->posX) && (abs($animal->posX - $this->posX) <= 9)){
                    if($animal->posY == $this->posY){
                        return -1;
                    }
                    else {
                        $result += 1;
                    }
                }
            }
            else if($animal->name == 'cat') {
                if(($animal->posX < $this->posX) && (abs($animal->posX - $this->posX) <= 9)){
                    return -1;
                }
            }
        }
        return $result;
    }
}

function chooseDecision(Animal $animal, array $animals)
{
    $decisions = $animal->decisions;
    $decisionsArr = array();
    $decisionsArr['goUp'] = $decisions->goUp($animals);
    $decisionsArr['goRight'] = $decisions->goRight($animals);
    $decisionsArr['goDown'] = $decisions->goDown($animals);
    $decisionsArr['goLeft'] = $decisions->goLeft($animals);
    if($animal->name == 'cat') {
        if($animal->sleep == 1) {
            return 'stay';
        }
        $decisionsArr['goUpRight'] = $decisions->goUpRight($animals);
        $decisionsArr['goUpLeft'] = $decisions->goUpLeft($animals);
        $decisionsArr['goDownLeft'] = $decisions->goDownLeft($animals);
        $decisionsArr['goDownRight'] = $decisions->goDownRight($animals);
    }
    $priorityDecision = -2;
    $nameDecision;
    //var_dump($decisionsArr);
    foreach($decisionsArr as $key => $valueDecision){
        if($valueDecision > $priorityDecision) {
            $priorityDecision = $valueDecision;
            $nameDecision = $key;
        }
    }
    if($priorityDecision == -1) {
        return 'stay';
    }
    else {
        return $nameDecision;
    }
}

function makeDecision(Animal $animal, string $decision){
    if($decision == 'stay') $animal->stay();
    else if($decision == 'goUp') $animal->goUp();
    else if($decision == 'goDown') $animal->goDown();
    else if($decision == 'goRight') $animal->goRight();
    else if($decision == 'goLeft') $animal->goLeft();
    else if($decision == 'goUpRight') $animal->goUpRight();
    else if($decision == 'goUpLeft') $animal->goUpLeft();
    else if($decision == 'goDownRight') $animal->goDownRight();
    else if($decision == 'goDownLeft') $animal->goDownLeft();
}

function printAnimals($animals) 
{
    $mouses = 1;
    $thereIsAnimal = 0;
    for($y = FIELD_SIZE;$y >= 0; $y--){
        for($x = 0; $x <= FIELD_SIZE; $x++){
            foreach($animals as $animal){
                if(($animal->posX == $x) && ($animal->posY == $y)) {
                    if($animal->name == 'cat'){
                        if($animal->sleep == 1){
                            echo "@";
                            $thereIsAnimal = 1;
                        }
                        else {
                            echo "K";
                            $thereIsAnimal = 1;
                        }
                    }
                    else if($animal->name == 'mouse') {
                        echo $mouses;
                        $mouses++;
                        $thereIsAnimal = 1;
                    }
                }
            }
            if($thereIsAnimal) {
                $thereIsAnimal = 0;
            }
            else echo "-";
        }
        echo "\n";
    }
    echo "\n";
}

$cat1 = new Cat(4,5);
$cat2 = new Cat(3,1);
$mouse1 = new Mouse(1,1);
$mouse2 = new Mouse(9,9);
$mouse3 = new Mouse(8,8);
$animals = array($cat1, $cat2, $mouse1, $mouse2, $mouse3);
for($i = 0; $i < 20; $i++){
    //var_dump($animals);
    foreach($animals as $animal) {
        $decision = chooseDecision($animal, $animals);
        //echo $decision."\n";
        //printAnimals($animals);
        makeDecision($animal, $decision);
        //printAnimals($animals);
        if($animal->name == "cat") {
            $x = $animal->posX;
            $y = $animal->posY;
            if($animal->sleep == 0){
                foreach($animals as $key => $animalMouse){
                    if($animalMouse->name == 'mouse'){
                        if(($animalMouse->posX == $x) && ($animalMouse->posY == $y)) {
                            unset($animals[$key]);
                            $animal->sleep = 1;
                        }
                    }
                }
            }
            else if($animal->turnsToWakeUp > 0){
                $animal->turnsToWakeUp--;
            }
            else {
                $animal->sleep = 0;
            }
        }
    }
    printAnimals($animals);
}