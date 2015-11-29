<?php namespace Modules\Translation\Exporters;

use League\Csv\Writer;
use Maatwebsite\Excel\Excel;
use Modules\Translation\Services\TranslationsService;
use SplTempFileObject;

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
        $keys = array_keys($data[0]);

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne($keys);
        $csv->insertAll($data);
        $csv->output($this->getFileName());
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return $this->filename . time() . '.csv';
    }

    /**
     * @return array
     */
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
