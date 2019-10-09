<?php

namespace Mailjet\LaravelMailjet;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\TransportManager;
use Mailjet\LaravelMailjet\Transport\MailjetTransport;

class MailjetMailServiceProvider extends ServiceProvider
{
    /**
     * Register the Swift Transport instance.
     *
     * @return void
     */
    public function register()
    {
        $this->app->afterResolving(TransportManager::class, function(TransportManager $manager) {
            $this->extendTransportManager($manager);
        });
    }

    public function extendTransportManager(TransportManager $manager)
    {
        $manager->extend('mailjet', function() {
            $config = $this->app['config']->get('services.mailjet', array());
            $call = $this->app['config']->get('services.mailjet.transactionnal.call', true);
            $options = $this->app['config']->get('services.mailjet.transactionnal.options', array());

            return new MailjetTransport(new \Swift_Events_SimpleEventDispatcher(), $config['key'], $config['secret'], $call, $options);
        });
    }
}
