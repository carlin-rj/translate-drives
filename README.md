## Baidu, Alibaba Cloud Translate, and Google Translate driver package, ready to use out of the box

[**简体中文 🇨🇳**](README_cn.md)

### Translate driver package usage tutorial
#### Environmental requirements
- `PHP` >= 8.0

## install
```
composer require carlin/translate-drivers
```

## Usage
### Baidu

```php
use Carlin\TranslateDrivers\TranslateManager;
use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\Supports\LangCode;

$configs = [
    'drivers' => [
        Provider::BAIDU => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];
$manager = new TranslateManager($configs);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::BAIDU)->translate($query, LangCode::EN);
//or
TranslateManager::baidu($you_config = [])->translate($query, LangCode::EN);

$res->getDst(); //translate text
$res->getSrc(); //origin text
$res->getOriginal(); //original result


//Simple configuration


```


### Google

```php
use Carlin\TranslateDrivers\TranslateManager;
use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\Supports\LangCode;

$configs = [
    'drivers' => [
        Provider::GOOGLE => [],
    ],
];
$manager = new TranslateManager($configs);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::GOOGLE)->translate($query, LangCode::EN);
//or
$res = TranslateManager::google()->translate($query, LangCode::EN);
```

### Alibaba cloud

```php
use Carlin\TranslateDrivers\TranslateManager;
use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\Supports\LangCode;

$configs = [
    'drivers' => [
        Provider::ALIBABA_CLOUD => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];
$manager = new TranslateManager($configs);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::ALIBABA_CLOUD)->translate($query, LangCode::EN);
//or
$res = TranslateManager::alibabaCloud($you_config = [])->translate($query, LangCode::EN);
```

## Custom driver
```php

use Carlin\TranslateDrivers\Providers\AbstractProvider;
use Carlin\TranslateDrivers\TranslateManager;
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

$configs = [
    'drivers' => [
        'my_driver' => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];

$manager = new TranslateManager($configs);
$query = '我喜欢你的冷态度 :test';
$res = $manager->extend('my_driver', function ($configs) {
    $config = $configs['drivers']['my_driver'] ?? [];
    //you configuration code
    return new MyTranslateDriver(config:$config);
})->driver('my_driver')->translate($query);
```

## Preserving Parameters

The ```preserveParameters()``` method allows you to preserve certain parameters in strings while performing translations. This is particularly useful when dealing with localization files or templating engines where specific placeholders need to be excluded from translation.

Default regex is ```/:(\w+)/``` which covers parameters starting with :. Useful for translating language files of Laravel and other frameworks. You can also pass your custom regex to modify the parameter syntax.
```php
use Carlin\TranslateDrivers\TranslateManager;
use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\Supports\LangCode;

$configs = [
    'drivers' => [
        Provider::GOOGLE => [],
    ],
];
$manager = new TranslateManager($configs);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver(Provider::GOOGLE)->preserveParameters()->translate($query, LangCode::EN); //I like your cold attitude :test
```

Or use custom regex:

```php
use Carlin\TranslateDrivers\TranslateManager;
use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\Supports\LangCode;


$configs = [
    'drivers' => [
        Provider::GOOGLE => [],
    ],
];
$manager = new TranslateManager($configs);
$query = '我喜欢你的冷态度 {{test}}';
$res = $manager->driver(Provider::GOOGLE)->preserveParameters('/\{\{([^}]+)\}\}/')->translate($query, LangCode::EN); //I like your cold attitude :test
```

## If you have a better translation driver, please feel free to submit a PR
