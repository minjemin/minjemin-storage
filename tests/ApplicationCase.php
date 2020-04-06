<?php

namespace Minjemin\Flysystem\MinjeminStorage\Test;

use Dotenv\Dotenv;
use Minjemin\Flysystem\MinjeminStorage\MinjeminClient;
use Minjemin\Flysystem\MinjeminStorage\MinjeminStorageAdapter as Adapter;
use PHPUnit\Framework\TestCase;
use League\Flysystem\Filesystem;

class ApplicationCase extends TestCase
{
    const IMAGE = __DIR__ . '/logo-git.png';

    protected static $adapter;

    protected static $minjeminStorage;

    protected static $client;

    protected static $config;

    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

        self::$config = [
            'api_url' => 'http://localhost:5000',
            'api_username' => getenv('API_KEY'),
            'api_password' => getenv('API_SECRET')
        ];

        self::$client = new MinjeminClient(
            self::$config['api_url'],
            self::$config['api_username'],
            self::$config['api_password']
        );

        self::$minjeminStorage = new Adapter(self::$client);
        self::$adapter = new Filesystem(self::$minjeminStorage);
    }

    public function getContentFile(){
        return file_get_contents(self::IMAGE);
    }
}