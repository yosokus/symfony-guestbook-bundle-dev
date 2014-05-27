<?php

/*
 * This file is part of the RPSGuestbookBundle package.
 *
 * (c) Yos Okus <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\GuestbookBundle\Mailer;

use RPS\GuestbookBundle\Model\EntryInterface;
use RPS\GuestbookBundle\Event\Events;
use RPS\GuestbookBundle\Event\MailEvent;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Base class for the Mailer.
 */
abstract class BaseMailer implements MailerInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface 	$dispatcher
     * @param \Symfony\Component\Templating\EngineInterface                 $templating
     * @param array                                                         $config
     */
    public function __construct(EventDispatcherInterface $dispatcher, EngineInterface $templating, $config)
    {
        $this->config = $config;
        $this->dispatcher = $dispatcher;
        $this->templating = $templating;
    }

    /**
     * @param EntryInterface $entry
     *
     * @return mixed
     */
    public function sendAdminNotification(EntryInterface $entry)
    {
        $emailTitle = str_replace(
            array('{name}','{email}'),
            array($entry->getName(), $entry->getEmail()),
            $this->config['notification']['title']
		);

        $mailOptions = array();
        $mailOptions['subject'] = $emailTitle;
        $mailOptions['from'] = $this->config['notification']['from'];
        $mailOptions['to'] = $this->config['notification']['to'];
        $mailOptions['body'] = $this->templating->render(
            $this->config['notification']['view'],
            array('entry' => $entry)
        );

        $event = new MailEvent($entry, $mailOptions);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_NOTIFY, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }
		
		$this->sendEmail($mailOptions);
        $this->dispatcher->dispatch(Events::ENTRY_POST_NOTIFY, $event);

		return true;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface		$form
     * @param EntryInterface 							$entry
     *
     * @return boolean
     */
    public function sendReply(FormInterface $form, EntryInterface $entry)
    {
        $mailOptions = array();
        $mailOptions['from'] = $form->get('senderEmail')->getData();
        $mailOptions['to'] = $form->get('email')->getData();
        $mailOptions['body'] = $form->get('message')->getData();
        $mailOptions['subject'] = $form->get('title')->getData();

        $event = new MailEvent($entry, $mailOptions);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_REPLY, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }
		
		$this->sendEmail($mailOptions);
        $this->dispatcher->dispatch(Events::ENTRY_POST_REPLY, $event);
		
		return true;
    }
}
