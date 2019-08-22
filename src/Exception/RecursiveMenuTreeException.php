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

namespace TASoft\MenuService\Exception;


use TASoft\MenuService\MenuInterface;
use TASoft\MenuService\MenuItemInterface;

class RecursiveMenuTreeException extends MenuServiceException
{
    /** @var MenuItemInterface */
    private $menuItem;

    /** @var MenuInterface */
    private $submenu;

    /**
     * @return MenuItemInterface
     */
    public function getMenuItem(): MenuItemInterface
    {
        return $this->menuItem;
    }

    /**
     * @param MenuItemInterface $menuItem
     */
    public function setMenuItem(MenuItemInterface $menuItem): void
    {
        $this->menuItem = $menuItem;
    }

    /**
     * @return MenuInterface
     */
    public function getSubmenu(): MenuInterface
    {
        return $this->submenu;
    }

    /**
     * @param MenuInterface $submenu
     */
    public function setSubmenu(MenuInterface $submenu): void
    {
        $this->submenu = $submenu;
    }
}