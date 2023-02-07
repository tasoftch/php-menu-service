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
 * MenuConsistencyTest.php
 * php-menu
 *
 * Created on 2019-08-22 16:19 by thomas
 */

use PHPUnit\Framework\TestCase;
use TASoft\MenuService\Menu;
use TASoft\MenuService\MenuItem;

class MenuConsistencyTest extends TestCase
{
    public function testSimpleChildConsistency() {
        $menu = new Menu("menu");
        $item = new MenuItem("item");

        $menu->addItem($item);

        $this->assertSame($menu, $item->getMenu());
        $this->assertSame($item, $menu->getItem(0));
        $this->assertSame($item, $menu->findItem("item"));

        $this->assertEquals([$item], $menu->getMenuItems());
    }

    public function testParentMenuSetting() {
        $menu = new Menu("menu");
        $item = new MenuItem("item");

        $item->setMenu($menu);
        $this->assertEquals([$item], $menu->getMenuItems());

        $item2 = new MenuItem("item2");
        $item2->setMenu($menu);

        $this->assertEquals([$item, $item2], $menu->getMenuItems());

        // Must happen nothing!
        $item->setMenu($menu);
        $item->setMenu($menu);
        $item->setMenu($menu);
        $item->setMenu($menu);

        $item2->setMenu($menu);
        $item2->setMenu($menu);
        $item2->setMenu($menu);

        $this->assertEquals([$item, $item2], $menu->getMenuItems());
    }

    public function testSubmenu() {
        $menu = new Menu("menu");
        $item = new MenuItem("item");
        $item2 = new MenuItem("item2");

        $menu->addItem($item);
        $menu->addItem($item2);

        $sm = new Menu("sub");
        $item2->setSubmenu($sm);
        $sm->addItem( new MenuItem("test") );

        $this->assertSame($sm, $item2->getSubmenu());
        $this->assertSame($item2, $sm->getParentItem());
    }

    public function testParentItem() {
        $menu = new Menu("menu");
        $item = new MenuItem("item");
        $item2 = new MenuItem("item2");

        $menu->addItem($item);
        $menu->addItem($item2);

        $sm = new Menu("sub");
        $sm->addItem( new MenuItem("test") );

        $sm->setParentItem($item2);
        $this->assertSame($sm, $item2->getSubmenu());
        $this->assertSame($item2, $sm->getParentItem());
    }

    public function testItemRemoving() {
        $menu = new Menu("menu");
        $menu->addItem($item1 = new MenuItem("item1"))
            ->addItem($item2 = new MenuItem("item2"))
            ->addItem($item3 = new MenuItem("item3"))
            ->addItem($item4 = new MenuItem("item4"));

        $this->assertSame([$item1, $item2, $item3, $item4], $menu->getMenuItems());
        $this->assertSame($menu, $item3->getMenu());

        $menu->removeItemAtIndex(2);
        $this->assertSame([$item1, $item2, $item4], $menu->getMenuItems());

        $this->assertNull($item3->getMenu());

        $item1->setMenu(NULL);
        $this->assertSame([$item2, $item4], $menu->getMenuItems());
        $this->assertNull($item1->getMenu());
    }

    /**
     * @expectedException TASoft\MenuService\Exception\RecursiveMenuTreeException
     */
    public function testRecursion() {
        $menu = new Menu("menu");
        $menu->addItem($item1 = new MenuItem("item1"))
            ->addItem($item2 = new MenuItem("item2"))
            ->addItem($item3 = new MenuItem("item3"))
            ->addItem($item4 = new MenuItem("item4"));

		$this->expectException(\TASoft\MenuService\Exception\RecursiveMenuTreeException::class);
        $item4->setSubmenu($menu);
    }
}
