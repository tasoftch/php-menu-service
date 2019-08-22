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

namespace TASoft\MenuService;

/**
 * A menu is a container of menu items.
 *
 * @package TASoft\MenuService
 */
interface MenuInterface
{
    /**
     * Each menu should have a unique id
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Optionally or for translation reasons, add a title.
     * Normally a parent menu item (menu item submenu) is responsable for the title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Returns if a menu is visible or not
     *
     * @return bool
     */
    public function isHidden(): bool;

    /**
     * Return a list with all contained menu items (ordered!)
     *
     * @return MenuItemInterface[]
     */
    public function getMenuItems(): array;

    /**
     * Add a menu item
     *
     * @param MenuItemInterface $item
     * @return static
     */
    public function addItem(MenuItemInterface $item);

    /**
     * Removes an item from menu
     *
     * @param MenuItemInterface $item
     */
    public function removeItem(MenuItemInterface $item);

    /**
     * Get a parent menu item
     *
     * @return MenuItemInterface|null
     */
    public function getParentItem(): ?MenuItemInterface;

    /**
     * Set a parent item
     *
     * @param MenuItemInterface|null $item
     * @return void
     */
    public function setParentItem(?MenuItemInterface $item);
}