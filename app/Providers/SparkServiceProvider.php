<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'srvAudit',
        'product' => 'srvAudit',
        'street' => '128a N Curtis Ave',
        'location' => 'Willcox, AZ 85643',
        'phone' => '520-507-5266',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = 'support@srvaudit.com';

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'cotton@srvaudit.com',
        'justin@srvaudit.com'
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = true;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        Spark::useStripe()->noCardUpFront()->teamTrialDays(30);

        Spark::freeTeamPlan()
            ->features([
                'Unlimited users', '1 host', 'Free Forever!'
            ]);

        Spark::teamPlan('Professional', 'pro')
            ->price(49)
            ->features([
                'Unlimited users', '5 hosts', 'All app features'
            ]);

        Spark::teamPlan('Professional Plus', 'proplus')
            ->price(199)
            ->features([
                'Unlimited users', '25 hosts', 'For medium sized infrastructures'
            ]);

        Spark::teamPlan('Enterprise', 'ent')
            ->price(499)
            ->features([
                'Unlimited users', '100 hosts', 'Prioritized support'
            ]);

        Spark::tokensCan([
            'log-sessions' => 'Log Sessions'
            ]);

        Spark::noAdditionalTeams();

        Spark::afterLoginRedirectTo('/home');
    }
}
