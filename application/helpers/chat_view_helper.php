<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('chat_view_avatar_colors')) {
    function chat_view_avatar_colors()
    {
        return array('#1a73e8', '#188038', '#e37400', '#c5221f', '#9334e6', '#e52592', '#1967d2', '#e8710a');
    }
}

if ( ! function_exists('chat_view_avatar_color')) {
    function chat_view_avatar_color($id, $colors = NULL)
    {
        $palette = is_array($colors) ? $colors : chat_view_avatar_colors();
        $index = abs((int) $id) % count($palette);

        return $palette[$index];
    }
}

if ( ! function_exists('chat_view_initials')) {
    function chat_view_initials($nom, $prenom)
    {
        return strtoupper(mb_substr($nom, 0, 1) . mb_substr($prenom, 0, 1));
    }
}

if ( ! function_exists('chat_view_attachment_meta')) {
    function chat_view_attachment_meta($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $map = array(
            'pdf' => array('icon' => 'fas fa-file-pdf', 'color' => '#d93025', 'label' => 'PDF'),
            'doc' => array('icon' => 'fas fa-file-word', 'color' => '#185abc', 'label' => 'Word'),
            'docx' => array('icon' => 'fas fa-file-word', 'color' => '#185abc', 'label' => 'Word'),
            'xls' => array('icon' => 'fas fa-file-excel', 'color' => '#137333', 'label' => 'Excel'),
            'xlsx' => array('icon' => 'fas fa-file-excel', 'color' => '#137333', 'label' => 'Excel'),
            'ppt' => array('icon' => 'fas fa-file-powerpoint', 'color' => '#c26401', 'label' => 'PowerPoint'),
            'pptx' => array('icon' => 'fas fa-file-powerpoint', 'color' => '#c26401', 'label' => 'PowerPoint'),
            'png' => array('icon' => 'fas fa-file-image', 'color' => '#a142f4', 'label' => 'Image'),
            'jpg' => array('icon' => 'fas fa-file-image', 'color' => '#a142f4', 'label' => 'Image'),
            'jpeg' => array('icon' => 'fas fa-file-image', 'color' => '#a142f4', 'label' => 'Image'),
            'gif' => array('icon' => 'fas fa-file-image', 'color' => '#a142f4', 'label' => 'Image'),
            'txt' => array('icon' => 'fas fa-file-lines', 'color' => '#5f6368', 'label' => 'Texte'),
            'csv' => array('icon' => 'fas fa-file-csv', 'color' => '#188038', 'label' => 'CSV'),
            'zip' => array('icon' => 'fas fa-file-zipper', 'color' => '#5f6368', 'label' => 'Archive'),
            'rar' => array('icon' => 'fas fa-file-zipper', 'color' => '#5f6368', 'label' => 'Archive')
        );

        if (isset($map[$extension])) {
            return $map[$extension];
        }

        return array('icon' => 'fas fa-file', 'color' => '#5f6368', 'label' => strtoupper($extension ? $extension : 'Fichier'));
    }
}

if ( ! function_exists('chat_view_format_bytes')) {
    function chat_view_format_bytes($bytes)
    {
        $size = (float) $bytes;
        $units = array('o', 'Ko', 'Mo', 'Go');
        $unit_index = 0;

        while ($size >= 1024 && $unit_index < count($units) - 1) {
            $size = $size / 1024;
            $unit_index++;
        }

        return number_format($size, ($unit_index === 0 ? 0 : 1), ',', ' ') . ' ' . $units[$unit_index];
    }
}