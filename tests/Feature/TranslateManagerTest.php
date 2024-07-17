<?php

namespace Carlin\TranslateDrivers\Tests\Feature;

use Carlin\TranslateDrivers\Supports\Translate;
use Carlin\TranslateDrivers\Tests\TestCase;

class TranslateManagerTest extends TestCase
{

	public function testGoogle(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->driver('google')->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}


	public function testBaidu(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->driver('baidu')->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}


	public function testAlibaba(): void
	{
		$query = '我喜欢你的冷态度 :test';
		$res = $this->manager->driver('alibaba')->translate($query);
		$this->assertIsObject($res);
		$this->assertInstanceOf(Translate::class, $res);
		echo $res->getDst();
	}
}
