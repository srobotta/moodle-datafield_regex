# Database Field Regular expression

![Release](https://img.shields.io/badge/Release-1.1-blue.svg)
[![Moodle Plugin
CI](https://github.com/srobotta/moodle-datafield_regex/workflows/Moodle%20Plugin%20CI/badge.svg?branch=master)](https://github.com/srobotta/moodle-datafield_regex/actions?query=workflow%3A%22Moodle+Plugin+CI%22+branch%3Amaster)
![Supported](https://img.shields.io/badge/Moodle-4.1+-orange.svg)
[![License GPL-3.0](https://img.shields.io/github/license/srobotta/moodle-datafield_regex?color=lightgrey)](https://github.com/srobotta/moodle-datafield_regex/blob/master/LICENSE)

This plugin provides a new field type for regular expression for the
database activity. The field is very similar to the "Short text" field.
The main difference is that input can be validated against a regular
expression that must match before the value is accepted.

## Installation

Please run the following steps:
1. Extract the zip archive into 
`<moodle_install_dir>/mod/data/field/`. 
1. Rename the newly created directory `moodle-datafield_regex` into `regex`
so that the files from the repository are inside the directory hierarchy
`<moodle_install_dir>/mod/data/field/regex`.
1. Run `php mod/data/field/regex/cli/install.php` to symlink/copy
the icon file from the plugin directory into the `mod/data/pix/field`
directory. This step is optional, when not executed, the icon next to the
regex entry under the button "Create a field" and in the list of existing
fields will be missing.
1. Finish the installation via the Moodle admin page.

## Usage

Within your database activity in the *Fields* tab when creating a new
field, the selection contains the new item "Regex".

Apart from the standard settings for a field, there are:
* Regular expression: for the regular expression itself. The term
must not contain delimiter characters, such as `/`. Also, these characters
don't need to be escaped in the string.
* Case-sensitive: check for case in the regex term. This need to be checked
when the patter mixes upper case and lower case letters or, you want to explicit
have a certain format, without transformation of the values in post processing.
* Only partial match: By default the regular expression is applied to the entire
string (from the beginning to the end - using the anchor characters `^` and
`$`). To enable a partial match, this option can be set. The regex pattern
then must only match somewhere in the submitted value.

The other options *Required field* and *Allow autolink* are standard
Moodle options that do not change in this context.

## Attribution

The SVG icon representing the regular expression datatype plugin was
taken from https://iconduck.com/icons/275980/regex. It's published
under the [CC BY 4.0](https://iconduck.com/licenses/cc-by-4.0) license.

## Version History

### 1.0

Initial release.