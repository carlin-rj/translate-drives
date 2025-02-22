<?php

namespace Carlin\TranslateDrives\Providers;

use Carlin\TranslateDrives\Exceptions\TranslateException;
use Carlin\TranslateDrives\Supports\LangCode;
use Carlin\TranslateDrives\Supports\Translate;
use GuzzleHttp\Client;
use Throwable;

/**
 * Class BaiduProvider.
 *
 * @see http://api.fanyi.baidu.com/api/trans/product/apidoc
 */
class BaiduProvider extends AbstractProvider
{
	public const HTTP_URL = 'http://api.fanyi.baidu.com/api/trans/vip/translate';

    public const HTTPS_URL = 'https://fanyi-api.baidu.com/api/trans/vip/translate';

	protected array $langMap = [
		LangCode::JA => 'jp', // 日语
		LangCode::KO => 'kor', // 韩语
		LangCode::FR => 'fra', // 法语
		LangCode::ES => 'spa', // 西班牙语
		LangCode::ZH_TW => 'cht', // 繁体中文
		LangCode::VI => 'vie', // 越南语
	];


	/**
	 * @param string $q
	 * @param string $from
	 * @param string $to
	 * @return array
	 * @throws \Exception
	 * @author: whj
	 * @date: 2023/4/10 13:39
	 */
    protected function getRequestParams(string $q, string $from = LangCode::AUTO, string $to = LangCode::EN): array
	{
		$to = $this->langMap($to);
		$from = $this->langMap($from);

        $params = [
            'q' => $q,
            'from' => $from,
            'to' => $to,
            'appid' => $this->appId,
            'salt' => random_int(10000, 99999)
        ];

        $params['sign'] = $this->makeSignature($params);

        return $params;
    }

    protected function makeSignature(array $params): string
	{
        return md5($this->appId.$params['q'].$params['salt'].$this->appKey);
    }

	/**
	 * @param string $query
	 * @param string $from
	 * @param string $to
	 *
	 * @return Translate
	 *
	 * @throws TranslateException
	 * @see https://fanyi-api.baidu.com/api/trans/vip/translate
	 */
	protected function handlerTranslate(string $query, string $to = LangCode::EN, string $from = LangCode::AUTO): Translate
	{
		//请求
		$params = $this->getRequestParams($query, $from, $to);

		try {
			// 配置请求参数
			// 请求登录接口
			$client = new Client();
			// 配置请求参数
			$response = $client->request('POST', $this->getTranslateUrl(), [
				'form_params' => $params,
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
			]);
			// 处理响应
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);
		}catch (Throwable $e) {
			throw new TranslateException($e->getMessage());
		}
		return new Translate($this->mapTranslateResult($data));
    }

	/**
	 * @param array $translateResult
	 * @return array
	 * @throws TranslateException
	 * @author: whj
	 * @date: 2023/4/10 13:15
	 */
    protected function mapTranslateResult(array $translateResult): array
	{
		if ($this->isErrorResponse($translateResult)) {
		    $this->handleErrorResponse($translateResult);
		}

        return [
            'src' => reset($translateResult['trans_result'])['src'],
            'dst' => reset($translateResult['trans_result'])['dst'],
            'original' => $translateResult,
        ];
    }

    public function isErrorResponse(array $data): bool
    {
        return !empty($data['error_code']);
    }

    public function handleErrorResponse(array $data = []): void
	{
        throw new TranslateException("请求接口错误，错误信息：{$data['error_msg']}", $data['error_code']);
    }
}
