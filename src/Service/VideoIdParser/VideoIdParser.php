<?php

namespace App\Service\VideoIdParser;

/**
 * Helper to retrieve id from different video platform URL
 */
class VideoIdParser
{
  private const PLATFORMS = [
    // https://stackoverflow.com/questions/6903823/regex-for-youtube-id
    'youtube' => '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i',
  ];

  function getPlatformAndId(string $url): array
  {
    $matches = null;

    foreach (self::PLATFORMS as $platform => $regex) {
      if (preg_match($regex, $url, $matches)) {
        return $this->$platform($matches);
      };
    }

    throw new Exception("Video Url is not supported!");
  }

  public function youtube($matches)
  {
    return [
      'platform' => 'youtube',
      'id' => $matches[1],
    ];
  }
}
