<?php

namespace Carlin\TranslateDrivers\Contracts;

use Carlin\TranslateDrivers\Supports\LangCode;
use Carlin\TranslateDrivers\Supports\Translate;

/**
 * Interface ProviderInterface.
 */
interface ProviderInterface
{
    /**
     * Translate giving string from.
     *
     * @param string $query
     * @param string $from
     * @param string $to
     *
     * @return mixed
     */
    public function translate(string $query,  string $from = LangCode::Auto, string $to = LangCode::EN):Translate;
}
