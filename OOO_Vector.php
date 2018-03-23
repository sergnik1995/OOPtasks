<?php
 
error_reporting(-1);

class Company
{
    private $departments;
    public function __construct(array $a)
    {
        $this->departments = $a;
    }
    public function getOutput()
    {
        padRight('Департамент', 15);
        padLeft('сотр.', 10);
        padLeft('тугр.', 10);
        padLeft('кофе', 10);
        padLeft('стр.', 10);
        padLeft('тугр./стр.', 10);
        echo "\n".str_repeat("-", 70)."\n";
        foreach($this->departments as $department){
            $department->getOutput();
            echo "\n";
        }
        padRight('Среднее', 15);
        padLeft($this->getAverageWorkers(), 10);
        padLeft($this->getAveragePay(), 10);
        padLeft($this->getAverageCoffee(), 10);
        padLeft($this->getAverageLists(), 10);
        padLeft($this->getAveragePayInLists(), 10);
        echo "\n";
        padRight('Всего', 15);
        padLeft($this->getTotalWorkers(), 10);
        padLeft($this->getTotalPay(), 10);
        padLeft($this->getTotalCoffee(), 10);
        padLeft($this->getTotalLists(), 10);
        padLeft($this->getTotalPayInLists(), 10);
        echo "\n";
    }
    public function getAverageWorkers(){
        $count = 0;
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumWorkers();
            $count++;
        }
        return round($sum / $count, 1);
    }
    public function getAveragePay(){
        $count = 0;
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumPay();
            $count++;
        }
        return round($sum / $count, 1);
    }
    public function getAverageCoffee(){
        $count = 0;
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumCoffee();
            $count++;
        }
        return round($sum / $count, 1);
    }
    public function getAverageLists(){
        $count = 0;
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumLists();
            $count++;
        }
        return round($sum / $count, 1);
    }
    public function getAveragePayInLists(){
        $count = 0;
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->getPayInLists();
            $count++;
        }
        return round($sum / $count, 1);
    }
    public function getTotalWorkers(){
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumWorkers();
        }
        return $sum;
    }
    public function getTotalPay(){
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumPay();
        }
        return $sum;
    }
    public function getTotalCoffee(){
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumCoffee();
        }
        return $sum;
    }
    public function getTotalLists(){
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumLists();
        }
        return $sum;
    }
    public function getTotalPayInLists(){
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->getPayInLists();
        }
        return $sum;
    }
}

class Department
{
    private $name;
    private $workers;
    public function __construct(string $a, array $b){
        $this->name = $a;
        $this->workers = $b;
    }
    public function sumWorkers(){
        $count = 0;
        foreach($this->workers as $worker){
            $count++;
        }
        return $count;
    }
    public function sumPay(){
        $sumPay = 0;
        foreach($this->workers as $worker){
            $sumPay += $worker->getPay();
        }
        return $sumPay;
    }
    public function sumCoffee(){
        $sumCoffee = 0;
        foreach($this->workers as $worker){
            $sumCoffee += $worker->getCoffee();
        }
        return $sumCoffee;
    }
    public function sumLists(){
        $sumLists = 0;
        foreach($this->workers as $worker){
            $sumLists += $worker->getLists();
        }
        return $sumLists;
    }
    public function getPayInLists(){
        $sumPay = $this->sumPay();
        $sumLists = $this->sumLists();
        return round($sumPay / $sumLists, 1);
    }
    public function getOutput(){
        padRight($this->name, 15);
        padLeft($this->sumWorkers(), 10);
        padLeft($this->sumPay(), 10);
        padLeft($this->sumCoffee(), 10);
        padLeft($this->sumLists(), 10);
        padLeft($this->getPayInLists(), 10);
    }
}

