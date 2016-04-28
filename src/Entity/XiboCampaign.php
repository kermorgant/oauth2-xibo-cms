<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2016 Spring Signage Ltd
 * (XiboCampaign.php)
 */


namespace Xibo\OAuth2\Client\Entity;


use Xibo\OAuth2\Client\Exception\XiboApiException;

class XiboCampaign extends XiboEntity
{
    private $url = '/campaign';

    public $campaignId;
    public $ownerId;
    public $campaign;
    public $isLayoutSpecific = 0;
    public $numberLayouts;

    /**
     * @param int $start
     * @param int $length
     * @return array|XiboCampaign
     */
    public function get($start = 0, $length = 10)
    {
        $entries = [];
        $response = $this->doGet($this->url, [
            'start' => $start,
            'length' => $length
        ]);

        foreach ($response as $item) {
            $entries[] = $this->hydrate($item);
        }

        return $entries;
    }

    /**
     * @param $id
     * @return XiboCampaign
     * @throws XiboApiException
     */
    public function getById($id)
    {
        $response = $this->doGet($this->url, [
            'campaignId' => $id
        ]);

        if (count($response) <= 0)
            throw new XiboApiException('Expecting a single record, found ' . count($response));

        return $this->hydrate($response[0]);
    }

    /**
     * @param $campaign
     * @return XiboCampaign
     */
    public function create($campaign)
    {
        $this->ownerId = $this->getEntityProvider()->getMe()->getId();
        $this->campaign = $campaign;

        $response = $this->doPost($this->url, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * @param $campaign
     * @return XiboCampaign
     */
    public function edit($campaign)
    {
        $this->ownerId = $this->getEntityProvider()->getMe()->getId();
        $this->campaign = $campaign;

        $response = $this->doPut($this->url . '/' . $this->campaignId, $this->toArray());

        return $this->hydrate($response);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->doDelete($this->url . '/' . $this->campaignId);

        return true;
    }
}