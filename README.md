## Baidu, Alibaba Cloud Translate, and Google Translate driver package, ready to use out of the box

[**简体中文 🇨🇳**](README_cn.md)

### Translate driver package usage tutorial
#### Environmental requirements
- `PHP` >= 8.1

#### install
```
composer require carlin/translate-drivers
```

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
$this->manager = new TranslateManager($config);
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
$this->manager = new TranslateManager($config);
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
$this->manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::ALIBABA_CLOUD)->translate($query);
```
