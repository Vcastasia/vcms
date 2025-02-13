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
namespace Xibo\Entity;

use Carbon\Carbon;
use Respect\Validation\Validator as v;
use Xibo\Factory\MediaFactory;
use Xibo\Factory\PermissionFactory;
use Xibo\Factory\TagFactory;
use Xibo\Helper\DateFormatHelper;
use Xibo\Service\ConfigServiceInterface;
use Xibo\Service\LogServiceInterface;
use Xibo\Storage\StorageServiceInterface;
use Xibo\Support\Exception\ConfigurationException;
use Xibo\Support\Exception\DuplicateEntityException;
use Xibo\Support\Exception\GeneralException;
use Xibo\Support\Exception\InvalidArgumentException;

/**
 * Class Media
 * @package Xibo\Entity
 *
 * @SWG\Definition()
 */
class Media implements \JsonSerializable
{
    use EntityTrait;

    /**
     * @SWG\Property(description="The Media ID")
     * @var int
     */
    public $mediaId;

    /**
     * @SWG\Property(description="The ID of the User that owns this Media")
     * @var int
     */
    public $ownerId;

    /**
     * @SWG\Property(description="The Parent ID of this Media if it has been revised")
     * @var int
     */
    public $parentId;

    /**
     * @SWG\Property(description="The Name of this Media")
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(description="The module type of this Media")
     * @var string
     */
    public $mediaType;

    /**
     * @SWG\Property(description="The file name of the media as stored in the library")
     * @var string
     */
    public $storedAs;

    /**
     * @SWG\Property(description="The original file name as it was uploaded")
     * @var string
     */
    public $fileName;

    // Thing that might be referred to
    /**
     * @SWG\Property(description="Tags associated with this Media")
     * @var Tag[]
     */
    public $tags = [];
    public $tagValues;

    /**
     * @SWG\Property(description="The file size in bytes")
     * @var int
     */
    public $fileSize;

    /**
     * @SWG\Property(description="The duration to use when assigning this media to a Layout widget")
     * @var int
     */
    public $duration = 0;

    /**
     * @SWG\Property(description="Flag indicating whether this media is valid.")
     * @var int
     */
    public $valid = 1;

    /**
     * @SWG\Property(description="Flag indicating whether this media is a system file or not")
     * @var int
     */
    public $moduleSystemFile = 0;

    /**
     * @SWG\Property(description="Timestamp indicating when this media should expire")
     * @var int
     */
    public $expires = 0;

    /**
     * @SWG\Property(description="Flag indicating whether this media is retired")
     * @var int
     */
    public $retired = 0;

    /**
     * @SWG\Property(description="Flag indicating whether this media has been edited and replaced with a newer file")
     * @var int
     */
    public $isEdited = 0;

    /**
     * @SWG\Property(description="A MD5 checksum of the stored media file")
     * @var string
     */
    public $md5;

    /**
     * @SWG\Property(description="The username of the User that owns this media")
     * @var string
     */
    public $owner;

    /**
     * @SWG\Property(description="A comma separated list of groups/users with permissions to this Media")
     * @var string
     */
    public $groupsWithPermissions;

    /**
     * @SWG\Property(description="A flag indicating whether this media has been released")
     * @var int
     */
    public $released = 1;

    /**
     * @SWG\Property(description="An API reference")
     * @var string
     */
    public $apiRef;

    /**
     * @var string
     * @SWG\Property(
     *  description="The datetime the Media was created"
     * )
     */
    public $createdDt;

    /**
     * @var string
     * @SWG\Property(
     *  description="The datetime the Media was last modified"
     * )
     */
    public $modifiedDt;

    /**
     * @var string
     * @SWG\Property(
     *  description="The option to enable the collection of Media Proof of Play statistics"
     * )
     */
    public $enableStat;

    /**
     * @var string
     * @SWG\Property(
     *  description="The orientation of the Media file"
     * )
     */
    public $orientation;

    // Private
    private $unassignTags = [];
    private $requestOptions = [];
    private $datesToFormat = ['expires'];
    // New file revision
    public $isSaveRequired;
    public $isRemote;

    public $cloned = false;
    public $newExpiry;
    public $alwaysCopy = false;

    /**
     * @SWG\Property(description="The id of the Folder this Media belongs to")
     * @var int
     */
    public $folderId;

    /**
     * @SWG\Property(description="The id of the Folder responsible for providing permissions for this Media")
     * @var int
     */
    public $permissionsFolderId;

