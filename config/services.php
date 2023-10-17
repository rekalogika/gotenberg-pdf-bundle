<?php

declare(strict_types=1);

/*
 * This file is part of rekalogika/print-src package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

use Psr\Http\Client\ClientInterface;
use Rekalogika\Contracts\Print\PdfGeneratorInterface;
use Rekalogika\GotenbergPdfBundle\GotenbergPdfGenerator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(GotenbergPdfGenerator::class)
        ->arg('$httpClient', service(ClientInterface::class));

    $services->alias(PdfGeneratorInterface::class, GotenbergPdfGenerator::class);
};
