<?php

/*
 * This file is part of the RPSGuestbookBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RPS\GuestbookBundle\Model;

/**
 * Interface to be implemented by the comment class.
 */
 
interface EntryInterface
{
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string 
     */
    public function getName();

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail();

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment);

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment();

    /**
     * Set created
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt);
	
    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt();

    /**
     * Set state
     *
     * @param boolean $state
     */
    public function setState($state);

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState();
	
    /**
     * Set modified
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();

    /**
     * Set replied
     *
     * @param boolean $replied
     */
    public function setReplied($replied);

    /**
     * Get replied
     *
     * @return boolean
     */
    public function getReplied();

    /**
     * Set repliedAt
     *
     * @param \DateTime $repliedAt
     */
    public function setRepliedAt($repliedAt);

    /**
     * Get repliedAt
     *
     * @return boolean
     */
    public function getRepliedAt();
	
    /**
     * Update reply fields
     */
    public function updateRepliedAt();
}
