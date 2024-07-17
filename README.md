## Baidu, Alibaba Cloud Translate, and Google Translate driver package, ready to use out of the box

### Translate driver package usage tutorial
#### 环境要求
- `PHP` >= 8.0

#### 安装
```
composer require carlin/translate-drivers
```

### Baidu

```php

$config = [
    // 驱动
    'drivers' => [
        'baidu' => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];
$this->manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver('baidu')->translate($query);
```


### Goolge

```php

$config = [
    // 驱动
    'drivers' => [
        'google' => [],
    ],
];
$this->manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver('google')->translate($query);
```

### Alibaba

```php

$config = [
    // 驱动
    'drivers' => [
        'alibaba' => [
            'app_id'  => 'xxx',
            'app_key' => 'xxx',
        ],
    ],
];
$this->manager = new TranslateManager($config);
$query = '我喜欢你的冷态度 :test';
$res = $manager->driver('alibaba')->translate($query);
```
