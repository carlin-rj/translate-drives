<?php

namespace Carlin\TranslateDrivers\Providers;

use Carlin\TranslateDrivers\Exceptions\TranslateException;
use Carlin\TranslateDrivers\Supports\LangCode;
use Carlin\TranslateDrivers\Supports\Translate;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;

class GoogleProvider extends AbstractProvider
{
	protected function handlerTranslate(string $query, $from = LangCode::Auto, $to = LangCode::EN): Translate
    {
		try {
			$data = GoogleTranslate::trans($query, $to, $from === LangCode::Auto ? null : $from);
		}catch (Throwable $exception) {
			throw new TranslateException($exception->getMessage());
		}

        return new Translate($this->mapTranslateResult([
            'src' => $query,
            'dst' => $data
        ]));
    }

    protected function mapTranslateResult(array $translateResult): array
    {
        $translateResult['original'] = $translateResult;
        return $translateResult;
    }
}
