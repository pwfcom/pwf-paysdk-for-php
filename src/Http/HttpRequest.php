<?php
namespace Pwf\PaySDK\Http;


use GuzzleHttp\Psr7\Request as PsrRequest;
use GuzzleHttp\Psr7\Uri;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;

class HttpRequest extends PsrRequest
{

    /**
     * @var string
     */
    public $api_uri = '';

    /**
     * @var string
     */
    public $pathname = '/';

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var array
     */
    public $query = [];

    /**
     * @var string
     */
    public $body;

    /**
     * @var int
     */
    public $port;

    public $method;

    public function __construct($method = 'GET', $uri = '', array $headers = [], $body = null, $version = '1.1')
    {
        parent::__construct($method, $uri, $headers, $body, $version);
        $this->method = $method;
    }

    public function getPsrRequest()
    {
        $this->assertQuery($this->query);

        $request = clone $this;

        $uri = new Uri($this->api_uri);
        if ($this->pathname) {
            $uri = $uri->withPath($this->pathname);
        }

        $request = $request->withUri($uri);
        $request = $request->withMethod($this->method);

        if ('' !== $this->body && null !== $this->body) {
            if ($this->body instanceof StreamInterface) {
                $request = $request->withBody($this->body);
            } else {
                if (\function_exists('\GuzzleHttp\Psr7\stream_for')) {
                    // @deprecated stream_for will be removed in guzzlehttp/psr7:2.0
                    $request = $request->withBody(\GuzzleHttp\Psr7\stream_for($this->body));
                } else {
                    $request = $request->withBody(\GuzzleHttp\Psr7\Utils::streamFor($this->body));
                }
            }
        }

        if ($this->headers) {
            foreach ($this->headers as $key => $value) {
                $request = $request->withHeader($key, $value);
            }
        }

        return $request;
    }

    /**
     * @param array $query
     */
    private function assertQuery($query)
    {
        if (!\is_array($query) && $query !== null) {
            throw new InvalidArgumentException('Query must be array.');
        }
    }
}

