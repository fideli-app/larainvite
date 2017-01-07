<?php namespace Junaidnasir\Larainvite;

use Exception;
use Carbon\Carbon;
use Junaidnasir\Larainvite\InvitationInterface;

/**
* User Invitation class
*/
class UserInvitation
{
    private $invite;
    function __construct(InvitationInterface $invite)
    {
        $this->invite = $invite;
    }

    public function invite($email, $referral, $expires = null)
    {
        $expires = $expires === null ? Carbon::now()->addHour(config('larainvite.expires')) : $expires;
        $this->validateEmail($email);
        return $this->invite->invite($email, $referral, $expires);
    }

    public function get($token)
    {
        return $this->invite->setToken($token)->get();
    }

    public function status($token)
    {
        return $this->invite->setToken($token)->status();
    }

    public function isValid($token)
    {
        return $this->invite->setToken($token)->isValid();
    }

    public function isExpired($token)
    {
        return $this->invite->setToken($token)->isExpired();
    }

    public function isPending($token)
    {
        return $this->invite->setToken($token)->isPending();
    }

    public function isAllowed($token, $email)
    {
        return $this->invite->setToken($token)->isAllowed($email);
    }
    public function consume($token)
    {
        return $this->invite->setToken($token)->consume();
    }

    public function cancel($token)
    {
        return $this->invite->setToken($token)->cancel();
    }

    public function reminder($token)
    {
        return $this->invite->setToken($token)->reminder();
    }
    public function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid Email Address", 1);
        }
        return $this;
    }
}
