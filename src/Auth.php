<?php
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

class Auth extends Driver
{
    

    public function logIn($driver, $login, $password) {
        try {
            $url = 'https://wordstat.yandex.ru/';
            $driver->get($url);

            sleep(1);

            $driver->findElement(WebDriverBy::xpath("//*[text()='Войти']"))->click();
            sleep(1);

            
        } catch (Exception\WebDriverException $e) {
            return false;
            $driver->quit();
        }
    }
}