<?php

declare(strict_types=1);

namespace Carlin\TranslateDrivers\Tests;

use Carlin\TranslateDrivers\Supports\Provider;
use Carlin\TranslateDrivers\TranslateManager;

/**
 * @internal
 *
 * @coversNothing
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
	protected ?TranslateManager $manager = null;

	protected function getConfig(): array
	{
		return [
			// 驱动
			'drivers' => [
				// 免费版
				Provider::GOOGLE => [

				],
				Provider::BAIDU => [
					'app_id'  => '',
					'app_key' => '',
				],
				Provider::ALIBABA_CLOUD => [
					'app_id'  => '',
					'app_key' => '',
				],
			],
		];
	}

    protected function setUp(): void
    {
		$this->manager = new TranslateManager($this->getConfig());
    }

    protected function tearDown(): void
    {
		$this->manager = null;
    }
}
