<?php

namespace AksService\DocumentWrapper;


use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class Document
{
    private string $DEFAULT_TEMPLATE;
    private string $DEFAULT_HEADER;
    private string $DEFAULT_FOOTER;
    private string $DEFAULT_FILENAME;
    private string $DEFAULT_PATH;

    private string $template;
    private string $header;
    private string $footer;

    private array $data;
    private array $headerData = [];
    private array $footerData = [];


    private string $fileName;
    private array $options;

    private bool $isHeaderSet = true;
    private bool $isFooterSet = true;



    public static function make(string $template = '', string $header = '', string $footer = '', array $data = [], array $headerData = [], array $footerData = [], string $fileName = '', array $options = []) : Document
    {
        return new static(template: $template, header: $header, footer: $footer, data: $data, headerData: $headerData, footerData: $footerData, fileName: $fileName, options: $options);
    }


    public function __construct(string $template = '', string $header = '', string $footer = '', array $data = [], array $headerData = [], array $footerData = [], string $fileName = '', array $options = [])
    {
        $this->template = $template;
        $this->header = $header;
        $this->footer = $footer;
        $this->data = $data;
        $this->fileName = $fileName;
        $this->options = $options;

        $this->loadConfig();
    }

    private function loadConfig() : void
    {
        $this->DEFAULT_TEMPLATE = config('document.DEFAULT_TEMPLATE', 'document::pdf.template');
        $this->DEFAULT_HEADER = config('document.DEFAULT_HEADER', 'document::pdf.header');
        $this->DEFAULT_FOOTER = config('document.DEFAULT_FOOTER', 'document::pdf.footer');
        $this->DEFAULT_FILENAME = config('document.DEFAULT_FILENAME', 'report.pdf');
        $this->DEFAULT_PATH = config('document.DEFAULT_PATH', 'document');

        $this->isHeaderSet = config('document.USE_DEFAULT_HEADER', true);
        $this->isFooterSet = config('document.USE_DEFAULT_FOOTER', true);
    }

    private function getPDFObject() : \Barryvdh\Snappy\PdfWrapper
    {
        $data = $this->data;

        if(Storage::exists($this->getFilePath())){
            return PDF::loadFile($this->getFilePath());
        }

        $file = PDF::loadView($this->getTemplate() != '' ? $this->getTemplate() : $this->getDefaultTemplate(), compact('data'))
            ->setOptions(array_merge($this->getOptions(), $this->getHeaderOptions(), $this->getFooterOptions()));
        Storage::disk('local')->put($this->getFilePath(), $file->output());
        return $file;
    }

    /********************/
    /* HEADER FUNCTIONS */
    /********************/

    private function getHeaderOptions() : array
    {
        $data = $this->getHeaderData();
        return $this->isHeaderSet() ? [
            'header-html' => \view($this->getHeader(), compact('data'))
        ] : [];
    }

    private function isHeaderSet() : bool
    {
        return $this->isHeaderSet;
    }


    /********************/
    /* FOOTER FUNCTIONS */
    /********************/

    private function getFooterOptions() : array
    {
        $data = $this->getFooterData();
        return $this->isFooterSet() ? [
            'footer-html' => \View($this->getFooter(), compact('data'))
        ] : [];
    }

    private function isFooterSet() : bool
    {
        return $this->isFooterSet;
    }


    /********************/
    /* OUTPUT FUNCTIONS */
    /********************/

    public function getStream() : Response
    {
        return $this->getPDFObject()->inline($this->getFileName());
    }

    public function getFile() : Response
    {
        return $this->getPDFObject()->download($this->getFileName());
    }


    /********************/
    /* SETTER FUNCTIONS */
    /********************/

    public function setTemplate(string $template) : static
    {
        $this->template = $template;
        return $this;
    }

    public function setHeader(string $header) : static
    {
        $this->header = $header;
        return $this;
    }

    public function setFooter(string $footer): static
    {
        $this->footer = $footer;
        return $this;
    }

    public function setFileName(string $fileName) : static
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function setData(array $data) : static
    {
        $this->data = $data;
        return $this;
    }

    public function setHeaderData(array $data) : static
    {
        $this->headerData = $data;
        return $this;
    }

    public function setFooterData(array $data) : static
    {
        $this->footerData = $data;
        return $this;
    }

    public function setOptions(array $options) : static
    {
        $this->options = $options;
        return $this;
    }


    /********************/
    /* GETTER FUNCTIONS */
    /********************/

    private function getDefaultTemplate() : string
    {
        return $this->DEFAULT_TEMPLATE;
    }

    private function getDefaultHeader() : string
    {
        return $this->DEFAULT_HEADER;
    }

    private function getDefaultFooter() : string
    {
        return $this->DEFAULT_FOOTER;
    }

    private function getDefaultFilename() : string
    {
        return $this->DEFAULT_FILENAME;
    }

    private function getFilePath() : string
    {
        return $this->DEFAULT_PATH . '/' . $this->getFileName();
    }

    public function getTemplate() : string
    {
        return $this->template != '' ? $this->template : $this->getDefaultTemplate();
    }

    public function getHeader(): string
    {
        return $this->header != '' ? $this->header : $this->getDefaultHeader();
    }

    public function getFooter(): string
    {
        return $this->footer != '' ? $this->footer : $this->getDefaultFooter();
    }

    public function getFileName(): string
    {
        return $this->fileName != '' ? $this->fileName : $this->getDefaultFilename();

    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getHeaderData(): array
    {
        return $this->headerData;
    }

    public function getFooterData(): array
    {
        return $this->footerData;
    }

    public function getOptions() : array
    {
        return $this->options;
    }


    /********************/
    /* REMOVE FUNCTIONS */
    /********************/

    public function removeHeader() : static
    {
        $this->isHeaderSet = false;
        return $this;
    }

    public function removeFooter(): static
    {
        $this->isFooterSet = false;
        return $this;
    }


}
