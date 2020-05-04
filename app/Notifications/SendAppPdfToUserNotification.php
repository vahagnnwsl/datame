<?php

namespace App\Notifications;

use App\Packages\Transformer\AppTransformer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class SendAppPdfToUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $app;
    protected $name;

    public function __construct($app)
    {
        $this->app = $app;
        $this->name = $app->created_at->format("Y.m.d-H.m.s")."-"
            .cyrillic_to_latin($app->lastname)."_"
            .cyrillic_to_latin($app->name)."_".cyrillic_to_latin($app->patronymic).".pdf";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name  = $this->name;
        $filePath = storage_path('app/public/pdf/'.$name);

        PDF::loadFile(route('app-report.template.pdf', [
            'identity' => $this->app->identity,
            'font_td' => 21,
            'font_name' => 28,
            'font_stat' => 18,
            'font_type' => 'px',
        ]))
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->save($filePath);


        return (new MailMessage)
            ->attach($filePath, [
                'as' =>$name,
                'mime' => 'application/pdf',
            ]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

}
