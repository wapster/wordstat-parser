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

            $login_input = $driver->findElement(WebDriverBy::id("b-domik_popup-username"));
            $password_input = $driver->findElement(WebDriverBy::id("b-domik_popup-password"));

            $login_input->sendKeys($login);
            sleep(1);
            $password_input->sendKeys($password);
            sleep(1);

            $submit_button = $driver->findElement(WebDriverBy::xpath("/html/body/form/table/tbody/tr[2]/td[2]/div/div[5]/span[1]/input"));
            $submit_button->click();
            sleep(3);

            $log_out_link = $driver->findElements(WebDriverBy::xpath("//*[text()='Выход']"));
            if (count($log_out_link) > 0) {
                return true;
            } else return false;

        } catch (Exception\WebDriverException $e) {
            return false;
            $driver->quit();
        }
    }
}