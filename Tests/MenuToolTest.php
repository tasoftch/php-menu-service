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

/**
 * MenuToolTest.php
 * php-menu
 *
 * Created on 2019-08-22 17:52 by thomas
 */

use PHPUnit\Framework\TestCase;
use TASoft\MenuService\Action\RegexStringAction;
use TASoft\MenuService\Menu;
use TASoft\MenuService\MenuItem;
use TASoft\MenuService\MenuItemInterface;
use TASoft\MenuService\MenuTool;
use TASoft\MenuService\Validation\ValidationInterface;

class MenuToolTest extends TestCase
{
    public function testValidation() {
        $menu = new Menu("menu");
        $menu->addItem($item1 = new MenuItem("1"))
            ->addItem($item2 = new MenuItem("2"))
            ->addItem($item3 = new MenuItem("3"))
            ->addItem($item4 = new MenuItem("4"));

        $validator = new class() implements ValidationInterface {
            public function validateMenuItem(MenuItemInterface $menuItem): bool
            {
                return $menuItem->getIdentifier() % 2 == 1 ? true : false;
            }
        };

        $this->assertTrue($item1->isEnabled());
        $this->assertTrue($item2->isEnabled());
        $this->assertTrue($item3->isEnabled());
        $this->assertTrue($item4->isEnabled());

        MenuTool::validateMenu($menu, [$validator]);

        $this->assertTrue($item1->isEnabled());
        $this->assertFalse($item2->isEnabled());
        $this->assertTrue($item3->isEnabled());
        $this->assertFalse($item4->isEnabled());
    }

    public function testSelection() {
        $menu = new Menu("menu");
        $menu->addItem($item1 = new MenuItem("1"))
            ->addItem($item2 = new MenuItem("2"))
            ->addItem($item3 = new MenuItem("3"))
            ->addItem($item4 = new MenuItem("4"));

        $item1->setAction(new RegexStringAction("", "%^/my%i"));
        $item2->setAction(new RegexStringAction("", "%^/other/(url|txt)%i"));
        $item3->setAction(new RegexStringAction("", "%^/my/stuff/%i"));
        $item4->setAction(new RegexStringAction("", "%^/(my|you)/(most|here)/%i"));


        $this->assertFalse($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertFalse($item3->isSelected());
        $this->assertFalse($item4->isSelected());

        MenuTool::selectMenuItem($menu, "/hi/there");

        $this->assertFalse($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertFalse($item3->isSelected());
        $this->assertFalse($item4->isSelected());

        MenuTool::selectMenuItem($menu, "/my/space/here");

        $this->assertTrue($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertFalse($item3->isSelected());
        $this->assertFalse($item4->isSelected());

        MenuTool::selectMenuItem($menu, "/hi/there");

        $this->assertFalse($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertFalse($item3->isSelected());
        $this->assertFalse($item4->isSelected());

        MenuTool::selectMenuItem($menu, "/my/stuff/here");

        $this->assertTrue($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertTrue($item3->isSelected());
        $this->assertFalse($item4->isSelected());
    }

    public function testNestedValidation() {
        $menu = new Menu("menu");
        $menu->addItem($item1 = new MenuItem("1"));

        $menu2 = new Menu("");
        $menu2->addItem($item2 = new MenuItem("2"));
        $item1->setSubmenu($menu2);

        $menu3 = new Menu("");
        $menu3->addItem($item3 = new MenuItem("3"));
        $item2->setSubmenu($menu3);

        $menu4 = new Menu("");
        $menu4->addItem($item4 = new MenuItem("4"));
        $item3->setSubmenu($menu4);

        $validator = new class() implements ValidationInterface {
            public function validateMenuItem(MenuItemInterface $menuItem): bool
            {
                return $menuItem->getIdentifier() % 2 == 1 ? true : false;
            }
        };

        $this->assertTrue($item1->isEnabled());
        $this->assertTrue($item2->isEnabled());
        $this->assertTrue($item3->isEnabled());
        $this->assertTrue($item4->isEnabled());

        MenuTool::validateMenu($menu, [$validator]);

        $this->assertTrue($item1->isEnabled());
        $this->assertFalse($item2->isEnabled());
        $this->assertTrue($item3->isEnabled());
        $this->assertFalse($item4->isEnabled());
    }

    public function testNestedSelection() {
        $menu = new Menu("menu");
        $menu->addItem($item1 = new MenuItem("1"));

        $menu2 = new Menu("");
        $menu2->addItem($item2 = new MenuItem("2"));
        $item1->setSubmenu($menu2);

        $menu3 = new Menu("");
        $menu3->addItem($item3 = new MenuItem("3"));
        $item2->setSubmenu($menu3);

        $menu4 = new Menu("");
        $menu4->addItem($item4 = new MenuItem("4"));
        $item3->setSubmenu($menu4);

        $item1->setAction(new RegexStringAction("", "%^/my%i"));
        $item2->setAction(new RegexStringAction("", "%^/other/(url|txt)%i"));
        $item3->setAction(new RegexStringAction("", "%^/my/stuff/?%i"));
        $item4->setAction(new RegexStringAction("", "%^/(my|you)/(most|here)/%i"));


        $this->assertFalse($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertFalse($item3->isSelected());
        $this->assertFalse($item4->isSelected());

        MenuTool::selectMenuItem($menu, "/my/stuff");

        $this->assertTrue($item1->isSelected());
        $this->assertFalse($item2->isSelected());
        $this->assertTrue($item3->isSelected());
        $this->assertFalse($item4->isSelected());
    }
}
