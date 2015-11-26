<?php namespace Modules\Translation\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Translation\Repositories\TranslationRepository;

class TranslationController extends Controller
{
    /**
     * @var TranslationRepository
     */
    private $translation;

    public function __construct(TranslationRepository $translation)
    {
        $this->translation = $translation;
    }

    public function update(Request $request)
    {
        $this->translation->saveTranslationForLocaleAndKey(
            $request->get('locale'),
            $request->get('key'),
            $request->get('value')
        );
    }
}
