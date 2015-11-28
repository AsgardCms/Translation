<?php namespace Modules\Translation\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Translation\Exporters\TranslationsExporter;
use Modules\Translation\Http\Requests\ImportTranslationsRequest;
use Modules\Translation\Importers\TranslationsImporter;
use Modules\Translation\Services\TranslationsService;
use Pingpong\Modules\Facades\Module;

class TranslationController extends AdminBaseController
{
    /**
     * @var TranslationsService
     */
    private $translationsService;

    public function __construct(TranslationsService $translationsService)
    {
        parent::__construct();

        $this->translationsService = $translationsService;
        $this->requireAssets();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $translations = $this->translationsService->getFileAndDatabaseMergedTranslations();

        return view('translation::admin.translations.index', compact('translations'));
    }

    public function export(TranslationsExporter $exporter)
    {
        $exporter->export();

        return redirect()->route('admin.translation.translation.index')->withSuccess(trans('translation::translations.Translations exported'));
    }

    public function import(ImportTranslationsRequest $request, TranslationsImporter $importer)
    {
        $importer->import($request->file('file'));

        return redirect()->route('admin.translation.translation.index')->withSuccess(trans('translation::translations.Translations imported'));
    }

    private function requireAssets()
    {
        $this->assetManager->addAsset('bootstrap-editable.css', Module::asset('translation:vendor/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css'));
        $this->assetManager->addAsset('bootstrap-editable.js', Module::asset('translation:vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js'));
        $this->assetPipeline->requireJs('bootstrap-editable.js');
        $this->assetPipeline->requireCss('bootstrap-editable.css');
    }
}
