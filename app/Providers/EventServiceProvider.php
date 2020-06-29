<?php

namespace App\Providers;

use App\Events\AppEvent;
use App\Events\CustomDataCheckingEvent;
use App\Events\DebtorCheckingEvent;
use App\Events\DisqCheckingEvent;
use App\Events\FedFsmCheckingEvent;
use App\Events\FindDepartmentCheckingEvent;
use App\Events\FsspCheckingEvent;

use App\Events\FsinChekingEvent;

use App\Events\FsspWantedCheckingEvent;
use App\Events\FtsCheckingEvent;
use App\Events\HonestBusinessCheckingEvent;
use App\Events\InnCheckingEvent;
use App\Events\InnFoundEvent;
use App\Events\InnNotFoundEvent;
use App\Events\InterpolRedCheckingEvent;
use App\Events\InterpolYellowCheckingEvent;
use App\Events\MvdWantedCheckingEvent;
use App\Events\PassportCheckingEvent;
use App\Events\PassportNotValidEvent;
use App\Listeners\AppListener;
use App\Listeners\FindCustomDataListener;
use App\Listeners\DebtorListener;
use App\Listeners\DisqListener;
use App\Listeners\FedFsmListener;

use App\Listeners\FsinListner;


use App\Listeners\FindDepartmentListener;
use App\Listeners\FsspListener;
use App\Listeners\FsspWantedListener;
use App\Listeners\FtsListener;
use App\Listeners\HonestBusinessListener;
use App\Listeners\InnFoundListener;
use App\Listeners\InnListener;
use App\Listeners\InnNotFoundListener;
use App\Listeners\InterpolRedCheckingListener;
use App\Listeners\InterpolYellowCheckingListener;
use App\Listeners\MvdWantedListener;
use App\Listeners\PassportListener;
use App\Listeners\PassportNotValidListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AppEvent::class => [
            AppListener::class
        ],
        InnFoundEvent::class => [
            InnFoundListener::class
        ],
        FsinChekingEvent::class => [
            FsinListner::class
        ],
        InnNotFoundEvent::class => [
            InnNotFoundListener::class
        ],
        FedFsmCheckingEvent::class => [
            FedFsmListener::class
        ],
        FsspCheckingEvent::class => [
            FsspListener::class
        ],
        InnCheckingEvent::class => [
            InnListener::class
        ],
        PassportCheckingEvent::class => [
            PassportListener::class
        ],
        PassportNotValidEvent::class => [
            PassportNotValidListener::class
        ],
        InterpolYellowCheckingEvent::class => [
            InterpolYellowCheckingListener::class
        ],
        InterpolRedCheckingEvent::class => [
            InterpolRedCheckingListener::class
        ],
        MvdWantedCheckingEvent::class => [
            MvdWantedListener::class
        ],
        FsspWantedCheckingEvent::class => [
            FsspWantedListener::class
        ],
        DisqCheckingEvent::class => [
            DisqListener::class
        ],
        DebtorCheckingEvent::class => [
            DebtorListener::class
        ],
        HonestBusinessCheckingEvent::class => [
            HonestBusinessListener::class
        ],
        FindDepartmentCheckingEvent::class => [
            FindDepartmentListener::class
        ],
        CustomDataCheckingEvent::class => [
            FindCustomDataListener::class
        ],
        FtsCheckingEvent::class => [
            FtsListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
