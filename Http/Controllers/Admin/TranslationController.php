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
        //$translations = $this->translation->all();

        return view('translation::admin.translations.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('translation::admin.translations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->translation->create($request->all());

        flash()->success(trans('core::core.messages.resource created', ['name' => trans('translation::translations.title.translations')]));

        return redirect()->route('admin.translation.translation.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Translation $translation
     * @return Response
     */
    public function edit(Translation $translation)
    {
        return view('translation::admin.translations.edit', compact('translation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Translation $translation
     * @param  Request $request
     * @return Response
     */
    public function update(Translation $translation, Request $request)
    {
        $this->translation->update($translation, $request->all());

        flash()->success(trans('core::core.messages.resource updated', ['name' => trans('translation::translations.title.translations')]));

        return redirect()->route('admin.translation.translation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Translation $translation
     * @return Response
     */
    public function destroy(Translation $translation)
    {
        $this->translation->destroy($translation);

        flash()->success(trans('core::core.messages.resource deleted', ['name' => trans('translation::translations.title.translations')]));

        return redirect()->route('admin.translation.translation.index');
    }
}
