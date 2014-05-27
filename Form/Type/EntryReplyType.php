<?php

/*
 * This file is part of the RPSGuestbookBundle
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RPS\GuestbookBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntryReplyType extends AbstractType
{	
    /**
     * @var string
     */
    private $entryClass;

    /**
     * Constructor
     *
     * @param string $entryClass
     */
    public function __construct($entryClass)
    {
        $this->entryClass = $entryClass;
    }

    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->add('email', 'email', array(
                'label' => 'form.reply.to',
                'translation_domain' => 'RPSGuestbookBundle',
            ))
            ->add('senderEmail', 'email', array(
                'mapped' => false,
                'label' => 'form.reply.from',
                'translation_domain' => 'RPSGuestbookBundle',
            ))
            ->add('title', 'text', array(
                'mapped' => false,
                'label' => 'form.reply.title',
                'translation_domain' => 'RPSGuestbookBundle',
            ))
            ->add('message', 'textarea', array(
                'mapped' => false,
                'label' => 'form.reply.message',
                'translation_domain' => 'RPSGuestbookBundle',
            ))
            ->getForm();
	}

    /**
     * {@inheritdoc}
     */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array('data_class' => $this->entryClass));
	}

    /**
     * {@inheritdoc}
     */
	public function getName()
	{
		return 'rps_guestbook_entry_reply';
	}
}