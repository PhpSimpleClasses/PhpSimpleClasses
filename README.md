# PhpSimpleClasses

![GitHub release (latest by date)](https://img.shields.io/github/v/release/zimaldo/PhpSimpleClasses?style=for-the-badge)
![Packagist Version](https://img.shields.io/packagist/v/psc/psc?style=for-the-badge)
![GitHub repo size](https://img.shields.io/github/repo-size/zimaldo/PhpSimpleClasses?style=for-the-badge)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/psc/psc?style=for-the-badge)
![GitHub](https://img.shields.io/github/license/zimaldo/PhpSimpleClasses?style=for-the-badge)

Only what is necessary to start an empty Object Oriented PHP project, with a route scheme.

## Get Started:

### Creating a new project PSC Based

### Git Clone:

```shell
$ git clone https://github.com/zimaldo/PhpSimpleClasses
$ mv PhpSimpleClasses MyProject
$ cd MyProject
```

OR

### Composer:

```shell
$ composer create-project psc/psc
$ mv psc MyProject
$ cd MyProject
```

## Basic Structure:

- [`_core`](_core): contains all system base (classes manager, loaders, DB functions, etc).

- [`public`](public): contains the main entry point to webserver ([`index.php`](public/index.php)), where is you have to store all public/static files (like css, js, images, etc).

- [`src`](src): contains all backend resources, it has the MVC design pattern, but you can add or create anything you need and it will be available in any scope inside [`src`](src) (see more in [Basic Usage](README.md#basic-usage)).

- [`.htaccess`](.htaccess): (for apache) is configured to redirect any access to your domain to [`index.php`](public/index.php), or if the access is to an existing file: `public/myFile.png`.

- [`config.php`](config.php): define all initial constants, like basepath and DB auth, you can add your own default constants and configs too.

- [`routes.php`](routes.php): define all routes to public access in your project.

## Basic Usage:

First, configure your `context` section in [`config.php`](config.php), if you want to use a DB (MySQL), configure `DB` section too.

### Routes:

Open [`routes.php`](routes.php), as can you see in examples routes, you must set in the `$routes` array, a route like key and a class/function like value.
(This class have to be on [`Controllers`](src/Controllers)).

Ex.:

```php
$routes['/my/first'] = 'Test/firstFunction';
```

To pass parameters, use `$` as wildcard on write a route. The function on controller will receive the parameters in same order.

Ex.:

```php
$routes['/user/$/details'] = 'Users/details';

//In src/Controllers/Users.php:
//...
public function details($userId){
    echo $userId;
    //Will print any string in url between 'user/' and '/details'
}
```

### DB:

First you need to extends the `PSC` class:

Ex.:

```php
//In src/Controllers/Something.php:

namespace Controllers;

use _core\PSC;

class Something extends PSC
{
    public function __construct()
    {
        parent::__construct();
    }
}
```

**Note:** Ever write the `__construct()` function and lists the parent constructor to preserve the flow and prevent errors when extending the PSC class.

Then can use `$this->db` function:

```php
public function getUserDetails($userId){
    $this->db->select('users', '*');
    $this->db->where('id', $userId);
    $results = $this->db->get();
    return $results;
}
```

All SQL methods return `$this->db`, then you can call all methods after first call to `$this->db->`...:

```php
public function getUserDetails($userId){
    $results = $this->db->select('users', '*')
    ->where('id', $userId)
    ->get();
    return $results;
}
```

Anytime you can use the function `$this->exec` to execute SQL Querys without query builder:

```php
public function getAllUsers(){
    $results = $this->db->exec("SELECT * FROM users");
    return $results;
}
```

### Load Classes and Other Scripts:

#### Classes:

All content in [`src`](src) follow PHP default OO scheme. If are you running a route to `Controllers/Test.php`, your current namespace is `Controllers`, then you can call in this class:

```php
$myOtherClass = new OtherClass();
//This will instantiate src/Controllers/OtherClass.php on $myOtherClass
```

But when you want to call a class in another namespace, use `\` to go back to [`src`](src):

```php
$myData = new \Models\OtherData();
//$this will instantiate src/Models/OtherData.php on $myData
```

#### Other Scripts:

To load (include) other scripts without classes (like views and helpers), you can to use the function `$this->load` (from PSC class):

```php
$this->load('Views/test');
//this will include src/Views/test.php
```

With this function you can pass all variable you need to use in script in a array, it will be extracted to use directly inside him:

```php
$vars['name'] = 'Junior';
$vars['age'] = '21';
$this->load('Views/test', $vars);
```

Then inside `src/Views/test.php`:

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hello</title>
  </head>
  <body>
    Hello! My name is
    <?= $name ?>
    and i have
    <?= $age ?>
    years old.
  </body>
</html>
```
