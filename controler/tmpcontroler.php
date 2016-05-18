<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
$tmp = "";

if(isset($_POST['tmp'])){
   
    $tmp = $_POST['tmp'];
}

if(strlen($tmp) > 0){
    
    sleep(20);
    unlink($_SERVER['DOCUMENT_ROOT'] . $urlroot . "/report/tmp/" . $tmp);
}