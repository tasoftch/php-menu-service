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

class MenuItem implements MenuItemInterface
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $title;

    /** @var ActionInterface */
    private $action;

    /** @var MenuInterface */
    private $menu;

    /** @var MenuInterface|null */
    private $submenu;

    /** @var int */
    private $tag;

    /** @var bool */
    private $enabled = true;

    private $selected = false;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     */
    public function setSelected(bool $selected): void
    {
        $this->selected = $selected;
    }

    /**
     * MenuItem constructor.
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return ActionInterface
     */
    public function getAction(): ?ActionInterface
    {
        return $this->action;
    }

    /**
     * @param ActionInterface $action
     */
    public function setAction(ActionInterface $action): void
    {
        $this->action = $action;
    }

    /**
     * @return MenuInterface|null
     */
    public function getMenu(): ?MenuInterface
    {
        return $this->menu;
    }

    /**
     * @param MenuInterface $menu
     */
    public function setMenu(?MenuInterface $menu): void
    {
        if(!$menu && $this->menu) {
            $this->menu->removeItem($this);
            $this->menu = NULL;
            return;
        }

        if($this->menu !== $menu) {
            $this->menu = $menu;
            if($menu)
                $menu->addItem($this);
        }
    }

    /**
     * @return MenuInterface|null
     */
    public function getSubmenu(): ?MenuInterface
    {
        return $this->submenu;
    }

    /**
     * @param MenuInterface|null $submenu
     */
    public function setSubmenu(?MenuInterface $submenu): void
    {
        if($this->submenu !== $submenu) {
            $this->submenu = $submenu;
            if($submenu)
                $submenu->setParentItem($this);
        }
    }

    /**
     * @return int
     */
    public function getTag(): int
    {
        return $this->tag;
    }

    /**
     * @param int $tag
     */
    public function setTag(int $tag): void
    {
        $this->tag = $tag;
    }
}