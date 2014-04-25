<?php

/*
 * This file is part of the RPSGuestbookBundle package.
 *
 * (c) Yos Okus <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\GuestbookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Guestbook frontend controller.
 */
class GuestbookController extends BaseController
{
    /**
     * Shows the entries.
     *
     * @param int $page	query offset
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page=1)
    {
        $manager = $this->getManager();
        $limit = $this->container->getParameter('rps_guestbook.entry_per_page');

        $entries = $manager->getPaginatedList($page, $limit, array('state'=>1));
        $pagerHtml = $manager->getPaginationHtml();

        $view = $this->getView('frontend.list');
        $form = $this->getFormFactory('entry');

        return $this->render($view, array(
                'entries'=>$entries,
                'form' => $form->createView(),
                'pagination_html' => $pagerHtml,
                'date_format' => $this->container->getParameter('rps_guestbook.date_format')
            )
        );

    }

    /**
     * Adds a new Entry/Show guestbook form.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->container->get('rps_guestbook.form_factory.entry');

        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $entry = $form->getData();

                // check for spam
                if ($this->container->getParameter('rps_guestbook.enable_spam_detection')) {
                    $spamDetector = $this->container->get('rps_guestbook.spam_detector');

                    if ($spamDetector->isSpam($entry)) {
                        $this->setFlashMessage('flash.error.spam_detected', array(), 'error');

                        return $this->renderPage('new', array('form' => $form->createView()));
                    }
                }

                // save entry
                if ($this->getManager()->save($entry) !== false) {
                    $this->setFlashMessage('flash.save.success');

                    if(!$this->container->getParameter('rps_guestbook.auto_publish')) {
                        $this->setFlashMessage('flash.awaiting_approval');
                    }

                    // notify admin
                    if($this->container->getParameter('rps_guestbook.notify_admin')) {
                        $this->get('rps_guestbook.mailer')->sendAdminNotification($entry);
                    }

                    return $this->redirect($this->generateUrl('rps_guestbook_list'));
                } else {
                    $this->setFlashMessage('flash.error.bad_request', array(), 'error');
                }
            }
        }

        $view = $this->getView('frontend.new');

        return $this->render($view, array('form' => $form->createView()));
    }

}
