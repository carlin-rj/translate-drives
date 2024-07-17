<?php

namespace Carlin\TranslateDrivers\Providers;

use Carlin\TranslateDrivers\Exceptions\TranslateException;
use Carlin\TranslateDrivers\Supports\LangCode;
use Carlin\TranslateDrivers\Supports\Translate;
use GuzzleHttp\Client;

class AlibabaCloudProvider extends AbstractProvider
{
    public const HTTPS_URL = 'https://mt.cn-hangzhou.aliyuncs.com';
    public const HTTP_URL = 'http://mt.cn-hangzhou.aliyuncs.com';

	protected function handlerTranslate(string $query, $from = LangCode::Auto, $to = LangCode::EN): Translate
    {
        $params = $this->getRequestParams($query, $from, $to);
		try {
			// 配置请求参数
			// 请求登录接口
			$client = new Client();
			// 配置请求参数
			$response = $client->request('GET', $this->getTranslateUrl(), [
				'query' => $params,
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
			]);
			// 处理响应
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);
		}catch (\Throwable $e) {
			throw new TranslateException($e->getMessage());
		}
        if ($this->isErrorResponse($data)) {
            $this->handleErrorResponse($data);
        }
        return new Translate($this->mapTranslateResult([
            'src' => $query,
            'dst' => $data['Data']['Translated'],
        ]));
    }

    protected function getRequestParams(string $q, string $from, string $to): array
    {
        $params = [
            'Action' => 'TranslateGeneral',
            'Format' => 'json',
            'Version' => '2018-10-12',
            'Timestamp' => $this->timestamp(),
            'SignatureNonce' => $this->getNonce(),
            'SignatureMethod' => 'HMAC-SHA1',
            'SignatureVersion' => '1.0',
            'AccessKeyId' => $this->appId,
            'FormatType' => 'text',
            'Scene' => 'general',
            'SourceLanguage' => $from,
            'SourceText' => $q,
            'TargetLanguage' => $to,
        ];
        $params["Signature"] = $this->getRPCSignature($params, $this->appKey);
        return $params;
    }

    private function timestamp(): string
    {
        return gmdate('Y-m-d\\TH:i:s\\Z');
    }

    private function getNonce(): string
    {
        return md5(uniqid('', true) . uniqid(md5(microtime(true)), true));
    }

    private function getRPCSignature(array $signedParams, string $secret): ?string
    {
        $secret .= '&';
        $strToSign = $this->getRpcStrToSign($signedParams);

        $signMethod = 'HMAC-SHA1';

        return $this->encodeSign($signMethod, $strToSign, $secret);
    }

    private function getRpcStrToSign($query): string
    {
        ksort($query);

        $params = [];
        foreach ($query as $k => $v) {
            if (null !== $v) {
                $k = rawurlencode($k);
                $v = rawurlencode($v);
                $params[] = $k . '=' . $v;
            }
        }
        $str = implode('&', $params);

        return 'GET' . '&' . rawurlencode('/') . '&' . rawurlencode($str);
    }

    private function encodeSign($signMethod, $strToSign, $secret): ?string
    {
        return match ($signMethod) {
            'HMAC-SHA256' => base64_encode(hash_hmac('sha256', $strToSign, $secret, true)),
            default => base64_encode(hash_hmac('sha1', $strToSign, $secret, true)),
        };
    }

    private function isErrorResponse($data): bool
    {
        return !isset($data['Code']) || ((int)$data['Code']) !== 200;
    }

    /**
     * @throws TranslateException
     */
    private function handleErrorResponse(array $data): void
    {
        throw new TranslateException("请求接口错误，错误信息：" . ($data['Message'] ?? ''), $data['Code']);
    }

    protected function mapTranslateResult(array $translateResult): array
    {
        $translateResult['original'] = $translateResult;
        return $translateResult;
    }
}
