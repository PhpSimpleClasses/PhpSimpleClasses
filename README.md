# PhpSimpleClasses

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

[`_core`](_core): contains all system base (classes manager, loaders, DB functions, etc).

[`public`](public): contains the main entry point to webserver ([`index.php`](public/index.php)), where is you have to store all public/static files (like css, js, images, etc).

[`src`](src): contains all backend resources, it has the MVC design pattern, but you can add or create anything you need and it will be available in any scope inside [`src`](src) (see more in [Basic Usage](README.md#basic-usage)).

[`.htaccess`](.htaccess) file (for apache) is configured to redirect any access to your domain to [`index.php`](public/index.php), or if the access is to an existing file: `public/myFile.png`.

## Basic Usage:

...
