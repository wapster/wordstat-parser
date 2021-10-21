<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception;

use Facebook\WebDriver\Proxy;

class Driver {

    public function get() 
    {
        $host = 'http://localhost:4444';
        $options = new ChromeOptions();
            $options->addArguments( [
                // '--headless',
                // '--proxy-server=195.209.145.29:51493',
                '--start-maximized',
                '--no-sandbox',
                '--disable-dev-shm-usage',
                ] );

            $caps = DesiredCapabilities::chrome();
            $caps->setCapability(ChromeOptions::CAPABILITY, $options);
            $driver = RemoteWebDriver::create($host, $caps, 90000, 90000);

            return $driver;
    }


    // закрываем браузер
    public function closeBrowser($driver) {
        $driver->quit();
    }

}