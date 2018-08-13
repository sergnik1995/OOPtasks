<?php
 
error_reporting(-1);

const FIELD_SIZE = 10;
const MOUSE_FIELD_OF_VIEW = 9;

abstract class Animal
{
	public $posX;
	public $posY;

	public function goUp()
	{
		$this->posY--;
	}

	public function goRight()
	{
		$this->posX++;
	}

	public function goLeft()
	{
		$this->posX--;
	}

	public function goDown()
	{
		$this->posY++;
	}

}

class Mouse extends Animal
{

	public function __construct(int $posX, int $posY)
	{
		$this->posX = $posX;
		$this->posY = $posY;
	}

	public function makeDecision(Map $map)
	{
	    $decisions = array('Up' => 0, 'UpRight' => 0, 'Right' => 0, 'DownRight' => 0, 
						   'Down' => 0, 'DownLeft' => 0, 'Left' => 0, 'UpLeft' => 0);
		$decisions = checkBorders($this) + $decisions;
		$animalsInRadius = array();
		if(MOUSE_FIELD_OF_VIEW <= FIELD_SIZE) {
			$maxRadius = MOUSE_FIELD_OF_VIEW;
		} else {
			$maxRadius = FIELD_SIZE;
		}
		for($radius = 1; $radius < $maxRadius; $radius++) {
			$animalsInRadius = $map->searchInRadius($this->posX, $this->posY, $radius);
			if(count($animalsInRadius) == 1) {
			} else {
				foreach($animalsInRadius as $animal) {
					if($animal == $this) {
					} elseif ($animal instanceof Mouse) {
						if($radius == 1) {
							$decisions[checkDirection($this, $animal)] += -100;
						} else {
							$decisions[checkDirection($this, $animal)] += 1;
						}							
					} elseif ($animal instanceof Cat) {
						$direction = checkDirection($this, $animal);
						if($direction == 'Up') {
						    $decisions[$direction] += -1;
						    $decisions['UpRight'] += -1;
						    $decisions['UpLeft'] += -1;
						} elseif($direction == 'UpRight') {
						    $decisions[$direction] += -1;
						    $decisions['Up'] += -1;
						    $decisions['Right'] += -1;
						} elseif($direction == 'Right') {
						    $decisions[$direction] += -1;
						    $decisions['UpRight'] += -1;
						    $decisions['DownRight'] += -1;
						} elseif($direction == 'DownRight') {
						    $decisions[$direction] += -1;
						    $decisions['Right'] += -1;
						    $decisions['Down'] += -1;
						} elseif($direction == 'Down') {
						    $decisions[$direction] += -1;
						    $decisions['DownLeft'] += -1;
						    $decisions['DownRight'] += -1;
						} elseif($direction == 'DownLeft') {
						    $decisions[$direction] += -1;
						    $decisions['Left'] += -1;
						    $decisions['Down'] += -1;
						} elseif($direction == 'Left') {
						    $decisions[$direction] += -1;
						    $decisions['UpLeft'] += -1;
						    $decisions['DownLeft'] += -1;
						} elseif($direction == 'UpLeft') {
						    $decisions[$direction] += -1;
						    $decisions['Left'] += -1;
						    $decisions['Up'] += -1;
						}
					}
				}
			}
		}
		//var_dump($decisions);
		$maxValuableDecision['value'] = -1;
		$maxValuableDecision['name'] = 'stay';
		foreach ($decisions as $decision => $value) {
			if($value > $maxValuableDecision['value']) {
				$maxValuableDecision['value'] = $value;
				$maxValuableDecision['name'] = $decision;
			}
		}
		//var_dump($maxValuableDecision);
		if ($maxValuableDecision['name'] == 'stay') {
			return 0;
		} elseif ($maxValuableDecision['name'] == 'Up') {
			$this->goUp();
		} elseif ($maxValuableDecision['name'] == 'UpRight') {
			if($decisions['Up'] > $decisions['Right']) $this->goUp();
			else if($decisions['Up'] < $decisions['Right']) $this->goRight();
		} elseif ($maxValuableDecision['name'] == 'Right') {
			$this->goRight();
		} elseif ($maxValuableDecision['name'] == 'DownRight') {
			if($decisions['Down'] > $decisions['Right']) $this->goDown();
			else if($decisions['Down'] < $decisions['Right']) $this->goRight();
		} elseif ($maxValuableDecision['name'] == 'Down') {
			$this->goDown();
		} elseif ($maxValuableDecision['name'] == 'DownLeft') {
			if($decisions['Down'] > $decisions['Left']) $this->goDown();
			else if($decisions['Down'] < $decisions['Left']) $this->goLeft();
		} elseif ($maxValuableDecision['name'] == 'Left') {
			$this->goLeft();
		} elseif ($maxValuableDecision['name'] == 'UpLeft') {
			if($decisions['Up'] > $decisions['Left']) $this->goUp();
			else if($decisions['Up'] < $decisions['Left']) $this->goLeft();
		}
	}

}

