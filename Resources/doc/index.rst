1 : Installation
================

Download and install RPSGuestbookBundle
---------------------------------------

1. Add the following lines in your `deps` file

.. code-block:: php

    // composer.json
    "require": {
    //...,
        "rps/core-bundle": "dev-master",
        "rps/guestbook-bundle": "dev-master"
    }

2. Update the dependency

.. code-block:: bash

    php composer.phar update rps/guestbook-bundle

Or you can do both steps in one command

.. code-block:: bash

    php composer.phar require rps/core-bundle:dev-master rps/guestbook-bundle:dev-master


Enable the bundle
-----------------

Add the RPSCoreBundle and RPSGuestbookBundle to your application kernel

.. code-block:: php

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new RPS\CoreBundle\RPSCoreBundle(),
            new RPS\GuestbookBundle\RPSGuestbookBundle(),
        );
    }


Update your schema
------------------

Run the following command

    app/console doctrine:schema:update --force


Install the css file
--------------------

Run the following command

.. code-block:: php

    php app/console assets:install web [--symlink]

use the ``--symlink`` option to use symlinks to bundle assets.


Enable Translator
-----------------

Enable the translator in your configuration

.. code-block:: yml

    # app/config/config.yml
    framework:
        translator: { fallback: ~ }


For more information about translations, check `Symfony Translation documentation`_

.. _`Symfony Translation documentation`: http://symfony.com/doc/current/book/translation.html


Import Guestbook routing file
-----------------------------

Import the RPSGuestbook routing file by adding the following to you routing file

.. code-block:: yml

    # app/config/routing.yml
    rps_guestbook:
        resource: "@RPSGuestbookBundle/Resources/config/routing.yml"
        prefix:   /


Default Configuration
---------------------

`View Default Configuration`_.

.. _`View Default Configuration`: Resources/doc/default_configuration.rst


2: Doctrine configuration
=========================

The RPS GuestbookBundle supports both Doctrine ORM and Doctrine ODM.
It is configured for ORM by default. To use Doctrine ODM, you must set this in the db_driver option.

.. code-block:: yml

    rps_guestbook:
        db_driver: mongodb



Update your schema
------------------

    app/console doctrine:schema:update --force


Using a custom model class
--------------------------

You can specify a custom model class by overriding the guestbook model class option e.g.

.. code-block:: yml

    rps_guestbook:
        class:
            model: MyProject\MyBundle\Entity\MyGuestbook

Your custom model class may extend the ``RPS\GuestbookBundle\Model\Entry`` class. If you are not extending the
``RPS\GuestbookBundle\Model\Entry`` class, your custom manager class must implement the
``RPS\GuestbookBundle\Model\EntryInterface`` interface.


Using a custom manager class
----------------------------

You can specify a custom guestbook entry manager class by overriding the manager class option e.g.

.. code-block:: yml

    rps_guestbook:
        class:
            manager: MyProject\MyBundle\Entity\MyGuestbookManager

Your custom class may extend the ``RPS\GuestbookBundle\Model\EntryManager`` class. If you are not extending the
``RPS\GuestbookBundle\Model\EntryManager`` class, your custom manager class must implement the
``RPS\GuestbookBundle\Model\EntryManagerInterface`` interface.


3: Mailer Configuration
=======================

To send emails, SwitfMailer must be installed and configured.

To send admin notification emails (email sent to the admin each time a new guestbook entry is saved),
you must enable the mailer service and set the mail ``admin_email`` and ``sender_email`` config options

.. code-block:: yml

    rps_guestbook:
        notify_admin: true

        mailer:
            admin_email: admin@localhost.com                # email the admin notification is sent to
            sender_email: admin@localhost.com               # sender email used
            email_title: New guestbook entry from {name}    # (optional)


Using a custom mailer class
---------------------------
You can specify your custom guestbook mailer manager class by overriding the mailer class option e.g.

.. code-block:: yml

    rps_guestbook:
        class:
            manager: MyProject\MyBundle\Mailer\Mailer

Your custom class may extend the ``RPS\GuestbookBundle\Mailer\BaseMailer`` class. If you are not extending the
``RPS\GuestbookBundle\Mailer\BaseMailer`` class, your custom mailer class must implement the
``RPS\GuestbookBundle\Mailer\MailerInterface`` interface.


Using a custom notification template
------------------------------------

You can specify a custom notification template by overriding the mail template config setting

.. code-block:: yml

    rps_guestbook:
        view:
            mail:
                notify: MyBundle:Mail:notify.txt.twig



4: Pager Installation and Configuration
=======================================
Pagination is enabled by default.

# using WhiteOctoberPagerfantaBundle for pagination
The RPS GuestbookBundle is integrated with the WhiteOctoberPagerfantaBundle.

The GuestbookBundle automatically checks if the WhiteOctoberPagerfantaBundle is installed.
If the WhiteOctoberPagerfantaBundle is not installed, the GuestbookBundle will disable pagination (note this can be override in the app/config/config.yml file)

To limit the number of entries shown, set the ``entry_per_page`` config option

.. code-block:: yml

    rps_guestbook:
        entry_per_page: 25

Using a custom pager manager class
----------------------------------

You can specify your custom pager manager class by overriding the pager class option.e.g.

.. code-block:: yml

    rps_guestbook:
        class:
            manager: MyProject\MyBundle\Pager\Pager

Your custom class must implement the ``\RPS\CoreBundle\Pager\PagerInterface`` interface.

Using a custom pager service
----------------------------

You can also specify a custom pager service to handle the guestbook entries pagination by setting the pager service config option.

.. code-block:: yml

    rps_guestbook:
        service:
            pager: my_pager

Your pager service class should implement the ``\RPS\CoreBundle\Pager\PagerInterface`` interface.


5: Spam Detection
=================

By default spam will not be detected.

To enable spam detection, you must set the spam_detection config option.

.. code-block:: yml

    rps_guestbook:
        spam_detection:
            enable: true

You must then install the OrnicarAkismentBundle or use a different spam dector and integrate it with the GuestbookBundle.

Using Akismet for Spam Detection
--------------------------------

The RPS GuestbookBundle is integrated with the OrnicarAkismentBundle.

To use AkismetBundle for spam detection, you must install the OrnicarAkismentBundle (https://github.com/ornicar/OrnicarAkismetBundle)
and configure it properly (see the docs for more information).

The GuestbookBundle automatically checks if the OrnicarAkismentBundle is installed.
If the OrnicarAkismentBundle is not installed, the GuestbookBundle will disable spam detection.
Setting the spam_detection config option in the app/config/config file will override this setting.

Using a custom spam detection service
-------------------------------------

You can also specify a custom spam detection service by setting the spam_detection service config option.

.. code-block:: yml

    rps_guestbook:
        spam_detection:
            service: my_spam_detector

Your spam detector service class must implement the ``RPS\GuestbookBundle\SpamDetection\SpamDetectorInterface`` interface.

6: Custom Views/Templates
=========================

You can specify custom templates/views by overriding the corresponding view parameter. E.g.

.. code-block:: yml

    rps_guestbook:
        view:
            frontend:
                list: MyprojectMyBundle:Frontend:index.html.twig
                new: MyprojectMyBundle:Frontend:new.html.twig

            admin:
                list: MyprojectMyBundle:Admin:index.html.twig
                edit: MyprojectMyBundle:Admin:edit.html.twig
                reply: MyprojectMyBundle:Admin:reply.html.twig

            mail:
                notify: MyprojectMyBundle:Mail:notify.txt.twig
