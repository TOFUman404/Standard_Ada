<?php
/*
 * Copyright 2016-2017 MongoDB, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace MongoDB\GridFS;

use MongoDB\Exception\InvalidArgumentException;
use MongoDB\GridFS\Exception\CorruptFileException;
use IteratorIterator;
use stdClass;

/**
 * ReadableStream abstracts the process of reading a GridFS file.
 *
 * @internal
 */
class ReadableStream
{
    private $buffer;
    private $bufferOffset = 0;
    private $chunkSize;
    private $chunkOffset = 0;
    private $chunksIterator;
    private $collectionWrapper;
    private $expectedLastChunkSize = 0;
    private $file;
    private $length;
    private $numChunks = 0;

    /**
     * Constructs a readable GridFS stream.
     *
     * @param CollectionWrapper $collectionWrapper GridFS collection wrapper
     * @param stdClass          $file              GridFS file document
     * @throws CorruptFileException
     */
    public function __construct(CollectionWrapper $collectionWrapper, stdClass $file)
    {
        if ( ! isset($file->chunkSize) || ! is_integer($file->chunkSize) || $file->chunkSize < 1) {
            throw new CorruptFileException('file.chunkSize is not an integer >= 1');
        }

        if ( ! isset($file->length) || ! is_integer($file->length) || $file->length < 0) {
            throw new CorruptFileException('file.length is not an integer > 0');
        }

        if ( ! isset($file->_id) && ! property_exists($file, '_id')) {
            throw new CorruptFileException('file._id does not exist');
        }

        $this->file = $file;
        $this->chunkSize = (integer) $file->chunkSize;
        $this->length = (integer) $file->length;

        $this->collectionWrapper = $collectionWrapper;

        if ($this->length > 0) {
            $this->numChunks = (integer) ceil($this->length / $this->chunkSize);
            $this->expectedLastChunkSize = ($this->length - (($this->numChunks - 1) * $this->chunkSize));
        }
    }

    /**
     * Return internal properties for debugging purposes.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'bucketName' => $this->collectionWrapper->getBucketName(),
            'databaseName' => $this->collectionWrapper->getDatabaseName(),
            'file' => $this->file,
        ];
    }

    public function close()
    {
        // Nothing to do
    }

    /**
     * Return the stream's file document.
     *
     * @return stdClass
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Return the stream's size in bytes.
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->length;
    }

    /**
    * Return whether the current read position is at the end of the stream.
    *
    * @return boolean
    */
    public function isEOF()
    {
        if ($this->chunkOffset === $this->numChunks - 1) {
            return $this->bufferOffset >= $this->expectedLastChunkSize;
        }

        return $this->chunkOffset >= $this->numChunks;
    }

    /**
     * Read bytes from the stream.
     *
     * Note: this method may return a string smaller than the requested length
     * if data is not available to be read.
     * 
     * @param integer $length Number of bytes to read
     * @return string
     * @throws InvalidArgumentException if $length is negative
     */
    public function readBytes($length)
    {
        if ($length < 0) {
            throw new InvalidArgumentException(sprintf('$length must be >= 0; given: %d', $length));
        }

        if ($this->chunksIterator === null) {
            $this->initChunksIterator();
        }

        if ($this->buffer === null && ! $this->initBufferFromCurrentChunk()) {
            return '';
        }

        $data = '';

        while (strlen($data) < $length) {
            if ($this->bufferOffset >= strlen($this->buffer) && ! $this->initBufferFromNextChunk()) {
                break;
            }

            $initialDataLength = strlen($data);
            $data .= substr($this->buffer, $this->bufferOffset, $length - $initialDataLength);
            $this->bufferOffset += strlen($data) - $initialDataLength;
        }

        return $data;
    }

    /**
     * Initialize the buffer to the current chunk's data.
     *
     * @return boolean Whether there was a current chunk to read
     * @throws CorruptFileException if an expected chunk could not be read successfully
     */
    private function initBufferFromCurrentChunk()
    {
        if ($this->chunkOffset === 0 && $this->numChunks === 0) {
            return false;
        }

        if ( ! $this->chunksIterator->valid()) {
            throw CorruptFileException::missingChunk($this->chunkOffset);
        }

        $currentChunk = $this->chunksIterator->current();

        if ($currentChunk->n !== $this->chunkOffset) {
            throw CorruptFileException::unexpectedIndex($currentChunk->n, $this->chunkOffset);
        }

        $this->buffer = $currentChunk->data->getData();

        $actualChunkSize = strlen($this->buffer);

        $expectedChunkSize = ($this->chunkOffset === $this->numChunks - 1)
            ? $this->expectedLastChunkSize
            : $this->chunkSize;

        if ($actualChunkSize !== $expectedChunkSize) {
            throw CorruptFileException::unexpectedSize($actualChunkSize, $expectedChunkSize);
        }

        return true;
    }

    /**
     * Advance to the next chunk and initialize the buffer to its data.
     *
     * @return boolean Whether there was a next chunk to read
     * @throws CorruptFileException if an expected chunk could not be read successfully
     */
    private function initBufferFromNextChunk()
    {
        if ($this->chunkOffset === $this->numChunks - 1) {
            return false;
        }

        $this->bufferOffset = 0;
        $this->chunkOffset++;
        $this->chunksIterator->next();

        return $this->initBufferFromCurrentChunk();
    }

    /**
     * Initializes the chunk iterator starting from the current offset.
     */
    private function initChunksIterator()
    {
        $cursor = $this->collectionWrapper->findChunksByFileId($this->file->_id, $this->chunkOffset);

        $this->chunksIterator = new IteratorIterator($cursor);
        $this->chunksIterator->rewind();
    }
}
