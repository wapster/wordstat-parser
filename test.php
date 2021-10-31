<?php
set_time_limit(0);


// останавливаем браузер, если он завис
// shell_exec('killall chrome');
// sleep(1);


use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception;
use Facebook\WebDriver\WebDriverElement;

require_once('vendor/autoload.php');

function debug($str){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}


$proxy = new Proxys();
$ip = $proxy->getProxy();

$driver_obj = new Driver();
$driver = $driver_obj->get();

$accounts = new Accounts();
$account = $accounts->getAccount();

$login = $account[0];
$password = $account[1];

$auth = new Auth();
$login = $auth->logIn($driver, $login, $password);

$key_words = file('config/keywords.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$key_words = array_values(array_unique($key_words));


$runner = new Runner();

$result = $runner->go($driver, $key_words = ['acer aspire 4810t'], $region_id = '0');
debug($result);