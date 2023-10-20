# rekalogika/gotenberg-pdf-bundle

Symfony Bundle for generating PDF using Gotenberg.

## Features

* Easy provisioning with Symfony Flex, Symfony CLI, and Docker Compose. Just
  install the bundle and you're ready to generate your first PDF.
* Separated high-level interfaces in `rekalogika/print-contracts`. If Gotenberg
  gets out of fashion in the future, hopefully we only need to replace this
  package, and don't need to change our code.
* Abstractions for paper sizes and page layouts.
* Leverages Symfony HTTP Client. See the requests and responses in Symfony
  profiler for debugging.
* With the heavy lifting already done by Gotenberg, there is no need to deal
  with Chrome instances, CLI tools, Puppeteer, NodeJS, etc.
* Scalable architecture. Suitable for low-volume development and high-volume
  usage alike. No need to reengineer if your usage outgrows your solution. Just
  add more instances of Gotenberg with Docker Compose or the container
  orchestration tool you are using.

## Installation

Preinstallation checklists:

* Make sure Composer is installed globally, as explained in the [installation
  chapter](https://getcomposer.org/doc/00-intro.md) of the Composer
  documentation. Run `composer about` to verify.
* Make sure your project has Symfony Flex installed and enabled (it is enabled
  by default). Run `composer why symfony/flex` to verify.

Open a command console, enter your project directory, and execute:

```bash
composer config extra.symfony.allow-contrib true
composer require rekalogika/gotenberg-pdf-bundle
```

## Quick Start

Checklist:

* Make sure you have Docker Compose installed. Run `docker compose version` to
  verify.
* Make sure you have Symfony CLI installed. Run `symfony version` to verify.

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
    #[Route('/pdf')]
    public function index(PdfGeneratorInterface $pdfGenerator): Response
    {
        $pdf = $pdfGenerator->generatePdfFromHtml(
            htmlContent: '<h1>Hello World</h1>',
            paper: Paper::A4(),
            pageLayout: PageLayout::inMm(PageOrientation::Portrait, 30)
        );

        return new StreamedResponse(
            callback: fn () => fpassthru($pdf->detach()),
            status: 200,
            headers: [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}
```

Then open your browser and go to <http://localhost:8000/pdf>.

## Documentation

[rekalogika/gotenberg-pdf-bundle](https://rekalogika.dev/gotenberg-pdf-bundle)

## License

MIT

## Contributing

The `rekalogika/gotenberg-pdf-bundle` repository is a read-only repo split from
the main repo. Issues and pull requests should be submitted to the
[rekalogika/print-src](https://github.com/rekalogika/print-src) monorepo.
