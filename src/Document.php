<?php

namespace AksService\DocumentWrapper;


use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Response;
use Illuminate\View\View;


class Document
{
    private string $DEFAULT_TEMPLATE = 'documentwrapper::pdf.template';
    private string $DEFAULT_HEADER = 'documentwrapper::pdf.header';
    private string $DEFAULT_FOOTER = 'documentwrapper::pdf.footer';
    private string $DEFAULT_FILENAME = 'report.pdf';

    private string $template;
    private string $header;
    private string $footer;
    private array $data;
    private string $fileName;
    private array $options;




    public static function make(string $template = '', string $header = '', string $footer = '', array $data = [], string $fileName = '', array $options = []) : Document
    {
        return new static($template, $header, $footer, $data, $fileName, $options);
    }


    public function __construct(string $template = '', string $header = '', string $footer = '', array $data = [], string $fileName = '', array $options = [])
    {
        $this->template = $template;
        $this->header = $header;
        $this->footer = $footer;
        $this->data = $data;
        $this->fileName = $fileName;
        $this->options = $options;
    }

    private function getPDFObject() : \Barryvdh\Snappy\PdfWrapper
    {
        $data = $this->data;
        return PDF::loadView($this->getTemplate() != '' ? $this->getTemplate() : $this->getDefaultTemplate(), compact('data'))
            ->setOptions(array_merge($this->getOptions(), [
                'footer-html' => \View($this->getFooter()),
                'header-html' => \view($this->getHeader()),
            ]));
    }

    public function getStream() : Response
    {
        return $this->getPDFObject()->inline($this->getFileName());
    }

    public function download() : Response
    {
        return $this->getPDFObject()->download($this->getFileName());
    }

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

    public function setOptions(array $options) : static
    {
        $this->options = $options;
        return $this;
    }

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

    public function getTemplate() : string
    {
        return $this->template != '' ? $this->getTemplate() : $this->getDefaultTemplate();
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

    public function getOptions() : array
    {
        return $this->options;
    }
}
