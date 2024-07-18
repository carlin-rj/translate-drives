## 百度、阿里云翻译和免费的谷歌翻译驱动程序包，开箱即用
[**English 🇺🇸**](README.md)
### 翻译驱动程序包使用教程
#### 环境要求
- `PHP` >= 8.0

## install
```
composer require carlin/translate-drivers
```

### 百度

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
$res = $manager->driver(Provider::BAIDU)->translate($query,  LangCode::EN);

//or
TranslateManager::baidu($you_config = [])->translate($query, LangCode::EN);

$res->getDst(); //translate text
$res->getSrc(); //origin text
$res->getOriginal(); //original result
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
$res = $manager->driver(Provider::GOOGLE)->translate($query,  LangCode::EN);

//Simpler calling
$res = TranslateManager::google()->translate($query, LangCode::EN);
```

### 阿里云翻译

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
$res = $manager->driver(Provider::ALIBABA_CLOUD)->translate($query,  LangCode::EN);

//Simpler calling
$res = TranslateManager::alibabaCloud($you_config = [])->translate($query, LangCode::EN);
```

## 自定义驱动
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

    protected function handlerTranslate(string $query, string $to = LangCode::EN, string $from = LangCode::AUTO): Translate
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

## 保留翻译占位参数

```preserveParameters()``` 方法允许您在执行翻译时保留字符串中的某些参数。这在处理需要从翻译中排除特定占位符的本地化文件或模板引擎时特别有用。

默认正则表达式是 ```/:(\w+)/``` ，它涵盖以 : 开头的参数。对于翻译 Laravel 和其他框架的语言文件很有用。您还可以传递自定义正则表达式来修改参数语法。
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

或者使用自定义正则表达式:

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
$res = $manager->driver(Provider::GOOGLE)->preserveParameters('/\{\{([^}]+)\}\}/')->translate($query, LangCode::EN); //I like your cold attitude {{test}}
```


## 如果您有更好的翻译驱动，欢迎提交 PR
