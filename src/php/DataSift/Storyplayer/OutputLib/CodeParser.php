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
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OutputLib;

use Exception;
use PhpParser\Parser;
use PhpParser\Lexer;

/**
 * helper for reproducing executing PHP code
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class CodeParser
{
    /**
     * the raw parser tree of this story, and of any templates that we
     * use
     *
     * @var array
     */
    protected $parserTrees = array();

    // ==================================================================
    //
    // Debugging / error reporting assistance
    //
    // ------------------------------------------------------------------

    /**
     * @param  string $filename
     * @return bool
     */
    public function buildParseTreeForFile($filename)
    {
        // special case - do we already have this file parsed?
        if (isset($this->parserTrees[$filename])) {
            // yes - nothing to do
            return;
        }

        // we need a little bit of help here
        $parser = new Parser(new Lexer);

        try {
            $this->parserTrees[$filename] = $parser->parse(file_get_contents($filename));

            // tell the caller that this worked
            return true;
        }
        catch (Exception $e) {
            // something went wrong parsing the template
            // nothing we can do about that
            $this->parserTrees[$filename] = null;

            // tell the caller that this didn't work
            return false;
        }
    }

    public function buildExecutingCodeLine($stackTrace)
    {
        static $ourPaths = [
            "src/bin/storyplayer",
            "src/php/DataSift/Storyplayer",
            "vendor/aws",
            "vendor/bin",
            "vendor/composer",
            "vendor/datasift",
            "vendor/guzzle",
            "vendor/nikic",
            "vendor/phix",
            "vendor/stuart",
            "vendor/symfony",
            "vendor/twig",
        ];

        // find the code that is executing
        foreach ($stackTrace as $index => $stackEntry)
        {
            if (!isset($stackEntry['file'])) {
                continue;
            }

            // filter out our files
            foreach ($ourPaths as $ourPath) {
                if (strpos($stackEntry['file'], $ourPath) !== false) {
                    continue 2;
                }
            }

            // do we have any code for this?
            $code = $this->getCodeFor($stackEntry['file'], $stackEntry['line']);
            if (!$code) {
                continue;
            }

            // because we chain multiple method calls on a single line,
            // a PHP stack entry can contain duplicate entries
            //
            // we don't want to show duplicate entries, so we use the
            // filename@line as a key in the array
            $key = $stackEntry['file'] . '@' . $stackEntry['line'];
            $codePoint = $stackEntry;
            $codePoint['code'] = CodeFormatter::formatCode($code);
            $codePoint['key']  = $key;

            // deal with magic
            if ($codePoint['function'] == '__call') {
                $codePoint['args'] = $codePoint['args'][1];
            }

            // we only care about code that is calling $st
            // for debugging purposes
            // if (strpos($codePoint['code'], '$st->') === false) {
            //  continue;
            // }

            // if we get here, we have code that's interesting
            return $codePoint;
        }

        // no success
        return [];
    }

    public function getCodeFor($filename, $lineToFind)
    {
        // do we have a parser tree for this file?
        if (!isset($this->parserTrees[$filename]))
        {
            // attempt to build a parse tree
            if (!$this->buildParseTreeForFile($filename)) {

                // sadly, that didn't work
                return null;
            }
        }

        // we have the parsed code
        //
        // find the line that the caller is looking for
        //
        // the line that we are looking for may well be inside one of
        // the top-level statements, so we may need to recurse in order
        // to find it
        return $this->searchCodeStatementsFor($this->parserTrees[$filename], $lineToFind);
    }

    protected function searchCodeStatementsFor($stmts, $lineToFind)
    {
        // remember the last thing we saw, as it may contain what
        // we are looking for
        $lastStmt = null;

        foreach($stmts as $stmt) {
            // where are we?
            $currentLine = $stmt->getLine();
            // var_dump($currentLine, $stmt->getType());

            // are we there yet?
            if ($currentLine < $lineToFind) {
                // still looking
                $lastStmt = $stmt;
                continue;
            }
            else if ($currentLine == $lineToFind) {
                // an exact match!!
                return $stmt;
            }
            else if ($lastStmt !== null) {
                // we have overshot!
                //
                // this is where we have to start working quite hard!
                return $this->searchCodeStatementFor($lastStmt, $lineToFind);
            }
            else {
                // something unexpected has happened
                return null;
            }
        }

        if ($lastStmt !== null) {
            return $this->searchCodeStatementFor($lastStmt, $lineToFind);
        }

        // if we get here, then we have been unable to find the code at
        // all
        return null;
    }

    protected function searchCodeStatementFor($stmt, $lineToFind)
    {
        // what are we looking at?
        $nodeType = $stmt->getType();

        switch ($nodeType)
        {
            case 'Arg':
                // this is an argument to an expression
                //
                // its value may be a closure
                return $this->searchCodeStatementFor($stmt->value, $lineToFind);

            case 'Expr_Closure':
                // this is a closure
                //
                // the line we are looking for may be inside
                return $this->searchCodeStatementsFor($stmt->stmts, $lineToFind);

            case 'Expr_MethodCall':
                // this is a method call
                //
                // one of the arguments to the method call may
                // be a closure
                return $this->searchCodeStatementsFor($stmt->args, $lineToFind);

            case 'Stmt_Class':
                // this is a PHP class
                return $this->searchCodeStatementsFor($stmt->stmts, $lineToFind);

            case 'Stmt_ClassMethod':
                // this is a method defined in a PHP class
                return $this->searchCodeStatementsFor($stmt->stmts, $lineToFind);

            case 'Stmt_Namespace':
                // this is a file containing code
                return $this->searchCodeStatementsFor($stmt->stmts, $lineToFind);

            default:
                // var_dump($nodeType);
                // var_dump($stmt->getSubNodeNames());
                // exit(0);

                // if we get here, these statements don't contain the line number
                // at all
                return null;
        }
    }
}