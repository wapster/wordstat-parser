<?php

use Facebook\WebDriver\WebDriverBy;

class Parser extends Driver 
{

    // получить ключевые слова со страницы
    public function getKeywords($driver): array {
        return $driver->findElements(WebDriverBy::className("b-word-statistics__td-phrase"));
    }


    // получить кол-во упоминаний ключевого слова
    public function getCountKeywords($driver): array {
        return $driver->findElements(WebDriverBy::className("b-word-statistics__td-number"));
    }



}