class Worker
{
    private $prof;
    private $rang;
    private $boss;
    public function __construct(string $a, int $b, bool $c){
        $this->prof = $a;
        $this->rang = $b;
        $this->boss = $c;
    }
    public function getPay(){
        $pay;
        if($this->prof == "me") {
            $pay = 500;
        }
        else if($this->prof == "ma") {
            $pay = 400;
        }
        else if($this->prof == "in") {
            $pay = 200;
        }
        else if($this->prof == "an") {
            $pay = 800;
        }
        if($this->rang == 2) {
            $pay *= 1.25;
        }
        else if($this->rang == 3) {
            $pay *= 1.5;
        }
        if($this->boss == 1) {
            $pay *= 1.5;
        }
        return $pay;
    }
    public function getCoffee(){
        $coffee;
        if($this->prof == "me") {
            $coffee = 20;
        }
        else if($this->prof == "ma") {
            $coffee = 15;
        }
        else if($this->prof == "in") {
            $coffee = 5;
        }
        else if($this->prof == "an") {
            $coffee = 50;
        }
        if($this->boss == 1) {
            $coffee *= 2;
        }
        return $coffee;
    }
    public function getLists(){
        $lists;
        if($this->boss == 1){
            return 0;
        }
        else if($this->prof == "me") {
            $lists = 200;
        }
        else if($this->prof == "ma") {
            $lists = 150;
        }
        else if($this->prof == "in") {
            $lists = 50;
        }
        else if($this->prof == "an") {
            $lists = 5;
        }
        return $lists;
    }
}

function padRight($string, $length){
    $strLen = mb_strlen($string);
    echo $string;
    echo str_repeat(" ", $length - $strLen);
}

function padLeft($string, $length){
    $strLen = mb_strlen($string);
    echo str_repeat(" ", $length - $strLen);
    echo $string;
}

function createDepartment(){
    $workers1 = array();
    for($i = 0; $i < 9; $i++){
        $workers1[$i] = new Worker("me", 1, 0);
    }
    for($i = 9; $i < 12; $i++) {
        $workers1[$i] = new Worker("me", 2, 0);
    }
    for($i = 12; $i < 14; $i++) {
        $workers1[$i] = new Worker("me", 3, 0);
    }
    for($i = 14; $i < 16; $i++) {
        $workers1[$i] = new Worker("ma", 1, 0);
    }
    $workers1[16] = new Worker("me", 2, 1);
    $workers2 = array();
    for($i = 0; $i < 12; $i++) {
        $workers2[$i] = new Worker("me", 1, 0);
    }
    for($i = 12; $i < 18; $i++) {
        $workers2[$i] = new Worker("ma", 1, 0);
    }
    for($i = 18; $i < 21; $i++) {
        $workers2[$i] = new Worker("an", 1, 0);
    }
    for($i = 21; $i < 23; $i++) {
        $workers2[$i] = new Worker("an", 2, 0);
    }
    $workers2[23] = new Worker('ma', 2, 1);
    $workers3 = array();
    for($i = 0; $i < 15; $i++) {
        $workers3[$i] = new Worker("ma", 1, 0);
    }
    for($i = 15; $i < 25; $i++) {
        $workers3[$i] = new Worker("ma", 2, 0);
    }
    for($i = 25; $i < 33; $i++) {
        $workers3[$i] = new Worker("me", 1, 0);
    }
    for($i = 33; $i < 35; $i++) {
        $workers3[$i] = new Worker("in", 1, 0);
    }
    $workers3[$i] = new Worker('ma', 3, 1);
    $workers4 = array();
    for($i = 0; $i < 13; $i++) {
        $workers4[$i] = new Worker("me", 1, 0);
    }
    for($i = 13; $i < 18; $i++) {
        $workers4[$i] = new Worker("me", 2, 0);
    }
    for($i = 18; $i < 23; $i++) {
        $workers4[$i] = new Worker("in", 1, 0);
    }
    $workers4[23] = new Worker('me', 1, 1);
    $departments = array();
    $departments[1] = new Department("Закупок", $workers1);
    $departments[2] = new Department("Продаж", $workers2);
    $departments[3] = new Department("Рекламы", $workers3);
    $departments[4] = new Department("Логистики", $workers4);
    $company = new Company($departments);
    return $company;
}

$company = createDepartment();
$company->getOutput();