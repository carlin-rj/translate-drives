<?php

namespace Carlin\TranslateDrives\Providers;

use Carlin\TranslateDrives\Exceptions\TranslateException;
use Carlin\TranslateDrives\Supports\LangCode;
use Carlin\TranslateDrives\Supports\Translate;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;

class GoogleProvider extends AbstractProvider
{
	protected array $langMap = [
		LangCode::ZH_TW => 'zh-TW',
	];

	protected function handlerTranslate(string $query, string $to = LangCode::EN, string $from = LangCode::AUTO): Translate
    {
		try {
			$to = $this->langMap($to);
			$from = $this->langMap($from);
			$data = GoogleTranslate::trans($query, $to, $from === LangCode::AUTO ? null : $from);
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
