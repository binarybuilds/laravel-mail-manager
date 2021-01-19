## Laravel Mail Manager
------------------------------------------------
A laravel package to easily resend system generated emails without going through entire application flow.

> This package does not provide any UI. If you are looking for a UI wrapper, See [laravel nova mail manager](https://github.com/binarybuilds/nova-mail-manager)

### How It Works?
This package will store all outgoing emails inside database and will track their status
if they are successfully sent or not.

You can resend all unsent emails, Or resend specific emails if your client requests you to resend an email.

> This package will only mark the status of emails whether they are sent or not. This package cannot determine whether 
the sent mail reached the user's email. So If a mail is marked as unsent, It means an error occured while sending the mail.
It might be due to ratelimit on the email sender, Invalid email credentials, etc. Do not assume the status of sent as a successful email delivery.
### Installation

This package can be installed using composer.
```bash
composer require binarybuilds/laravel-mail-manager
```
Next you must register the package's service provider by adding the below line to `providers` array inside `config/app.php` file.

```php
    'providers' => [
        //
        \BinaryBuilds\LaravelMailManager\LaravelMailManagerServiceProvider::class
    ]
```

#### Conflicts With Laravel Telescope
This package currently conflicts with laravel telescope. If you are using laravel telescope in your application, 
Make sure you register this package service provider after the telescope service provider is registered.

If you are registering telescope using `config/app.php` file, Then add the service provider after the telescope service provider as shown below.

```php
    'providers' => [
        //
        App\Providers\TelescopeServiceProvider::class,
        \BinaryBuilds\LaravelMailManager\LaravelMailManagerServiceProvider::class
    ]
```

If you are registering telescope manually using `AppServiceProvider.php` file, Then register this package service provider after the telescope service provider is registered as shown below

```php
$this->app->register(TelescopeServiceProvider::class);
$this->app->register(LaravelMailManagerServiceProvider::class);
```


Next, Publish the package configuration file by running
```bash
php artisan vendor:publish --tag=laravel-mail-manager-config
```
Run migrations to create the table required to store the emails.
```
php artisan migrate
```
This will create a table `mail_manager_mails`. You can configure the table name using the published configuration file located in `config/mail_manager.php`

### Usage
By default this package records all the outgoing mailables and notifications. 

### Ignoring Mailables And Notifications From Being Recorded
If you wish to ignore certain mailables or notifications from being recorded, 
You can add them to `ignore` array in `config/mail_manager.php` file.

#### Resending Mails

You can resend any mail by using the following command

```bash
php artisan mail-manager:resend-mail 1
```
Here 1 represents the ID of the mail to resend.

#### Resending All Un-Sent Mails
If you wish to resend all the mails which are unsent, You can use the following artisan command
```bash
php artisan mail-manager:resend-unsent-mail
```
Since this command will only resend the mails which are failed to send, You can safely schedule this command to resend your failed emails.
```php
$schedule->command('mail-manager:resend-unsent-mail')->daily();
```
#### Deleting Older Entries
Since this package records all outgoing emails, Your database table will start growing quickly. To automatically delete older entires, 
This package provides an artisan command to schedule deletion of older entries. 

You can schedule deletion of older entries by adding the following line to your scheduler.
```php
$schedule->command('mail-manager:prune --hours=72')->daily();
```
This will delete all entries which are older than 72 hours.

## Contributing

Thank you for considering contributing to Laravel mail manager! Please create a pull request with your contributions with detailed explanation of the changes you are proposing.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
