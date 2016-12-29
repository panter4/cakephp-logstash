# Logstash log stream for CakePHP 3.x#


## Requirements ##

* CakePHP 3.x
* PHP 5.3+
* Composer

## Installation ##

1. copy plugin into

        /src/app/plugins/Logstash

2. enable plugin:

        //In config/bootstrap.php
        //Or in Application::bootstrap()
        Plugin::load('Logstash', ['autoload' => true]);


## Usage ##

in your app.php configure loging engine

    'Log' => [
        'debug' => [
            'className' => 'Logstash\Log\Engine\LogstashLog',
            'levels' => ['notice', 'info', 'debug'],

            'host'=>'udp://{logstashHostname}', //should support tcp://
            'port'=>{logstash input port},
            'tags'=>['cake_php', 'debug']
        ],
        'error' => [
            'className' => 'Logstash\Log\Engine\LogstashLog',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],

            'host'=>'udp://{logstashHostname}', //should support tcp://
            'port'=>{logstash input port},
            'tags'=>['cake_php', 'error']
        ],
    ],


## Known issues ##
missing:
    composer support - proper autoloading
    better documentation
    tests
