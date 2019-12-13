<?php

namespace App\Notifications;

use App\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ListingCreated extends Notification implements ShouldQueue {
    use Queueable;

    /** @var Listing */
    protected $listing;

    /**
     * Create a new notification instance.
     *
     * @param Listing $listing
     */
    public function __construct(Listing $listing) {
        //
        $this->listing = $listing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @param Listing $listing
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)
            ->line('Your listing is live!')
            ->action('View Listing', route('listings.view', ['address' => $this->listing->address, 'listing' => $this->listing->id]))
            ->line('Good luck with your sale!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            //
        ];
    }

    public function toSlack($notifiable) {
        return (new SlackMessage)
            ->content('A listing was created: <' . route('listings.view', ['address' => $this->listing->address, 'listing' => $this->listing->id]) . '|View the listing>');
    }
}
