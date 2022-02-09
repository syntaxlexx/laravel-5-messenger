# Laravel 5, 6, 7, 8 & 9 ChatMessenger (+ Pusher)

This package will allow you to add a full user messaging system into your Laravel application. It is a highly intuitive laravel 5/6 chatmessenger with added features such as maximum number of participants in a conversation, starred threads, and a unique social media sharing link for inviting users to a conversation(otherwise known as Thread in this package).

[![Total Downloads](https://poser.pugx.org/lexxyungcarter/chatmessenger/downloads)](https://packagist.org/packages/lexxyungcarter/chatmessenger) [![Latest Stable Version](https://poser.pugx.org/lexxyungcarter/chatmessenger/v/stable)](https://packagist.org/packages/lexxyungcarter/chatmessenger) [![Latest Unstable Version](https://poser.pugx.org/lexxyungcarter/chatmessenger/v/unstable)](//packagist.org/packages/lexxyungcarter/chatmessenger) [![License](https://poser.pugx.org/lexxyungcarter/chatmessenger/license)](https://packagist.org/packages/lexxyungcarter/chatmessenger) [![composer.lock available](https://poser.pugx.org/lexxyungcarter/chatmessenger/composerlock)](https://packagist.org/packages/lexxyungcarter/chatmessenger)


## Donating to the project
If you've found this useful and would like to buy the maintainers a coffee (or a Tesla, we're not picky), feel free to do so.

<a href="https://patreon.com/lexxyungcarter"><img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" alt="Patreon donate button" /> </a>

<a href="https://ko-fi.com/acelords" target="_blank" title="Buy me a Coffee"><img width="150" style="border:0px;width:150px;display:block;margin:0 auto" src="https://az743702.vo.msecnd.net/cdn/kofi2.png?v=0" border="0" alt="Buy Me a Coffee at ko-fi.com" /></a>

Or by buying products and merchandise at [Marketplace](https://store.acelords.space).

This funding is used for maintaining the project and adding new features into Code Style plus other open-source repositories.


| Laravel Version | Compatible?   |
| --------------- | ------------- |
| 9.x             | Yes! (^1.3.0) |
| 8.x             | Yes! (^1.3.0) |
| 7.x             | Yes! (^1.3.0) |
| 6.x             | Yes! (^1.2.0) |
| 5.8             | Yes!          |
| 5.7             | Yes!          |
| 5.6             | Yes!          |


| Laravel Version | Compatible Branch  |
| --------------- | ------------------ |
| 5.5             | v1 [1.0.8]         |
| 5.4             | v1 [1.0.8]         |
| 5.3             | v1 [1.0.8]         |
| 5.2             | v1 [1.0.8]         |
| 5.1             | v1 [1.0.8]         |

> Get on to voting for a tailwind/vue.js/vuerouter version of the project

## Features
* Multiple conversations per user
* Optionally loop in additional users with each new message
* View the last message for each thread available
* Returns either all messages in the system, all messages associated to the user, or all message associated to the user with new/unread messages
* Return the users unread message count easily
* Very flexible usage so you can implement your own access control
* Live chat features using Pusher broadcasting services
* Ability to set maximum number of participants per thread
* Ability to generate a unique url for sharing a thread to invite more users into the conversation
* Ability to add/remove users from a conversation - just like WhatsApp!
* Ability to star/favourite threads

## Common uses
* Open threads (everyone can see everything)
* Group messaging (only participants can see their threads)
* One to one messaging (private or direct thread)
* Push messages to view without having to refresh the page
* Have a maximum number of participants in a thread/conversation


## Installation (Laravel 4.x - no longer actively supported)
Installation instructions for Laravel 4 can be [found here](https://github.com/cmgmyr/laravel-messenger/tree/v1).

## Installation (Laravel 5.x)
### Laravel 5.6+
```bash
composer require lexxyungcarter/chatmessenger
```

### Laravel 5.1 > 5.5
```bash
composer require lexxyungcarter/chatmessenger@1.0.8
```

Or place manually in composer.json:

```php
"require": {
    "lexxyungcarter/chatmessenger": "^1.0"
}
```

Run:

```
composer update
```

#### >>> If using Laravel 5.4 and below
> **Note**: Laravel Messenger supports [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery). If using Laravel 5.5 and above, skip this part.

Add the service provider to `config/app.php` under `providers`:

```php
'providers' => [
    Lexx\ChatMessenger\ChatMessengerServiceProvider::class,
],
```

Publish config:

```php
php artisan vendor:publish --provider="Lexx\ChatMessenger\ChatMessengerServiceProvider" --tag="config"
```

Update config file to reference your User Model:

```php
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
This package utilizes [pusher/pusher-php-server](https://github.com/pusher/pusher-php-server)
that provides pusher services out-of-the-box. All you have to do is require the package, register the service providers, publish the vendor package, and that's it! You're good to go.

Please check out the examples section for a detailed example usage.

## Breaking Changes:
### Deprecated Packages
Since [Pusher Http Laravel](https://github.com/pusher/pusher-http-laravel) has been deprecated, the current 
demo uses the latest Laravel 5/6 trends of Broadcasting via events. Checkout [THE DEMO](https://github.com/lexxyungcarter/laravel-5-messenger-demo)
to see it in action. It becomes more manageable and expressive to configure channels individually.
> If you plan to migrate to Laravel 6, the Pusher Http Laravel deprecated package will prohibit you due to dependency issues.
> You will simply need to create an event to fire the broadcast message, and a channel for broadcasting. You can check the demo 
>for practical usage.

### Migration to v1.2
As pointed out in this [issue](https://github.com/lexxyungcarter/laravel-5-messenger/issues/10#issue-471480046), the starred
property has been moved from `threads` table to the `participants` table as it makes much more sense there. 
(Credits to [snarcraft](https://github.com/snarcraft)).
> run `php artisan vendor:publish --provider="Lexx\ChatMessenger\ChatMessengerServiceProvider" --tag="migrations"` to copy migration file,
> then run `php artisan migrate`. 

Starring a thread is as easy as calling the `star()` method on the thread. If no userId is passed, it defaults to the currently logged-in user. 
Same case applied to unstarring a thread. `$thread->unstar()`.

# API list with Usage Examples
### Thread
* $thread->messages() - Messages relationship
* $thread->getLatestMessageAttribute() - Returns the latest message from a thread
* $thread->participants() - Participants relationship
* $thread->creator() - Returns the user object that created the thread.
* $thread->getAllLatest() - Returns all of the latest threads by updated_at date
* $thread->getBySubject($subject) - Returns all threads by subject
* $thread->participantsUserIds($userId = null) - Returns an array of user ids that are associated with the thread (NO trash)
* $thread->participantsUserIdsWithTrashed($userId = null) - Returns an array of user ids that are associated with the thread (with trashed)
* $thread->addParticipant($userId) - Add users to thread as participants(also accepts array|mixed)
* $thread->removeParticipant($userId) - Remove participants from thread(also accepts array|mixed)
* $thread->markAsRead($userId) - Mark a thread as read for a user
* $thread->isUnread($userId) - See if the current thread is unread by the user
* $thread->activateAllParticipants() - Restores all participants within a thread that has a new message
* $thread->participantsString($userId = null, $columns = []) - Generates a string of participant information (The columns here reflect the DB columns in the users table to use when returning the names of participants. For easier management, you can define `CHATMESSENGER_PARTICIPANT_AKA` and set to a specific column. Then you can just ignore passing the $columns[] to the method).
* $thread->hasParticipant($userId) - Checks to see if a user is a current participant of the thread
* $thread->userUnreadMessages($userId) - Returns array of unread messages in thread for given user
* $thread->userUnreadMessagesCount($userId) - Returns count of unread messages in thread for given user
* $thread->getMaxParticipants() - Returns the max number of participants allowed in a thread
* $thread->hasMaxParticipants() - Checks if the max number of participants in a thread has been reached
* $thread->star($userId = null) - Star/favourite a thread (if no $userId is passed, it defaults to the logged-in user)
* $thread->unstar($userId = null) - Unstar/unfavourite a thread (if no $userId is passed, it defaults to the logged-in user)
* $thread->isStarred - check if thread has been starred

### Message
* $message->thread() - Thread relationship (Get a thread the message belongs to)
* $message->user() - User relationship (Get sender of the message)
* $message->recipients() - Recipients of this message

### Participant
* $participant->thread() - Thread relationship
* $participant->user() - User relationship

### User - (Lexx\ChatMessenger\Traits\Messagable)
* $user->messages() - messages relationship (return user messages)
* $user->threads() - threads relationship (return user threads)
* $user->newThreadsCount() - Returns the new messages count for user
* $user->unreadMessagesCount() - Returns the new messages count for user
* $user->threadsWithNewMessages() - Returns all threads with new messages

You can also check the individual models for more information about the functions. This package also utilizes [Scopes](https://laravel.com/docs/5.5/eloquent#query-scopes) in case you need more control over your queries.

## Examples
* [Controller - MessagesController](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/MessagesController.php)
* [Controller - ThreadController](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/ThreadController.php)
* [Event - MessageWasPosted](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/Events/MessageWasPosted.php)
* [Routes - web](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/routes/web.php)
* [Routes - channel](https://github.com/lexxyungcarter/laravel-5-messenger/blob/master/examples/routes/channels.php)
* [Views](https://github.com/lexxyungcarter/laravel-5-messenger/tree/master/examples/views)
* [Composer.json Sample](https://github.com/lexxyungcarter/laravel-5-messenger/tree/master/examples/composer.json)

## So, Where's the Demo?
- [Check the Source Code DEMO here](https://github.com/lexxyungcarter/laravel-5-messenger-demo)

- [Check the LIVE DEMO here](https://messenger.acelords.space)

> To get a clear picture of how it works, open two to four browsers (private/incognito mode is perfect for this case) and login with different accounts.

![Screenshot](examples/acelords-messenger.jpg?raw=true "Screenshot")

## Contributing?
Suggestions are welcome and any contributions whatsoever are highly valued. If feeling a little bit shy, feel free to send an email to [Lexx YungCarter](mailto:lexxyungcarter@gmail.com).

## Security

If you discover any security related issues, please use the issue tracker or better yet, send an email to [Lexx YungCarter](mailto:lexxyungcarter@gmail.com).

## Credits

- [Lexx YungCarter](https://github.com/lexxyungcarter)
- [Chris Gmyr](https://github.com/cmgmyr)

## What's Next?
We are on the verge of unleashing a [Vue.js](https://vuejs.org) version **+ Examples** for those of you requiring a boost in quickly setting up chatrooms/messages in your app.

So:-
- Laravel + Vue
- Vue Standalone App (Node)
- Flutter Standalone Android App

Should you be so greatful to provide code samples, feel free to share your code/repository with us. Thank you in advance!

### Special Thanks
This package used [cmgmyr/laravel-messenger](https://github.com/cmgmyr/laravel-messenger) as a starting point, which in turn initially used [AndreasHeiberg/laravel-messenger](https://github.com/AndreasHeiberg/laravel-messenger) as a starting point.
