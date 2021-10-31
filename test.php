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

$parser = new Parser();

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


$i = 0;
foreach ($key_words as $key_word) {
    $url = 'https://wordstat.yandex.ru/#!/?words=' . $key_word;
    
    $driver->get($url);
    $driver->wait(60, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[text()='Выход']")));

    sleep(5);
    
    $next_page_link = $driver->findElement(WebDriverBy::xpath("//*[text()='далее']"));
    $href = $next_page_link->getAttribute("href");

    $links = [];
    if ($href == NULL) {
        // если ссылки Далее нет, парсим один раз
        $key_words = $parser->getKeywords($driver);
        $counter_key_words = $parser->getCountKeywords($driver);

        for ($i = 0; $i<count($key_words); $i++) {
            $key_word = $key_words[$i]->getText();

            $counter = filter_var($counter_key_words[$i]->getText(), FILTER_SANITIZE_NUMBER_INT);

            $links[] = $key_word . ";" . $counter;
        }
        
    } else {
        while ($href !== NULL) {
            // если ссылка Далее есть, парсим и кликаем
            $key_words = $parser->getKeywords($driver);
            $counter_key_words = $parser->getCountKeywords($driver);
            
            for ($i = 0; $i<count($key_words); $i++) {
                $key_word = $key_words[$i]->getText();
                $counter = filter_var($counter_key_words[$i]->getText(), FILTER_SANITIZE_NUMBER_INT);
                $links[] = $key_word . ";" . $counter;
            }
            
            $next_page_link = $driver->findElement(WebDriverBy::xpath("//*[text()='далее']"));
            $href = $next_page_link->getAttribute("href");
            if ($href == '#next_page') {
                $next_page_link->click();
                sleep(1);
            
                // $exit_link = $driver->findElement(WebDriverBy::xpath("//*[text()='Выход']"));
                $driver->wait(60, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[text()='Выход']")));
                sleep(1);
                
                // Wait until an element is no longer attached to the DOM.
                $driver->wait(60, 1000)->until(WebDriverExpectedCondition::stalenessOf($next_page_link));
                sleep(1);
                
            }


        } //while
    }
    $links = array_values(array_unique($links));
    debug($i . " | " . $key_word);
    $i++;

} // end foreach

$driver->quit();
