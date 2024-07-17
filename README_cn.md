## 百度、阿里云翻译和免费的谷歌翻译驱动程序包，开箱即用
[**English 🇺🇸**](README.md)
### 翻译驱动程序包使用教程
#### 环境要求
- `PHP` >= 8.1

#### install
```
composer require carlin/translate-drivers
```

### 百度

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

### 阿里云翻译

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

## 自定义驱动
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
