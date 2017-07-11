<?php

/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @see http://www.php-fig.org/psr/psr-4/examples/
 * @param string $class The fully-qualified class name.
 * @return bool
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Purify\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // does the class use the namespace prefix?

    if (0 !== strpos($class, $prefix)) {
        // no, move to the next registered autoloader
        return false;
    }

    // get the relative class name
    $relative_class = substr($class, 7);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        /** @noinspection PhpIncludeInspection */
        require $file;
        return true;
    }
    return false;
});