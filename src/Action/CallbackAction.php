<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace TASoft\MenuService\Action;


class CallbackAction implements ActionInterface
{
    /** @var callable */
    private $exportCallback;

    /** @var callable */
    private $matchCallback;

    /**
     * CallbackAction constructor.
     * @param callable $exportCallback
     * @param callable $matchCallback
     */
    public function __construct(callable $exportCallback = NULL, callable $matchCallback = NULL)
    {
        $this->exportCallback = $exportCallback;
        $this->matchCallback = $matchCallback;
    }

    /**
     * @inheritDoc
     */
    public function getExportedActionVersion(): string
    {
        return is_callable($this->exportCallback) ? call_user_func($this->exportCallback) : "";
    }

    /**
     * @inheritDoc
     */
    public function matchActionVersion(string $version): bool
    {
        return is_callable($this->matchCallback) && call_user_func($this->matchCallback, $version);
    }

    /**
     * @return callable
     */
    public function getMatchCallback(): callable
    {
        return $this->matchCallback;
    }

    /**
     * @param callable $matchCallback
     */
    public function setMatchCallback(callable $matchCallback): void
    {
        $this->matchCallback = $matchCallback;
    }

    /**
     * @return callable
     */
    public function getExportCallback(): callable
    {
        return $this->exportCallback;
    }

    /**
     * @param callable $exportCallback
     */
    public function setExportCallback(callable $exportCallback): void
    {
        $this->exportCallback = $exportCallback;
    }
}