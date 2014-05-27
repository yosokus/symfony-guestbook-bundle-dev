<?php

/*
 * This file is part of the RPSGuestbookBundle package
 *
 * (c) Yos Okus <yos.okusanya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RPS\GuestbookBundle\Mailer;

use Symfony\Component\Form\FormInterface;
use RPS\GuestbookBundle\Model\EntryInterface;

/**
 * Mailer Interface
 */
interface MailerInterface
{
    /**
     * @param EntryInterface $comment
     */
    public function sendAdminNotification(EntryInterface $comment);

    /**
     * @param \Symfony\Component\Form\FormInterface		$form
     * @param EntryInterface              				$comment
     */
    public function sendReply(FormInterface $form, EntryInterface $comment);

    /**
     * @param array $options
     */
    public function sendEmail(array $options);

}
