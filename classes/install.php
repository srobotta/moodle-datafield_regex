<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Installation helper for datafield_regex.
 *
 * @package    datafield_regex
 * @copyright  2024 Stephan Robotta <stephan.robotta@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace datafield_regex;

/**
 * Helper class that copies the icon on installs and upgrades.
 */
class install {
    /**
     * Copy the regex icon into the mod/data/pix folder.
     * @return void
     */
    public static function copyicon() {
        $link = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '..', 'pix', 'field', 'icon.svg']);
        if (!file_exists($link)) {
            $target = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'pix', 'icon.svg']);
            echo 'Create symlink for regex icon... ';
            if (!\symlink($target, $link)) {
                echo 'failed' . PHP_EOL . 'Copy regex icon... ';
                if (!\copy($target, $link)) {
                    echo 'failed' . PHP_EOL . "Please copy {$target} to {$link}" . PHP_EOL;
                } else {
                    echo 'ok' . PHP_EOL;
                }
            } else {
                echo 'ok' . PHP_EOL;
            }
        }
    }
}
