<?php
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception;
use Facebook\WebDriver\WebDriverElement;

class Runner extends Driver
{
    
    public function go($driver, $key_words, $region_id = '0') {

        $result = [];

        foreach ($key_words as $key_word) {
            
            $url = 'https://wordstat.yandex.ru/#!/?regions='. $region_id .'&words=' . $key_word;
            
            $driver->get($url);
            $driver->wait(60, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[text()='далее']")));
        
            sleep(2);
            
            $next_page_link = $driver->findElement(WebDriverBy::xpath("//*[text()='далее']"));
            $href = $next_page_link->getAttribute("href");
        
            $links = [];
            if ($href == NULL) {
                // если ссылки Далее нет, парсим один раз
                $key_words = $this->getKeywords($driver);
                $counter_key_words = $this->getCountKeywords($driver);
        
                for ($i = 0; $i<count($key_words); $i++) {
                    $key_word = $key_words[$i]->getText();
        
                    $counter = filter_var($counter_key_words[$i]->getText(), FILTER_SANITIZE_NUMBER_INT);
        
                    $links[] = $key_word . ";" . $counter;
                }
                
            } else {
                while ($href !== NULL) {
                    // если ссылка Далее есть, парсим и кликаем
                    $key_words = $this->getKeywords($driver);
                    $counter_key_words = $this->getCountKeywords($driver);
                    
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
                    
                        $driver->wait(60, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[text()='Выход']")));
                        sleep(1);
                        
                        // Wait until an element is no longer attached to the DOM.
                        $driver->wait(60, 1000)->until(WebDriverExpectedCondition::stalenessOf($next_page_link));
                        sleep(1);
                        
                    }
        
        
                } //while
            }
        
            // итоговый массив с результатом
            $result[] = array_values(array_unique($links));
        
        } // end foreach


        $driver->quit();
        return $result;
    }





    // получить ключевые слова со страницы
    public function getKeywords($driver): array {
        return $driver->findElements(WebDriverBy::className("b-word-statistics__td-phrase"));
    }


    // получить кол-во упоминаний ключевого слова
    public function getCountKeywords($driver): array {
        return $driver->findElements(WebDriverBy::className("b-word-statistics__td-number"));
    }




}