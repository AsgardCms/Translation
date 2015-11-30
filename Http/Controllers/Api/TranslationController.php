<?php namespace Modules\Translation\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Collection;
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

    public function clearCache()
    {
        $this->translation->clearCache();
    }

    public function revisions(Request $request)
    {
        $translation = $this->translation->findTranslationByKey($request->get('key'));
        $translation = $translation->translate($request->get('locale'));

        return response()->json($this->formatRevisionHistory($translation->revisionHistory));
    }

    private function formatRevisionHistory(Collection $revisionHistory)
    {
        $formattedHistory = [];

        foreach ($revisionHistory as $history) {
            $timeAgo = $history->created_at->diffForHumans();
            if ($history->key == 'created_at' && !$history->old_value) {
                $formattedHistory[] = trans('translation::translations.history created translation', [
                    'name' => $history->userResponsible()->first_name,
                    'time' => $timeAgo,
                ]);
            } else {
                $formattedHistory[] = trans('translation::translations.history edited translation', [
                    'name' => $history->userResponsible()->first_name,
                    'oldValue' => $history->oldValue(),
                    'newValue' => $history->newValue(),
                    'time' => $timeAgo,
                ]);
            }
        }

        return $formattedHistory;
    }
}
