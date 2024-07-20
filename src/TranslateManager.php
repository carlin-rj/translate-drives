<?php

namespace Carlin\TranslateDrives;

use Carlin\TranslateDrives\Contracts\ProviderInterface;
use Carlin\TranslateDrives\Providers\AbstractProvider;
use Carlin\TranslateDrives\Providers\AlibabaCloudProvider;
use Carlin\TranslateDrives\Providers\BaiduProvider;
use Carlin\TranslateDrives\Providers\GoogleProvider;
use Carlin\TranslateDrives\Supports\Config;
use Carlin\TranslateDrives\Supports\Provider;
use Closure;
use InvalidArgumentException;
use RuntimeException;

/**
 * @method static BaiduProvider baidu(?array $config = null)
 * @method static GoogleProvider google(?array $config = null)
 * @method static AlibabaCloudProvider alibabaCloud(?array $config = null)
 */
class TranslateManager
{
    /**
     * The configuration.
     */
    protected Config $config;

    /**
     * The registered custom driver creators.
     */
    protected array $customDrives = [];

    /**
     * The initial drives.
     */
    protected array $initialDrives = [
		Provider::BAIDU         => BaiduProvider::class,
		Provider::GOOGLE        => GoogleProvider::class,
		Provider::ALIBABA_CLOUD => AlibabaCloudProvider::class,
    ];

    protected $defaultDriver;

    /**
     * The array of created "drives".
     *
     * @var ProviderInterface[]
     */
    protected array $drives = [];

    /**
     * TranslateManager constructor.
     */
    public function __construct(array $configs = [])
    {
		$this->config($configs);
    }

    /**
     * Set config instance.
     *
     * @return $this
     */
    public function config(array $config): self
    {
		$this->config = new Config($config);

		if (! empty($config['default'])) {
			$this->setDefaultDriver($config['default']);
		}

        return $this;
    }

    /**
     * Get a driver instance.
     */
    public function driver(?string $driver = null, ?array $config = null): AbstractProvider
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if (! isset($this->drives[$driver])) {
            $this->drives[$driver] = $this->createDriver($driver, $config);
        }

        return $this->drives[$driver];
    }

    public function getDefaultDriver()
    {
        if (empty($this->defaultDriver)) {
            throw new RuntimeException('Please set default driver');
        }

        return $this->defaultDriver;
    }

    public function setDefaultDriver($driver): self
    {
        $this->defaultDriver = $driver;

        return $this;
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @return $this
     */
    public function extend(string $driver, Closure $callback): self
    {
        $this->customDrives[$driver] = $callback;

        return $this;
    }

    /**
     * Get all of the created "drives".
     *
     * @return AbstractProvider[]
     */
    public function getDrives(): array
    {
        return $this->drives;
    }

    /**
     * Build an Translate provider instance.
     */
    public function buildProvider(string $provider, array $config = []): AbstractProvider
    {
        return new $provider(
            $config['app_id'] ?? null,
            $config['app_key'] ?? null,
            $config,
        );
    }

    /**
     * Format the server configuration.
     */
	protected function formatConfig(array $driverConfig): array
    {
        return array_merge([
            'app_id' => $driverConfig['app_id'] ?? '',
            'app_key' => $driverConfig['app_key'] ?? '',
        ], $driverConfig);
    }

    /**
     * Create a new driver instance.
     *
     * @throws InvalidArgumentException
     */
    protected function createDriver(string $driver, ?array $config = null): AbstractProvider
    {
        if (isset($this->initialDrives[$driver])) {
            $provider = $this->initialDrives[$driver];
            return $this->buildProvider($provider, $this->formatConfig(
				$config ?? $this->config['drives'][$driver] ?? []
            ));
        }

        if (isset($this->customDrives[$driver])) {
            return $this->callCustomDriver($driver);
        }

        throw new InvalidArgumentException("Driver [{$driver}] not supported.");
    }

    /**
     * Call a custom driver creator.
     */
    protected function callCustomDriver(string $driver): AbstractProvider
    {
        return $this->customDrives[$driver]($this->config->toArray());
    }

	public function __call($service, $config): AbstractProvider
	{
		return $this->driver($service, $config[0] ?? null);
	}

	public static function __callStatic($driver, $config): AbstractProvider
	{
		return (new self())->driver($driver, $config[0] ?? null);
	}
}
