# Configuration reference

The default bundle configuration:

```yaml
# Default configuration for extension with alias: "huh_pdf_creator"
huh_pdf_creator:

    # Set to true to use this bundle functionality in the contao article syndication.
    enable_contao_article_pdf_syndication: false

    # Set the paths allowed for file opening (e.g. image loading). Paths are be relativ to the project dir. Currently only used for dompdf (chroot).
    allowed_paths:

        # Defaults:
        - web
        - public
        - files
        - assets

    # PDF creator configurations
    configurations:

        # Prototype: The title of the configuration. Should be a unique alias/name containing just 'a-z0-9-_' like 'news_export','default','brand_a_themed'.
        title:

            # The pdf creator type (pdf library).
            type:                 ~ # One of "dompdf"; "mpdf"; "tcpdf"

            # A nice name for displaying in the backend.
            name:                 ~ # Example: 'A4 brochure with corporate logo'

            # Set a file name for the generated pdf files. You can use the %title% placeholder to use the title of the content to export in the file name.
            filename:             '%%title%%.pdf'

            # The path to the folder where the generated files should be stored. Only used if output_mode is AbstractPdfCreator::OUTPUT_MODE_FILE. Path must be relative to the project path.
            file_path:            ~ # Example: files/export/pdf

            # Set page orientation.
            orientation:          portrait # One of "portrait"; "landscape"

            # Set how to output the pdf.
            output_mode:          inline # One of "download"; "file"; "inline"; "string"

            # Set a page format. This could be a standardized format like A3, A4, A5 or Legal, otherwise you can specify the format in millimeter (width x height, seperated by comma, for example 180,210).
            format:               A4
            margins:

                # Relative path from project to font file.
                top:                  ~

                # Name of the font family
                left:                 ~

                # Font style
                bottom:               ~

                # Font weight
                right:                ~
                unit:                 mm # One of "mm"
            fonts:

                # Prototype
                -

                    # Relative path from project to font file.
                    path:                 ~

                    # Name of the font family
                    family:               ~

                    # Font style
                    style:                ~

                    # Font weight
                    weight:               ~

            # Set a pdf template (also known as master template), which will be the base template for the generated pdf files. Must be a path relative to the contao web root.
            base_template:        ~ # Example: files/media/news/news_base_template.pdf

            # Set a default url that will override the url that is determined by the request. This can be usefull on development servers with custom url mapping.
            base_url:             null # Example: 'https://stage.example.org:8001/examplepath/'

            # Set credentials for basic http authentication.
            credentials:          null # Example: 'user:password'
```