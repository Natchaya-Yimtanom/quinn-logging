<?php
namespace Quinn\Logging;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'logging';
    protected $primaryKey = 'id';
    public $timestamps = false;
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}