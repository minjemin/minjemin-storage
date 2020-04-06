<?php

namespace Minjemin\Flysystem\MinjeminStorage;

use Zttp\Zttp;

class MinjeminClient
{
    protected $apiUrl;
    protected $credentials;

    public function __construct($apiUrl, $username, $password)
    {
        $this->apiUrl = $apiUrl;
        $this->credentials = base64_encode(sprintf('%s:%s', $username, $password));
    }

    public function uploadImages($contents, $path)
    {
        $dest = __DIR__ . '/' . basename($path);
        if (is_string($contents)) {
            file_put_contents($dest, $contents);
        } else {
            $stream = fopen($dest, 'w+b');
            if (!$stream || stream_copy_to_stream($contents, $stream) === false || !fclose($stream)) {
                return false;
            }
        }

        $response = Zttp::withHeaders([
            'Authorization' => ['Basic ' . $this->credentials],
            'path' => dirname($path),
            'filename' => basename($path)
        ])->asMultipart()->post("$this->apiUrl/files/upload", [
            [
                'name' => 'images',
                'contents' => fopen($dest, 'r')
            ],
        ]);

        if (!$response->isOk()) {
            return false;
        }

        unlink($dest);
        return $response->json();
    }

    public function getImages($path)
    {
        return "$this->apiUrl/assets/original/${path}";
    }

    public function hasImage($path)
    {
        $response = Zttp::withHeaders([
            'Authorization' => ['Basic ' . $this->credentials],
        ])->post("$this->apiUrl/files/has", [
            'path' => $path
        ]);

        if (!$response->isOk()) {
            return false;
        }

        return $response->json()['message'];
    }

    public function deleteImage($path) {
        $response = Zttp::withHeaders([
            'Authorization' => ['Basic ' . $this->credentials],
        ])->delete("$this->apiUrl/files/destroy", [
            'path' => $path
        ]);

        if (!$response->isOk()) {
            return false;
        }

        return $response->json()['message'];
    }
}