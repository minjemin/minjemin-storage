<?php

namespace Minjemin\Flysystem\MinjeminStorage;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;

class MinjeminStorageAdapter implements AdapterInterface
{
    protected $client;

    public function __construct(MinjeminClient $client)
    {
        $this->client = $client;
    }

    /**
     * Write a new file.
     * Create temporary stream with content.
     * Pass to writeStream.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config
     * @return array|false
     */
    public function write($path, $contents, Config $config)
    {
        $uploaded = $this->client->uploadImages($contents, $path);
        return $uploaded;
    }


    /**
     * Write a new file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        $uploaded = $this->client->uploadImages($resource, $path);
        return $uploaded;
    }

    /**
     * Update a file
     *
     * @param string $path
     * @param string $contents
     * @param Config $config
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        return $this->write($path, $contents, $config);
    }

    /**
     * @inheritDoc
     */
    public function updateStream($path, $resource, Config $config)
    {
        return $this->write($path, $resource, $config);
    }

    /**
     * @inheritDoc
     */
    public function rename($path, $newpath)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function copy($path, $newpath)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function delete($path)
    {
        return $this->deleteDir(dirname($path));
    }

    /**
     * @inheritDoc
     */
    public function deleteDir($dirname)
    {
       return $this->client->deleteImage($dirname);
    }

    /**
     * @inheritDoc
     */
    public function createDir($dirname, Config $config)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function setVisibility($path, $visibility)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function has($path)
    {
        return (bool) $this->client->hasImage($path);
    }

    /**
     * @inheritDoc
     */
    public function read($path)
    {
        $contents = file_get_contents($this->client->getImages($path));
        return compact('contents', 'path');
    }

    /**
     * @inheritDoc
     */
    public function readStream($path)
    {
        try {
            $stream = fopen($this->client->getImages($path), 'r');
        } catch (\Exception $e) {
            return false;
        }
        return compact('stream', 'path');
    }

    /**
     * @inheritDoc
     */
    public function listContents($directory = '', $recursive = false)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($path)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getSize($path)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getMimetype($path)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTimestamp($path)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getVisibility($path)
    {
        return false;
    }
}