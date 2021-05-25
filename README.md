# Contao PDF Creator Bundle

This bundle adds a generic way to configure the creation of pdf files, reuse these configurations and create pdfs on base of them. It's based on [PDF Creator library](https://github.com/heimrichhannot/pdf-creator).

## Features
- add PDF configurations from backend module or yaml
- can be easily used in your bundle
- bundled support for: 
    - contao article syndication
    - [Syndication Type Bundle](https://github.com/heimrichhannot/contao-syndication-type-bundle)
    
> Note: there is no pdf library bundled with this bundle, you need to add the ones you want to use by your own! See Usage section for more information.

## Screenshot Configuration

![](docs/img/screenshot_contao_pdf_configuration.png)

## Usage

### Setup
1. Install the pdf library you want to use (currently dompdf, mpdf and tcpdf are supported, see [PDF Creator library](https://github.com/heimrichhannot/pdf-creator)) for more information
1. Install bundle with composer or contao manager 
   
        composer require heimrichhannot/contao-pdf-creator-bundle
   
1. Update database
1. Create a pdf configuration in contao backend within system -> pdf configuration or via yaml (see configuration reference)

### Export article as pdf

1. Set `huh_pdf_creator.enable_contao_article_pdf_syndication` to true

    ```yaml
    # config/config.yml or app/config/config.yml (Contao 4.4)
    huh_pdf_creator:
        enable_contao_article_pdf_syndication: true
    ```

1. Clear cache and update database
1. Choose pdf as syndication option in article configuration and select a pdf configuration

![](docs/img/screenshow_contao_article_syndication.png)

### Syndication Bundle

Select PDF syndication and choose the pdf creator setting you want for export.

### Logging

To get enhanced debug information while creating pdfs, you can enter the contao dev mode. 
In dev mode, pdf creator bundle save all logs created by the pdf library (if the library supports PSR-3 logging) to a huh_pdf_creator-[DATE].log file in the log folder.
For dompdf this bundle support the custom logging implementation and stores the log to a huh_pdf_creator-dompdf.log file in the log folder.

### Server routing issues / authentication

To avoid trouble with server routing (special url handling with docker etc.) and use the library on access restricted websites (for example staging setups), you can set a base_url that will override the url determined from the request and credentials (http basic authentication is supported only). These options are only available with yaml configuration as they usually server-specific.

```yaml
huh_pdf_creator:
  configurations:
    custom_pdf_config:
      type: dompdf
      name: "PDF behind authentication"
      base_url: 'https://customer.example.org'
      credentials: 'user:password'
```

## Developer

### Add pdf creator to your bundle

1. Use `PdfGenerator::generate()` to generator a pdf with your content. It expects an id of an PDF Creator config, html content and an `PdfContext` instance.
1. Use `DcaGenerator` to add an PDF Creator config field to your dca.

### Events

Event | Description
----- | -----------
BeforeCreateLibraryInstanceEvent | Passes the PDF Creator BeforeCreateLibraryInstanceCallback
BeforeOutputPdfCallbackEvent | Passes the PDF Creator BeforeOutputPdfCallback

## Configuration reference

```yaml
# Default configuration for extension with alias: "huh_pdf_creator"
huh_pdf_creator:

  # Set to true to use this bundle functionality in the contao article syndication.
  enable_contao_article_pdf_syndication: false
  configurations:

    # Prototype
    title:

      # The pdf create type (pdf library).
      type:                 ~ # One of "dompdf"; "mpdf"; "tcpdf"

      # A nice name for displaying in the backend.
      name:                 ~ # Example: 'A4 brochure with corporate logo'

      # Set a file name for the generated pdf files. You can use the %title% placeholder to use the title of the content to export in the file name.
      filename:             '%%title%%.pdf'

      # Set page orientation.
      orientation:          portrait # One of "portrait"; "landscape"

      # Set how to output the pdf.
      output_mode:          inline # One of "download"; "file"; "inline"; "string"

      # Set a page format. This could be a standardized format like A3, A4, A5 or Legal, otherwise you can specify the format in millimeter (width x height, seperated by comma, for example 180,210).
      format:               A4

      # Set a pdf template (also known as master template), which will be the base template for the generated pdf files. Must be a path relative to the contao web root.
      base_template:        ~ # Example: files/media/news/news_base_template.pdf

      # Set a default url that will override the url that is determined by the request. This can be usefull on development servers with custom url mapping.
      base_url:             null # Example: 'https://stage.example.org:8001/examplepath/'

      # Set credentials for basic http authentication.
      credentials:          null # Example: 'user:password'
```