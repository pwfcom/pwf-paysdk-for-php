<?php
namespace Pwf\PaySDK\Http;

use GuzzleHttp\Psr7\Response as PsrResponse;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpResponse extends PsrResponse
{
    public $headers = [];
    public $statusCode;
    public $statusMessage = '';

    /**
     * @var TransferStats
     */
    public static $info;

    /**
     * @var StreamInterface
     */
    public $body;

    /**
     * Response constructor.
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
        $this->headers    = $response->getHeaders();
        $this->body       = $response->getBody();
        $this->statusCode = $response->getStatusCode();
        if ($this->body->isSeekable()) {
            $this->body->seek(0);
        }

    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getBody();
    }

    public function __get($name)
    {
        $arr = $this->toArray();
        return isset($arr[$name]) ? $arr[$name] : null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return \GuzzleHttp\json_decode((string) $this->getBody(), true);
    }


}