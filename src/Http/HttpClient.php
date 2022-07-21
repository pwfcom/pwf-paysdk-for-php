<?php
namespace Pwf\PaySDK\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;


class HttpClient{


    /**
     * @throws GuzzleException
     *
     * @return HttpResponse
     */
    public static function send($request)
    {
        $request = $request->getPsrRequest();

        $config = [
        	'http_errors' => false,
            "connectTimeout" => 15000,
            "readTimeout" => 15000,
            'timeout' => 30,
            "retry" => [
                "maxAttempts" => 0
            ]
        ];

        $res = self::client()->send(
            $request,
            $config
        );

        return new HttpResponse($res);
    }

    /**
     * @return Client
     */
    public static function client(array $config = [])
    {

        $stack = HandlerStack::create();
        $stack->push(Middleware::mapResponse(static function (ResponseInterface $response) {
            return new HttpResponse($response);
        }));

        $config = [
        	'handler' => $stack,
        	'on_stats' => function (TransferStats $stats) {
	            HttpResponse::$info = $stats->getHandlerStats();
	        }
        ];

        return new Client($config);
    }

    /**
     * @param string              $method
     * @param string|UriInterface $uri
     * @param array               $options
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    public static function request($method, $uri, $options = [])
    {
        return self::client()->request($method, $uri, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @throws GuzzleException
     *
     * @return string
     */
    public static function string($method, $uri, $options = [])
    {
        return (string) self::client()->request($method, $uri, $options)
            ->getBody();
    }

    /**
     * @param string|UriInterface $uri
     * @param array               $options
     *
     * @throws GuzzleException
     *
     * @return null|mixed
     */
    public static function getHeaders($uri, $options = [])
    {
        return self::request('HEAD', $uri, $options)->getHeaders();
    }

    /**
     * @param string|UriInterface $uri
     * @param string              $key
     * @param null|mixed          $default
     *
     * @throws GuzzleException
     *
     * @return null|mixed
     */
    public static function getHeader($uri, $key, $default = null)
    {
        $headers = self::getHeaders($uri);

        return isset($headers[$key][0]) ? $headers[$key][0] : $default;
    }


}
