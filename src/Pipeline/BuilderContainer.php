<?php

declare(strict_types=1);

namespace WeasyPrint\Pipeline;

use WeasyPrint\Objects\Output;
use WeasyPrint\{Command, Service};

class BuilderContainer
{
  private string $inputPath;
  private string $outputPath;
  private Command $command;
  private Output $output;

  public function __construct(
    public Service $service
  ) {
  }

  public function makeTemporaryFilename(): string|false
  {
    return tempnam(
      sys_get_temp_dir(),
      $this->service->getConfig()->getCachePrefix()
    );
  }

  public function setInputPath(string $inputPath): void
  {
    $this->inputPath = $inputPath;
  }

  public function setOutputPath(): void
  {
    $this->outputPath = $this->makeTemporaryFilename();
  }

  public function setCommand(Command $command): void
  {
    $this->command = $command;
  }

  public function setOutput(Output $output): void
  {
    $this->output = $output;
  }

  public function getInputPath(): string
  {
    return $this->inputPath;
  }

  public function getOutputPath(): string
  {
    return $this->outputPath;
  }

  public function getCommand(): Command
  {
    return $this->command;
  }

  public function getOutput(): Output
  {
    return $this->output;
  }
}
