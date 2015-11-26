<?php namespace Modules\Translation\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\TranslationsService;

class TranslationController extends AdminBaseController
{
    /**
     * @var TranslationRepository
     */
    private $translation;
    /**
     * @var TranslationsService
     */
    private $translationsService;

    public function __construct(TranslationRepository $translation, TranslationsService $translationsService)
    {
        parent::__construct();

        $this->translation = $translation;
        $this->translationsService = $translationsService;
        $this->assetPipeline->requireJs('bootstrap-editable.js');
        $this->assetPipeline->requireCss('bootstrap-editable.css');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $translationsRaw = $this->translationsService->getFileAndDatabaseMergedTranslations();

        $translations = [];
        foreach ($translationsRaw as $locale => $translationGroup) {
            foreach ($translationGroup as $key => $translation) {
                $translations[$key][$locale] = $translation;
            }
        }

        return view('translation::admin.translations.index', compact('translations'));
    }
}
