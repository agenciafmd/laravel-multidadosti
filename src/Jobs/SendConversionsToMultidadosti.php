<?php

namespace Agenciafmd\Multidadosti\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\Cache;
use Monolog\Logger;

class SendConversionsToMultidadosti implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function handle()
    {
        if (! config('laravel-multidadosti.public_api_url')) {
            return false;
        }

        $client = $this->getClientRequest();
        $endpoint = config('laravel-multidadosti.public_api_url');
        $token = config('laravel-multidadosti.authorization_token');

        $response = $client->request('POST', $endpoint, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $token
            ],
            'json' => $this->data
        ]);
    }

    private function getClientRequest()
    {
        $logger = new Logger('Multidadosti');
        $logger->pushHandler(new StreamHandler(storage_path('logs/multidadosti-' . date('Y-m-d') . '.log')));

        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $logger,
                new MessageFormatter("{method} {uri} HTTP/{version} {req_body} | RESPONSE: {code} - {res_body}")
            )
        );

        return new Client([
            'timeout' => 60,
            'connect_timeout' => 60,
            'http_errors' => false,
            'verify' => false,
            'handler' => $stack,
        ]);
    }
}
