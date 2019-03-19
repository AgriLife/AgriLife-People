# AgriLife People

__Plugin URI:__ https://github.com/AgriLife/AgriLife-People

__Description:__ People custom post type with some other goodies

__Version:__ 1.1

__Author:__ J. Aaron Eaton, Zachary Watkins

__License:__ GPL2.0+

## Usage

The AgriLife People plugin allows you to display the faculty and staff of your department in a structured, easy-to-use manner. To get started you will need to activate the plugin on your site or submit a First Call ticket to have our support staff activate it for you. Once the plugin is activated you're ready to create your first person in the database.

### Creating a Person

1. To begin creating a new person, click on 'People' in the dashboard then click the 'Add New' link.

![Add New Person](https://communications.agrilife.org/files/agrilife-people-documentation-media/add-new-300x188.png)

2. The first few fields are fairly standard for people directories. Only the first and last name fields are required.

![Person Fields](https://communications.agrilife.org/files/agrilife-people-documentation-media/Add_New_Person_‹_AgriLife_People_Demo_—_WordPress-300x194.png)

3. Adding an image is simple. Just click the 'Add Image' button and upload your image. Don't forget to add a title and alt. text. You can also attach a resume/CV to the person's profile. [See the video](https://communications.agrilife.org/files/agrilife-people-documentation-media/Adding-Files.mp4) for more details.

4. The next few fields are Undergraduate Education, Graduate Education, Courses & Awards. These special fields allow you to create as many entries as necessary. [The next video](https://communications.agrilife.org/files/agrilife-people-documentation-media/Repeaters1.mp4) shows you how they work.

5. At the bottom of the page you have a content field to display text, images and galleries in each person page in a flexible manner. The content fields can even be reordered by dragging and dropping. [Check out the video](https://communications.agrilife.org/files/agrilife-people-documentation-media/Content-Fields.mp4).

6. Each person can be categorized by type for easy display on your site. You can add them in the person edit page or on People -> Types.  [Check out the video](https://communications.agrilife.org/files/agrilife-people-documentation-media/Types.mp4).

7. Just click 'Publish' and the person will be shown on the website. Click 'View Person' to see the results.

![Buzz Lightyear Profile](https://communications.agrilife.org/files/agrilife-people-documentation-media/Lightyear__Buzz___AgriLife_People_Demo-300x194.png)

### Viewing People

The first and most common method of viewing your list of people is the automatically generated 'People' page. You can find this at `<your-site-url>/people`. In order for this view to work, you must not have an existing page named 'People'.

![People Page](https://communications.agrilife.org/files/agrilife-people-documentation-media/People___AgriLife_People_Demo-300x194.png)


You can also view people by each type. This page is also automatically created and can be found at `<your-site-url/people/<type-slug>`. In this case our type slug is 'heroes'. It is the lowercase version of the type 'Heroes'.

![Heroes Type Page](https://communications.agrilife.org/files/agrilife-people-documentation-media/Heroes___Types___AgriLife_People_Demo-300x194.png)

Lastly, you can insert a list of people in any post or page you like by using the shortcode `[people_listing]`. You can even filter by type and choose whether or not to show the search form.

`[people_listing]` Show Everyone

`[people_listing type="heros"]` Show only Heroes

![Shortcodes](https://communications.agrilife.org/files/agrilife-people-documentation-media/Add_New_Page_%E2%80%B9_AgriLife_People_Demo_%E2%80%94_WordPress.png)


## Requirements

The following plugins must be installed and activated:

* Advanced Custom Fields PRO, version 5 or higher

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
 
