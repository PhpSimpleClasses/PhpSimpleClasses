<?php

namespace _core;

class ErrorHandler
{
    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
    }

    public function strDebug($depth = '')
    {
        $debug = debug_backtrace();
        $depth == '' ? $depth = count($debug) - 5 : $depth = $depth;
        if ($depth < 1) count($debug) == 1 ? $depth = 0 : $depth = 1;
        $callerFunc = $debug[$depth]['function'];
        @$callerLine = $debug[$depth]['line'];
        @$callerFile = $debug[$depth]['file'];
        @$callerClass = $debug[$depth]['class'];
        $errStr =
            ($callerFunc ? "<BR>Caller Function: $callerFunc" : '') .
            ($callerLine ? "<BR>Caller Line: $callerLine" : '') .
            ($callerFile ? "<BR>Caller File: $callerFile" : '') .
            ($callerClass ? "<BR>Caller Class: $callerClass" : '') .
            '<BR>';
        return $errStr;
    }

    public function errorHandler($errN, $msg, $file, $line, $context)
    {
        $errStr = $this->strDebug();
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
        @define("ERR_LOG", TRUE);
?>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Roboto', sans-serif;
            }
        </style>
        <div style="max-width: 1170px;margin:25px auto;position:relative!important;z-index:200;">
            <h1 style="color: #721c24;font-size:1.8rem;">
                <strong>Error:</strong><?= $msg ?></h1>
            <p style="margin-top: 15px;color: #721c24;background-color: #f8d7da;border:1px solid #f5c6cb;border-radius: .25rem;padding: 1rem 1.25rem;">
                <strong><?= $errors[$errN] ?? intl_error_name($errN) . " - ($errN)" ?></strong><br>
                <strong><?= $errStr ?></strong><br>
                File: <?= $file ?><br>
                <strong>Line <?= $line ?><br></strong>
                <?= $context && $errN != 256 ? @print_r($context) : '' ?>
            </p>
        </div>
<?php
    }
}
