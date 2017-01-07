<?php  namespace Junaidnasir\Larainvite;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
*   Laravel Invitation class
*/
class Invitation implements InvitationInterface
{
    
    /**
     * Email address to invite
     * @var string
     */
    private $email;

    /**
     * Referral token for invitation
     * @var string
     */
    private $token = null;

    /**
     * integer ID of referral
     * @var [type]
     */
    private $referral;

    /**
     * DateTime of referral code expiration
     * @var Carbon
     */
    private $expires;

    /**
     * Invitation Model
     * @var Junaidnasir\Larainvite\Models\Invite
     */
    private $instance = null;
    
    /**
     * {@inheritdoc}
     */
    public function invite($email, $referral, $expires)
    {
        $this->readyPayload($email, $referral, $expires)
             ->createInvite()
             ->publishEvent('invited');
        return $this->token;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->getModelInstance(false);
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->instance;
    }

        /**
     * {@inheritdoc}
     */
    public function status()
    {
        return $this->instance->status;
    }
    
    /**
     * {@inheritdoc}
     */
    public function consume()
    {
        if ($this->isValid()) {
            $this->instance->status = 'successful';
            $this->instance->consumed_at = new Carbon();
            $this->instance->save();
            $this->publishEvent('consumed');
            return true;
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        if ($this->isValid()) {
            $this->instance->status = 'canceled';
            $this->instance->save();
            $this->publishEvent('canceled');
            return true;
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return (!$this->isExpired() && $this->isPending());
    }
    
    /**
     * {@inheritdoc}
     */
    public function isExpired()
    {
        if (! $this->instance->expired_at->isPast()) {
            return false;
        }
        $this->instance->status = 'expired';
        $this->instance->save();
        $this->publishEvent('expired');
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isPending()
    {
        return $this->instance->status === 'pending';
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed($email)
    {
        return ($this->instance->email === $email) && $this->isValid();
    }
    
    /**
     * Fire junaidnasir.larainvite.invited again for the invitation
     * @return true
     */
    public function reminder()
    {
        return $this->publishEvent('invited');
    }

    /**
     * generate invitation code and call save
     * @return self
     */
    private function createInvite()
    {
        $token = md5(uniqid());
        return $this->save($token);
    }

    /**
     * saves invitation in DB
     * @param  string $token referral code
     * @return self
     */
    private function save($token)
    {
        $this->getModelInstance();
        $this->instance->email      = $this->email;
        $this->instance->user_id    = $this->referral;
        $this->instance->expired_at = $this->expires;
        $this->instance->token      = $token;
        $this->instance->save();

        $this->token = $token;
        return $this;
    }

    /**
     * set $this->instance to Junaidnasir\Larainvite\Models\LaraInviteModel instance
     * @param  boolean $allowNew allow new model
     * @return self
     */
    private function getModelInstance($allowNew = true)
    {
        $inviteModel = static::inviteModel();
        if ($allowNew) {
            $this->instance = new $inviteModel;
            return $this;
        }
        try {
            $this->instance = (new $inviteModel)->where('token', $this->token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new Exception("Invalid Token {$this->token}", 1);
        }
        
        return $this;
    }

    /**
     * set input variables
     * @param  string   $email    email to invite
     * @param  integer  $referral referral id
     * @param  DateTime $expires  expiration of token
     * @return self
     */
    private function readyPayload($email, $referral, $expires)
    {
        $this->email    = $email;
        $this->referral = $referral;
        $this->expires  = $expires;
        return $this;
    }

    /**
     * Fire Laravel event
     * @param  string $event event name
     * @return self
     */
    private function publishEvent($event)
    {
        Event::fire('larainvite.'.$event, $this->instance, false);
        return $this;
    }
    
    
    private static function inviteModel()
    {
        return config('larainvite.invite_model');
    }
}