class Cat extends Animal
{
	public $timeToSleep;
	public $movesDone;

	public function __construct(int $posX, int $posY)
	{
		$this->posX = $posX;
		$this->posY = $posY;
		$this->timeToSleep = 0;
		$this->movesDone = 0;
	}

	public function goUpRight()
	{
		$this->posY--;
		$this->posX++;
	}

	public function goUpLeft()
	{
		$this->posY--;
		$this->posX--;
	}

	public function goDownRight()
	{
		$this->posY++;
		$this->posX++;
	}

	public function goDownLeft()
	{
		$this->posY++;
		$this->posX--;
	}

	public function makeDecision(Map $map)
	{
		if($this->timeToSleep > 0) {
			$this->timeToSleep--;
			return 0;
		}
		$decisions = array('Up' => 0, 'UpRight' => 0, 'Right' => 0, 'DownRight' => 0, 
						   'Down' => 0, 'DownLeft' => 0, 'Left' => 0, 'UpLeft' => 0);
		$decisions = checkBorders($this) + $decisions;
		$animalsInRadius = array();
		$maxRadius = FIELD_SIZE;
		for($radius = 1; $radius < $maxRadius; $radius++) {
			$animalsInRadius = $map->searchInRadius($this->posX, $this->posY, $radius);
			if(count($animalsInRadius) == 1) {
			} else {
				foreach($animalsInRadius as $animal) {
					if($animal == $this) {
					} elseif ($animal instanceof Mouse) {
						if($radius == 1) {
							$mousesAround = 0;
							foreach($animalsInRadius as $nearestAnimal) {
								if($nearestAnimal instanceof Mouse) {
									if($animal == $nearestAnimal) {
									} elseif(checkDistance($animal, $nearestAnimal) == 1) {
										$mousesAround++;
									} 
								}
							}
							if($mousesAround >= 2) {
								$decisions[checkDirection($this, $animal)] += -1;
							}
							else {
								$decisions[checkDirection($this, $animal)] += 100;
							}
						} else {
							$decisions[checkDirection($this, $animal)] += 1;
						}							
					} elseif ($animal instanceof Cat) {
						$decisions[checkDirection($this, $animal)] += -1;	
					}
				}
			}
		}
		$maxValuableDecision['value'] = 0;
		$maxValuableDecision['name'] = 'stay';
		foreach ($decisions as $decision => $value) {
			if($value > $maxValuableDecision['value']) {
				$maxValuableDecision['value'] = $value;
				$maxValuableDecision['name'] = $decision;
			}
		}
		//var_dump($decisions);
		//var_dump($maxValuableDecision);
		if($maxValuableDecision['name'] == 'stay') {
			return 0;
		} elseif($maxValuableDecision['name'] == 'Up') {
			$this->goUp();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'UpRight') {
			$this->goUpRight();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'Right') {
			$this->goRight();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'DownRight') {
			$this->goDownRight();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'Down') {
			$this->goDown();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'DownLeft') {
			$this->goDownLeft();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'Left') {
			$this->goLeft();
			$this->movesDone++;
		} elseif ($maxValuableDecision['name'] == 'UpLeft') {
			$this->goUpLeft();
			$this->movesDone++;
		}
		foreach($map->animals as $animal) {
			if($animal instanceof Mouse) {
				if($animal->posX == $this->posX && $animal->posY == $this->posY) {
					$this->eatMouse($animal, $map);
					$this->timeToSleep = 1;
					return 0;
				}
			}
		}
		if($this->movesDone == 8 ) {
			$this->timeToSleep = 1;
		}
	}

	public function eatMouse(Mouse $mouse, Map $map)
	{
		$map->deleteAnimal($mouse);
	}

}

class Map
{
	public $animals;

	public function __construct()
	{
		$this->animals = array();
	}

	public function searchInRadius(int $posX, int $posY, int $radius): array
	{
		$animalsInRadius = array();
		$count = 0;
		foreach($this->animals as $animal) {
			if(abs($posX - $animal->posX) <= $radius) {
				if(abs($posY - $animal->posY) <= $radius) {
					$animalsInRadius[$count] = $animal;
					$count++;
				}
			}
		}
		return $animalsInRadius;
	}

	public function deleteAnimal(animal $animalToDelete) 
	{
		$key = array_search($animalToDelete, $this->animals);
		unset($this->animals[$key]); 
	}

}

function checkDirection(animal $from, animal $to): string
{
	if($from->posY > $to->posY && $from->posX == $to->posX) {
		return "Up";
	} elseif ($from->posY > $to->posY && $from->posX < $to->posX) {
		return "UpRight";
	} elseif ($from->posY == $to->posY && $from->posX < $to->posX) {
		return "Right";
	} elseif ($from->posY < $to->posY && $from->posX < $to->posX) {
		return "DownRight";
	} elseif ($from->posY < $to->posY && $from->posX == $to->posX) {
		return "Down";
	} elseif ($from->posY < $to->posY && $from->posX > $to->posX) {
		return "DownLeft";
	} elseif ($from->posY == $to->posY && $from->posX > $to->posX) {
		return "Left";
	} elseif ($from->posY > $to->posY && $from->posX > $to->posX) {
		return "UpLeft";
	}
}

