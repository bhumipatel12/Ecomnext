<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace Google\Site_Kit_Dependencies\Google\Service\Analytics;

class EntityAdWordsLink extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'profileIds';
    protected $adWordsAccountsType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\AdWordsAccount::class;
    protected $adWordsAccountsDataType = 'array';
    protected $entityType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\EntityAdWordsLinkEntity::class;
    protected $entityDataType = '';
    public $id;
    public $kind;
    public $name;
    public $profileIds;
    public $selfLink;
    /**
     * @param AdWordsAccount[]
     */
    public function setAdWordsAccounts($adWordsAccounts)
    {
        $this->adWordsAccounts = $adWordsAccounts;
    }
    /**
     * @return AdWordsAccount[]
     */
    public function getAdWordsAccounts()
    {
        return $this->adWordsAccounts;
    }
    /**
     * @param EntityAdWordsLinkEntity
     */
    public function setEntity(\Google\Site_Kit_Dependencies\Google\Service\Analytics\EntityAdWordsLinkEntity $entity)
    {
        $this->entity = $entity;
    }
    /**
     * @return EntityAdWordsLinkEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    public function getKind()
    {
        return $this->kind;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setProfileIds($profileIds)
    {
        $this->profileIds = $profileIds;
    }
    public function getProfileIds()
    {
        return $this->profileIds;
    }
    public function setSelfLink($selfLink)
    {
        $this->selfLink = $selfLink;
    }
    public function getSelfLink()
    {
        return $this->selfLink;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\Analytics\EntityAdWordsLink::class, 'Google\\Site_Kit_Dependencies\\Google_Service_Analytics_EntityAdWordsLink');
