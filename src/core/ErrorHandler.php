<?php

namespace core;

class ErrorHandler
{

    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
    }

    public function errorHandler($errN, $errStr, $file, $line, $context)
    {
        $errors = [
            E_NOTICE => "Notice",
            E_WARNING => "Warning",
            E_DEPRECATED => "Deprecated",
            256 => "PHP Simple Classes - Incorrect Function Usage"
        ];
        if (@($errN == E_NOTICE && IGNORE_NOTICE === TRUE) || ($errN == E_WARNING && IGNORE_WARNING === TRUE)) {
            return;
        }
?>
        <p style="background-color:red;padding:20px;">
            <strong>Error: <?= $errors[$errN] ?? intl_error_name($errN) . " - ($errN)" ?></strong><br><br>
            <strong><?= $errStr ?></strong><br>
            File: <?= $file ?><br>
            <strong>Line <?= $line ?><br></strong>
            <?= $context && $errN != 256 ? print_r($context) : '' ?>
        </p>
<?php
        die();
    }
}
