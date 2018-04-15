<?php
 
error_reporting(-1);

class Company
{
    public $departments;

    public function __construct(array $a)
    {
        $this->departments = $a;
    }

    public function getAverageWorkers() 
    {
        return $this->getTotalWorkers() / count($this->departments);
    }

    public function getAveragePay()
    {
        return $this->getTotalPay() / count($this->departments);
    }

    public function getAverageCoffee()
    {
        return $this->getTotalCoffee() / count($this->departments);
    }

    public function getAverageLists()
    {
        return $this->getTotalLists() / count($this->departments);
    }

    public function getAveragePayInLists()
    {
        return $this->getTotalPayInLists() / count($this->departments);
    }

    public function getTotalWorkers()
    {
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumWorkers();
        }
        return $sum;
    }

    public function getTotalPay()
    {
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumPay();
        }
        return $sum;
    }

    public function getTotalCoffee()
    {
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumCoffee();
        }
        return $sum;
    }

    public function getTotalLists()
    {
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->sumLists();
        }
        return $sum;
    }

    public function getTotalPayInLists()
    {
        $sum = 0;
        foreach($this->departments as $department){
            $sum += $department->getPayInLists();
        }
        return $sum;
    }

}

class Department
{
    public $name;
    public $workers;

    public function __construct(string $a, array $b)
    {
        $this->name = $a;
        $this->workers = $b;
    }

    public function sumWorkers()
    {
        return count($this->workers);
    }

    public function sumPay()
    {
        $sumPay = 0;
        foreach($this->workers as $worker) {
            $sumPay += $worker->getPay();
        }
        return $sumPay;
    }

    public function sumCoffee()
    {
        $sumCoffee = 0;
        foreach($this->workers as $worker) {
            $sumCoffee += $worker->getCoffee();
        }
        return $sumCoffee;
    }

    public function sumLists()
    {
        $sumLists = 0;
        foreach($this->workers as $worker) {
            $sumLists += $worker->getLists();
        }
        return $sumLists;
    }

    public function getPayInLists()
    {
        $sumPay = $this->sumPay();
        $sumLists = $this->sumLists();
        return $sumPay / $sumLists;
    }

}

class Worker
{
    private $rang;
    private $boss;

    public function __construct(int $rang, int $boss)
    {
    	$this->rang = $rang;
    	$this->boss = $boss;
    }

    public function getPay(): int
    {
        if($this->rang == 2) {
            $this->pay *= 1.25;
        } else if($this->rang == 3) {
            $this->pay *= 1.5;
        }
        if($this->boss == 1) {
            $this->pay *= 1.5;
        }
        return $this->pay;
    }

    public function getCoffee(): int
    {
        if($this->boss == 1) {
            $this->coffee *= 2;
        }
        return $this->coffee;
    }

    public function getLists(): int
    {
        if($this->boss == 1){
            return 0;
        }
        return $this->lists;
    }
}

class Manager extends Worker
{
	public $pay = 500;
	public $coffee = 20;
	public $lists = 200;
}

class Marketer extends Worker
{
	public $pay = 400;
	public $coffee = 15;
	public $lists = 150;
}

class Engineer extends Worker 
{
	public $pay = 200;
	public $coffee = 5;
	public $lists = 50;
}

class Analyst extends Worker 
{
	public $pay = 800;
	public $coffee = 50;
	public $lists = 5;
}

class CrisisAnalyst extends Analyst
{
	public $pay = 1100;
	public $coffee = 75;
}

function padRight($string, $length): string
{
    $strLen = mb_strlen($string);
    $string = $string . str_repeat(" ", $length - $strLen);
    return $string;
}

function padLeft($string, $length): string 
{
    $strLen = mb_strlen($string);
    $string = str_repeat(" ", $length - $strLen) . $string;
    return $string;
}

function getCompanyOutput(Company $company)
{
	echo padRight('Департамент', 15);
    echo padLeft('сотр.', 10);
	echo padLeft('тугр.', 10);
    echo padLeft('кофе', 10);
    echo padLeft('стр.', 10);
    echo padLeft('тугр./стр.', 10);
    echo "\n".str_repeat("-", 70)."\n";
    foreach($company->departments as $department){
    	getDepartmentOutput($department);
        echo "\n";
    }
    echo padRight('Среднее', 15);
    echo padLeft(round($company->getAverageWorkers(), 1), 10);
    echo padLeft(round($company->getAveragePay(), 1), 10);
    echo padLeft(round($company->getAverageCoffee(), 1), 10);
    echo padLeft(round($company->getAverageLists(), 1), 10);
    echo padLeft(round($company->getAveragePayInLists(), 1), 10);
    echo "\n";
    echo padRight('Всего', 15);
    echo padLeft($company->getTotalWorkers(), 10);
    echo padLeft($company->getTotalPay(), 10);
    echo padLeft($company->getTotalCoffee(), 10);
    echo padLeft($company->getTotalLists(), 10);
    echo padLeft(round($company->getTotalPayInLists(), 1), 10);
    echo "\n";
}

function getDepartmentOutput(Department $department)
{
	echo padRight($department->name, 15);
	echo padLeft($department->sumWorkers(), 10);
	echo padLeft($department->sumPay(), 10);
	echo padLeft($department->sumCoffee(), 10);
	echo padLeft($department->sumLists(), 10);
	echo padLeft(round($department->getPayInLists(), 1), 10);
}

