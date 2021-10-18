<?php

class Accounts 
{

    public function getAccount(): array {
        $accounts = file('config/accounts.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $counter = count($accounts);
        $index = rand(0, $counter - 1);
        $account = $accounts[$index];
        
        $account = explode(";", $account);
        return $account; // [0] - login, [1] - password
    }

}