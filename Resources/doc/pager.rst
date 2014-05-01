Pager Installation and Configuration
====================================

Pagination is enabled by default.

Using WhiteOctoberPagerfantaBundle for pagination
-------------------------------------------------

The RPS GuestbookBundle is integrated with the WhiteOctoberPagerfantaBundle.

To use WhiteOctoberPagerfantaBundle for pagination, you must install the WhiteOctoberPagerfantaBundle_.

.. _WhiteOctoberPagerfantaBundle:: https://github.com/whiteoctober/WhiteOctoberPagerfantaBundle‎

The GuestbookBundle automatically checks if the WhiteOctoberPagerfantaBundle is installed.
If the WhiteOctoberPagerfantaBundle is not installed, the GuestbookBundle will disable pagination.

To limit the number of entries shown, set the ``entry_per_page`` config option

.. code-block:: yml

    rps_guestbook:
        entry_per_page: 25

Using a custom pager manager class
----------------------------------

You can specify your custom pager manager class by overriding the pager class option e.g.

.. code-block:: yml

    rps_guestbook:
        class:
            manager: MyProject\MyBundle\Pager\Pager

Your custom class must implement the ``\RPS\CoreBundle\Pager\PagerInterface`` interface.

Using a custom pager service
----------------------------

You can also specify a custom pager service to handle the guestbook entries pagination
by setting the pager service config option.

.. code-block:: yml

    rps_guestbook:
        service:
            pager: my_pager

Your pager service class must implement the ``\RPS\CoreBundle\Pager\PagerInterface`` interface.


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Mailer COnfiguration`_

#. `Spam Detection`_

#. `Views/Templates`_

#. `Guestbook Administration`_

#. `Default Configuration`_

.. _Installation: Resources/doc/index.rst
.. _`Doctrine Configuration`: Resources/doc/doctrine.rst
.. _`Mailer Configuration`: Resources/doc/mailer.rst
.. _`Spam Detection`: Resources/doc/spam_detection.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Guestbook Administration`: Resources/doc/admin.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
