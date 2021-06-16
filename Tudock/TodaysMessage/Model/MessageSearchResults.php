<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */
namespace Tudock\TodaysMessage\Model;

use Tudock\TodaysMessage\Api\Data\MessageSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Message search results.
 */
class MessageSearchResults extends SearchResults implements MessageSearchResultsInterface
{
}
