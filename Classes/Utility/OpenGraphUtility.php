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
use NIMIUS\Workshops\Utility\ConfigurationUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\QueryResult;

/**
 * OpenGraph utility.
 *
 * Provides functionality for building open graph information.
 */
class OpenGraphUtility
{
    /**
     * Extracts the OpenGraph tags that should be added to the page for the given workshop.
     *
     * Returns an array with a property -> content mapping for meta tags.
     *
     * @param Workshop $workshop
     * @param QueryResult $upcoming
     * @return array
     */
    public static function extractOpenGraphInformationFromWorkshop(Workshop $workshop, $upcoming)
    {
        $settings = ConfigurationUtility::getTyposcriptConfiguration();
        if (!(int)$settings['openGraph']) {
            return [];
        }

        $name = $workshop->getName();
        $description = $workshop->getAbstract();
        $image = self::extractImageFromWorkshop($workshop, $upcoming);
        $location = self::extractLocationFromUpcoming($upcoming);

        // Use description if abstract is empty.
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

        if ($settings['openGraph.']['twitterCards']) {
            $openGraphTags['twitter:card'] = 'summary_large_image';
            $openGraphTags['twitter:title'] = $name;
            $openGraphTags['twitter:description'] = $description;
            $openGraphTags['twitter:image'] = $image;
        }

        return $openGraphTags;
    }

    /**
     * Get meta tags from an array.
     *
     * The key -> value pairs will be mapped to property and content.
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
     * Returns an array of base open graph configuration that is the same for every displayed item.
     *
     * @return array
     */
    private static function getBaseOpenGraphInformation()
    {
        $openGraphTags = [
            'og:url' => ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI']
        ];
        
        $settings = ConfigurationUtility::getTyposcriptConfiguration()['openGraph.'];
        
        // Add twitter username if available.
        if ($settings['twitterCards'] && !empty($settings['twitterCards.']['username'])) {
            $openGraphTags['twitter:site'] = $settings['twitterCards.']['username'];
            $openGraphTags['twitter:author'] = $settings['twitterCards.']['username'];
        }

        // Add facebook app id if available.
        if ($settings['facebook'] && !empty($settings['facebook.']['appId'])) {
            $openGraphTags['fb:app_id'] = $settings['facebook.']['appId'];
        }

        return $openGraphTags;
    }

    /**
     * Returns the image url that is to be used for the given workshop or null if no image has been found.
     *
     * @param Workshop $workshop
     * @param QueryResult $upcoming
     * @return string|null
     */
    private static function extractImageFromWorkshop(Workshop $workshop, $upcoming)
    {
        // Try to define an image based on workshop images.
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

        // If no image has been set, assign a static google maps image from the next upcoming location.
        $location = self::extractLocationFromUpcoming($upcoming);
        if ($location) {
            return 'https://maps.googleapis.com/maps/api/staticmap?size=300x300&zoom=7'
            . '&center=' . $location->getLatitude() . ',' . $location->getLongitude()
            . '&markers=' . $location->getLatitude() . ',' . $location->getLongitude();
        }

        return null;
    }

    /**
     * Gets the first location in the upcoming dates, or returns null if none found.
     *
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
