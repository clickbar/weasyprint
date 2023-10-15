<?php

declare(strict_types=1);

use WeasyPrint\Contracts\Factory;
use WeasyPrint\{Facade, Service};

test('can be instantiated via dependency injection', function (): void {
  expect($this->app->make(Factory::class))->toBeValidServiceInstance();
});

test('can be instantiated via instance container helper', function (): void {
  expect(Service::instance())->toBeValidServiceInstance();
});

test('can be instantiated via facade', function (): void {
  expect(Facade::getFacadeRoot())->toBeValidServiceInstance();
});
