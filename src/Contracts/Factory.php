<?php

declare(strict_types=1);

namespace WeasyPrint\Contracts;

use Illuminate\Contracts\Support\Renderable;
use Symfony\Component\HttpFoundation\StreamedResponse;
use WeasyPrint\Enums\OutputType;
use WeasyPrint\Objects\{Config, Output, Source};

interface Factory
{
  public static function new(mixed ...$configurationOptions): self;
  public function withConfiguration(mixed ...$configurationOptions): self;
  public function prepareSource(Source|Renderable|string $source): self;
  public function getConfig(): Config;
  public function getSource(): Source;
  public function addAttachment(string $pathToAttachment): Factory;
  public function to(OutputType $outputType): Factory;
  public function toPdf(): Factory;
  public function toPng(): Factory;
  public function getOutputType(): OutputType;
  public function build(): Output;
  public function download(string $filename, array $headers = [], bool $inline = false): StreamedResponse;
  public function inline(string $filename, array $headers = []): StreamedResponse;
  public function putFile(string $path, ?string $disk = null, array $options = []): bool;
}
