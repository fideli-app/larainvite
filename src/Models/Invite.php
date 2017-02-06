<?php

namespace Junaidnasir\Larainvite\Models;

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
    use \Junaidnasir\Larainvite\Traits\InviteModelTrait;
}
