<?php

declare(strict_types=1);

namespace WeasyPrint;

use Illuminate\Support\Collection;
use Symfony\Component\Process\{Exception\ProcessFailedException, Process};
use WeasyPrint\Exceptions\AttachmentNotFoundException;
use WeasyPrint\Objects\Config;

class Command
{
  protected Collection $arguments;

  public function __construct(
    protected Config $config,
    string $inputPath,
    string $outputPath,
    protected array $attachments = []
  ) {
    $this->arguments = new Collection([
      $config->getBinary(),
      $inputPath,
      $outputPath,
      '--quiet',
      '--encoding', $config->getInputEncoding(),
    ]);

    $this->prepareOptionalArguments();
  }

  protected function maybePushArgument(string $key, $value): void
  {
    if ($value === true) {
      $this->arguments->push($key);
    } else if ($value) {
      $this->arguments->push($key, $value);
    }
  }

  protected function prepareOptionalArguments(): void
  {
    $this->maybePushArgument(
      '--presentational-hints',
      $this->config->usePresentationalHints()
    );

    $this->maybePushArgument(
      '--base-url',
      $this->config->getBaseUrl()
    );

    $this->maybePushArgument(
      '--media-type',
      $this->config->getMediaType()
    );

    foreach ($this->attachments as $attachment) {
      if (!is_file($attachment)) {
        throw new AttachmentNotFoundException($attachment);
      }

      $this->maybePushArgument('--attachment', $attachment);
    }

    if ($stylesheets = $this->config->getStylesheets()) {
      foreach ($stylesheets as $stylesheet) {
        $this->maybePushArgument('--stylesheet', $stylesheet);
      }
    }
  }

  public function execute(): void
  {
    $process = new Process(
      command: $this->arguments->toArray(),
      env: $this->config->getProcessEnvironment(),
      timeout: $this->config->getTimeout()
    );

    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }
  }
}
