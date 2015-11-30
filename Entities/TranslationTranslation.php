<?php namespace Modules\Translation\Entities;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * @property string value
 */
class TranslationTranslation extends Model
{
    use RevisionableTrait;
    public $timestamps = false;
    protected $fillable = ['value'];
    protected $table = 'translation__translation_translations';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 100;

    public static function boot()
    {
        parent::boot();
    }
}
