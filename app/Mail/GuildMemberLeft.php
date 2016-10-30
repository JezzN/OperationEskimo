<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 23/10/2016
 * Time: 16:26
 */

namespace App\Mail;


use Illuminate\Mail\Mailable;

class GuildMemberLeft extends Mailable 
{
    private $members;

    public function __construct($members)
    {
        $this->members = $members;
    }

    public function build()
    {
        return $this->view('mail.left')->with('members', $this->members);
    }
}