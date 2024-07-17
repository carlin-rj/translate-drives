<?php

declare(strict_types=1);

namespace Carlin\TranslateDrivers\Tests;

use Carlin\TranslateDrivers\TranslateManager;

/**
 * @internal
 *
 * @coversNothing
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
	protected ?TranslateManager $manager = null;

    protected function setUp(): void
    {
		$config = [
			// 驱动
			'drivers' => [
				// 免费版
				'google' => [

				],
				'baidu' => [
					'app_id'  => '',
					'app_key' => '',
				],
				'alibaba' => [
					'app_id'  => '',
					'app_key' => '',
				],
			],
		];
		$this->manager = new TranslateManager($config);
    }

    protected function tearDown(): void
    {
		$this->manager = null;
    }
}
