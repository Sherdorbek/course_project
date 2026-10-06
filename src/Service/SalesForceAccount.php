<?php

namespace App\Service;

use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SalesForceAccount
{
  public function __construct(
    #[Autowire(env: 'SALESFORCE_CLIENT_URL')]
    private string $url,
    #[Autowire(env: 'SALESFORCE_CLIENT_ID')]
    private string $clientId,
    #[Autowire(env: 'SALESFORCE_CLIENT_SECRET')]
    private string $clientSecret,
    private HttpClientInterface $client,
    private CacheInterface $cache
  ) {}

  public function add(array $data): array
  {
    $accessToken = $this->getAccessToken();

    $response = $this->client->request(
      'POST',
      $this->url . '/services/data/latest/sobjects/Account/',
      [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
          'Content-Type' => 'application/json'
        ],

        'body' =>  json_encode([
          'Name' => $data['firstname'].' '.$data['lastname'],
        ]),
      ]
    );

    $id = $response->toArray()['id'];

    $response = $this->client->request(
      'POST',
      $this->url . '/services/data/latest/sobjects/Contact/',
      [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken,
          'Content-Type' => 'application/json'
        ],

        'body' =>  json_encode([
          'AccountId' => $id,
          'FirstName' => $data['firstname'],
          'LastName' => $data['lastname'],
          'Email' => $data['email'],
          'Phone' => $data['phoneNumber'],
          'MailingStreet' => $data['location'],
          'Birthdate' => $data['birthdate']->format('Y-m-d'),
          'HomePhone' => $data['homePhone'],
          'Description' => $data['description'],
          'Title' => $data['jobTitle'],
        ]),
      ]
    );
    
    return $response->toArray();
  }

  public function getAccessToken(): string
  {
    return $this->cache->get('salesforce_access_token', function (ItemInterface $item): string {
      $response = $this->client->request(
        'POST',
        $this->url . '/services/oauth2/token',
        [
          'body' => [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
          ]
        ]
      );

      $data = $response->toArray(false);
      if ($response->getStatusCode() !== 200) {
        throw new RuntimeException('Salesforce Authentication error');
      }

      $item->expiresAfter(900);
      return $data['access_token'];
    });
  }
}
