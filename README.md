# PHP Menu Model Library
The menu library is an approach to create abstract models of menus or navigation bars.  
It is no HTML render, just a model that stores, validates and selects menus and menu items.

````bin
$ composer require tasoft/menu-service
````

It allows to create menus (as containers) and menu items (as items) and maintains consistency.

```php
use TASoft\MenuService\Menu;
use TASoft\MenuService\MenuItem;

$menu = new Menu("menu");

$item = new MenuItem("item");
$menu->addItem($item);

// $item->getMenu() === $menu  => TRUE!
```
