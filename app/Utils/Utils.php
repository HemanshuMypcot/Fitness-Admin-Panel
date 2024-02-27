<?php
namespace App\Utils;
class Utils
{
	public static function flipTranslationArray(array $inputArray, array $fieldNamesToFlip = null)
    {
        foreach ($inputArray as $fieldName => $translations) {
            $fieldName = strpos($fieldName, '-')? formatName($fieldName,true) : $fieldName;
            if ((empty($fieldNamesToFlip) || in_array($fieldName, $fieldNamesToFlip)) && is_array($translations)) {
                foreach ($translations as $lang => $translatedValue) {
                    if (isset($inputArray[$lang])) {
                        $inputArray[$lang] = array_merge($inputArray[$lang], [$fieldName => $translatedValue]);
                    } else {
                        $inputArray[$lang] = [$fieldName => $translatedValue];
                    }
                }
                unset($inputArray[$fieldName]);
            }
        }
        return $inputArray;
    }
}
