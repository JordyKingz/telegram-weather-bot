<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class WeatherUpdate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTelegram($notifiable)
    {
      $url = 'https://api.darksky.net/forecast/'.env('FORECAST').'/53.1938687,6.5632098?units=si&&lang=nl';
      $json = file_get_contents($url);
      $result = json_decode($json);

      return TelegramMessage::create()
      ->to(env('CHAT_ID'))
      ->content("
        Goedemorgen Jordy, \n
        Dit is de weersverwachting van vandaag. \n
        In de ochtend om ". date('H:i:s', $result->hourly->data[2]->time) ."
        is het ". $result->hourly->data[2]->summary ."
        met een temperatuur van ". $result->hourly->data[2]->temperature .". \n

        Later op de dag rond ". date('H:i:s', $result->hourly->data[6]->time) ."
        is het ". $result->hourly->data[6]->summary ."
        met een temperatuur van ". $result->hourly->data[6]->temperature .". \n

        Savonds om ". date('H:i:s', $result->hourly->data[9]->time) ."
        is het ". $result->hourly->data[9]->summary ."
        met een temperatuur van ". $result->hourly->data[9]->temperature .". \n

        Succes vandaag!
      "); // Inline Button
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
