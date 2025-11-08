# Customer modul

Modul ügyféladatok nyilvántartására

## Előfeltételek

Telepíteni kell a következő modulokat.:
- https://gitlab.com/molitor/address

## Telepítés

### Provider regisztrálása
config/app.php
```php
'providers' => ServiceProvider::defaultProviders()->merge([
    /*
    * Package Service Providers...
    */
    \Molitor\Customer\Providers\CustomerServiceProvider::class,
])->toArray(),
```

### Menüpont megjelenítése az admin menüben

Ma a Menü modul telepítve van akkor meg lehet jeleníteni az admin menüben.
```php
<?php
//Menü builderek listája:
return [
    \Molitor\Customer\Services\Menu\CustomerMenuBuilder::class
];
```

### Menüpont megjelenítése az admin menüben

Ma a Menü modul telepítve van akkor meg lehet jeleníteni az admin menüben.

```php
<?php
//Menü builderek listája:
return [
    \Molitor\Customer\Services\Menu\CustomerMenuBuilder::class
];
```

### Breadcrumb telepítése

A modul breadcrumbs.php fileját regisztrálni kell a configs/breadcrumbs.php fileban.
```php
<?php
'files' => [
    base_path('/vendor/molitor/customer/src/routes/breadcrumbs.php'),
],
```
