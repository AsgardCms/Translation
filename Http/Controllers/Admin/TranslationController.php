<?php namespace Modules\Translation\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\TranslationsService;
use Pingpong\Modules\Facades\Module;

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

    private function requireAssets()
    {
        $this->assetManager->addAsset('bootstrap-editable.css', Module::asset('translation:vendor/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css'));
        $this->assetManager->addAsset('bootstrap-editable.js', Module::asset('translation:vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js'));
        $this->assetPipeline->requireJs('bootstrap-editable.js');
        $this->assetPipeline->requireCss('bootstrap-editable.css');
    }
}
