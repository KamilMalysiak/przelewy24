<?php
/**
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

/**
 * Class Przelewy24RestAbstract
 */
class Przelewy24RestAbstract
{

    const URL_PRODUCTION = 'https://secure.przelewy24.pl/api/v1';
    const URL_TEST = 'https://sandbox.przelewy24.pl/api/v1';

    /**
     * Shop id.
     *
     * @var int|null
     */
    protected $shopId;

    /**
     * Api key.
     *
     * @var string|null
     */
    protected $apiKey;

    /**
     * Url.
     *
     * @var string|null
     */
    protected $url;

    /**
     * Salt.
     *
     * @var string|null
     */
    protected $salt;

    /**
     * Przelewy24RestAbstract constructor.
     * @param int $shopId
     * @param string $apiKey
     * @param string $salt
     * @param bool $isTest
     */
    public function __construct($shopId, $apiKey, $salt, $isTest)
    {
        $this->shopId = (int)$shopId;
        $this->apiKey = (string)$apiKey;
        $this->salt = (string)$salt;
        if ($isTest) {
            $this->url = self::URL_TEST;
        } else {
            $this->url = self::URL_PRODUCTION;
        }
    }

    /**
     * Call rest command
     *
     * @param string $path
     * @param array|object $payload
     * @return string
     */
    protected function call($path, $payload, $method)
    {
        $headers = array(
            'Content-Type: application/json',
        );
        $options = array(
            CURLOPT_USERPWD => $this->shopId . ':' . $this->apiKey,
            CURLOPT_URL => $this->url . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => $headers,
        );
        if ('PUT' === $method) {
            $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
        }

        $h = curl_init();
        curl_setopt_array($h, $options);
        $ret = curl_exec($h);
        curl_close($h);

        $decoded = json_decode($ret, true);
        if (!is_array($decoded)) {
            $decoded = array();
        }

        return $decoded;
    }
}
