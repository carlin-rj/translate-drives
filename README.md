## Baidu, Alibaba Cloud Translate, and Google Translate driver package, ready to use out of the box

[**简体中文 🇨🇳**](README_cn.md)

### Translate driver package usage tutorial
#### Environmental requirements
- `PHP` >= 8.1

## install
```
composer require carlin/translate-drivers
```

## Usage
### Baidu

```php
use Carlin\TranslateDrivers\Supports\Provider;
$config = [
    'drivers' => [
        Provider::BAIDU => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];
$manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::BAIDU)->translate($query);
```


### Google

```php
use Carlin\TranslateDrivers\Supports\Provider;

$config = [
    'drivers' => [
        Provider::GOOGLE => [],
    ],
];
$manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::GOOGLE)->translate($query);
```

### Alibaba cloud

```php
use Carlin\TranslateDrivers\Supports\Provider;
$config = [
    'drivers' => [
        Provider::ALIBABA_CLOUD => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];
$manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::ALIBABA_CLOUD)->translate($query);
```

## Custom driver
```php

class MyTranslateDriver extends AbstractProvider
{
    public function __construct(?string $app_id = null, ?string $app_key = null, array $config = [])
    {
        parent::__construct($app_id, $app_key, $config);
        
        //you code
    }

    protected function handlerTranslate(string $query, string $from = LangCode::Auto, string $to = LangCode::EN): Translate
    {
        //you translation code
        return new Translate([
            'src'=>'',
            'dst'=>'',
        ]);
    }
    protected function mapTranslateResult(array $translateResult): array
    {
        //you translate Result code
        return [

        ];
    }
}

$config = [
    'drivers' => [
        'my_driver' => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];

$manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->extend('my_driver', function ($allConfig) {
    $config = $allConfig['drivers']['my_driver'] ?? [];
    //you configuration code
    return new MyTranslateDriver(config:$config);
})->driver('my_driver')->translate($query);
```
