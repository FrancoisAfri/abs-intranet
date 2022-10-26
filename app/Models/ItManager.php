<?php

namespace App\Models;

use App\HRPerson;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItManager extends Model
{
    use Uuids;

    /**
     * @var string
     */
    public $table = 'it_manager';


    /**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'status'
    ];

    public function manager()
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }

    public static function getListOfManagers()
    {
        return  ItManager::with('manager')->get();
    }
}
