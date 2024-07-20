<?php

namespace Carlin\TranslateDrives\Contracts;

use Carlin\TranslateDrives\Supports\LangCode;
use Carlin\TranslateDrives\Supports\Translate;

/**
 * Interface ProviderInterface.
 */
interface ProviderInterface
{
    /**
     * Translate giving string from.
     *
     * @param string $query
     * @param string $to
	 * @param string $from
	 * @return mixed
     */
    public function translate(string $query,  string $to = LangCode::EN, string $from = LangCode::AUTO):Translate;
}
