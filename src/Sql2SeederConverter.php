<?php

namespace Gadelgado\Sql2seeder;

use Illuminate\Support\Facades\Artisan;
use Gadelgado\Singularizer\Singularizer;

class Sql2SeederConverter
{
    private array $files;
    private string $model;
    private array $keys;
    private string $boilerplate;
    private bool $isBoilerplateCreated = false;
    private bool $isHeaderSet = false;
    private string $records = '';

    private function toPascalCase(string $word): string
    {
        return str_replace('_', '', ucwords($word, '_'));
    }

    private function getModelString(string $line): string
    {
        return ucfirst(str_replace('`', '', explode(' ', $line)[2]));
    }

    private function setModel(string $line): void
    {
        $this->model = Singularizer::singularize($this->toPascalCase($this->getModelString($line)));
    }

    private function setAttributes(string $line): void
    {
        $start = substr($line, strpos($line, '(') + 1);
        $this->keys = array_map('trim', explode(',', str_replace('`', '', substr($start, 0, strpos($start, ')')))));
    }

    private function createBoilerplate(): void
    {
        if (file_exists("database/seeds/{$this->model}Seeder.php")) unlink("database/seeds/{$this->model}Seeder.php");
        Artisan::call("make:seeder {$this->model}Seeder");
        $this->boilerplate = str_replace(
            'use Illuminate\Database\Seeder;',
            "use Illuminate\Database\Seeder; \nuse App\\{$this->model};",
            file_get_contents("database/seeds/{$this->model}Seeder.php")
        );
        $this->isBoilerplateCreated = true;
    }

    private function lineIsHeader(string $line): bool
    {
        return str_contains($line, 'INSERT INTO');
    }

    private function isSingleLineInstruction(string $header): bool
    {
        return preg_match('/values.*\(.*\)/i', $header);
    }

    private function processSingleLineInstruction($line): void
    {
        if(!$this->isBoilerplateCreated) $this->createBoilerplate();
        $this->processRecord(substr($line, strpos($line, ')')));
    }

    private function processMultiLineInstruction(): void
    {
        $this->createBoilerplate();
        $this->isHeaderSet = true;
    }

    private function processHeader(string $header): void
    {
        $this->setModel($header);
        $this->setAttributes($header);

        $this->isSingleLineInstruction($header)
        ? $this->processSingleLineInstruction($header)
        : $this->processMultiLineInstruction($header);
    }

    private function lineIsEmpty(string $line): bool
    {
        return trim($line) === '';
    }

    private function getValues(string $line): array
    {
        $start = substr($line, strpos($line, '(') + 1);
        return array_combine($this->keys, array_map('trim', explode(',', str_replace("'", '', substr($start, 0, strpos($start, ')'))))));
    }

    private function parseRecord($record): void
    {
        $this->records =   "{$this->records}{$this->model}::create([";
        foreach ($this->getValues($record) as $key => $value) {
            $this->records = "{$this->records }\n            '$key' => '$value',";
        }
        $this->records = "{$this->records }\n        ]);\n        ";
    }

    private function processRecord(string $record): void
    {
        !$this->lineIsEmpty($record) && $this->parseRecord($record);
    }

    private function saveFile(): void
    {
        $file = fopen("database/seeds/{$this->model}Seeder.php", 'w');
        fwrite($file, str_replace('//', "{$this->records }\n        //", $this->boilerplate));
        fclose($file);
    }

    private function processFile($file): void
    {
        $lines = file("database/sql/{$file}", FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            !$this->isHeaderSet && $this->lineIsHeader($line)
            ? $this->processHeader($line)
            : $this->processRecord($line);
        }
        $this->saveFile();
        $this->isHeaderSet = false;
        $this->records = '';
    }

    public function __construct()
    {
        $this->files = array_diff(scandir('database/sql'), ['.', '..']);
        array_map([$this, 'processFile'], $this->files);
    }
}
