# Logstash log stream for CakePHP 3.x#


## Requirements ##

* CakePHP 3.x
* PHP 5.3+
* Composer

## Installation ##

//TODO: documentation

enable plugin:

    // In config/bootstrap.php
    // Or in Application::bootstrap()

    //load all plugins
    Plugin::loadAll();

    // Loads a single plugin
    Plugin::load('Logstash');


## Usage ##

in your app.php configure loging engine

    'Log' => [
        'debug' => [
            'className' => 'Cake\Log\Engine\LogstashLog',
            'levels' => ['notice', 'info', 'debug'],

            'host'=>'udp://{logstashHostname}', //should support tcp://
            'port'=>{logstash input port},
            'tags'=>['cake_php', 'debug']
        ],
        'error' => [
            'className' => 'Cake\Log\Engine\LogstashLog',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],

            'host'=>'udp://{logstashHostname}', //should support tcp://
            'port'=>{logstash input port},
            'tags'=>['cake_php', 'error']
        ],
    ],


## Known issues ##
    missing
        proper documentation
        packagist composer support
