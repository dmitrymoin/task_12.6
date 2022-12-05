<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP task 12.6</title>
</head>
<body>
<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


// Используем случайную персону из массива

$randomPerson = $example_persons_array[rand(0, count($example_persons_array) -1)]['fullname'];
?>

<h4>Разбиение ФИО</h4>

<?php
function getPartsFromFullname($randomPerson){
   $arrayFullname = explode(' ', $randomPerson);
   global $splitName;
   $splitName = ['surname' => $arrayFullname[0], 'name' => $arrayFullname[1], 'patronymic' => $arrayFullname[2]];
   return $splitName;
}

getPartsFromFullname($randomPerson);
echo $splitName['surname'].'<br>';
echo $splitName['name'].'<br>';
echo $splitName['patronymic'].'<br>';
?>

<h4>Объединение ФИО</h4>

<?php
$surname = $splitName['surname'];
$name = $splitName['name'];
$patronymic = $splitName['patronymic'];

function getFullnameFromParts($surname, $name, $patronymic){
    global $combineName;
    $combineName = $surname .' '. $name .' '. $patronymic;
    return $combineName;
}

getFullnameFromParts($surname, $name, $patronymic);
echo $combineName.'<br>';
?>

<h4>Сокращение ФИО</h4>

<?php
function getShortName($randomPerson){
    $split = getPartsFromFullname($randomPerson);
    global $initials;
    $initials = $split['name'] .' '. mb_substr($split['surname'], 0, 1).'.';
    return $initials;
}

getShortName($randomPerson);
echo $initials;
?>

<h4>Функция определения пола по ФИО</h4>

<?php
function getGenderFromName($randomPerson){
    $split = getPartsFromFullname($randomPerson);
    $genderAmount = 0;

    if(mb_substr($split['patronymic'], -3) == 'вна'){
    $genderAmount--;
    }
    if(mb_substr($split['name'], -1) == 'а'){
    $genderAmount--;
    }
    if(mb_substr($split['surname'], -2) == 'ва'){
    $genderAmount--;
    }
    if(mb_substr($split['patronymic'], -2) == 'ич'){
    $genderAmount++;
    }
    if(mb_substr($split['name'], -1) == 'й' || mb_substr($name, -1) == 'н'){
    $genderAmount++;
    }
    if(mb_substr($split['surname'], -1) == 'в'){
    $genderAmount++;
    }

    $gender = $genderAmount <=> 0;

    if($gender == -1){
    echo 'Женщина';
    }
    if($gender == 1){
    echo 'Мужчина';
    }
    if($gender == 0){
    echo 'Пол неопределён';
    }
    return $gender;
}

getGenderFromName($randomPerson);
echo $gender;
?>

<h4>Определение возрастно-полового состава</h4>

<?php
function getGenderDescription($example_persons_array){
	$mailFilter = array_filter($example_persons_array, function ($example_persons_array){
		$fullname = $example_persons_array['fullname'];
		$mail = getGenderFromName($fullname);
		if ($mail == 1) return $mail;
	});

	$femailFilter = array_filter($example_persons_array, function ($example_persons_array){
		$fullname = $example_persons_array['fullname'];
		$femail = getGenderFromName($fullname);
		if ($femail == -1) return $femail;
	});

	$unknownFilter = array_filter($example_persons_array, function ($example_persons_array){
		$fullname = $example_persons_array['fullname'];
		$unknownGender = getGenderFromName($fullname);
		if ($unknownGender == 0) return $unknownGender + 1;
	});

	$arrCount = count($example_persons_array);
	$mailCount = count($mailFilter);
	$femailCount = count($femailFilter);
	$unknownCount = count($unknownFilter);
	$mailPercent = round((($mailCount / $arrCount) * 100), 1);
	$femailPercent = round((($femailCount / $arrCount) * 100), 1);
	$unknownPercent = round((($unknownCount / $arrCount) * 100), 1);

    global $text;
	$text = <<<HEREDOC
<br><br>Гендерный состав аудитории:<br>
--------------------------------------<br>
Мужчины - $mailPercent %<br>
Женщины - $femailPercent %<br>
Не удалось определить - $unknownPercent %
HEREDOC;

    return $text;
}

getGenderDescription($example_persons_array);
echo $text;
?>
</body>
</html>