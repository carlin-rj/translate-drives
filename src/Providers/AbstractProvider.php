<?php

namespace Carlin\TranslateDrivers\Providers;

use Carlin\TranslateDrivers\Contracts\ProviderInterface;
use Carlin\TranslateDrivers\Supports\Config;
use Carlin\TranslateDrivers\Supports\LangCode;
use Carlin\TranslateDrivers\Supports\Translate;

/**
 * Class AbstractProvider.
 */
abstract class AbstractProvider implements ProviderInterface
{
    public const HTTP_URL = '';

    public const HTTPS_URL = '';

    /**
     * Provider name.
     *
     * @var string
     */
    protected string $name;

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * The app id.
     *
     * @var null|string
     */
    protected ?string $appId;

    /**
     * The app key.
     *
     * @var null|string
     */
    protected ?string $appKey;

	/*
 * @var string|null Regex pattern to match replaceable parts in a string, defualts to "words"
 */
	protected ?string $pattern = null;

    /**
     * AbstractProvider constructor.
     *
     * @param null|string $app_id
     * @param null|string $app_key
     * @param array  $config
     */
    public function __construct(?string $app_id = null, ?string $app_key = null, array $config = [])
    {
        $this->appId = $app_id;
        $this->appKey = $app_key;

        $this->config = new Config($config);
    }

    /**
     * @return string
     *
     * @throws \ReflectionException
     */
    public function getName(): string
	{
        if (empty($this->name)) {
            $this->name = strstr((new \ReflectionClass(get_class($this)))->getShortName(), 'Provider', true);
        }

        return $this->name;
    }

    /**
     * Get the translate URL for the provider.
     *
     * @return string
     */
    protected function getTranslateUrl(): string
	{
		return $this->config['url'] ?? (($this->config['ssl'] ?? false) ? static::HTTPS_URL : static::HTTP_URL);
	}

    abstract protected function handlerTranslate(string $query, string $from = LangCode::Auto, string $to = LangCode::EN):Translate;

	public function translate(string $query, string $from = LangCode::Auto, string $to = LangCode::EN):Translate
	{
		$replacements = $this->getParameters($query);

		$translate = $this->handlerTranslate($this->extractParameters($query), $from, $to);

		if ($this->pattern) {
		    return $translate;
		}
		$dst = $this->injectParameters($translate->getDst(), $replacements);
		return new Translate([
			...$translate->getOriginal(),
			...['dst'=>$dst]
		]);
	}

    /**
     * @param array $translateResult
     *
     * @return array
     */
    abstract protected function mapTranslateResult(array $translateResult): array;


	public function preserveParameters(bool|string $pattern = true): self
	{
		if ($pattern === true) {
			$this->pattern = '/:(\w+)/'; // Default regex
		} elseif ($pattern === false) {
			$this->pattern = null;
		} elseif (is_string($pattern)) {
			$this->pattern = $pattern;
		}

		return $this;
	}

	protected function getParameters(string $string): array
	{
		$matches = [];

		// If no pattern is set, return empty array
		if (!$this->pattern) {
			return $matches;
		}

		// Find all matches for the pattern in our string
		preg_match_all($this->pattern, $string, $matches);

		return $matches[0];
	}

	/**
	 * Extract replaceable keywords from string using the supplied pattern
	 *
	 * @param string $string
	 * @return string
	 */
	protected function extractParameters(string $string): string
	{
		// If no pattern, return string as is
		if (!$this->pattern) {
			return $string;
		}

		// Replace all matches of our pattern with #{\d} for replacement later
		return preg_replace_callback(
			$this->pattern,
			function ($matches) {
				static $index = -1;

				$index++;

				return '#{' . $index . '}';
			},
			$string
		);
	}

	/**
	 * Inject the replacements back into the translated string
	 *
	 * @param string $string
	 * @param array<string> $replacements
	 * @return string
	 */
	protected function injectParameters(string $string, array $replacements): string
	{
		// Remove space added in the parameters
		$string = preg_replace('/#\{\s*(\d+)\s*\}/', '#{$1}', $string);

		return preg_replace_callback(
			'/\#{(\d+)}/',
			fn($matches) => $replacements[$matches[1]],
			$string
		);
	}
}
