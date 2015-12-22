<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * work with a PDO database connection
 *
 * great for managing test data
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingPDODB extends Prose
{
    public function __construct($st, $args)
    {
        // call our parent first
        parent::__construct($st, $args);

        // make sure we have a PDO connection to use
        if (!isset($this->args[0])) {
            throw Exceptions::newActionFailedException(__METHOD__, "param #1 must be a valid PDO connection");
        }
        if (!$this->args[0] instanceof PDO) {
            throw Exceptions::newActionFailedException(__METHOD__, "param #1 must be an instance of PDO");
        }
    }

    public function query($sql, $params = [], $driverParams = [])
    {
        // what are we doing?
        $log = usingLog()->startAction(["run SQL query:", $sql, "/ with params:", $params, "and driver params:", $driverParams]);

        try
        {
            // create a prepared statement
            //
            // we do this so that we can inject the $params into the SQL statement
            $stmt = $this->args[0]->prepare($sql, $driverParams);

            // execute the prepared statement
            $stmt->execute($params);

            // all done
            $log->endAction();
            return $stmt;
        }
        catch (Exception $e)
        {
            throw Exceptions::newActionFailedException(__METHOD__, $e->getMessage());
        }
    }

    public function rawQuery($sql, $driverParams = [])
    {
        // what are we doing?
        $log = usingLog()->startAction(["run raw SQL query:", $sql, "with driver params: ", $driverParams]);

        try
        {
            // execute the prepared statement
            //
            // we do this directly so that you can (hopefully) attempt
            // SQL injections for testing purposes
            $stmt = $this->args[0]->query($sql, $driverParams);

            // all done
            $log->endAction();
            return $stmt;
        }
        catch (Exception $e)
        {
            throw Exceptions::newActionFailedException(__METHOD__, $e->getMessage());
        }
    }

    public function beginTransaction()
    {
        // what are we doing?
        $log = usingLog()->startAction("begin PDO database transaction");

        try
        {
            $this->args[0]->beginTransaction();

            // all done
            $log->endAction();
        }
        catch (Exception $e)
        {
            throw Exceptions::newActionFailedException(__METHOD__, $e->getMessage());
        }
    }

    public function commitTransaction()
    {
        // what are we doing?
        $log = usingLog()->startAction("commit PDO database transaction");

        try
        {
            $this->args[0]->commit();

            // all done
            $log->endAction();
        }
        catch (Exception $e)
        {
            throw Exceptions::newActionFailedException(__METHOD__, $e->getMessage());
        }
    }

    public function rollbackTransaction()
    {
        // what are we doing?
        $log = usingLog()->startAction("rollback PDO database transaction");

        try
        {
            $this->args[0]->rollBack();

            // all done
            $log->endAction();
        }
        catch (Exception $e)
        {
            throw Exceptions::newActionFailedException(__METHOD__, $e->getMessage());
        }
    }

}