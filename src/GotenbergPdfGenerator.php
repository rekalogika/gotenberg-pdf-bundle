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

use Gotenberg\Gotenberg;
use Gotenberg\Stream;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamInterface;
use Rekalogika\Contracts\Print\PageLayoutInterface;
use Rekalogika\Contracts\Print\PageOrientation;
use Rekalogika\Contracts\Print\PaperInterface;
use Rekalogika\Contracts\Print\PdfGeneratorInterface;

class GotenbergPdfGenerator implements PdfGeneratorInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private string $gotenbergUrl = 'http://localhost:3000',
    ) {}

    public function generatePdfFromHtml(
        string $htmlContent,
        PaperInterface $paper,
        PageLayoutInterface $pageLayout,
        ?string $header = null,
        ?string $footer = null,
    ): StreamInterface {
        $chromium = Gotenberg::chromium($this->gotenbergUrl)
            ->paperSize(
                $paper->getWidth() * 39.3701,
                $paper->getHeight() * 39.3701,
            )
            ->margins(
                $pageLayout->getTopMargin() * 39.3701,
                $pageLayout->getBottomMargin() * 39.3701,
                $pageLayout->getLeftMargin() * 39.3701,
                $pageLayout->getRightMargin() * 39.3701,
            )
            ->printBackground();

        if ($header !== null) {
            $chromium->header(Stream::string('header.html', $header));
        }

        if ($footer !== null) {
            $chromium->footer(Stream::string('footer.html', $footer));
        }

        if ($pageLayout->getPageOrientation() == PageOrientation::Landscape) {
            $chromium->landscape();
        }

        $request = $chromium->html(Stream::string('index.html', $htmlContent));
        $response = $this->httpClient->sendRequest($request);
        $stream = $response->getBody();

        return $stream;
    }
}
