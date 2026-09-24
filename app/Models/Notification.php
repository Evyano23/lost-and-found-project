<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    // Inherits all polymorphic notifiable relationships, data casting, and markAsRead functionality.
}
