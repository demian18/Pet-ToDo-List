<?php

use Core\Session;

beforeEach(function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
});

test('Session has, flash method', function (){
    Session::flash('error', 'password');
    $result = Session::has('error');
    expect($result)->toBeTrue();
});

test('Session flash, get method', function (){
    Session::flash('error', 'password');
    $result = Session::get('error');
    expect($result)->toEqual('password');
});

test('Session put, get method', function () {
    Session::put('user', 'email');
    $result = Session::get('user');
    expect($result)->toEqual('email');
});

test('Session destroy method', function (){
    Session::flash('error', 'password');
    Session::destroy();
    $result = Session::get('error');
    expect($result)->toBeEmpty();
});

test('Session unflash method', function () {
    Session::flash('error', 'password');
    Session::unflash();
    $result = Session::get('error');
    expect($result)->toBeEmpty();
});

test('Session unflash method with session', function () {
    Session::put('user', 'email');
    Session::unflash();
    $result = Session::get('user');
    expect($result)->toEqual('email');
});

test('Session get method with default value', function () {
    Session::put('user', 'email');
    $result = Session::get('product', 'default');
    expect($result)->toEqual('default');
});