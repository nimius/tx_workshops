<?php
namespace NIMIUS\Workshops\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use NIMIUS\Workshops\Domain\Model\Date;
use NIMIUS\Workshops\Domain\Model\Location;
use NIMIUS\Workshops\Domain\Model\Workshop;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\QueryResult;

class OpenGraphUtility
{
    /**
     * Extracts the OpenGraph tags that should be added to the page for the given workshop
     *
     * Returns an array with a property -> content mapping for metatags
     *
     * @param Workshop $workshop
     * @param QueryResult $upcoming
     * @return array
     */
    public static function extractOpenGraphInformationFromWorkshop(Workshop $workshop, $upcoming)
    {
        // TODO REFACTOR configuration utility...
        $ts = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_workshops.']['settings.'];
        if (!$ts['openGraph']) {
            return [];
        }

        $name = $workshop->getName();
        $description = $workshop->getAbstract();
        $image = self::extractImageFromWorkshop($workshop, $upcoming);
        $location = self::extractLocationFromUpcoming($upcoming);

        // use description, if abstract was empty
        if (empty($description)) {
            $cutToLength = 150;
            $addDots = strlen($description) > $cutToLength;
            $description = substr($workshop->getDescription(), 0, $cutToLength);
            if ($addDots) {
                $description .= '...';
            }
        }

        $openGraphTags = self::getBaseOpenGraphInformation();
        $openGraphTags['og:type'] = 'place';
        $openGraphTags['og:title'] = $name;
        $openGraphTags['og:description'] = $description;
        $openGraphTags['og:image'] = $image;
        $openGraphTags['place:location:latitude'] = $location->getLatitude();
        $openGraphTags['place:location:longitude'] = $location->getLongitude();

        if ($ts['openGraph.']['twitterCards']) {
            $openGraphTags['twitter:card'] = 'summary_large_image';
            $openGraphTags['twitter:title'] = $name;
            $openGraphTags['twitter:description'] = $description;
            $openGraphTags['twitter:image'] = $image;
        }

        return $openGraphTags;
    }

    /**
     * gets meta tags from an array.
     * The key -> value pairs will be mapped to property & content
     *
     * @param array $tags
     * @return string
     */
    public static function getOpenGraphMetaTags(array $tags)
    {
        $metaTags = [];
        foreach ($tags as $property => $content) {
            if (!$content) {
                continue;
            }
            $metaTags[] = '<meta property="' . $property . '" content="' . htmlentities($content) . '" />';
        }
        return implode("\n", $metaTags);
    }

    /**
     * Returns an array of base open graph configuration that is the same for every displayed item
     * @return array
     */
    private static function getBaseOpenGraphInformation()
    {
        $openGraphTags = [
            'og:url' => ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI']
        ];
        $ts = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_workshops.']['settings.'];

        // add twitter username if available
        if ($ts['openGraph.']['twitterCards'] && !empty($ts['openGraph.']['twitterCards.']['username'])) {
            $openGraphTags['twitter:site'] = $ts['openGraph.']['twitterCards.']['username'];
            $openGraphTags['twitter:author'] = $ts['openGraph.']['twitterCards.']['username'];
        }

        // add facebook app id if available
        if ($ts['openGraph.']['facebook'] && !empty($ts['openGraph.']['facebook.']['appId'])) {
            $openGraphTags['fb:app_id'] = $ts['openGraph.']['facebook.']['appId'];
        }

        return $openGraphTags;
    }

    /**
     * Returns the image url that is to be used for the given workshop or null if no image has been found
     * @param Workshop $workshop
     * @param QueryResult $upcoming
     * @return string|null
     */
    private static function extractImageFromWorkshop(Workshop $workshop, $upcoming)
    {

        // first: check images
        foreach ($workshop->getImages() as $imageFileReference) {
            /** @var FileReference $imageFileReference */
            $original = $imageFileReference->getOriginalResource();
            if (!$original) {
                continue;
            }

            $url = $original->getPublicUrl();
            if (!$url) {
                continue;
            }

            return ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . $url;
        }

        // then: check upcoming dates for locations
        $location = self::extractLocationFromUpcoming($upcoming);
        if ($location) {
            return 'https://maps.googleapis.com/maps/api/staticmap?size=300x300&zoom=7'
            . '&center=' . $location->getLatitude() . ',' . $location->getLongitude()
            . '&markers=' . $location->getLatitude() . ',' . $location->getLongitude();
        }

        // return null, if nothing else returned an image
        return null;
    }

    /**
     * Gets the first location in the upcoming dates or returns null, if none found
     * @param QueryResult $upcoming
     * @return Location|null
     */
    private static function extractLocationFromUpcoming($upcoming)
    {
        foreach ($upcoming as $date) {
            /** @var Date $date */
            $location = $date->getLocation();
            if ($location) {
                return $location;
            }
        }
        return null;
    }
}
