<?php

$page = 'home/home';

if (isset($_GET['section'])) {
    $section = $_GET['section'];
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$allowed_pages = [
    'schoolList',
    'departmentList',
    'programList',
    'studentList',
    'schoolCreate',
    'schoolUpdate',
    'schoolDelete',
    'processSchoolData',
    'processDataChanges',
    'chooseSchool',
    'processSchoolChoice',
    '500',
    '404',


    'choosePrograms',
    'processProgramChoice',
    'programsList',

    'chooseStudent',
    'processStudentChoice',
    'studentsList',

    'processDataDelete',


    //Department

    'chooseSchool',
    'departmentCreate',
    'departmentDelete',
    'departmentList',
    'departmentUpdate',
    'processDataChanges',
    'processDepartmentData',
    'processSchoolChoice',


    //Program
    'choosePrograms',
    'programsCreate',
    'programsDelete',
    'programsList',
    'programsUpdate',
    'processDataChanges',
    'processProgramsData',
    'processProgramChoice',

    //Student
    'chooseStudent',
    'studentCreate',
    'studentDelete',
    'studentsList',
    'studentUpdate',
    'processDataChanges',
    'processStudentData',
    'processStudentChoice'
];

if (in_array($page, $allowed_pages)) {
    if(file_exists("{$section}/{$page}.php")) {
        $file = "{$section}/{$page}.php";
    } else {
        $file = "404/404.php";
    }
} else {
    $file = "home/home.php";
}


require_once $file;
