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
     * Send weather update with Telegram
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTelegram($notifiable)
    {
      // This notification runs on a cronjob everymorning at 08.00
      $url = 'https://api.darksky.net/forecast/'.env('FORECAST').'/'.env('LOCATION').'?units=si&&lang=nl';
      $json = file_get_contents($url);
      $result = json_decode($json);

      return TelegramMessage::create()
      ->to(env('CHAT_ID')) // Keep you chat_ID private
      ->content("In de ochtend om ". date('H:i:s', $result->hourly->data[2]->time) ."
is het ". $result->hourly->data[2]->summary ."
met een temperatuur van ". $result->hourly->data[2]->temperature .". \n \n". // Morning at 08.00. [2] is time at the moment

"In de middag ". date('H:i:s', $result->hourly->data[6]->time) ."
is het ". $result->hourly->data[6]->summary ."
met een temperatuur van ". $result->hourly->data[6]->temperature .". \n \n". // 12.00. [6] plus 4 hours

"Om een uur of ". date('H:i:s', $result->hourly->data[10]->time) ."
is het ". $result->hourly->data[10]->summary ."
met een temperatuur van ". $result->hourly->data[10]->temperature .". \n \n". // 16.00. [10] plus 8 hours

"afsluitend om ". date('H:i:s', $result->hourly->data[16]->time) ."
is het ". $result->hourly->data[16]->summary ."
met een temperatuur van ". $result->hourly->data[16]->temperature .". \n \n" // 22.00. [16] plus 14 hours
      );
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
