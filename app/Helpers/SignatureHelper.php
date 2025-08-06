<?php

namespace App\Helpers;

class SignatureHelper
{
    /**
     * Safely load a signature image and convert it to base64 for embedding in PDF
     *
     * @param string|null $signaturePath The stored path to the signature image
     * @return string|null Base64 encoded image or null if image doesn't exist
     */
    public static function getBase64Signature($signaturePath)
    {
        if (empty($signaturePath)) {
            return null;
        }

        try {
            // Remove 'storage/' prefix if it exists
            if (strpos($signaturePath, 'storage/') === 0) {
                $signaturePath = substr($signaturePath, 8);
            }
            
            $fullPath = storage_path('app/public/' . $signaturePath);
            
            if (file_exists($fullPath)) {
                return base64_encode(file_get_contents($fullPath));
            }
        } catch (\Exception $e) {
            // Log the error or handle it silently
            // \Log::error('Failed to load signature: ' . $e->getMessage());
        }
        
        return null;
    }
} 