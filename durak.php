<?php

class Cards 
{
    public $suits;
    public $number;
    public $power;

    public function __construct($suits, $number, $power)
    {
        $this->suits = $suits;
        $this->number = $number;
        $this->power = $power;
    }
}

class Player 
{
    private $name;
    private $cards = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setCard($card)
    {
        $this->cards[] = $card;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCards()
    {
        return $this->cards;
    }
}

// создание колоды (pack)
$all_suits = ['Черви', 'Пики', 'Крести', 'Буби'];
$all_card_nums = ['6', '7', '8', '9', '10', 'Валет', 'Дама', 'Король', 'Туз'];
$pack = [];

foreach ($all_suits as $suits) 
{
    foreach ($all_card_nums as $number) 
    {
        $card_power = array_search($number, $all_card_nums) + 1;
        $card = new Cards($suits, $number, $card_power);
        $pack[] = $card;
    }
}

// перемешивание колоды
shuffle($pack);

// создание игроков с уникальными именами
// берём массив с именами из прошлой задачи и создаём пустой массив для будущих игроков
$name_generate = ['Артём', 'Кирилл', 'Филипп', 'Марк', 'Иван', 'Полина', 'Екатерина','Анна', 'Николь', 'Валерия'];
$players = [];

for ($i = 0; $i < 4; $i++) 
{
    $index = array_rand($name_generate);
    $playerName = $name_generate[$index];
    // индекс указывает на выбранное имя, которое впоследствие будет удалено
    // для гарантии уникальности имен удаляем выбранное имя из массива медотом array_splice
    array_splice($name_generate, $index, 1);

    $player = new Player($playerName);
    $players[] = $player;
}

// поиск козыря
$random_trump_key = array_rand($pack);
$random_trump_card = $pack[$random_trump_key];
echo 'Козырь - ' . $random_trump_card->number . " " . $random_trump_card->suits . "\n";

// раздача карт игрокам
foreach ($players as $player) 
{
    for ($i = 0; $i < 6; $i++) {
        // извлекаем карту из колоды
        $card = array_shift($pack);
        // с помощью метода array_shift извлекаем первые 6 элементов массива с картами для каждого игрока

        // проверяем, является ли карта козырем
        if (($card->suits === $random_trump_card->suits) && ($card->number === $random_trump_card->number)) {
            // если карта - козырь, возвращаем ее обратно в колоду и извлекаем новую
            array_push($pack, $card);
            $i--;
            // уменьшает переменную $i на единицу. это нужно для того, чтобы в следующей 
            // итерации цикла взять новую карту, так как текущую, являющуюся козырем, мы вернули обратно в колоду
            continue;
        }
        // выдаём карту игроку
        $player->setCard($card);
    }
}

// увеличение силы козырных карт
foreach ($players as $player) 
{
    foreach ($player->getCards() as $card) 
    {
        if ($card->suits === $random_trump_card->suits) {
            // если масть карты совпадает с мастью козыря - увеличиваем её силу на 10
            $card->power += 10;
        }
    }
}

// вывод карт каждого игрока
foreach ($players as $player) 
{
    echo "Имя игрока: {$player->getName()}\n";
    echo "Карты игрока:\n";
    print_r($player->getCards());
    echo "\n";
}

// подсчет суммы очков каждого игрока. для этого заводим массив scores (очки)
$scores = [];

foreach ($players as $player) 
{
    $score = 0;
    
    foreach ($player->getCards() as $card) {
        $score += $card->power;
    }

    $scores[$player->getName()] = $score;
}

// вывод счетов
foreach ($scores as $playerName => $score) 
{
    echo "Счёт игрока {$playerName}: {$score}\n";
}

// определение победителя (winner)
$maxScore = max($scores);
$winner = 0;

foreach ($scores as $playerName => $score) 
{
    if ($score === $maxScore) {
        $winner = $playerName;
    }
}

// вывод победителя
echo "Победитель: " . $winner . "\n";

?>