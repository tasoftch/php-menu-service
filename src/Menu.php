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


use ArrayAccess;
use Countable;
use InvalidArgumentException;
use TASoft\MenuService\Exception\RecursiveMenuTreeException;

class Menu implements MenuInterface, Countable, ArrayAccess
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $title;

    private $hidden = false;

    /** @var MenuItemInterface[] */
    private $menuItems = [];

    /** @var MenuItemInterface|null */
    private $parentItem;

    /**
     * Menu constructor.
     * @param string $identifier
     * @param string $title
     */
    public function __construct(string $identifier, string $title = "")
    {
        $this->identifier = $identifier;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getMenuItems(): array
    {
        return $this->menuItems;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return MenuItemInterface|null
     */
    public function getParentItem(): ?MenuItemInterface
    {
        return $this->parentItem;
    }

    /**
     * @param MenuItemInterface|null $menuItem
     */
    public function setParentItem(?MenuItemInterface $menuItem)
    {
        if($this->parentItem !== $menuItem) {
            $this->parentItem = $menuItem;
            if($menuItem)
                $menuItem->setSubmenu($this);

            while($menuItem && $pMenu = $menuItem->getMenu()) {
                if($pMenu === $this) {
                    $e = new RecursiveMenuTreeException("Can not set parent item because of recursion", 77);
                    $e->setSubmenu($this);
                    $e->setMenuItem($menuItem);
                    throw $e;
                }
                $menuItem = $pMenu->getParentItem();
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->menuItems);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        if(is_numeric($offset))
            return isset($this->menuItems[$offset]);
        foreach($this->yieldItems($offset) as $_)
            return true;
        return false;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        if(is_numeric($offset))
            return $this->menuItems[$offset] ?? NULL;
        foreach($this->yieldItems($offset) as $_)
            return $_;
        return NULL;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        throw new InvalidArgumentException("Can not modify menu using array access.");
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        throw new InvalidArgumentException("Can not modify menu using array access.");
    }

    /**
     * Returns a menu item on a specified position
     *
     * @param int $index
     * @return MenuItemInterface|null
     */
    public function getItem(int $index): ?MenuItemInterface {
        return $this->menuItems[$index] ?? NULL;
    }

    /**
     * Yields all found menu items.
     * Pass nothing to this method to yield them all or an integer to find items by tag
     * Pass a string to find items by title or id
     *
     * @param $titleIdOrTag
     * @return \Generator
     */
    public function yieldItems($titleIdOrTag = NULL) {
        foreach($this->menuItems as $menuItem) {
            if(!is_null($titleIdOrTag)) {
                if(is_numeric($titleIdOrTag) && $menuItem->getTag() != $titleIdOrTag)
                    continue;
                elseif($menuItem->getIdentifier() != $titleIdOrTag && $menuItem->getTitle() != $titleIdOrTag)
                    continue;
            }

            yield $menuItem;
        }
    }

    /**
     * Find first occurrence of yieldItems
     *
     * @param $titleIdOrTag
     * @return MenuItemInterface|null
     */
    public function findItem($titleIdOrTag) {
        foreach($this->yieldItems($titleIdOrTag) as $menuItem)
            return $menuItem;
        return NULL;
    }

    /**
     * Searches for the position of an item in the menu.
     * Returns -1 if not found
     *
     * @param MenuItemInterface $item
     * @return int
     */
    public function getIndexOfItem(MenuItemInterface $item) {
        return ($idx = array_search($item, $this->menuItems)) !== false ? $idx : -1;
    }

    /**
     * Searches for a menu item by a title and return its position
     *
     * @param $title
     * @return int
     */
    public function getIndexOfItemWithTitle($title) {
        for($e=0;$e < $this->count();$e++) {
            if($this->menuItems[$e]->getTitle() == $title)
                return $e;
        }
        return -1;
    }

    /**
     * Searches for a menu item by a tag and return its position
     *
     * @param $tag
     * @return int
     */
    public function getIndexOfItemWithTag($tag) {
        for($e=0;$e < $this->count();$e++) {
            if($this->menuItems[$e]->getTag() == $tag)
                return $e;
        }
        return -1;
    }

    /**
     * @inheritDoc
     */
    public function addItem(MenuItemInterface $item) {
        if(!$this->containsItem($item)) {
            $this->menuItems[] = $item;
            $this->maintainMenuConsistency($item);
        }
        return $this;
    }

    /**
     * Inserts a menu item on a given position
     *
     * @param MenuItemInterface $item
     * @param int $index
     * @return static
     */
    public function insertItem(MenuItemInterface $item, int $index) {
        array_splice($this->menuItems, $index, 0, $item);
        $this->maintainMenuConsistency($item);
        return $this;
    }

    /**
     * Returns true whether the menu contains an item or not
     *
     * @param MenuItemInterface $item
     * @return bool
     */
    public function containsItem(MenuItemInterface $item): bool {
        return array_search($item, $this->menuItems) !== false ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function removeItem(MenuItemInterface $item) {
        if($item->getMenu() === $this) {
            if(($idx = array_search($item, $this->menuItems)) !== false) {
                array_splice($this->menuItems, $idx, 1);
                $item->setMenu(NULL);
            }
        }
    }

    /**
     * Removes an item on a given index
     *
     * @param int $index
     */
    public function removeItemAtIndex(int $index) {
        if(isset($this->menuItems[$index])) {
            $this->removeItem( $this->menuItems[$index] );
        }
    }

    /**
     * Removes all items from menu
     */
    public function removeAllItems() {
        array_walk($this->menuItems, function(MenuItemInterface $item) {
            $item->setMenu(NULL);
        });
        $this->menuItems = [];
    }

    /**
     * @internal
     */
    private function maintainMenuConsistency(MenuItemInterface $newItem) {
        if($newItem->getMenu() != NULL) {
            if($newItem->getMenu() === $this)
                return;
        }
        $newItem->setMenu($this);
    }
}