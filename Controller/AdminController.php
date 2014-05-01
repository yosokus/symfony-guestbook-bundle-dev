<?php

/*
 * This file is part of the RPSGuestbookBundle package.
 *
 * (c) Yos Okusanya <yos.okus@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\GuestbookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Form;

/**
 * Guestbook Admin Controller.
 */
class AdminController extends BaseController
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

        //save current page to reuse later
        $this->get('request')->getSession()->set('rps_guestbook_page', $page);

        $view = $this->getView('admin.list');
        $form = $this->getFormFactory('entry');

        return $this->render($view, array(
                'form' => $form->createView(),
                'entries'=> $manager->getPaginatedList($page, $limit),
                'pagination_html' => $manager->getPaginationHtml(),
                'date_format' => $this->container->getParameter('rps_guestbook.date_format')
            )
        );
    }

    /**
     * Edits a entry/Show admin form
     *
     * @param Request	$request	Current request
     * @param int       $id			Guestbook id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        $entry = $this->getEntry($id);
        $form = $this->getFormFactory('edit');
        $form->setData($entry);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $entry = $form->getData();

                if ($this->getManager()->save($entry) !== false) {
                    $this->setFlashMessage('flash.update.success');

                    return $this->redirectToList();
                } else {
                    $this->setFlashMessage('flash.update.error', array(), 'error');
                }
            }
        }

        $view = $this->getView('admin.edit');

        return $this->render($view, array('form' => $form->createView(), 'id'=>$id,) );
    }

    /**
     * Reply a entry/Show reply form
     *
     * @param Request   $request	Current request
     * @param int   	$id			Guestbook id
     *
     * @return Response
     */
    public function replyAction(Request $request, $id)
    {
        $entry = $this->getEntry($id);

        $form = $this->getFormFactory('reply');
        $form->setData($entry);

        $message = $this->get('translator')->trans(
            'email.reply',
            array(
                '%name%'=> $entry->getName(),
                '%comment%'=> $entry->getComment(),
                '%id%'=> $id
            ),
            'RPSGuestbookBundle'
        );

        $form->get('message')->setData($message);
        $form->get('senderEmail')->setData($this->container->getParameter('rps_guestbook.mailer.admin_email'));

        if('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                // send reply
                if($this->get('rps_guestbook.mailer')->sendReply($form, $entry) !== false) {

                    // update reply fields
                    $this->getManager()->updateReplyFields($entry, $form);

                    $this->setFlashMessage('flash.reply.success', array(
                        '%name%'=>$entry->getName(),
                        '%email%'=>$entry->getEmail(),
                    ));

                    return $this->redirectToList();
                } else {
                    $this->setFlashMessage('flash.reply.error', array(), 'error');
                }
            }

        }

        $view = $this->getView('admin.reply');

        return $this->render($view, array('form' => $form->createView(), 'id'=>$id,) );
    }

    /**
     * Delete entry
     *
     * @param int $id Guestbook id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id)
    {
        $entry = $this->getEntry($id);

        if($this->getManager()->remove($entry) !== false) {
            $this->setFlashMessage('flash.remove.success');
        } else {
            $this->setFlashMessage('flash.remove.error', array('%id%'=>$id), 'error');
        }

        return $this->redirectToList();
    }

    /**
     * Delete a list of entries
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $ids = $request->get('ids', array());

        if (empty($ids)) {
            $this->setFlashMessage('flash.select_error.delete');

            return $this->redirectToList();
        }

        if ($this->getManager()->delete($ids) !== false) {
            $nbEntries = count($ids);
            $translated = $this->get('translator')->transChoice(
                'flash.delete.success',
                $nbEntries,
                array('%count%' => $nbEntries),
                'RPSGuestbookBundle'
            );

            $this->get('session')->getFlashBag()->add('notice', $translated);
        } else {
            $this->setFlashMessage('flash.delete.error', array('%ids%'=>implode(', ', $ids)), 'error');
        }

        return $this->redirectToList();
    }

    /**
     * Publish/Unpublish a list of entries
     *
     * @param Request   $request
     * @param integer   $state
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeStateAction(Request $request, $state)
    {
        $ids = $request->get('ids', array());

        if (!is_array($ids)) {
            $ids = array($ids);
        }

        $task = $state ? 'publish' : 'unpublish';

        if (empty($ids)) {
            $this->setFlashMessage('flash.select_error.' . $task);
        } else {
            if ($this->getManager()->updateState($ids, $state) !== false) {
                $nbEntries = count($ids);
                $translated = $this->get('translator')->transChoice(
                    'flash.' . $task . '.success',
                    $nbEntries,
                    array('%count%' => $nbEntries),
                    'RPSGuestbookBundle'
                );

                $this->get('session')->getFlashBag()->add('notice', $translated);

            } else {
                $this->setFlashMessage('flash.' . $task . 'error', array('%ids%'=>implode(', ', $ids)));
            }
        }

        return $this->redirectToList();
    }

    /**
     * Cancel edit
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function cancelAction()
    {
        return $this->redirectToList();
    }

    /**
     * Redirect to last viewed list page
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function redirectToList()
    {
        return $this->redirect(
            $this->generateUrl(
                'rps_guestbook_admin',
                array('page' => $this->get('request')->getSession()->get('rps_guestbook_page', 1))
            ));
    }
}
