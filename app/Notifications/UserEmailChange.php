<?php


namespace App\Notifications;


use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class UserEmailChange extends Notification implements ShouldQueue {
    use Queueable;

    /** @var User */
    protected $user;
    /** @var string */
    protected $oldEmail;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param null $oldEmail
     */
    public function __construct(User $user, $oldEmail = null) {
        $this->user = $user;
        $this->oldEmail = $oldEmail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @param User $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)
            ->line('Your email address has been changed!')
//            ->line('')
            ->line('If you did not make this change, let us know!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param User $notifiable
     * @return array
     */
    public function toArray(User $notifiable) {
        return [
            'title' => $this->user->name,
            'user' => $notifiable->name
        ];
    }

    public function toSlack($notifiable) {
//        return (new SlackMessage)
//            ->content('A user was created: <' . route('users.view', ['address' => $this->user->address, 'user' => $this->user->id]) . '|View the user>');
    }

}
