<?php

namespace App\Utils;

use App\Entity\User;

class Notifier
{

    public function sendNotifToAdmins(User $user){

        //code pour envoyer une notif
        file_put_contents('notif.txt', $user->getEmail());

    }


}