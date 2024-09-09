<?php

namespace App\Helpers;

class Validations
{
    /**
     * Checks if required fields are present in the request and validates them.
     *
     * @param array $request Request data.
     * @param array $fields Required fields associated with custom messages.
     * @return array List of error messages for missing or invalid fields.
     */
    public static function checkFields(array $request, array $fields): array
    {
        $missingFields = [];
        foreach ($fields as $field => $rules) {
            // Split rules and extract field label
            $rulesArray = explode('|', $rules);
            $fieldLabel = $rulesArray[0] ?? $field; // Use field name if label is not provided
            $isRequired = in_array('required', $rulesArray);
            
            // Check if the field is required and is missing or empty
            if ($isRequired && (!isset($request[$field]) || is_null($request[$field]) || trim($request[$field]) === '')) {
                $missingFields[] = "{$fieldLabel} is required.";
            }

            // Additional validations based on rules
            if (isset($request[$field]) && !is_null($request[$field]) && trim($request[$field]) !== '') {
                $value = $request[$field];

                // Validate string fields
                if (in_array('string', $rulesArray) && !is_string($value)) {
                    $missingFields[] = "The {$fieldLabel} must be a string.";
                }

                // Validate numeric fields
                if (in_array('numeric', $rulesArray) && !is_numeric($value)) {
                    $missingFields[] = "The {$fieldLabel} must be a number.";
                }

                // Validate email fields
                if (in_array('email', $rulesArray) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $missingFields[] = "The {$fieldLabel} must be a valid email address.";
                }
            }
        }

        return $missingFields;
    }
}
