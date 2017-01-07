<?php

namespace Junaidnasir\Larainvite;

trait InviteTrait
{
    /**
     * return all invitation as laravel collection
     * @return hasMany invitation Models
     */
    public function invites()
    {
        return $this->hasMany(Junaidnasir\Larainvite\Models\Invite::class);
    }

    /**
     * return successful initation by a user
     * @return hasMany
     */
    public function successfulInvites()
    {
        return $this->invites()->where('status', 'successful');
    }
    /**
     * return pending invitations by a user
     * @return hasMany
     */
    public function pendingInvites()
    {
        return $this->invites()->where('status', 'pending');
    }
}