    public $widgets = [];
    public $displayGroups = [];
    public $layoutBackgroundImages = [];
    private $permissions = [];

    /**
     * @var ConfigServiceInterface
     */
    private $config;

    /**
     * @var MediaFactory
     */
    private $mediaFactory;

    /**
     * @var TagFactory
     */
    private $tagFactory;

    /**
     * @var PermissionFactory
     */
    private $permissionFactory;

    /**
     * Entity constructor.
     * @param StorageServiceInterface $store
     * @param LogServiceInterface $log
     * @param ConfigServiceInterface $config
     * @param MediaFactory $mediaFactory
     * @param PermissionFactory $permissionFactory
     * @param TagFactory $tagFactory
     */
    public function __construct($store, $log, $config, $mediaFactory, $permissionFactory, $tagFactory)
    {
        $this->setCommonDependencies($store, $log);

        $this->config = $config;
        $this->mediaFactory = $mediaFactory;
        $this->permissionFactory = $permissionFactory;
        $this->tagFactory = $tagFactory;
    }

    public function __clone()
    {
        // Clear the ID's and all widget/displayGroup assignments
        $this->mediaId = null;
        $this->widgets = [];
        $this->displayGroups = [];
        $this->layoutBackgroundImages = [];
        $this->permissions = [];

        // We need to do something with the name
        $this->name = sprintf(__('Copy of %s on %s'), $this->name, Carbon::now()->format(DateFormatHelper::getSystemFormat()));

        // Set so that when we add, we copy the existing file in the library
        $this->fileName = $this->storedAs;
        $this->storedAs = null;
        $this->cloned = true;
    }

    /**
     * Get Id
     * @return int
     */
    public function getId()
    {
        return $this->mediaId;
    }

    public function getPermissionFolderId()
    {
        return $this->permissionsFolderId;
    }

    /**
     * Get Owner Id
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Sets the Owner
     * @param int $ownerId
     */
    public function setOwner($ownerId)
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return int
     * @throws GeneralException
     */
    private function countUsages()
    {
        $this->load(['fullInfo' => true]);

        return count($this->widgets) + count($this->displayGroups) + count($this->layoutBackgroundImages);
    }

    /**
     * Is this media used
     * @param int $usages threshold
     * @return bool
     * @throws GeneralException
     */
    public function isUsed($usages = 0)
    {
        return $this->countUsages() > $usages;
    }

    /**
     * Assign Tag
     * @param Tag $tag
     * @return $this
     * @throws GeneralException
     */
    public function assignTag($tag)
    {
        $this->load();

        if ($this->tags != [$tag]) {

            if (!in_array($tag, $this->tags)) {
                $this->tags[] = $tag;
            } else {
                foreach ($this->tags as $currentTag) {
                    if ($currentTag === $tag->tagId && $currentTag->value !== $tag->value) {
                        $this->tags[] = $tag;
                    }
                }
            }
        } else {
            $this->getLog()->debug('No Tags to assign');
        }

        return $this;
    }

    /**
     * Unassign tag
     * @param Tag $tag
     * @return $this
     * @throws GeneralException
     */
    public function unassignTag($tag)
    {
        $this->load();

        foreach ($this->tags as $key => $currentTag) {
            if ($currentTag->tagId === $tag->tagId && $currentTag->value === $tag->value) {
                $this->unassignTags[] = $tag;
                array_splice($this->tags, $key, 1);
            }
        }

        $this->getLog()->debug(sprintf('Tags after removal %s', json_encode($this->tags)));

        return $this;
    }

    /**
     * @param array[Tag] $tags
     */
    public function replaceTags($tags = [])
    {
        if (!is_array($this->tags) || count($this->tags) <= 0)
            $this->tags = $this->tagFactory->loadByMediaId($this->mediaId);

        if ($this->tags != $tags) {
            $this->unassignTags = array_udiff($this->tags, $tags, function ($a, $b) {
                /* @var Tag $a */
                /* @var Tag $b */
                return $a->tagId - $b->tagId;
            });

            $this->getLog()->debug(sprintf('Tags to be removed: %s', json_encode($this->unassignTags)));

            // Replace the arrays
            $this->tags = $tags;

            $this->getLog()->debug(sprintf('Tags remaining: %s', json_encode($this->tags)));
        } else {
            $this->getLog()->debug('Tags were not changed');
        }
    }

