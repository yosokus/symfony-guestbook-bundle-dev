Default Configuration
=====================

.. code-block:: yml

	rps_guestbook:
		db_driver: orm
		entry_per_page: 25				                            # number of entries to show on a page
		auto_publish: true				                            # publish new entries or wait for admin approval
		notify_admin: false				                            # send notification mail to admin when a new entry is saved
		date_format: "d/m/Y H:i:s"		                            # date format used

		mailer:
			admin_email: admin@localhost.com				        # email the admin notification is sent to
			sender_email: admin@localhost.com   				    # sender email used
			email_title: New guestbook entry from {name}			# (optional) notification email title

		class:
			model: RPS\GuestbookBundle\Entity\Entry					# (optional) default model
			manager: RPS\GuestbookBundle\Entity\EntryManager		# (optional) default manager
			pager : RPS\CoreBundle\Pager\PagerfantaORM				# (optional) default pager
			mailer: RPS\GuestbookBundle\Mailer\Mailer				# (optional) default mailer

		view:
			frontend:
				list: RPSGuestbookBundle:Frontend:index.html.twig	# guestbook entries view
				new: RPSGuestbookBundle:Frontend:new.html.twig	    # guestbook form

			admin:
				list: RPSGuestbookBundle:Admin:index.html.twig  	# admin guestbook entries view
				edit: RPSGuestbookBundle:Admin:edit.html.twig		# admin guestbook entry edit view
				reply: RPSGuestbookBundle:Admin:reply.html.twig		# admin guestbook entry reply view

			mail:
				notify: RPSGuestbookBundle:Mail:notify.txt.twig		# notification mail template

		form:
			entry:
				name: rps_guestbook_entry
				type: rps_guestbook_entry
				class: RPS\GuestbookBundle\Form\Type\EntryType		# guestbook entry form class

			edit:
				name: rps_guestbook_entry_edit
				type: rps_guestbook_entry_edit
				class: RPS\GuestbookBundle\Form\Type\EntryEditType	# guestbook entry edit form class

			reply:
				name: rps_guestbook_entry_reply
				type: rps_guestbook_entry_reply
				class: RPS\GuestbookBundle\Form\Type\EntryReplyType	# guestbook entry reply form class

		spam_detection:
			enable: false				                            # set to true to enable spam detection
			service: ~					                            # (optional) custom spam detector service

		service:
			pager: ~					                            # (optional) custom pager service

