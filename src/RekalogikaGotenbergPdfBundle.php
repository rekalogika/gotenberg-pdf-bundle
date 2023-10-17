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

namespace Rekalogika\GotenbergPdfBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RekalogikaGotenbergPdfBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
