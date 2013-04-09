About this theme
================

This is the Bootstrap theme for Moodle.

* package    theme
* subpackage Foundation
* copyright  2013 Danny Wahl <http://iyware.com>
* authors    Danny Wahl
* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

This theme has been created with the help of:
Danny Wahl

This theme is based on the Foundation Zurb CSS framework version 4.1.1.
HTML5 tags are used in the /layout/general.php file.


JavaScript Libraries
--------------------
Foundation uses the version of jQuery that is bundled with Moodle.  Zepto.js is included
but not called.  If you wish to use zepto.js instead of jQuery you must include it in the
theme->javascripts_footer() array.  It is recommened that you remove the theme_foundation_page_init()
function from the theme lib if you include Zepto.

Updating Zurb Foundation
========================

Zurb Foundation
---------------
This theme uses the original unmodified version 4.1.1 of Foundation. These are
compiled JS and CSS files (not SCSS) as well as dependent libraries. The Foundation repository is available on:

https://github.com/zurb/foundation

To update to the latest release of Foundation Zurb download the zip and overwrite the files in
/style/foundation/ and /javascript/foundation/

Licenses & Authors
==================

Zurb Foundation
---------------
Copyright (c) 2012 Mark Hayes

MIT License

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Moodle
------
Moodle is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Moodle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Moodle.  If not, see <http://www.gnu.org/licenses/>.