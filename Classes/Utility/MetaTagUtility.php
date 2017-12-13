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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\QueryResult;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * OpenGraph utility.
 *
 * Provides functionality for building open graph information.
 */
class MetaTagUtility
{
    /**
     * Extracts the meta tags that should be added to the page for the given workshop.
     * Currently Facebook OpenGraph Tags and Twitter Cards information is being generated.
     *
     * Returns an array with a property -> content mapping for meta tags that can be rendered
     * into full HTML <meta> tags using MetaTagUtility::renderTags().
     *
     * @see MetaTagUtility::renderTags()
     * @see https://dev.twitter.com/cards/getting-started
     * @see http://ogp.me/
     * @param Workshop $workshop
     * @param QueryResult $upcoming
     * @return array
     */
    public static function extractInformationFromWorkshop(Workshop $workshop, $upcoming)
    {
        $settings = ConfigurationUtility::getTyposcriptConfiguration();
        if (!(int)$settings['openGraph']) {
            return [];
        }

        $name = $workshop->getName();
        $description = $workshop->getAbstract();
        $image = self::extractImageFromWorkshop($workshop, $upcoming);

        // Use description if abstract is empty.
        if (empty($description)) {
            $cutToLength = 150;
            $addDots = strlen($description) > $cutToLength;
            $description = substr(strip_tags($workshop->getDescription()), 0, $cutToLength);
            if ($addDots) {
                $description .= '...';
            }
        }

        $openGraphTags = self::getBaseMetaInformation();
        $openGraphTags['og:type'] = 'place';
        $openGraphTags['og:title'] = $name;
        $openGraphTags['og:description'] = $description;
        $openGraphTags['og:image'] = $image;
        
        $location = self::extractLocationFromUpcoming($upcoming);
        if ($location) {
            $openGraphTags['place:location:latitude'] = $location->getLatitude();
            $openGraphTags['place:location:longitude'] = $location->getLongitude();
        }

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
    public static function renderTags(array $tags)
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
     * Returns an array of base meta information that is the same for every displayed item.
     *
     * @return array
     */
    private static function getBaseMetaInformation()
    {
        $openGraphTags = [
            'og:url' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')
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

            return GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . '/' . $url;
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
