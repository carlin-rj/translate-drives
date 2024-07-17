<?php

namespace Carlin\TranslateDrivers;

use Carlin\TranslateDrivers\Contracts\ProviderInterface;
use Carlin\TranslateDrivers\Providers\AbstractProvider;
use Carlin\TranslateDrivers\Providers\AlibabaProvider;
use Carlin\TranslateDrivers\Providers\BaiduProvider;
use Carlin\TranslateDrivers\Providers\GoogleProvider;
use Carlin\TranslateDrivers\Supports\Config;
use Closure;
use InvalidArgumentException;
use RuntimeException;

class TranslateManager
{
    /**
     * The configuration.
     */
    protected Config $config;

    /**
     * The registered custom driver creators.
     */
    protected array $customCreators = [];

    /**
     * The initial drivers.
     */
    protected array $initialDrivers = [
		'baidu'   => BaiduProvider::class,
		'google'  => GoogleProvider::class,
		'alibaba' => AlibabaProvider::class,
    ];

    protected $defaultDriver;

    /**
     * The array of created "drivers".
     *
     * @var ProviderInterface[]
     */
    protected array $drivers = [];

    /**
     * TranslateManager constructor.
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        if (! empty($config['default'])) {
            $this->setDefaultDriver($config['default']);
        }
    }

    /**
     * Set config instance.
     *
     * @return $this
     */
    public function config(Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get a driver instance.
     */
    public function driver(string $driver = null): AbstractProvider
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if (! isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->createDriver($driver);
        }

        return $this->drivers[$driver];
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
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    /**
     * Get all of the created "drivers".
     *
     * @return AbstractProvider[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
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
    public function formatConfig(array $driverConfig): array
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
    protected function createDriver(string $driver): AbstractProvider
    {
        if (isset($this->initialDrivers[$driver])) {
            $provider = $this->initialDrivers[$driver];
            return $this->buildProvider($provider, $this->formatConfig(
                $this->config['drivers'][$driver] ?? []
            ));
        }

        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driver);
        }

        throw new InvalidArgumentException("Driver [{$driver}] not supported.");
    }

    /**
     * Call a custom driver creator.
     */
    protected function callCustomCreator(string $driver): AbstractProvider
    {
        return $this->customCreators[$driver]($this->config);
    }
}