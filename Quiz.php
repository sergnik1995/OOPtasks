<?php
 
error_reporting(-1);
mb_internal_encoding('utf-8');

abstract class AbstractQuestion 
{
    public $text;
    public $correctOption; // ответ
    abstract function checkAnswer($answer);
    abstract function getQuestionOutput();
}

class ChoiceQuestion extends AbstractQuestion
{
    public $options = array(); // варианты ответа
    public function __construct($stringText, $stringCorrect, $arrayOptions){
        $this->text = $stringText;
        $this->correctOption = $stringCorrect;
        $this->options = $arrayOptions;
    }
    public function checkAnswer($answer){
        if($this->correctOption == $answer) return 1;
        else return 0;

    }
    public function getQuestionOutput(){
        echo $this->text . "\n";
        foreach($this->options as $key => $option){
            echo $key . ") " . $option . " ";
        }
        echo "\n";
    }
}

class NumericQuestion extends AbstractQuestion
{
    public $deviation; // допустимая погрешность
    public function __construct($stringText, $stringCorrect, $numberDeviation){
        $this->text = $stringText;
        $this->correctOption = $stringCorrect;
        $this->deviation = $numberDeviation;
    }
    public function checkAnswer($answer){
        $answer = round($answer, $this->deviation);
        if($this->correctOption == $answer) return 1;
        else return 0;
    }
    public function getQuestionOutput(){
        echo $this->text . "\n";
    }
}

function createQuestions(){
    $temp = array("a" => "Альберт Эйнштейн", "b" => "Нильс Бор", "c" => "Макс Планк", "d" => "Лайнус Полинг");
    $q1 = new ChoiceQuestion("Кто из этих ученых дважды был лауреатом Нобелевской премии?",
                             "d", $temp);
    $q2 = new NumericQuestion("Чему равно число пи?", 3.14, 2);
    $arrayQuestions = array($q1, $q2);
    return $arrayQuestions;
}

$questions = createQuestions();
$answers = array('d', 3.14);
foreach($questions as $key => $question) {
    //var_dump($answers);
    $question->getQuestionOutput();
    if($question->checkAnswer($answers[$key])){
        echo "Вы выбрали правильный ответ!\n";
    }
    else{
        echo "Вы выбрали неправильный ответ. Правильный ответ - {$question->correctOption}.\n";
    }
}