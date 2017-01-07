<?php

namespace Junaidnasir\Larainvite\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invite
 *
 * @property int id
 * @property int user_id
 * @property string token
 * @property string email
 * @property string status
 * @property Carbon expired_at
 * @property Carbon consumed_at
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property \App\User user
 */
class Invite extends Model
{
    
    protected $fillable = [
        'user_id',
        'token',
        'email',
        'status',
        'expired_at',
        'consumed_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'expired_at',
        'consumed_at',
    ];

    protected $casts = [
        'user_id'     => 'int',
        'token'       => 'string',
        'email'       => 'string',
        'status'      => 'string',
        'consumed_at' => 'date',
        'expired_at'  => 'date',

        'created_at' => 'date',
        'updated_at' => 'date',
    ];
    
    public function __construct(array $attributes = []) {
      $this->table_name = config('larainvite.table_name');
      
      parent::__construct($attributes);
    }
    
    /**
     * Referral User
     */
    public function user()
    {
        return $this->belongsTo(config('larainvite.user_model'));
    }
}
