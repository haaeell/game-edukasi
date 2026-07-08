<?php

namespace App\Mail;

use App\Models\GameRoomInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GameRoomInvitationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public GameRoomInvitation $invitation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Room Game: '.$this->invitation->room->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.game-room-invitation',
        );
    }
}
