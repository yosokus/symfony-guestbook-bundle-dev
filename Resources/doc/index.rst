Step 1 : Installation
=====================

Download and install RPSGuestbookBundle
---------------------------------------

Add the following lines in your `deps` file

.. code-block:: php

    //composer.json
    "require": {
    //...
        "rps/symfony-core-bundle": "dev-master",
        "rps/symfony-guestbook-bundle": "dev-master",
    //...
    }

Update the dependency

.. code-block:: bash

    php composer.phar require rps/symfony-guestbook-bundle"

    php composer.phar require "rps/symfony-core-bundle:dev-master rps/symfony-guestbook-bundle:dev-master"


Enable the bundle
-----------------

Add the RPSCoreBundle and RPSGuestbookBundle to your application kernel

.. code-block:: php

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new RPSCoreBundle\RPSCoreBundle(),
            new RPS\GuestbookBundle\RPSGuestbookBundle(),
        );
    }


Update your schema
------------------

Run the following command

    app/console doctrine:schema:update --force


Install the css file
--------------------

Click here:doc:`default_configuration` to see the default configuration

Default Configuration
---------------------

.. code-block:: yml

	rps_guestbook:
		db_driver: orm
		entry_per_page: 25				# number of entries to show on a page
		auto_publish: true				# publish new entries or wait for admin approval
		notify_admin: false				# send notification mail to admin when a new entry is saved
		date_format: "d/m/Y H:i:s"		# date format used

		mailer:
			admin_email: ~				# email the admin notification is sent to
			sender_email: ~				# sender email used
			email_title: ~				# (optional)

		class:
			model: ~					# (optional)
			manager: ~					# (optional)
			pager : ~					# (optional)
			mailer: ~					# (optional)

		view:
			frontend:
				list: ~					# guestbook entries view
				new: ~					# guestbook form

			admin:
				list: ~					# admin guestbook entries view
				edit: ~					# admin guestbook entry edit view
				reply: ~				# admin guestbook entry reply view

			mail:
				notify: ~				# notification mail template

		form:
			entry:
				name: ~
				type: ~
				class: ~				# guestbook entry form class

			edit:
				name: ~
				type: ~
				class: ~				# guestbook entry edit form class

			reply:
				name: ~
				type: ~
				class: ~				# guestbook entry reply form class

		spam_detection:
			enable: false				# set to true to enable spam detection
			service: ~					# custom spam detector service (optional)

		service:
			pager: ~					# custom pager service (optional)


Each configuration option can be overriden in the app/config/config.yml file


Step 2: Doctrine configuration
==============================

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


Step 3: Mailer Configuration
============================

To send emails, SwitfMailer must be installed and configured.

To send admin notification emails (email sent to the admin each time a new guestbook entry is saved),
you must enable the mailer service and set the mail ``admin_email`` and ``sender_email`` config options

.. code-block:: yml

	rps_guestbook:
		notify_admin: true

		mailer:
			admin_email: admin@localhost.com				# email the admin notification is sent to
			sender_email: admin@localhost.com				# sender email used
			email_title: New guestbook entry from {name}	# (optional)


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



Step 4: Pager Installation and Configuration
============================================
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


Step 5: Spam Detection
======================

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

Step 6: Custom Views/Templates
==============================

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
