<?php

namespace _core;

class ErrorHandler
{

    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
    }

    public function strDebug($msg, $depth = '')
    {
        $debug = debug_backtrace();
        $depth == '' ? $depth = count($debug) - 5 : $depth = $depth;
        if ($depth < 1) count($debug) == 1 ? $depth = 0 : $depth = 1;
        $callerFunc = $debug[$depth]['function'];
        @$callerLine = $debug[$depth]['line'];
        @$callerFile = $debug[$depth]['file'];
        @$callerClass = $debug[$depth]['class'];
        $errStr = $msg .

            ($callerFunc ? "<BR>Caller Function: $callerFunc" : '') .
            ($callerLine ? "<BR>Caller Line: $callerLine" : '') .
            ($callerFile ? "<BR>Caller File: $callerFile" : '') .
            ($callerClass ? "<BR>Caller Class: $callerClass" : '') .
            '<BR>';
        return $errStr;
    }

    public function errorHandler($errN, $errStr, $file, $line, $context)
    {
        if (ENVIRONMENT == 'production') return;
        $errors = [
            E_NOTICE => "Notice",
            E_WARNING => "Warning",
            E_DEPRECATED => "Deprecated",
            256 => "PHP Simple Classes - Error"
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
    }
}