    /**
     * Validate
     * @param array $options
     * @throws GeneralException
     */
    public function validate($options)
    {
        if (!v::stringType()->notEmpty()->validate($this->mediaType))
            throw new InvalidArgumentException(__('Unknown Module Type'), 'type');

        if (!v::stringType()->notEmpty()->length(1, 100)->validate($this->name))
            throw new InvalidArgumentException(__('The name must be between 1 and 100 characters'), 'name');

        // Check the naming of this item to ensure it doesn't conflict
        $params = array();
        $checkSQL = 'SELECT `name` FROM `media` WHERE `name` = :name AND userid = :userId';

        if ($this->mediaId != 0) {
            $checkSQL .= ' AND mediaId <> :mediaId AND IsEdited = 0 ';
            $params['mediaId'] = $this->mediaId;
        }
        else if ($options['oldMedia'] != null && $this->name == $options['oldMedia']->name) {
            $checkSQL .= ' AND IsEdited = 0 ';
        }

        $params['name'] = $this->name;
        $params['userId'] = $this->ownerId;

        $result = $this->getStore()->select($checkSQL, $params);

        if (count($result) > 0)
            throw new DuplicateEntityException(__('Media you own already has this name. Please choose another.'));
    }

    /**
     * Load
     * @param array $options
     * @throws GeneralException
     */
    public function load($options = [])
    {
        if ($this->loaded || $this->mediaId == null) {
            return;
        }

        $options = array_merge([
            'deleting' => false,
            'fullInfo' => false
        ], $options);

        $this->getLog()->debug(sprintf('Loading Media. Options = %s', json_encode($options)));

        // Tags
        $this->tags = $this->tagFactory->loadByMediaId($this->mediaId);

        // Are we loading for a delete? If so load the child models, unless we're a module file in which case
        // we've no need.
        if ($this->mediaType !== 'module' && ($options['deleting'] || $options['fullInfo'])) {
            // Permissions
            $this->permissions = $this->permissionFactory->getByObjectId(get_class($this), $this->mediaId);
        }

        $this->loaded = true;
    }

    /**
     * Save this media
     * @param array $options
     * @throws ConfigurationException
     * @throws DuplicateEntityException
     * @throws InvalidArgumentException
     * @throws GeneralException
     */
    public function save($options = [])
    {
        $this->getLog()->debug('Save for mediaId: ' . $this->mediaId);

        $options = array_merge([
            'validate' => true,
            'oldMedia' => null,
            'deferred' => false,
            'saveTags' => true
        ], $options);

        if ($options['validate'] && $this->mediaType != 'module')
            $this->validate($options);

        // Add or edit
        if ($this->mediaId == null || $this->mediaId == 0) {
            $this->add();

            // Always set force to true as we always want to save new files
            $this->isSaveRequired = true;

            $this->audit($this->mediaId, 'Added', ['mediaId' => $this->mediaId, 'name' => $this->name, 'mediaType' => $this->mediaType, 'fileName' => $this->fileName, 'folderId' => $this->folderId]);
        }
        else {
            $this->edit();

            // If the media file is invalid, then force an update (only applies to module files)
            $expires = $this->getOriginalValue('expires');
            $this->isSaveRequired = ($this->isSaveRequired || $this->valid == 0 || ($expires > 0 && $expires < Carbon::now()->format('U')));
            $this->audit($this->mediaId, 'Updated', $this->getChangedProperties());
        }

        if ($options['deferred']) {
            $this->getLog()->debug('Media Update deferred until later');
        } else {
            $this->getLog()->debug('Media Update happening now');

            // Call save file
            if ($this->isSaveRequired)
                $this->saveFile();
        }

        if ($options['saveTags']) {
            // Remove unwanted ones
            if (is_array($this->unassignTags)) {
                foreach ($this->unassignTags as $tag) {
                    /* @var Tag $tag */
                    $tag->unassignMedia($this->mediaId);
                    $tag->save();
                }
            }

            // Save the tags
            if (is_array($this->tags)) {
                foreach ($this->tags as $tag) {
                    /* @var Tag $tag */
                    $tag->assignMedia($this->mediaId);
                    $tag->save();
                }
            }
        }
    }

    /**
     * Save Async
     * @param array $options
     * @return $this
     * @throws ConfigurationException
     * @throws DuplicateEntityException
     * @throws GeneralException
     * @throws InvalidArgumentException
     */
    public function saveAsync($options = [])
    {
        $options = array_merge([
            'deferred' => true,
            'requestOptions' => []
        ], $options);
        $this->requestOptions = $options['requestOptions'];
        $this->save($options);

        return $this;
    }

