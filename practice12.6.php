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



function getPartsFromFullname ($SurNmPt){
    $parts = explode(" ", $SurNmPt);
    $ArrParts = ['surname' => $parts[0],'name' => $parts[1],'patronomyc' => $parts[2]];
    return $ArrParts;
};

function getFullnameFromParts($surname, $name, $patronymic){
    $strng = $surname. ' ' .$name . ' ' . $patronymic;
    return $strng;
};

function getShortName($strng){
    $parts = getPartsFromFullname($strng);
    $name = $parts['name'];
    $surname = $parts['surname'];
    $abrName = $name . ' ' . mb_strimwidth($surname, 0, 2, '.');
    return $abrName;
};

function getGenderFromName($strng){
    $parts = getPartsFromFullname($strng);
    $name = $parts['name'];
    $surname = $parts['surname'];
    $patronymic = $parts['patronomyc'];
    $gender = 0;
    if(mb_substr($name, -1) === 'а') {
        $gender--;
    };
    if(mb_substr($surname, -2) === 'ва'){
        $gender--;
    };
    if(mb_substr($patronymic, -3) === 'вна'){
        $gender--;
    };

    if(mb_substr($name, -1) === 'й'){
        $gender++;
    };       
    if(mb_substr($name, -1) === 'н'){
        $gender++;
    };       
    if(mb_substr($surname, -1) === 'в'){
        $gender++;
    };
    if(mb_substr($patronymic, -2) === 'ич'){
        $gender++;
    };
    return $gender <=> 0;
};

function getGenderDescription($arr){
    $males = 0;
    $females = 0;
    $asexual = 0;
    for ($i = 0; $i <= count($arr)-1; $i++){
        $personArr = getPartsFromFullname($arr[$i]['fullname']);
        $person = getFullnameFromParts($personArr['surname'], $personArr['name'], $personArr['patronomyc']);
        $gender = getGenderFromName($person);
        if($gender === 1)
            $males++;
        if($gender === -1)
            $females++;
        if($gender === 0)
            $asexual++;
        };
    $males = round($males / count($arr) * 100, 1);
    $females = round($females / count($arr) * 100, 1);
    $asexual = round($asexual / count($arr) * 100, 1);
    echo "\nГендерный состав аудитории:\n---------------------------";
    echo "\nМужчины - ". $males ."%";
    echo "\nЖенщины - ". $females ."%";
    echo "\nНе удалось определить - ". $asexual ."%\n";
};

function getPerfectPartner($surname, $name, $patronymic, $arr){
    $fullName = mb_convert_case(getFullnameFromParts($surname, $name, $patronymic), MB_CASE_TITLE, "UTF-8");
    $genderFirstPerson = getGenderFromName($fullName);
    if($genderFirstPerson != 0){
        $g = 2;
        while ($g != 0){
            $randomSurNmPt = $arr[mt_rand(0, count($arr) -1)]['fullname'];
            $g = $genderFirstPerson + getGenderFromName($randomSurNmPt);
        }
        echo getShortName($fullName). ' + ' .getShortName($randomSurNmPt) . ' =';
        echo "\n♡ Идеально на ".number_format(rand(5000, 10000) / 100, 2, '.', '')."% ♡";
    }else{
        echo "Невозможно определить пол для подбора пары, попробуйте другое имя";
    }
};

echo getGenderDescription($example_persons_array);
echo getPerfectPartner('Бардо','Жаклин','Фёдоровна',$example_persons_array);
