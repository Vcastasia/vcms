<?php
/**
 * Copyright (C) 2021 Xibo Signage Ltd
 *
 * Xibo - Digital Signage - http://www.xibo.org.uk
 *
 * This file is part of Xibo.
 *
 * Xibo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Xibo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Xibo.  If not, see <http://www.gnu.org/licenses/>.
 */


namespace Xibo\Factory;

use Carbon\Carbon;
use Xibo\Entity\Notification;
use Xibo\Entity\User;
use Xibo\Support\Exception\NotFoundException;

/**
 * Class NotificationFactory
 * @package Xibo\Factory
 */
class NotificationFactory extends BaseFactory
{
    /** @var  UserGroupFactory */
    private $userGroupFactory;

    /** @var  DisplayGroupFactory */
    private $displayGroupFactory;

    /**
     * Construct a factory
     * @param User $user
     * @param UserFactory $userFactory
     * @param UserGroupFactory $userGroupFactory
     * @param DisplayGroupFactory $displayGroupFactory
     */
    public function __construct($user, $userFactory, $userGroupFactory, $displayGroupFactory)
    {
        $this->setAclDependencies($user, $userFactory);

        $this->userGroupFactory = $userGroupFactory;
        $this->displayGroupFactory = $displayGroupFactory;
    }

    /**
     * @return Notification
     */
    public function createEmpty()
    {
        return new Notification($this->getStore(), $this->getLog(), $this->userGroupFactory, $this->displayGroupFactory);
    }

    /**
     * @param string $subject
     * @param string $body
     * @param Carbon $date
     * @param bool $isEmail
     * @param bool $addGroups
     * @return Notification
     * @throws NotFoundException
     */
    public function createSystemNotification($subject, $body, $date, $isEmail = true, $addGroups = true)
    {
        $userId = $this->getUser()->userId;

        $notification = $this->createEmpty();
        $notification->subject = $subject;
        $notification->body = $body;
        $notification->createdDt = $date->format('U');
        $notification->releaseDt = $date->format('U');
        $notification->isEmail = ($isEmail) ? 1 : 0;
        $notification->isInterrupt = 0;
        $notification->userId = $userId;
        $notification->isSystem = 1;

        if ($addGroups) {
            // Add the system notifications group - if there is one.
            foreach ($this->userGroupFactory->getSystemNotificationGroups() as $group) {
                /* @var \Xibo\Entity\UserGroup $group */
                $notification->assignUserGroup($group);
            }
        }

        return $notification;
    }

    /**
     * Get by Id
     * @param int $notificationId
     * @return Notification
     * @throws NotFoundException
     */
    public function getById($notificationId)
    {
        $notifications = $this->query(null, ['notificationId' => $notificationId]);

        if (count($notifications) <= 0)
            throw new NotFoundException();

        return $notifications[0];
    }

    /**
     * @param string $subject
     * @param int $fromDt
     * @param int $toDt
     * @return Notification[]
     * @throws NotFoundException
     */
    public function getBySubjectAndDate($subject, $fromDt, $toDt)
    {
        return $this->query(null, ['subject' => $subject, 'createFromDt' => $fromDt, 'createToDt' => $toDt]);
    }

    public function getByOwnerId($ownerId)
    {
        return $this->query(null, ['ownerId' => $ownerId, 'disableUserCheck' => 1]);
    }

    /**
     * @param null $sortOrder
     * @param array $filterBy
     * @return Notification[]
     * @throws NotFoundException
     */
    public function query($sortOrder = null, $filterBy = [])
    {
        $entries = [];
        $sanitizedFilter = $this->getSanitizer($filterBy);

        if ($sortOrder == null)
            $sortOrder = ['subject'];

        $params = array();
        $select = 'SELECT `notification`.notificationId,
            `notification`.subject,
            `notification`.createDt,
            `notification`.releaseDt,
            `notification`.body,
            `notification`.isEmail,
            `notification`.isInterrupt,
            `notification`.isSystem,
            `notification`.filename,
            `notification`.originalFileName,
            `notification`.nonusers,
            `notification`.userId ';

        $body = ' FROM `notification` ';

        $body .= ' WHERE 1 = 1 ';

        if ($sanitizedFilter->getInt('notificationId') !== null) {
            $body .= ' AND `notification`.notificationId = :notificationId ';
            $params['notificationId'] = $sanitizedFilter->getInt('notificationId');
        }

        if ($sanitizedFilter->getString('subject') != null) {
            $body .= ' AND `notification`.subject = :subject ';
            $params['subject'] = $sanitizedFilter->getString('subject');
        }

        if ($sanitizedFilter->getInt('createFromDt') != null) {
            $body .= ' AND `notification`.createDt >= :createFromDt ';
            $params['createFromDt'] = $sanitizedFilter->getInt('createFromDt');
        }

        if ($sanitizedFilter->getInt('releaseDt') != null) {
            $body .= ' AND `notification`.releaseDt >= :releaseDt ';
            $params['releaseDt'] = $sanitizedFilter->getInt('releaseDt');
        }

        if ($sanitizedFilter->getInt('createToDt') != null) {
            $body .= ' AND `notification`.createDt < :createToDt ';
            $params['createToDt'] = $sanitizedFilter->getInt('createToDt');
        }

        if ($sanitizedFilter->getInt('onlyReleased') === 1) {
            $body .= ' AND `notification`.releaseDt <= :now ';
            $params['now'] = Carbon::now()->format('U');
        }

        if ($sanitizedFilter->getInt('ownerId') !== null) {
            $body .= ' AND `notification`.userId = :ownerId ';
            $params['ownerId'] = $sanitizedFilter->getInt('ownerId');
        }

        // User Id?
        if ($sanitizedFilter->getInt('userId') !== null) {
            $body .= ' AND `notification`.notificationId IN (
              SELECT notificationId 
                FROM `lknotificationuser`
               WHERE userId = :userId 
            )';
            $params['userId'] = $sanitizedFilter->getInt('userId');
        }

        // Display Id?
        if ($sanitizedFilter->getInt('displayId') !== null) {
            $body .= ' AND `notification`.notificationId IN (
              SELECT notificationId 
                FROM `lknotificationdg`
                    INNER JOIN `lkdgdg`
                    ON `lkdgdg`.parentId = `lknotificationdg`.displayGroupId
                    INNER JOIN `lkdisplaydg`
                    ON `lkdisplaydg`.displayGroupId = `lkdgdg`.childId
               WHERE `lkdisplaydg`.displayId = :displayId 
            )';
            $params['displayId'] = $sanitizedFilter->getInt('displayId');
        }

        self::viewPermissionSql('Xibo\Entity\Notification', $body, $params, '`notification`.notificationId', '`notification`.userId', $filterBy);

        // Sorting?
        $order = '';
        if (is_array($sortOrder))
            $order .= 'ORDER BY ' . implode(',', $sortOrder);

        $limit = '';
        // Paging
        if ($filterBy !== null && $sanitizedFilter->getInt('start') !== null && $sanitizedFilter->getInt('length') !== null) {
            $limit = ' LIMIT ' . intval($sanitizedFilter->getInt('start'), 0) . ', ' . $sanitizedFilter->getInt('length', ['default' => 10]);
        }

        $sql = $select . $body . $order . $limit;

        foreach ($this->getStore()->select($sql, $params) as $row) {
            $entries[] = $this->createEmpty()->hydrate($row);
        }

        // Paging
        if ($limit != '' && count($entries) > 0) {
            $results = $this->getStore()->select('SELECT COUNT(*) AS total ' . $body, $params);
            $this->_countLast = intval($results[0]['total']);
        }

        return $entries;
    }
}
