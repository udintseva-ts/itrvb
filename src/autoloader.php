<?php
function my_autoloader($class) {

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $class = str_replace('_', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/some/path/' . $class . '.php';

    if (file_exists($file)) {
        require $file;
    }
}

spl_autoload_register('my_autoloader');
?>