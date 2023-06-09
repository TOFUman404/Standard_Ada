<?php
/*
 * Copyright 2015-2017 MongoDB, Inc.
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

namespace MongoDB\Operation;

use MongoDB\UpdateResult;
use MongoDB\Driver\BulkWrite as Bulk;
use MongoDB\Driver\Server;
use MongoDB\Driver\WriteConcern;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnsupportedException;

/**
 * Operation for the update command.
 *
 * This class is used internally by the ReplaceOne, UpdateMany, and UpdateOne
 * operation classes.
 *
 * @internal
 * @see http://docs.mongodb.org/manual/reference/command/update/
 */
class Update implements Executable
{
    private static $wireVersionForCollation = 5;
    private static $wireVersionForDocumentLevelValidation = 4;

    private $databaseName;
    private $collectionName;
    private $filter;
    private $update;
    private $options;

    /**
     * Constructs a update command.
     *
     * Supported options:
     *
     *  * bypassDocumentValidation (boolean): If true, allows the write to opt
     *    out of document level validation.
     *
     *  * collation (document): Collation specification.
     *
     *    This is not supported for server versions < 3.4 and will result in an
     *    exception at execution time if used.
     *
     *  * multi (boolean): When true, updates all documents matching the query.
     *    This option cannot be true if the $update argument is a replacement
     *    document (i.e. contains no update operators). The default is false.
     *
     *  * upsert (boolean): When true, a new document is created if no document
     *    matches the query. The default is false.
     *
     *  * writeConcern (MongoDB\Driver\WriteConcern): Write concern.
     *
     * @param string       $databaseName   Database name
     * @param string       $collectionName Collection name
     * @param array|object $filter         Query by which to delete documents
     * @param array|object $update         Update to apply to the matched
     *                                     document(s) or a replacement document
     * @param array        $options        Command options
     * @throws InvalidArgumentException for parameter/option parsing errors
     */
    public function __construct($databaseName, $collectionName, $filter, $update, array $options = [])
    {
        if ( ! is_array($filter) && ! is_object($filter)) {
            throw InvalidArgumentException::invalidType('$filter', $filter, 'array or object');
        }

        if ( ! is_array($update) && ! is_object($update)) {
            throw InvalidArgumentException::invalidType('$update', $filter, 'array or object');
        }

        $options += [
            'multi' => false,
            'upsert' => false,
        ];

        if (isset($options['bypassDocumentValidation']) && ! is_bool($options['bypassDocumentValidation'])) {
            throw InvalidArgumentException::invalidType('"bypassDocumentValidation" option', $options['bypassDocumentValidation'], 'boolean');
        }

        if (isset($options['collation']) && ! is_array($options['collation']) && ! is_object($options['collation'])) {
            throw InvalidArgumentException::invalidType('"collation" option', $options['collation'], 'array or object');
        }

        if ( ! is_bool($options['multi'])) {
            throw InvalidArgumentException::invalidType('"multi" option', $options['multi'], 'boolean');
        }

        if ($options['multi'] && ! \MongoDB\is_first_key_operator($update)) {
            throw new InvalidArgumentException('"multi" option cannot be true if $update is a replacement document');
        }

        if ( ! is_bool($options['upsert'])) {
            throw InvalidArgumentException::invalidType('"upsert" option', $options['upsert'], 'boolean');
        }

        if (isset($options['writeConcern']) && ! $options['writeConcern'] instanceof WriteConcern) {
            throw InvalidArgumentException::invalidType('"writeConcern" option', $options['writeConcern'], 'MongoDB\Driver\WriteConcern');
        }

        $this->databaseName = (string) $databaseName;
        $this->collectionName = (string) $collectionName;
        $this->filter = $filter;
        $this->update = $update;
        $this->options = $options;
    }

    /**
     * Execute the operation.
     *
     * @see Executable::execute()
     * @param Server $server
     * @return UpdateResult
     * @throws UnsupportedException if collation is used and unsupported
     * @throws DriverRuntimeException for other driver errors (e.g. connection errors)
     */
    public function execute(Server $server)
    {
        if (isset($this->options['collation']) && ! \MongoDB\server_supports_feature($server, self::$wireVersionForCollation)) {
            throw UnsupportedException::collationNotSupported();
        }

        $updateOptions = [
            'multi' => $this->options['multi'],
            'upsert' => $this->options['upsert'],
        ];

        if (isset($this->options['collation'])) {
            $updateOptions['collation'] = (object) $this->options['collation'];
        }

        $bulkOptions = [];

        if (isset($this->options['bypassDocumentValidation']) && \MongoDB\server_supports_feature($server, self::$wireVersionForDocumentLevelValidation)) {
            $bulkOptions['bypassDocumentValidation'] = $this->options['bypassDocumentValidation'];
        }

        $bulk = new Bulk($bulkOptions);
        $bulk->update($this->filter, $this->update, $updateOptions);

        $writeConcern = isset($this->options['writeConcern']) ? $this->options['writeConcern'] : null;
        $writeResult = $server->executeBulkWrite($this->databaseName . '.' . $this->collectionName, $bulk, $writeConcern);

        return new UpdateResult($writeResult);
    }
}
