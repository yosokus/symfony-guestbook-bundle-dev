Installation
============

1. Add the following lines to your ``composer.json``

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

**Or** you can do both steps in one command

.. code-block:: bash

    php composer.phar require rps/core-bundle:dev-master rps/guestbook-bundle:dev-master


#. Publish the bundle assets.

Run the following command

.. code-block:: php

    php app/console assets:install web [--symlink]

use the ``--symlink`` option to use symlinks to bundle assets.


#. Enable the bundle

Add the RPSCoreBundle and RPSGuestbookBundle to your application kernel.

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


#. Import Guestbook routing file

Add the following to you routing file

.. code-block:: yml

    # app/config/routing.yml
    rps_guestbook:
        resource: "@RPSGuestbookBundle/Resources/config/routing.yml"
        prefix:   /


#. Update your schema

Run the following command

    app/console doctrine:schema:update --force


#. Enable the translator in your configuration

.. code-block:: yml

    # app/config/config.yml
    framework:
        translator: { fallback: ~ }


For more information about translations, check the `Symfony Translation documentation`_

.. _`Symfony Translation documentation`: http://symfony.com/doc/current/book/translation.html


Other topics
============
#. `Doctrine Configuration`_

#. `Mailer Configuration`_

#. `Pager Configuration`_

#. `Spam Detection`_

#. `Views/Templates`_

#. `Default Configuration`_

.. _Doctrine Configuration: Resources/doc/doctrine.rst
.. _Mailer Configuration: Resources/doc/mailer.rst
.. _Pager Configuration: Resources/doc/pager.rst
.. _`Spam Detection`: Resources/doc/spam_detection.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Guestbook Administration`: Resources/doc/admin.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
