<?php namespace Modules\Translation\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Repositories\TranslationRepository;

class TranslationController extends AdminBaseController
{
    /**
     * @var TranslationRepository
     */
    private $translation;

    public function __construct(TranslationRepository $translation)
    {
        parent::__construct();

        $this->translation = $translation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $translations = $this->translation->all();

        return view('translation::admin.translations.index', compact('translations'));
    }
}
