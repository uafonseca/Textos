<?php
/**
 * Created by PhpStorm.
 * Name:  Ubel Angel Fonseca Cedeño
 * Email: ubelangelfonseca@gmail.com
 * Date:  21/12/20
 * Time:  11:58
 */

namespace App\Util;

/**
 * Class FileIcons
 * @package App\Util
 */
class FileIcons
{
    /**
     * @param $mime_type
     * @return string
     */
    public static function getIcon ($mime_type) {
        // List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
        $icon_classes = array(
            // Media
            'image' => 'fa-image',
            'audio' => 'fa-audio',
            'video' => 'fa-video',
            // Documents
            'application/pdf' => 'fa-file-pdf',
            'application/msword' => 'fa-file-word',
            'application/vnd.ms-word' => 'fa-file-word',
            'application/vnd.oasis.opendocument.text' => 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'fa-file-word',
            'application/vnd.ms-excel' => 'fa-file-excel-o',
            'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'fa-file-excel',
            'application/vnd.oasis.opendocument.spreadsheet' => 'fa-file-excel',
            'application/vnd.ms-powerpoint' => 'fa-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml' => 'fa-file-powerpoint',
            'application/vnd.oasis.opendocument.presentation' => 'fa-file-powerpoint',
            'text/plain' => 'fa-file-text',
            'text/html' => 'fa-file-code',
            'application/json' => 'fa-file-code',
            // Archives
            'application/gzip' => 'fa-file-archive',
            'application/zip' => 'fa-file-archive',
        );
        foreach ($icon_classes as $text => $icon) {
            if (strpos($mime_type, $text) === 0) {
                return $icon;
            }
        }
        return 'fa-file-o';
    }
}