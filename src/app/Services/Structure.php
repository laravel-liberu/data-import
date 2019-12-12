<?php

namespace LaravelEnso\DataImport\app\Services;

use Illuminate\Http\UploadedFile;
use LaravelEnso\DataImport\app\Services\Reader\Structure as Reader;
use LaravelEnso\DataImport\app\Services\Validators\Structure as Validator;

class Structure
{
    private $file;
    private $summary;
    private $template;

    public function __construct(Template $template, UploadedFile $file)
    {
        $this->template = $template;
        $this->file = $file;
        $this->summary = new Summary($this->file->getClientOriginalName());
    }

    public function validates()
    {
        (new Validator(
            $this->template, $this->structure(), $this->summary
        ))->run();

        return ! $this->summary->hasErrors();
    }

    public function summary()
    {
        return $this->summary;
    }

    private function structure()
    {
        return (new Reader($this->file))
            ->get();
    }
}
