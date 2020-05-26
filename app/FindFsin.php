<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FindFsin extends Model
{
    protected $fillable = [
        'app_id',
        'result',
        'full_name',
        'territorial_authorities',
        'federal_authorities',
        'error_message',
    ];
    public function app() {
        return $this->belongsTo('App\App');
    }
}
