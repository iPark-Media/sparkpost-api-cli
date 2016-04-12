sparkpost-cli-api
=================

Latest release: none yet

PHP >= 7.0.0

Sparkpost Api Cli Client

Goal
----

The goal of this package is:

* to build a small and easy to use cli-client for the sparkpost api


Installation
------------

For now just clone this repo (checkout master, development is for, well, development) and enter

```
$ composer install
```

Basic usage
-------------------

To get an overview of the available commands enter

```
$ ./bin/sparkpost
```

This will give you something like 

```bash
Sparkpost version 0.0.1

USAGE
  sparkpost [-h] [-q] [-v [<level>]] [-V] [--ansi] [--no-ansi] [-n] <command> [<arg1>] ... [<argN>]

ARGUMENTS
  <command>              The command to execute
  <arg>                  The arguments of the command

GLOBAL OPTIONS
  -h (--help)            Display help about the command
  -q (--quiet)           Do not output any message
  -v (--verbose)         Increase the verbosity of messages: "-v" for normal output, "-vv" for more verbose output and "-vvv" for debug
  -V (--version)         Display this application version
  --ansi                 Force ANSI output
  --no-ansi              Disable ANSI output
  -n (--no-interaction)  Do not ask any interactive question

AVAILABLE COMMANDS
  help                   Display the manual of a command
  sending-domains        Handle sending domains
  subaccounts            Handle subaccounts

```

The available commands are to be found at the bottom. Each command has its own subcommands:

```
$ ./bin/sparkpost subaccounts --help
```
will return the available subcommands for this command:

```bash
COMMANDS
  activate
    <subaccount>         the id of the subaccount to activate

  add
    <name>               name of the subaccount to create

  list
    <subaccountId>       the subaccount id the info is requested for

  suspend
    <subaccount>         the id of the subaccount to suspend

  terminate
    <subaccount>         the id of the subaccount to terminate

  update
    <subaccount>         the id of the subaccount to update

OPTIONS
  --key                  the Api-Key for the request
  --config               where to read the config from

```

At the bottom you see, that all subcommands have two optional options: "key" and "config". The application needs a config to be able to know which values are going to be used when firing requests. This config files needs to return a PHP array. If config is not set, it looks for a file called `config.php` in the current directory.


Config
--------

A basic config is provided in config.php.dist you can rename it to config.php (which added to .gitignore) and change the values according to your needs. Each command/api-endpoint has its own key in the array.
The config also has a `key` entry. If you do not want do put your api-key here you can provide it via the --key option.

Examples
--------
List all subaccounts (list is default command, so optional)
```
$  ./bin/sparkpost subaccounts
```

List all subaccounts with custom config and api-key
```
$  ./bin/sparkpost subaccounts list --config ~/sparkpost/config.php --key superSecretApiKey
```

List one subaccount
```
$  ./bin/sparkpost subaccounts list 840
```

Add subaccount (dont forget to copy the api-key)
```
$  ./bin/sparkpost subaccounts add my_customer
```

List all sending domains (list is default command, so optional)
```
$  ./bin/sparkpost sending-domains
```

Add sending-domain for master account
```
$  ./bin/sparkpost sending-domains add example1.com
```

Add sending-domain for subaccount
```
$  ./bin/sparkpost sending-domains add example1.com 840
```


Todos
--------
 * Tests
 * get rid of stupid [Sparkpost Extension](https://github.com/iPark-Media/sparkpost-api-cli/blob/development/src/Sparkpost/Sparkpost.php) when [Sparkpost Client provides ability to add subaccounts to requests](https://github.com/SparkPost/php-sparkpost/issues/73)
 * improve documentation
 * add more endpoints
 * create phar

Authors
-------

* [Roman Sachse] a.k.a. [@r0mmsen]

Contribute
----------

Contributions to are very welcome!

* Report any bugs or issues you find on the [issue tracker].
* You can grab the source code at the package’s [Git repository].
* Please file pull requests against development branch


Acknowledgement
----------
Uses the beautiful https://github.com/webmozart/console by @webmozart.

License
-------

All contents of this package are licensed under the [MIT license].

[iPark-Media]: http://ipark-media.de
[issue tracker]: https://github.com/iPark-Media/sparkpost-api-cli/issues
[Git repository]: https://github.com/iPark-Media/sparkpost-api-cli
[@r0mmsen]: https://twitter.com/r0mmsen
[MIT license]: LICENSE
