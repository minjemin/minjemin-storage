<?php

use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use Minjemin\Flysystem\MinjeminStorage\MinjeminStorageAdapter as Adapter;
use Minjemin\Flysystem\MinjeminStorage\Test\ApplicationCase;


class MinjeminStorageAdapterTest extends ApplicationCase
{
    protected static $id;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$id = sprintf('items/%s/%s', uniqid(), 'test.png');
    }

    /**
     * Validate instance type is Class Core
     * @return void
     */
    public function test_valid_instance()
    {
        $minjeminStorage = new Adapter(self::$client);
        $this->assertInstanceOf('Minjemin\Flysystem\MinjeminStorage\MinjeminStorageAdapter', $minjeminStorage);

    }

    /**
     * @depends test_valid_instance
     * Upload success file on api
     * @return void
     * @throws FileExistsException
     */
    public function test_success_upload_file()
    {
        $adapter = self::$adapter;
        $up = $adapter->write(self::$id, $this->getContentFile());
        $this->assertJson($up);

    }

    /**
     * @depends test_success_upload_file
     * Read file on api
     * @return void
     * @throws FileNotFoundException
     */
    public function test_read_file()
    {
        $adapter = self::$adapter;
        $content = $adapter->read(self::$id);

        $this->assertTrue($content == $this->getContentFile());
    }

    /**
     * @depends test_read_file
     * Delete file on api
     * @return void
     * @throws FileNotFoundException
     */
    public function test_delete_file()
    {
        $adapter = self::$adapter;
        $content = $adapter->delete(self::$id);

        $this->assertTrue($content == $this->getContentFile());
    }

}