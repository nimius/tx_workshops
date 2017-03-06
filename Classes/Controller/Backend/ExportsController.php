<?php
namespace NIMIUS\Workshops\Controller\Backend;

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
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Exports controller.
 *
 * Backend controller to export data.
 */
class ExportsController extends AbstractController
{
    /**
     * @var \TYPO3\CMS\Core\Utility\File\BasicFileUtility
     * @inject
     */
    protected $basicFileUtility;

    /**
     * New action.
     *
     * Displays a form to get a new export.
     *
     * @return void
     */
    public function createAction(Date $date)
    {
        $registrations = $date->getRegistrations()->toArray();
        $headers = [];
        foreach ($registrations[0]->toArray() as $field => $value) {
            $headers[] = $this->translateModelProperty('registration', $field);
        }

        $filename = $this->basicFileUtility->cleanFileName($date->getWorkshop()->getName()) . '.csv';
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Content-Transfer-Encoding: binary');

        ob_start();
        $stream = fopen('php://output', 'w');
        fputcsv($stream, $headers);
        foreach ($registrations as $registration) {
            $data = $registration->toArray();
            $values = array_values($data);

            $additionalValues = '';
            foreach ($data['additionalFields'] as $field => $value) {
                $additionalValues .= $field . ': ' . $value . "\r\n";
            }

            $additionalFieldsOffset = array_search('additionalFields', array_keys($data));
            $values[$additionalFieldsOffset] = $additionalValues;
            fputcsv($stream, $values);
        }
        fclose($stream);
        return ob_get_clean();
    }

    /**
     * Returns a translation label for the given model and property names.
     *
     * @param string $modelName
     * @param string $propertyName
     * @return string
     */
    protected function translateModelProperty($modelName, $propertyName)
    {
        return LocalizationUtility::translate(
            'model.' . $modelName . '.property.' . $propertyName,
            'workshops'
        );
    }
}
