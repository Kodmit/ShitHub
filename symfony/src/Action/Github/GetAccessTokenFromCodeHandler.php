<?php
namespace App\Action\Github;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetAccessTokenFromCodeHandler implements MessageHandlerInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function __invoke(GetAccessTokenFromCode $command)
    {
        $code = $command->getCode();
        $apiRouteUrl = $_ENV['GITHUB_TOKEN_URI'];

        $request = [
            'json' => [
                'code' =>$code,
                'accept' => 'json',
                'client_id' => $_ENV['CLIENT_ID'],
                'client_secret' => $_ENV['CLIENT_SECRET']
            ]
        ];
        try {
            $response = $this->httpClient->request('POST', $apiRouteUrl, $request);
        } catch (\Exception $e) {
            dump($e);
        }

        if (200 !== $response->getStatusCode()) {
            throw new \Exception(sprintf('error %s during call', $response->getStatusCode()));
        }

        return $response->getContent();
    }
}