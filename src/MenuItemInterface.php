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


use TASoft\MenuService\Action\ActionInterface;

interface MenuItemInterface
{
    /**
     * Each menu item should have a unique id
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Define tags to group menu items (eg. for validation)
     * @return int
     */
    public function getTag(): int;

    /**
     * A title of the menu item
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Define an action if the menu item was selected
     *
     * @return ActionInterface|null
     */
    public function getAction(): ?ActionInterface;


    /**
     * Returns true, if the menu item is enabled, false otherwise
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Set the menu item enabled or disabled
     * @param bool $flag
     * @return void
     */
    public function setEnabled(bool $flag);

    /**
     * Returns true, if the menu item is selected
     * @return bool
     */
    public function isSelected(): bool;

    /**
     * Selects the menu item
     *
     * @param bool $flag
     * @return void
     */
    public function setSelected(bool $flag);

    /**
     * Get the menu this items belongs to
     *
     * @return MenuInterface|null
     */
    public function getMenu(): ?MenuInterface;

    /**
     * Sets a menu.
     *
     * @param MenuInterface $menu
     * @return void
     */
    public function setMenu(?MenuInterface $menu);

    /**
     * Get the submenu of that item
     *
     * @return MenuInterface|null
     */
    public function getSubmenu(): ?MenuInterface;

    /**
     * Sets a sub menu
     * This method should maintain consistency:
     *
     * @param MenuInterface|null $subMenu
     * @return void
     */
    public function setSubmenu(?MenuInterface $subMenu);
}