function checkDistance(animal $from, animal $to): int
{
	$x = abs($from->posX - $to->posX);
	$y = abs($from->posY - $to->posY);
	$distance = round(sqrt(pow($x, 2) + pow($y, 2)));
	return $distance; 
}

function checkBorders(Animal $animal, Map $map = NULL): array
{
	$decisions = array();
	if($map != NULL) {
	    $nearestAnimals = array();
	    $nearestAnimals = $map->searchInRadius($animal->posX, $animal->posY, 1);
	    foreach($nearestAnimals as $nearestAnimal) {
	        if($nearestAnimal->posY == ($animal->posY - 1)) {
	            if($nearestAnimal->posX == ($animal->posX - 1)) {
	                $decisions['UpLeft'] = -1;
	            }
	            else if($nearestAnimal->posX == $animal->posX) {
	                $decisions['Up'] = -1;
	            }
	            else if($nearestAnimal->posX == ($animal->posX + 1)) {
	                $decisions['UpRight'] = -1;
	            }
	        }
	        else if($nearestAnimal->posY == $animal->posY) {
	            if($nearestAnimal->posX == ($animal->posX - 1)) {
	                $decisions['Left'] = -1;
	            }
	            else if($nearestAnimal->posX == ($animal->posX + 1)) {
	                $decisions['Right'] = -1;
	            }
	        }
	        else if($nearestAnimal->posY == ($animal->posY + 1)) {
	            if($nearestAnimal->posX == ($animal->posX - 1)) {
	                $decisions['DownLeft'] = -1;
	            }
	            else if($nearestAnimal->posX == $animal->posX) {
	                $decisions['Down'] = -1;
	            }
	            else if($nearestAnimal->posX == ($animal->posX + 1)) {
	                $decisions['DownRight'] = -1;
	            }
	        }
	    }
	}
	if($animal->posX == FIELD_SIZE) {
		$decisions['Right'] = -1;
		$decisions['DownRight'] = -1;
		$decisions['UpRight'] = -1;
		if($animal->posY == FIELD_SIZE) {
			$decisions['Down'] = -1;
			$decisions['DownLeft'] = -1;
		} elseif($animal->posY == 1) {
			$decisions['Up'] = -1;
			$decisions['UpLeft'] = -1;
		}
	} elseif($animal->posX == 1) {
		$decisions['Left'] = -1;
		$decisions['DownLeft'] = -1;
		$decisions['UpLeft'] = -1;
		if($animal->posY == FIELD_SIZE) {
			$decisions['Down'] = -1;
			$decisions['DownRight'] = -1;
		} elseif($animal->posY == 1) {
			$decisions['Up'] = -1;
			$decisions['UpRight'] = -1;
		}
	} elseif($animal->posY == FIELD_SIZE){
		$decisions['Down'] = -1;
		$decisions['DownLeft'] = -1;
		$decisions['DownRight'] = -1;
	} elseif($animal->posY == 1){
		$decisions['Up'] = -1;
		$decisions['UpLeft'] = -1;
		$decisions['UpRight'] = -1;
	}
	return $decisions;
}

function printMap($map) 
{
	$mapToPrint = array();
	$mouseCount = 0;
	foreach ($map->animals as $animal) {
		if($animal instanceof Mouse) {
			$mouseCount++;
			$mapToPrint[$animal->posY][$animal->posX] = $mouseCount;
		} elseif ($animal instanceof Cat) {
		    if($animal->timeToSleep != 0){
		        $mapToPrint[$animal->posY][$animal->posX] = '@';
		    }
			else{ 
			    $mapToPrint[$animal->posY][$animal->posX] = 'M';
			}
		}
	}
	for($y = 1; $y <= FIELD_SIZE; $y++) {
		for($x = 1; $x <= FIELD_SIZE; $x++) {
			if(isset($mapToPrint[$y][$x])) {
				echo $mapToPrint[$y][$x];
			} else {
				echo "-";
			}
		}
		echo "\n";
	}
	echo "\n";
}

function createGame()
{
	$mouse1 = new Mouse(2, 5);
	$mouse2 = new Mouse(4, 8);
	$mouse3 = new Mouse(4, 5);
	$cat1 = new Cat(5, 1);
	$cat2 = new Cat(6, 3);
	$map = new Map();
	$map->animals = array($mouse1, $mouse2, $mouse3, $cat1, $cat2);
	printMap($map);
	for($i = 0; $i < 50; $i++) {
		foreach($map->animals as $animal) {
			if($animal instanceof Mouse) {
				$animal->makeDecision($map);
			}
		}
		foreach($map->animals as $animal) {
			if($animal instanceof Cat) {
				$animal->makeDecision($map);
			}
		}
		$i++;
		printMap($map);
	}
}

createGame();