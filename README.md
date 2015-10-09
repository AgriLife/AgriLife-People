# AgriLife People

__Plugin URI:__ https://github.com/AgriLife/AgriLife-People

__Description:__ People custom post type with some other goodies

__Version:__ 1.1

__Author:__ J. Aaron Eaton

__Author URI:__ http://channeleaton.com

__License:__ GPL2

## Requirements

The following plugins must be installed and activated:

* Advanced Custom Fields
* ACF: Repeater Field
* ACF: Flexible Content Field
* ACF: Gallery Field
* Jetpack by WordPress.com

To set up the repo for development for the first time, navigate to the directory in the console and run "npm run once".

To watch for changes to Sass files, run "npm watch".

To manually compile Sass files, run "npm build".

## Notices

* This is a fork of the now deprecated AgriLife Staff plugin. You may continue to use AgriLife Staff, but updates will not be made.

## Changelog

### 1.0

* Fixed resume link to be conditional
* Forcing reduced font size on email addresses
* Flushing rewrite rules on activation/deactivation
* Alerts users if required plugins are not installed/activated
* Commented and cleaned up code

### 0.9.5

* Fixed search not working with new templates
* Added "search" parameter to people_listing shortcode

### 0.9.4

* Fixed query
* Placed query in static method for easy reuse
* Added Resume/CV field

### 0.9.3

* Fixed archive template title
* Fixed name going blank when using quick edit

### 0.9.2

* Allowing shortcode to filter by Type
* Fixed taxonomy archive template

### 0.9.1

* Fixed people search box not displaying on some themes
* Added blurb field to Featured Person widget

### 0.9

* Forked AgriLife Staff plugin
* Updated to use Advanced Custom Fields
* Added flexible content areas for people pages
* Tweaked default styles