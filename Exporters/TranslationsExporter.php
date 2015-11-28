<?php namespace Modules\Translation\Exporters;

use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Modules\Translation\Services\TranslationsService;

class TranslationsExporter
{
    /**
     * @var TranslationsService
     */
    private $translations;
    /**
     * @var Excel
     */
    private $excel;
    private $filename = 'translations_';

    public function __construct(TranslationsService $translations, Excel $excel)
    {
        $this->translations = $translations;
        $this->excel = $excel;
    }

    public function export()
    {
        $data = $this->formatData();

        $this->excel->create($this->getFileName(), function (LaravelExcelWriter $excel) use ($data) {
            $excel->sheet($this->filename, function (LaravelExcelWorksheet $sheet) use ($data) {
                $sheet->fromArray($data);
                $sheet->freezeFirstRow();
            });
        })->export('csv');
    }

    private function getFileName()
    {
        return $this->filename . time();
    }

    private function formatData()
    {
        $translations = $this->translations->getFileAndDatabaseMergedTranslations();
        $translations = $translations->all();

        $data = [];
        foreach ($translations as $key => $translation) {
            $data[] = array_merge(['key' => $key], $translation);
        }
        return $data;
    }
}
