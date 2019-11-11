<?php

namespace common\components\flysystem;

use Aws\S3\Exception\S3Exception;
use creocoder\flysystem\AwsS3Filesystem as BaseAwsS3Filesystem;
use League\Flysystem\AdapterInterface;

class AwsS3Filesystem extends BaseAwsS3Filesystem
{
    /**
     * @var string
     */
    public $region = '';

    /**
     * @var bool
     */
    public $pathStyleEndpoint = true;

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {
        $command = $this->s3Client->getCommand(
            'putObjectAcl',
            [
                'Bucket' => $this->bucket,
                'Key' => $this->applyPathPrefix($path),
                'ACL' => $visibility === AdapterInterface::VISIBILITY_PUBLIC ? 'public-read' : 'private',
            ]
        );
        try {
            $this->s3Client->execute($command);
        } catch (S3Exception $exception) {
            return false;
        }
        return compact('path', 'visibility');
    }

    /**
     * @param string $key
     * @param int|string|\DateTime $expires
     * @return string
     */
    public function getPresignedUrl($key, $expires)
    {
        /** @var \Aws\S3\S3Client $s3Client */
        $s3Client = $this->getAdapter()->getClient();

        $request = $s3Client->createPresignedRequest($s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key
        ]), $expires);

        return (string) $request->getUri();
    }

    /**
     * @param string $key
     * @return string
     */
    public function getPublicUrl($key)
    {
        /** @var \Aws\S3\S3Client $s3Client */
        $s3Client = $this->getAdapter()->getClient();

        return $s3Client->getObjectUrl($this->bucket, $key);
    }

    /**
     * @param string $key
     * @return string
     */
    public function getPublicPreviewUrl($key)
    {
        $preview = 'preview' . $key;

        if (!$this->has($preview)) {
            $stream = $this->readStream($key);

            // обработка
            $previewStream = $stream;

            $this->writeStream($preview, $previewStream);
        }

        return $this->getPublicUrl($preview);
    }
}