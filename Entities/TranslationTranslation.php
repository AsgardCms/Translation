<?php namespace Modules\Translation\Entities;

use Illuminate\Database\Eloquent\Model;

class TranslationTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['value'];
    protected $table = 'translation__translation_translations';
}
