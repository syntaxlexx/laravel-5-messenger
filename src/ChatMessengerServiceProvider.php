<?php

namespace Lexx\ChatMessenger;

use Lexx\ChatMessenger\Models\Message;
use Lexx\ChatMessenger\Models\Models;
use Lexx\ChatMessenger\Models\Participant;
use Lexx\ChatMessenger\Models\Thread;
use Illuminate\Support\ServiceProvider;

class ChatMessengerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // add extra routes to our app
        // include __DIR__.'/routes.php'; // leave it to others for customization

        $this->offerPublishing();
        $this->setMessengerModels();
        $this->setUserModel();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Setup the configuration for ChatMessenger.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            base_path('vendor/lexxyungcarter/chatmessenger/config/config.php'),
            'chatmessenger'
        );
    }

    /**
     * Setup the resource publishing groups for ChatMessenger.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                base_path('vendor/lexxyungcarter/chatmessenger/config/config.php') => config_path('chatmessenger.php'),
            ], 'config');

            $this->publishes([
                base_path('vendor/lexxyungcarter/chatmessenger/migrations') => base_path('database/migrations'),
            ], 'migrations');
        }
    }

    /**
     * Define Messenger's models in registry
     *
     * @return void
     */
    protected function setMessengerModels()
    {
        $config = $this->app->make('config');

        Models::setMessageModel($config->get('chatmessenger.message_model', Message::class));
        Models::setThreadModel($config->get('chatmessenger.thread_model', Thread::class));
        Models::setParticipantModel($config->get('chatmessenger.participant_model', Participant::class));

        Models::setTables([
            'messages' => $config->get('chatmessenger.messages_table', Models::message()->getTable()),
            'participants' => $config->get('chatmessenger.participants_table', Models::participant()->getTable()),
            'threads' => $config->get('chatmessenger.threads_table', Models::thread()->getTable()),
        ]);
    }

    /**
     * Define User model in Messenger's model registry.
     *
     * @return void
     */
    protected function setUserModel()
    {
        $config = $this->app->make('config');

        $model = $config->get('auth.providers.users.model', function () use ($config) {
            return $config->get('auth.model', $config->get('chatmessenger.user_model'));
        });

        Models::setUserModel($model);

        Models::setTables([
            'users' => (new $model)->getTable(),
        ]);
    }
}
