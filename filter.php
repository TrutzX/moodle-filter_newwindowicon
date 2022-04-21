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
 *  New Window Filter
 *
 *  This filter will find links that open in a new window
 *  and append the Font Awesome external link icon to them.
 *
 * @package    filter
 * @subpackage newwindowicon
 * @author     Max MacCluer and Michael Spall
 * @copyright  2022 Idaho State University, Max MacCluer and Michael Spall
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class filter_newwindowicon extends moodle_text_filter {

    public function filter($text, array $options = array()) {

        if (!is_string($text) or empty($text)) {
            // Non-string data can not be filtered anyway.
            return $text;
        }

        if (stripos($text, 'target=') === false) {
            // Performance shortcut - if there is no target attribute, nothing can match.
            return $text;
        }

        if (stripos($text, '<div class="helpdoclink">') > 0) {
            // Moodle help document links already add the new window icon, so we can skip them.
            return $text;
        }

        $pattern = '/<a[\s\S]*?<\/a>/i';

        $callback = function ($matches) {
            $targetpattern = "/target=[\"'][\s]*(_blank|_new)[\s]*[\"']/i";
            $targetfound = preg_match($targetpattern, $matches[0]);
            if($targetfound) {
                return substr($matches[0],0,-4) . "<i aria-hidden=\"true\" class=\"icon fa fa-external-link fa-fw fa fa-externallink fa-fw\" title=\"Opens in new window\"></i><span class=\"sr-only\">Opens in new window</span></a>";
            }
            return $matches[0];
        };

        return preg_replace_callback($pattern, $callback, $text);
    }
}