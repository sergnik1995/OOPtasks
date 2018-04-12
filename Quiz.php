<?php
 
error_reporting(-1);
mb_internal_encoding('utf-8');

abstract class AbstractQuestion 
{
    public $text;
    public $correctOption;

    abstract function checkAnswer($answer);
    abstract function getQuestionOutput();
}

class ChoiceQuestion extends AbstractQuestion
{
    public $options = array();

    public function __construct(string $text, string $correctOption, array $options)
    {
        $this->text = $text;
        $this->correctOption = $correctOption;
        $this->options = $options;
    }

    public function checkAnswer($answer)
    {
        if($this->correctOption == $answer) {
        	return 1;
        }
        return 0;
    }

    public function getQuestionOutput()
    {
        echo $this->text . "\n";
        foreach($this->options as $key => $option) {
            echo $key . ") " . $option . " ";
        }
        echo "\n";
    }

}

class NumericQuestion extends AbstractQuestion
{
    public $deviation;

    public function __construct(string $text, float $correctOption, int $deviation)
    {
        $this->text = $text;
        $this->correctOption = $correctOption;
        $this->deviation = $deviation;
    }

    public function checkAnswer($answer)
    {
        $answer = round($answer, $this->deviation);
        if($this->correctOption == $answer) {
        	return 1;
        }
        return 0;
    }

    public function getQuestionOutput()
    {
        echo $this->text . "\n";
    }

}

function createQuestions()
{
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
    $question->getQuestionOutput();
    if($question->checkAnswer($answers[$key])) {
        echo "Вы выбрали правильный ответ!\n";
    } else {
        echo "Вы выбрали неправильный ответ. Правильный ответ - {$question->correctOption}.\n";
    }
}