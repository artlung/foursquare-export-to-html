<?php

spl_autoload_register(function ($class_name) {
    include 'library/' . $class_name . '.php';
});