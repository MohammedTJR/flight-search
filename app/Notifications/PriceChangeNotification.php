<?php

namespace App\Notifications;

use App\Models\FavoriteFlight;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceChangeNotification extends Notification
{
    use Queueable;

    protected $favoriteFlight;
    protected $oldPrice;
    protected $newPrice;

    /**
     * Create a new notification instance.
     */
    public function __construct(FavoriteFlight $favoriteFlight, $oldPrice, $newPrice)
    {
        $this->favoriteFlight = $favoriteFlight;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $priceChange = $this->newPrice - $this->oldPrice;
        $changeType = $priceChange > 0 ? 'aumentado' : 'disminuido';
        $changeAmount = abs($priceChange);

        return (new MailMessage)
            ->subject('¡Cambio de precio en tu vuelo favorito!')
            ->view('mails.price-change', [
                'flight' => $this->favoriteFlight,
                'oldPrice' => $this->oldPrice,
                'newPrice' => $this->newPrice,
                'changeType' => $changeType,
                'changeAmount' => $changeAmount
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
