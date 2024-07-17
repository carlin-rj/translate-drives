<?php

namespace Carlin\TranslateDrivers\Tests\Feature;

use Carlin\TranslateDrivers\Providers\AbstractProvider;
use Carlin\TranslateDrivers\Supports\Config;
use Carlin\TranslateDrivers\Supports\LangCode;
use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\Supports\Translate;
use Carlin\TranslateDrivers\Tests\TestCase;

class TranslateManagerTest extends TestCase
{

	public function testGoogle(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->driver(Provider::GOOGLE)->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}


	public function testBaidu(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->driver(Provider::BAIDU)->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}


	public function testAlibabaCloud(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->driver(Provider::ALIBABA_CLOUD)->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}

	public function testDefaultDriver()
	{
		$query = '我喜欢你的冷态度 :test';
		$config = $this->getConfig();
		$config['default'] = Provider::GOOGLE;
		$res = $this->manager->config($config)->driver()->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}

	public function testExtend(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->extend('my_driver', function ($config) {
			return new class extends AbstractProvider
			{
				public function __construct(?string $app_id = null, ?string $app_key = null, array $config = [])
				{
					parent::__construct($app_id, $app_key, $config);
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
					return [

					];
				}
			};
		})->driver('my_driver')->translate($query);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}
}
