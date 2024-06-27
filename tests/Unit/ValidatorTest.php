<?php

use Core\Validator;

test('validator string method', function (){
    $result = Validator::string("Do it test", 5 , INF);

    expect($result)->toBeTrue();
});

test('validator email method', function (){
    $result = Validator::string("test@example.com");

    expect($result)->toBeTrue();
});