    /**
     * Delete
     * @param array $options
     * @throws GeneralException
     */
    public function delete($options = [])
    {
        $options = array_merge([
            'rollback' => false
        ], $options);

        if ($options['rollback']) {
            $this->deleteRecord();
            $this->deleteFile();
            return;
        }

        $this->load(['deleting' => true]);

        foreach ($this->permissions as $permission) {
            /* @var Permission $permission */
            $permission->delete();
        }

        foreach ($this->tags as $tag) {
            /* @var Tag $tag */
            $tag->unassignMedia($this->mediaId);
            $tag->save();
        }

        $this->deleteRecord();
        $this->deleteFile();

        $this->audit($this->mediaId, 'Deleted', ['mediaId' => $this->mediaId, 'name' => $this->name, 'mediaType' => $this->mediaType, 'fileName' => $this->fileName]);
    }

    /**
     * Add
     */
    private function add()
    {
        // The originalFileName column has limit of 254 characters
        // if the filename basename that we are about to save is still over the limit, attempt to strip query string
        // we cannot make any operations directly on $this->fileName, as that might be still needed to processDownloads
        $fileName = basename($this->fileName);
        if (strpos(basename($fileName), '?')) {
            $fileName = substr(basename($fileName), 0, strpos(basename($fileName), '?'));
        }

        $this->mediaId = $this->getStore()->insert('
            INSERT INTO `media` (`name`, `type`, duration, originalFilename, userID, retired, moduleSystemFile, released, apiRef, valid, `createdDt`, `modifiedDt`, `enableStat`, `folderId`, `permissionsFolderId`, `orientation`)
              VALUES (:name, :type, :duration, :originalFileName, :userId, :retired, :moduleSystemFile, :released, :apiRef, :valid, :createdDt, :modifiedDt, :enableStat, :folderId, :permissionsFolderId, :orientation)
        ', [
            'name' => $this->name,
            'type' => $this->mediaType,
            'duration' => $this->duration,
            'originalFileName' => $fileName,
            'userId' => $this->ownerId,
            'retired' => $this->retired,
            'moduleSystemFile' => (($this->moduleSystemFile) ? 1 : 0),
            'released' => $this->released,
            'apiRef' => $this->apiRef,
            'valid' => 0,
            'createdDt' => Carbon::now()->format(DateFormatHelper::getSystemFormat()),
            'modifiedDt' => Carbon::now()->format(DateFormatHelper::getSystemFormat()),
            'enableStat' => $this->enableStat,
            'folderId' => ($this->folderId === null) ? 1 : $this->folderId,
            'permissionsFolderId' => ($this->permissionsFolderId == null) ? 1 : $this->permissionsFolderId,
            'orientation' => $this->orientation
        ]);
    }

    /**
     * Edit
     */
    private function edit()
    {
        $sql = '
          UPDATE `media`
            SET `name` = :name,
                duration = :duration,
                retired = :retired,
                moduleSystemFile = :moduleSystemFile,
                editedMediaId = :editedMediaId,
                isEdited = :isEdited,
                userId = :userId,
                released = :released,
                apiRef = :apiRef,
                modifiedDt = :modifiedDt,
                `enableStat` = :enableStat,
                expires = :expires,
                folderId = :folderId,
                permissionsFolderId = :permissionsFolderId,
                orientation = :orientation
           WHERE mediaId = :mediaId
        ';

        $params = [
            'name' => $this->name,
            'duration' => $this->duration,
            'retired' => $this->retired,
            'moduleSystemFile' => $this->moduleSystemFile,
            'editedMediaId' => $this->parentId,
            'isEdited' => $this->isEdited,
            'userId' => $this->ownerId,
            'released' => $this->released,
            'apiRef' => $this->apiRef,
            'mediaId' => $this->mediaId,
            'modifiedDt' => Carbon::now()->format(DateFormatHelper::getSystemFormat()),
            'enableStat' => $this->enableStat,
            'expires' => $this->expires,
            'folderId' => $this->folderId,
            'permissionsFolderId' => $this->permissionsFolderId,
            'orientation' => $this->orientation
        ];

        $this->getStore()->update($sql, $params);
    }

    /**
     * Delete record
     */
    private function deleteRecord()
    {
        $this->getStore()->update('DELETE FROM media WHERE MediaID = :mediaId', ['mediaId' => $this->mediaId]);
    }

    /**
     * Save File to Library
     *  works on files that are already in the File system
     * @throws ConfigurationException
     */
    public function saveFile()
    {
        $libraryFolder = $this->config->getSetting('LIBRARY_LOCATION');

        // Work out the extension
        $lastPeriod = strrchr($this->fileName, '.');

        // Determine the save name
        if ($lastPeriod === false) {
            $saveName = $this->mediaId;
        } else {
            $saveName = $this->mediaId . '.' . strtolower(substr($lastPeriod, 1));
        }

        if(isset($this->urlDownload) && $this->urlDownload === true) {

            // for upload via URL, handle cases where URL do not have specified extension in url
            // we either have a long string after lastPeriod or nothing

            if (isset($this->extension) && (strlen($lastPeriod) > 3 || $lastPeriod === false)) {
                $saveName = $this->mediaId . '.' . $this->extension;
            }

            $this->storedAs = $saveName;
        }

        $this->getLog()->debug('saveFile for "' . $this->name . '" [' . $this->mediaId . '] with storedAs = "'
            . $this->storedAs . '", fileName = "' . $this->fileName . '" to "' . $saveName . '". Always Copy = "'
            . $this->alwaysCopy . '", Cloned = "' . $this->cloned . '"');

        // If the storesAs is empty, then set it to be the moved file name
        if (empty($this->storedAs) && !$this->alwaysCopy) {

            // We could be a fresh file entirely, or we could be a clone
            if ($this->cloned) {
                $this->getLog()->debug('Copying cloned file: ' . $libraryFolder . $this->fileName);
                // Copy the file into the library
                if (!@copy($libraryFolder . $this->fileName, $libraryFolder . $saveName))
                    throw new ConfigurationException(__('Problem copying file in the Library Folder'));

            } else {
                $this->getLog()->debug('Moving temporary file: ' . $libraryFolder . 'temp/' . $this->fileName);
                // Move the file into the library
                if (!$this->moveFile($libraryFolder . 'temp/' . $this->fileName, $libraryFolder . $saveName))
                    throw new ConfigurationException(__('Problem moving uploaded file into the Library Folder'));
            }

            // Set the storedAs
            $this->storedAs = $saveName;
        }
        else {
            // We have pre-defined where we want this to be stored
            if (empty($this->storedAs)) {
                // Assume we want to set this automatically (i.e. we are set to always copy)
                $this->storedAs = $saveName;
            }

            if ($this->isRemote) {
                $this->getLog()->debug('Moving temporary file: ' . $libraryFolder . 'temp/' . $this->name);

                // Move the file into the library
                if (!$this->moveFile($libraryFolder . 'temp/' . $this->name, $libraryFolder . $this->storedAs))
                    throw new ConfigurationException(__('Problem moving downloaded file into the Library Folder'));
            } else {
                $this->getLog()->debug('Copying specified file: ' . $this->fileName);

                if (!@copy($this->fileName, $libraryFolder . $this->storedAs)) {
                    $this->getLog()->error('Cannot copy %s to %s', $this->fileName, $libraryFolder . $this->storedAs);
                    throw new ConfigurationException(__('Problem copying provided file into the Library Folder'));
                }
            }
        }

        // Work out the MD5
        $this->md5 = md5_file($libraryFolder . $this->storedAs);
        $this->fileSize = filesize($libraryFolder . $this->storedAs);

        // Set to valid
        $this->valid = 1;

        // Resize image dimensions if threshold exceeds
        // This also sets orientation
        $this->assessDimensions();

        // Update the MD5 and storedAs to suit
        $this->getStore()->update('UPDATE `media` SET md5 = :md5, fileSize = :fileSize, storedAs = :storedAs, expires = :expires, released = :released, orientation = :orientation, valid = 1 WHERE mediaId = :mediaId', [
            'fileSize' => $this->fileSize,
            'md5' => $this->md5,
            'storedAs' => $this->storedAs,
            'expires' => $this->expires,
            'released' => $this->released,
            'orientation' => $this->orientation,
            'mediaId' => $this->mediaId
        ]);
    }

    private function assessDimensions()
    {
        if ($this->mediaType === 'image' || ($this->mediaType === 'module' && $this->moduleSystemFile === 0)) {
            $libraryFolder = $this->config->getSetting('LIBRARY_LOCATION');
            $filePath = $libraryFolder . $this->storedAs;
            list($imgWidth, $imgHeight) = @getimagesize($filePath);

            $resizeThreshold = $this->config->getSetting('DEFAULT_RESIZE_THRESHOLD');
            $resizeLimit = $this->config->getSetting('DEFAULT_RESIZE_LIMIT');

            $this->orientation = ($imgWidth >= $imgHeight) ? 'landscape' : 'portrait';

            // Media released set to 0 for large size images
            // if image size is greater than Resize Limit then we flag that image as too big
            if ($resizeLimit > 0 && ($imgWidth > $resizeLimit || $imgHeight > $resizeLimit)) {
                $this->released = 2;
                $this->getLog()->debug('Image size is too big. MediaId '. $this->mediaId);
            } elseif ($resizeThreshold > 0) {
                if ($imgWidth > $imgHeight) { // 'landscape';
                    if ($imgWidth <= $resizeThreshold) {
                        $this->released = 1;
                    } else {
                        if ($resizeThreshold > 0) {
                            $this->released = 0;
                            $this->getLog()->debug('Image exceeded threshold, released set to 0. MediaId '. $this->mediaId);
                        }
                    }
                } else { // 'portrait';
                    if ($imgHeight <= $resizeThreshold) {
                        $this->released = 1;
                    } else {
                        if ($resizeThreshold > 0) {
                            $this->released = 0;
                            $this->getLog()->debug('Image exceeded threshold, released set to 0. MediaId '. $this->mediaId);
                        }
                    }
                }
            }
        }
    }

    /**
     * Release an image from image processing
     * @param $md5
     * @param $fileSize
     */
    public function release($md5, $fileSize)
    {
        // Update the MD5 and fileSize
        $this->getStore()->update('UPDATE `media` SET md5 = :md5, fileSize = :fileSize, released = :released, modifiedDt = :modifiedDt WHERE mediaId = :mediaId', [
            'fileSize' => $fileSize,
            'md5' => $md5,
            'released' => 1,
            'mediaId' => $this->mediaId,
            'modifiedDt' => Carbon::now()->format(DateFormatHelper::getSystemFormat())
        ]);
        $this->getLog()->debug('Updating image md5 and fileSize. MediaId '. $this->mediaId);

    }

    /**
     * Delete a Library File
     */
    private function deleteFile()
    {
        // Make sure storedAs isn't null
        if ($this->storedAs == null) {
            $this->getLog()->error(sprintf('Deleting media [%s] with empty stored as. Skipping library file delete.', $this->name));
            return;
        }

        // Library location
        $libraryLocation = $this->config->getSetting("LIBRARY_LOCATION");

        // 3 things to check for..
        // the actual file, the thumbnail, the background
        // video cover image and its thumbnail
        if (file_exists($libraryLocation . $this->storedAs)) {
            unlink($libraryLocation . $this->storedAs);
        }

        if (file_exists($libraryLocation . 'tn_' . $this->storedAs)) {
            unlink($libraryLocation . 'tn_' . $this->storedAs);
        }

        if (file_exists($libraryLocation . 'tn_' . $this->mediaId . '_videocover.png')) {
            unlink($libraryLocation . 'tn_' . $this->mediaId . '_videocover.png');
        }

        if (file_exists($libraryLocation . $this->mediaId . '_videocover.png')) {
            unlink($libraryLocation . $this->mediaId . '_videocover.png');
        }
    }

    /**
     * Workaround for moving files across file systems
     * @param $from
     * @param $to
     * @return bool
     */
    private function moveFile($from, $to)
    {
        // Try to move the file first
        $moved = rename($from, $to);

        if (!$moved) {
            $this->getLog()->info('Cannot move file: ' . $from . ' to ' . $to . ', will try and copy/delete instead.');

            // Copy
            $moved = copy($from, $to);

            // Delete
            if (!@unlink($from)) {
                $this->getLog()->error('Cannot delete file: ' . $from . ' after copying to ' . $to);
            }
        }

        return $moved;
    }

    /**
     * Download URL
     * @return string
     */
    public function downloadUrl()
    {
        return $this->fileName;
    }

    /**
     * Download Sink
     * @return string
     */
    public function downloadSink()
    {
        return $this->config->getSetting('LIBRARY_LOCATION') . 'temp' . DIRECTORY_SEPARATOR . $this->name;
    }

    /**
     * Get optional options for downloading media files
     * @return array
     */
    public function downloadRequestOptions()
    {
        return $this->requestOptions;
    }
}