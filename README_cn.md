## 百度、阿里云翻译和免费的谷歌翻译驱动程序包，开箱即用
[**English 🇺🇸**](README.md)
### 翻译驱动程序包使用教程
#### 环境要求
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

### Alibaba

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
