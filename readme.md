# Laravel 5 ChatMessenger (+ Pusher)
This package will allow you to add a full user messaging system into your Laravel application. It is a fork of the [cmgmyr/laravel-messenger](https://github.com/cmgmyr/laravel-messenger) with added features such as maximum number of participants in a conversation, and a unique social media sharing code for inviting users to a conversation(otherwise known as Thread in this package).

## Features
* Multiple conversations per user
* Optionally loop in additional users with each new message
* View the last message for each thread available
* Returns either all messages in the system, all messages associated to the user, or all message associated to the user with new/unread messages
* Return the users unread message count easily
* Very flexible usage so you can implement your own access control
* Live chat features using Pusher broadcasting services
* Ability to set maximum number of participants per thread
* Ability to generate a unique url to share a thread to invite more users

## Common uses
* Open threads (everyone can see everything)
* Group messaging (only participants can see their threads)
* One to one messaging (private or direct thread)
* Push messages to view without having to refresh the page
* Have a maximum number of participants in a thread/conversation

## Installation (Laravel 4.x)
Installation instructions for Laravel 4 can be [found here](https://github.com/cmgmyr/laravel-messenger/tree/v1).

## Installation (Laravel 5.x)
```
composer require lexxyungcarter/chatmessenger
```

Or place manually in composer.json:

```
"require": {
    "lexxyungcarter/chatmessenger": "^1.0"
}
```

Run:

```
composer update
```

Add the service provider to `config/app.php` under `providers`:

```php
'providers' => [
    Lexx\ChatMessenger\ChatMessengerServiceProvider::class,
],
```

> **Note**: If you are using Laravel 5.5, this step is unnecessary. Laravel Messenger supports [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

Publish config:

```
php artisan vendor:publish --provider="Lexx\ChatMessenger\ChatMessengerServiceProvider" --tag="config"
```
	
Update config file to reference your User Model:

```
config/chatmessenger.php
```

Create a `users` table if you do not have one already. If you need one, the default Laravel migration will be satisfactory.

**(Optional)** Define names of database tables in package config file if you don't want to use default ones:

```php
'messages_table' => 'lexx_messages',
'participants_table' => 'lexx_participants',
'threads_table' => 'lexx_threads',
```
    
Publish migrations:

```
php artisan vendor:publish --provider="Lexx\ChatMessenger\ChatMessengerServiceProvider" --tag="migrations"
```

Migrate your database:

```
php artisan migrate
```

Add the trait to your user model:

```php
use Lexx\ChatMessenger\Traits\Messagable;

class User extends Authenticatable {
    use Messagable;
}
```

# Pusher Integration
This package utilizes [Pusher Http Laravel](https://github.com/pusher/pusher-http-laravel) 
that provides pusher services out-of-the-box. All you have to do is require the package, register the service providers, publish the vendor package, and that's it! You're good to go.

Please check out the examples section for a detailed example usage.

# API list with Usage Examples
### Thread
* $thread->messages() - Messages relationship
* $thread->getLatestMessageAttribute() - Returns the latest message from a thread
* $thread->participants() - Participants relationship
* $thread->creator() - Returns the user object that created the thread.
* $thread->getAllLatest() - Returns all of the latest threads by updated_at date
* $thread->getBySubject($subject) - Returns all threads by subject
* $thread->participantsUserIds($userId = null) - Returns an array of user ids that are associated with the thread (with trashed)
* $thread->scopeForUser($userId) - Returns threads that the user is associated with (returns Builder instance)
* $thread->scopeForUserWithNewMessages($userId) - Returns threads with new messages that the user is associated with (returns Builder instance)
* $thread->scopeBetween(array $participants) - Returns threads between given user ids(returns Builder instance)
* $thread->addParticipant($userId) - Add users to thread as participants(also accepts array|mixed)
* $thread->removeParticipant($userId) - Remove participants from thread(also accepts array|mixed)
* $thread->markAsRead($userId) - Mark a thread as read for a user
* $thread->isUnread($userId) - See if the current thread is unread by the user
* $thread->activateAllParticipants() - Restores all participants within a thread that has a new message
* $thread->participantsString($userId = null, $columns = ['name']) - Generates a string of participant information
* $thread->hasParticipant($userId) - Checks to see if a user is a current participant of the thread
* $thread->userUnreadMessages($userId) - Returns array of unread messages in thread for given user
* $thread->userUnreadMessagesCount($userId) - Returns count of unread messages in thread for given user
* $thread->getMaxParticipants() - Returns the max number of participants allowed in a thread
* $thread->hasMaxParticipants() - Checks if the max number of participants in a thread has been reached

### Message
* $message->thread() - Thread relationship (Get a thread the message belongs to)
* $message->user() - User relationship (Get sender of the message)
* $message->recipients() - Recipients of this message
* $message->scopeUnreadForUser($userId) - Returns unread messages given the userId (returns Builder instance)

### Participant
* $participant->thread() - Thread relationship
* $participant->user() - User relationship

### User - (Lexx\ChatMessenger\Traits\Messagable)
* $user->messages() - messages relationship (return user messages)
* $user->threads() - threads relationship (return user threads)
* $user->newThreadsCount() - Returns the new messages count for user
* $user->unreadMessagesCount() - Returns the new messages count for user
* $user->threadsWithNewMessages() - Returns all threads with new messages

## Examples
* [Controller](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/MessagesController.php)
* [Routes](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/routes.php)
* [Views](https://github.com/lexxyungcarter/laravel-5-messenger/tree/master/examples/views)

## Other Example Projects
* [WIP] [Pusher](https://github.com/cmgmyr/laravel-messenger-pusher-demo)
* [WIP] [Lumen API](https://github.com/cmgmyr/lumen-messenger-api)


## Contributing? 
Please contribute to the original repo at [cmgmyr/laravel-messenger](https://github.com/cmgmyr/laravel-messenger)

## Security

If you discover any security related issues, please email [Chris Gmyr](mailto:cmgmyr@gmail.com) instead of using the issue tracker. Then we can pull changes from the original repo.

## Credits

- [Lexx YungCarter](https://github.com/lexxyungcarter)
- [Chris Gmyr](https://github.com/cmgmyr)

### Special Thanks
This package used [cmgmyr/laravel-messenger](https://github.com/cmgmyr/laravel-messenger) as a starting point, which in turn initially used [AndreasHeiberg/laravel-messenger](https://github.com/AndreasHeiberg/laravel-messenger) as a starting point.
