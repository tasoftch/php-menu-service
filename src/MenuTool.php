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


use TASoft\MenuService\Validation\ValidationInterface;

/**
 * Class MenuValidation
 * @package TASoft\MenuService
 */
abstract class MenuTool
{
    const SEL_OPTION_RECURSIVE = 1<<0;
    const SEL_OPTION_LEAF_ONLY = 1<<1;
    const SEL_OPTION_FLOW = 1<<2;
    const SEL_OPTION_BACKWARD = 1<<3;

    /**
     * @param MenuInterface $
     * @param ValidationInterface[] $validators
     */
    public static function validateMenu(MenuInterface $menu, array $validators) {
        if($validators) {
            $handler = function(MenuInterface $m) use ($validators, &$handler) {
                foreach($m->getMenuItems() as $menuItem) {
                    $menuItem->setEnabled(true);

                    foreach($validators as $validator) {
                        if(!$validator->validateMenuItem($menuItem)) {
                            $menuItem->setEnabled(false);
                            break;
                        }
                    }

                    if($sub = $menuItem->getSubmenu()) {
                        $handler($sub);
                    }
                }
            };
            $handler($menu);
        }
    }

    /**
     * Iterates over all menu items and select menu items which action's matching to the version
     * Use options here
     *
     * @param MenuInterface $menu
     * @param string $actionVersion
     * @param int $options
     */
    public static function selectMenuItem(MenuInterface $menu, string $actionVersion, int $options = self::SEL_OPTION_RECURSIVE) {
        $handler = function(MenuInterface $menu, bool $deselectAll = false) use ($actionVersion, $options, &$handler) {
            foreach($menu->getMenuItems() as $menuItem) {
            	$action = $menuItem->getAction();
				if($action && $action->matchActionVersion($actionVersion)) {
					if($options & self::SEL_OPTION_LEAF_ONLY && !$menuItem->getSubmenu()) {} else {
						$menuItem->setSelected(true);
						if($options & self::SEL_OPTION_BACKWARD) {
							$mi = $menuItem;
							while($mi->getMenu() && ($mi = $mi->getMenu()->getParentItem())) {
								$mi->setSelected(true);
							}
						}
					}

					if($options & self::SEL_OPTION_RECURSIVE && $menuItem->getSubmenu())
						$handler($menuItem->getSubmenu());
				} else {
					$menuItem->setSelected(false);
					if($options & self::SEL_OPTION_RECURSIVE && $menuItem->getSubmenu())
						$handler($menuItem->getSubmenu(), $options & self::SEL_OPTION_FLOW ? false : true);
				}
            }
        };
        $handler($menu);
    }
}