function createDepartment()
{
    $workers1 = array();
    for($i = 0; $i < 9; $i++){
        $workers1[$i] = new Manager(1, 0);
    }
    for($i = 9; $i < 12; $i++) {
        $workers1[$i] = new Manager(2, 0);
    }
    for($i = 12; $i < 14; $i++) {
        $workers1[$i] = new Manager(3, 0);
    }
    for($i = 14; $i < 16; $i++) {
        $workers1[$i] = new Marketer(1, 0);
    }
    $workers1[16] = new Manager(2, 1);
    $workers2 = array();
    for($i = 0; $i < 12; $i++) {
        $workers2[$i] = new Manager(1, 0);
    }
    for($i = 12; $i < 18; $i++) {
        $workers2[$i] = new Marketer(1, 0);
    }
    for($i = 18; $i < 21; $i++) {
        $workers2[$i] = new Analyst(1, 0);
    }
    for($i = 21; $i < 23; $i++) {
        $workers2[$i] = new Analyst(2, 0);
    }
    $workers2[23] = new Marketer(2, 1);
    $workers3 = array();
    for($i = 0; $i < 15; $i++) {
        $workers3[$i] = new Marketer(1, 0);
    }
    for($i = 15; $i < 25; $i++) {
        $workers3[$i] = new Marketer(2, 0);
    }
    for($i = 25; $i < 33; $i++) {
        $workers3[$i] = new Manager(1, 0);
    }
    for($i = 33; $i < 35; $i++) {
        $workers3[$i] = new Engineer(1, 0);
    }
    $workers3[$i] = new Marketer(3, 1);
    $workers4 = array();
    for($i = 0; $i < 13; $i++) {
        $workers4[$i] = new Manager(1, 0);
    }
    for($i = 13; $i < 18; $i++) {
        $workers4[$i] = new Manager(2, 0);
    }
    for($i = 18; $i < 23; $i++) {
        $workers4[$i] = new Engineer( 1, 0);
    }
    $workers4[23] = new Manager(1, 1);
    $departments = array();
    $departments[1] = new Department("Закупок", $workers1);
    $departments[2] = new Department("Продаж", $workers2);
    $departments[3] = new Department("Рекламы", $workers3);
    $departments[4] = new Department("Логистики", $workers4);
    $company = new Company($departments);
    return $company;
}

function createDepartmentCrisis()
{
    $workers1 = array();
    for($i = 0; $i < 4; $i++) {
        $workers1[$i] = new Manager(1, 0);
    }
    for($i = 4; $i < 10; $i++) {
        $workers1[$i] = new Manager(2, 0);
    }
    for($i = 10; $i < 14; $i++) {
        $workers1[$i] = new Manager(3, 0);
    }
    for($i = 14; $i < 16; $i++) {
        $workers1[$i] = new Marketer(1, 0);
    }
    $workers1[16] = new Manager(2, 1);
    $workers2 = array();
    for($i = 0; $i < 6; $i++) {
        $workers2[$i] = new Manager(1, 0);
    }
    for($i = 6; $i < 12; $i++) {
        $workers2[$i] = new Manager(2, 0);
    }
    for($i = 12; $i < 18; $i++) {
        $workers2[$i] = new Marketer(1, 0);
    }
    $workers2[$i] = new Marketer(2, 0);
    for($i = 19; $i < 22; $i++) {
        $workers2[$i] = new CrisisAnalyst(1, 0);
    }
    for($i = 22; $i < 23; $i++) {
        $workers2[$i] = new CrisisAnalyst(2, 0);
    }
    $workers2[23] = new CrisisAnalyst(2, 1);
    $workers3 = array();
    for($i = 0; $i < 15; $i++) {
        $workers3[$i] = new Marketer(1, 0);
    }
    for($i = 15; $i < 25; $i++) {
        $workers3[$i] = new Marketer(2, 0);
    }
    for($i = 25; $i < 29; $i++) {
        $workers3[$i] = new Manager(1, 0);
    }
    for($i = 29; $i < 33; $i++) {
        $workers3[$i] = new Manager(2, 0);
    }
    for($i = 33; $i < 34; $i++) {
        $workers3[$i] = new Engineer(1, 0);
    }
    $workers3[$i] = new Marketer(3, 1);
    $workers4 = array();
    for($i = 0; $i < 5; $i++) {
        $workers4[$i] = new Manager(1, 0);
    }
    for($i = 5; $i < 15; $i++) {
        $workers4[$i] = new Manager(2, 0);
    }	
    for($i = 15; $i < 18; $i++) {
        $workers4[$i] = new Manager(3, 0);
    }
    for($i = 18; $i < 21; $i++) {
        $workers4[$i] = new Engineer(1, 0);
    }
    $workers4[23] = new Manager(1, 1);
    $departments = array();
    $departments[1] = new Department("Закупок", $workers1);
    $departments[2] = new Department("Продаж", $workers2);
    $departments[3] = new Department("Рекламы", $workers3);
    $departments[4] = new Department("Логистики", $workers4);
    $company = new Company($departments);
    return $company;
}

$company = createDepartment();
getCompanyOutput($company);

echo "\nАнтикризисный вариант\n\n";
$crisisCompany = createDepartmentCrisis();
getCompanyOutput($crisisCompany);