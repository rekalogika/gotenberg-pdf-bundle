# rekalogika/gotenberg-pdf-bundle

Symfony Bundle for generating PDF using Gotenberg.

## Features

* Easy provisioning with Symfony Flex, Symfony CLI, and Docker Compose. Just
  install the bundle and you're ready to generate your first PDF.
* Separated high-level interfaces in `rekalogika/print-contracts`. If there is a
  better practice of generating PDFs in the future, hopefully we only need to
  replace this package, and we don't need to change our code.
* High-level object-oriented API built on top of the official Gotenberg PHP
  client.
* With the heavy lifting already done by Gotenberg, there is no need to deal
  with Chrome instances, CLI tools, Puppeteer, NodeJS, etc.
* Infinitely scalable. Just add more instances of Gotenberg with Docker Compose
  or another container orchestration tool.

## Installation

Preinstallation checklists:

* Make sure Composer is installed globally, as explained in the [installation
  chapter](https://getcomposer.org/doc/00-intro.md) of the Composer
  documentation. Run `composer about` to verify.
* Make sure your project has Symfony Flex installed and enabled (it is enabled
  by default). Run `composer why symfony/flex` to verify.
* Make sure you have Docker Compose installed. Run `docker composer version` to
  verify.
* Make sure you have Symfony CLI installed. Run `symfony version` to verify.

Open a command console, enter your project directory, and execute:

```bash
composer config extra.symfony.allow-contrib true
composer require rekalogika/gotenberg-pdf-bundle
```

## Quick Start

To start the Gotenberg server (and other services registered in the Docker
Compose configuration), run:

```bash
docker compose up -d
```

Then start the web server using Symfony CLI:

```bash
symfony serve
```

Create a sample controller for generating a PDF file:

```php title="src/Controller/AppController.php"
namespace App\Controller;

use Rekalogika\Contracts\Print\PageOrientation;
use Rekalogika\Contracts\Print\PdfGeneratorInterface;
use Rekalogika\Print\PageLayout;
use Rekalogika\Print\Paper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/app', name: 'app_app')]
    public function index(PdfGeneratorInterface $pdfGenerator): Response
    {
        $pdf = $pdfGenerator->generatePdfFromHtml(
            htmlContent: '<h1>Hello World</h1>',
            paper: Paper::A4(),
            pageLayout: PageLayout::inMm(PageOrientation::Portrait, 30)
        );

        // $pdf is a StreamedInterface containing the resulting PDF file.
        // to get a raw stream, call $pdf->detach().

        return new StreamedResponse(
            fn () => fpassthru($pdf->detach()),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="file.pdf"',
            ]
        );
    }
}
```

Then open your browser and go to <http://localhost:8000/app>.

## License

MIT

## Contributing

The `rekalogika/gotenberg-pdf-bundle` repository is a read-only repo split from
the main repo. Issues and pull requests should be submitted to the
[rekalogika/print-src](https://github.com/rekalogika/print-src) monorepo.
