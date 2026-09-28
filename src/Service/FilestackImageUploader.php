<?php

namespace App\Service;

use Filestack\FilestackClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FilestackImageUploader
{
  public function __construct(
    #[Autowire(env: 'FILESTACK_API_KEY')]
    private string $apiKey,
  ) {}

  public function upload(UploadedFile $image): string
  {
    $client = new FilestackClient($this->apiKey);

    $filelink = $client->upload($image->getPathname(), ['filename' => bin2hex(random_bytes(16)) . '.png']);

    return $filelink->url();
  }
}
