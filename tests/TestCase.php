<?php

declare(strict_types=1);

namespace Carlin\TranslateDrives\Tests;

use Carlin\TranslateDrives\Supports\Provider;
use Carlin\TranslateDrives\TranslateManager;

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
			'drives' => [
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
