<?php
//Рандомное ФИО
$i = rand(0, count($example_persons_array) - 1);
$fullName = ($example_persons_array[$i]['fullname']);
// echo ($fullName);
// echo "<br>";
// echo "<br>";

//Принимается строка - возращается массив
function getPartsFromFullname($fullName)
{
    $explode = explode(" ", $fullName);
    $division = [
        'surname' => $explode[0],
        'name' => $explode[1],
        'patronomyc' => $explode[2],
    ];

    return $division;
}
// var_dump(getPartsFromFullname($fullName));
// echo "<br>";
// echo "<br>";

//ФИО поэлементно в переменных из прошлой функции
$surname = getPartsFromFullname($fullName)['surname'];
$name = getPartsFromFullname($fullName)['name'];
$patronomyc = getPartsFromFullname($fullName)['patronomyc'];

//Принимает 3 строки, возвращается полное ФИО через пробел
function getFullnameFromParts($surname, $name, $patronomyc)
{
    $fullName = $surname . " " . $name . " " . $patronomyc;
    return $fullName;

}
// echo "<br>";
// echo (getFullnameFromParts($surname, $name, $patronomyc));
// echo "<br>";

//Сокращается ФИО
function getShortName($fullName)
{
    $division = getPartsFromFullname($fullName);
    $shortName = $division['name'] . ' ' . mb_substr($division['surname'], 0, 1) . '.';
    return $shortName;
}
// echo "<br>";
// echo (getShortName($fullName));
// echo "<br>";

//Определение пола по ФИО
function getGenderFromName($fullName)
{
    $division = getPartsFromFullname($fullName);
    $gender = 0;
    //Фамилия
    if (mb_substr($division['surname'], -2, 2) == 'ва') {
        $gender = -1;
    } elseif (mb_substr($division['surname'], -1, 1) == 'в') {
        $gender = 1;
    } else {
        $gender = 0;
    }

    //Имя
    if (mb_substr($division['name'], -1, 1) == 'а' || mb_substr($division['name'], -1, 1) == 'я') {
        $gender = -1;
    } elseif (mb_substr($division['name'], -1, 1) == 'й' || mb_substr($division['name'], -1, 1) == 'н') {
        $gender = 1;
    } else {
        $gender = 0;
    }

    //Отчество
    if (mb_substr($division['patronomyc'], -3, 3) == 'вна') {
        $gender = -1;
    } elseif (mb_substr($division['patronomyc'], -2, 2) == 'ич') {
        $gender = 1;
    } else {
        $gender = 0;
    }

    if (($gender <=> 0) === 1) {
        return "Мужской пол";
    } elseif (($gender <=> 0) === -1) {
        return "Женский пол";
    } else {
        return "Неопределенный пол";
    }
}
// echo "<br>";
// echo (getGenderFromName($fullName));
// echo "<br>";


//Определение возрастно-полового состава
$person = $example_persons_array;
function getGenderDescription($person)
{
    $male = array_filter($person, function ($person) {
        return (getGenderFromName($person['fullname']) == "Мужской пол");
    });
    $female = array_filter($person, function ($person) {
        return (getGenderFromName($person['fullname']) == "Женский пол");
    });
    $unknown = array_filter($person, function ($person) {
        return (getGenderFromName($person['fullname']) == "Неопределенный пол");
    });

    $sum = count($male) + count($female) + count($unknown);
    $malePers = round(count($male) / $sum * 100, 1);
    $femalePers = round(count($female) / $sum * 100, 1);
    $unknownPers = round(count($unknown) / $sum * 100, 1);

    $genderDescription = <<<HEREDOC
Гендерный состав аудитории:<br>
---------------------------<br>
Мужчины - $malePers%<br>
Женщины - $femalePers%<br>
Не удалось определить - $unknownPers%<br>
HEREDOC;

    return $genderDescription;
}
// echo "<br>";
// echo (getGenderDescription($person));
// echo "<br>";

//Идеальный подбор пары
function getPerfectPartner($surname, $name, $patronomyc, $person)
{

    //Приведение к привычному регистру
    $surname = mb_convert_case($surname, MB_CASE_TITLE, "UTF-8");
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE, "UTF-8");

    //Склейка ФИО
    $fullName = getFullnameFromParts($surname, $name, $patronomyc);

    //Определение пола
    $oneGender = getGenderFromName($fullName);
    // echo $oneGender;
    // echo "<br>";
    // echo "<br>";


    //Проверяем с помощью getGenderFromName, что выбранное из Массива ФИО 
//- Противоположного пола, если нет, то возвращаемся к шагу 4, 
//Если да - возвращаем информацию.

    //Рандомный выбор человека
    $i = rand(0, count($person) - 1);
    $randomPerson = $person[$i]['fullname'];
    $randomGender = getGenderFromName($randomPerson);
    // echo $randomPerson;
    // echo "<br>";

    // echo $randomGender;
    // echo "<br>";
    // echo "<br>";

    if ($oneGender == "Неопределенный пол" || $oneGender == $randomGender || $randomGender == "Неопределенный пол") {
        return "Не удалось подобрать пару";
    } else {
        $randomPerson = $person[$i]["fullname"];
        $randomGender = getGenderFromName($randomPerson);
    }
    $shotOne = getShortName($fullName);
    $shotRandom = getShortName($randomPerson);
    $persent = rand(50, 100) + rand(0, 99) / 100;
    $perfectPartner = <<<HEREDOC
$shotOne + $shotRandom =<br>
  Идеально на $persent% <br>
HEREDOC;

    return $perfectPartner;

}
// echo "<br>";
// echo getPerfectPartner($surname, $name, $patronomyc, $person);