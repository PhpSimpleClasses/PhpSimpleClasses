<?php

define('VERSION', '1.5.7');

$configFile = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
if (!file_exists($configFile))
    printl("Error! config.php is required to use PSC CLI!", 'black', true, 'red');

require_once($configFile);


function printl(string $text, string $textColor = 'default', bool $end = false, string $bgColor = '')
{
    $textColors = [
        'default' => '39m', 'red' => '31m', 'green' => '32m', 'yellow' => '33m', 'blue' => '34m',
        'magenta' => '35m', 'cyan' => '36m', 'lightRed' => '91m', 'lightGreen' => '92m',
        'lightYellow' => '93m', 'lightCyan' => '96m', 'white' => '97m', 'black' => '30m'
    ];
    $tCode = $textColors[$textColor] ?? $textColor;

    $backgroundColors = [
        'black' => '40m', 'red' => '41m', 'green' => '42m', 'yellow' => '43m', 'blue' => '44m',
        'magenta' => '45m', 'cyan' => '46m', 'lightGray' => '47m'
    ];
    $bgCode = null;
    if ($bgColor) $bgCode = $backgroundColors[$bgColor] ?? $bgColor;

    echo "\e[{$tCode}" . ($bgCode ? "\e[{$bgCode}" : '') . "{$text} \e[0m\n";
    if ($end) die;
}

function newFolder(string $path)
{
    try {
        mkdir($path);
    } catch (\Exception $e) {
        printl("Error when try to create directory: '$path'", 'red');
        printl($e, 'black', true, 'red');
    }
}

function newFile(string $path, string $content)
{
    try {
        file_put_contents($path, $content);
    } catch (\Exception $e) {
        printl("Error when try to write file: '$path'", 'red');
        printl($e, 'black', true, 'red');
    }
}

function interpreter($args)
{
    $cmd = $args[1];
    if (!function_exists($cmd)) {
        printl("'$cmd' is not a valid PSC command", 'lightRed');
        printl("Use 'help' to see a list of available commands.", 'black', true, 'yellow');
    }
    call_user_func($cmd, $args);
}

function create($args)
{
    $itemType = $args[2] ?? null;
    $itemName = $args[3] ?? null;
    if (!$itemType) {
        printl('You must specify a item to be created', 'red');
        printl('E.g.: create model users', 'black', true, 'yellow');
    }
    if (!$itemName) {
        printl('You must specify a name to item', 'red');
        printl('E.g.: create controller main', 'black', true, 'yellow');
    }
    $supportedTypes = ['Model', 'Controller', 'Library'];
    $itemType[0] = strtoupper($itemType[0]);
    $itemName[0] = strtoupper($itemName[0]);
    if (!in_array($itemType, $supportedTypes))
        printl("'$itemType' is not a supported item to create", 'red', true);

    $folder = $itemType . 's';
    if ($itemType[strlen($itemType) - 1] == 'y') $folder = substr($itemType, 0, strlen($itemType) - 1) . 'ies';

    $txt =
        "<?php

namespace $folder;

use _core\PSC;

class $itemName extends PSC
{

    public function __construct()
    {
        parent::__construct();
    }

}
    ";

    if (!file_exists(SOURCEPATH . $folder)) newFolder(SOURCEPATH . $folder);
    if (file_exists(SOURCEPATH . $folder . DS . "$itemName.php"))
        printl("$itemType '$itemName' already exists!", 'red', true);

    newFile(SOURCEPATH . $folder . DS . "$itemName.php", $txt);

    printl("Success!", 'black', false, 'green');
}

function help($args)
{
    $commands = [
        'create' => [
            'Create a class based on specified type inside respective folder',
            "E.g.: create <controller || model || library> <name>"
        ],
        'help' => ['Show this command list'],
        'version' => ['Show PSC current version']
    ];

    foreach ($commands as $name => $data) {

        $description = $data[0];
        $additional = $data[1] ?? null;
        printl("$name - $description", 'yellow');
        if ($additional) printl($additional);
        printl('');
    }
}

function version($args)
{
    printl(VERSION);
}

interpreter($argv);
