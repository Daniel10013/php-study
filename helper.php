<?php

function dd(mixed $var, bool $die = true, bool $backtrace = false): void {
    echo "<pre style='color: black'>";
    if($backtrace == true){
        var_dump(debug_backtrace());
    }

    var_dump($var);
    if($die == true){
        die;
    }
}