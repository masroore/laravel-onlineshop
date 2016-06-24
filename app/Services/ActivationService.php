<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use App\Repository\ActivationRepository;
use App\User;

/**
 * Description of ActivationService
 *
 * @author nthanh
 */
class ActivationService
{

    //put your code here
    protected $mailer;
    protected $activationRepo;
    protected $resendAfter = 24;

    public function __construct(Mailer $mailer, ActivationRepository $activationRepo)
    {
        $this->mailer = $mailer;
        $this->activationRepo = $activationRepo;
    }

    public function sendActivationMail($user)
    {
        if ($user->activated || !$this->shouldSend($user))
        {
            return;
        }

        // get token
        $token = $this->activationRepo->createActivation($user);
        $link = route('user.activate', $token);
        $message = sprintf('Activate account <a href="%s">%s</a>', $link, $link);

        $this->mailer->raw($message, function (Message $m) use ($user)
        {
            $m->to($user->email)->subject('Activation mail');
        });
    }

    public function activateUser($token)
    {
        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null)
        {
            return null;
        }

        //get user by user id
        $user = User::find($activation->user_id);

        $user->activated = true;

        $user->save();

        // delete activation by token
        $this->activationRepo->deleteActivation($token);

        return $user;
    }

    private function shouldSend($user)
    {
        $activation = $this->activationRepo->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }

}
