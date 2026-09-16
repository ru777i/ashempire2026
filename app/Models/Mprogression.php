<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mprogression extends Model
{
    protected $fillable = [
        'progre',
        'sessionn_id',
        'sessionn_module_id',

    ];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function sessionn()
    {
        return $this->belongsTo(Sessionn::class);
    }
}
