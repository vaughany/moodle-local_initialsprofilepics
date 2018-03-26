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
 * Show a whole bunch of random profile pictures to test how they are generated.
 *
 * @package     local_initialsprofilepics
 * @copyright   2018 Paul Vaughan <paulieboo@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/vendor/autoload.php');

admin_externalpage_setup('local_initialsprofilepics_testing');

echo $OUTPUT->header();

$l = INITIALSPROFILEPICS_INITIALSPOOL;
$ll = strlen($l) - 1;

echo $OUTPUT->heading('Testing', 2);

echo $OUTPUT->heading('Your Profile Image', 4);
echo $OUTPUT->heading('With the currently saved settings, your profile image should look like this. (Note that if you have changed the settings but have not regenerated your profile image, the two may differ.)', 6);
$initials   = initialsprofilepics_get_initials_from_user($USER);
$shape      = get_config('local_initialsprofilepics', 'shape');
$colour     = initialsprofilepics_get_working_colour($initials);
$size       = 400;
$fontsize   = get_config('local_initialsprofilepics', 'fontsize');
$fontalpha  = get_config('local_initialsprofilepics', 'fontalpha');
echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen($initials, $shape, $colour, $size, $fontsize, $fontalpha)]);

// echo $OUTPUT->heading('Image generated for unit testing purposes.', 6);
// $initials       = ['A', 'Z'];
// $shape          = INITIALSPROFILEPICS_SHAPES_SQUARE;
// $colour         = initialsprofilepics_get_working_colour($initials);
// $size           = 200;
// $fontsize       = 1.4;
// $fontalpha      = 0.2;
// echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen($initials, $shape, $colour, $size, $fontsize, $fontalpha)]);

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('No Settings At All', 4);
echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen()]);

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('Testing for Anything', 4);
foreach (INITIALSPROFILEPICS_SHAPES as $shape) {
    foreach ([['日', '本'], ['A', 'B'], ['C', 'D'], ['E', 'F'], ['G', 'H']] as $initials) {
        $colour = initialsprofilepics_get_colour_from_initials($initials);
        echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen($initials, $shape, $colour, 200)]);
    }
    echo html_writer::empty_tag('br');
}

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('Generic Lettters and Common Symbols', 4);
foreach (INITIALSPROFILEPICS_SHAPES as $shape) {
    for ($j = 0; $j <= $ll; $j++) {
        $initials = [$l[$j], $l[$j]];
        $colour = initialsprofilepics_get_colour_from_initials($initials);
        echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen($initials, $shape, $colour, 100)]);
    }
    echo html_writer::empty_tag('br');
}

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('Random Letters, Numbers and Symbols', 4);
for ($j = 1; $j <= 40; $j++) {
    $initials = [$l[rand(0, $ll)], $l[rand(0, $ll)]];
    $shape = INITIALSPROFILEPICS_SHAPES[rand(0, count(INITIALSPROFILEPICS_SHAPES) - 1)];
    $colour = initialsprofilepics_get_colour_from_initials($initials);
    echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen($initials, $shape, $colour, 100)]);
}

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('Different Sizes', 4);
foreach (INITIALSPROFILEPICS_SHAPES as $shape) {
    $colour = initialsprofilepics_get_random_colour();
    foreach ([25, 33, 50, 75, 100, 150, 250, 333, 500] as $size) {
        $s = (string) $size;
        echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen([$s[0], $s[1]], $shape, $colour, $size)]);
    }
    echo html_writer::empty_tag('br');
}

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('One Each of All ' . count(INITIALSPROFILEPICS_COLOURS) . ' Colours', 4);
foreach (INITIALSPROFILEPICS_COLOURS as $colour) {
    echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen(['A', 'B'], INITIALSPROFILEPICS_SHAPES_CIRCLE, $colour, 200)]);
}

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('One Each of All ' . count(INITIALSPROFILEPICS_FONTSIZE) . ' Font Sizes', 4);
$colour = INITIALSPROFILEPICS_COLOURS[4];
foreach (INITIALSPROFILEPICS_FONTSIZE as $fontsize => $name) {
    echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen(['A', 'B'], INITIALSPROFILEPICS_SHAPES_CIRCLE, $colour, 250, $fontsize)]);
}

echo html_writer::empty_tag('hr');
echo $OUTPUT->heading('One Each of All ' . count(INITIALSPROFILEPICS_FONTSIZE) . ' Font Alpha-Transparency Settings', 4);
$colour = INITIALSPROFILEPICS_COLOURS[4];
foreach (INITIALSPROFILEPICS_FONTALPHA as $fontalpha => $name) {
    echo html_writer::empty_tag('img', ['src' => initialsprofilepics_create_and_dump_onscreen(['A', 'B'], INITIALSPROFILEPICS_SHAPES_CIRCLE, $colour, 200, 1.4, $fontalpha)]);
}

echo $OUTPUT->footer();
