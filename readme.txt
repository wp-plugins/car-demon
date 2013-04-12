=== Car Demon ===
Contributors: Belphegor 
Donate link: http://www.cardemons.com/donate/
Tags: car dealer, automotive, car sales, car lots, auto dealer
Requires at least: 3.4.2
Tested up to: 3.4.2
Stable tag: 1.2.4
License: GPLv2

Car Demon is a PlugIn designed for car dealers. Full Inventory Management, Lead Forms with ADFxml support, Dynamic Lead Routing, Staff Page and more.

== Description ==

The Car Demon PlugIn is full of features. It has a general contact form, service appointment form, service quote form, trade-in form, a fiance application and a vehicle information form all with AdfXml support.

It also contains a powerful inventory management tool with optional VinQuery Integration. It has a custom front page slider, custom header and custom theme colors. You also have an optional compare vehicles tool, multiple location support and a whole lot more.

Customize The Site for Each Salesman!!!

Each sales person can have their own affiliate links to customize the site with their information. Contact information and forms can be routed directly to them. They can also have their own custom banner and even a customized slide on the homepage.

What can Car Demon do?

Car Demon was designed for Car Dealers looking for a powerful and inexpensive tool to manage all aspects of their website. Car Demon is that tool.

Since Car Demon is a WordPress PlugIn you can leverage all the power of WordPress to make your site stand out from the rest.

Full Featured Inventory Control

After you quickly and easily add and remove vehicles your visitors will be able to use the search widget to find the vehicles that most interest them. You have the option to enable a compare vehicle tool and the power to enable an auto load feature that continually loads inventory without the need to click on the next page.

Easy to Use Admin Area

Turn features on and off easily letting you customize your site with ease. Inventory management is a snap, quickly add and remove vehicles, upload photos & change prices.

Super Fast Load Times

Car Demon is optimized to load super fast, helping to reduce visitor bounce rates. With site load time now being used as a ranking factor, you'll never have to worry about your site getting hit with a Google performance penalty.


== Installation ==


Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.


Go to your admin area and expand the menu for "Cars for Sale" and click on "Car Demon Settings". Adjust your settings as desired then click "Update Car Demon".


We've also included a few buttons on this page to help you quickly create your lead forms. You can also use the shortcodes to add forms to any post or page. It is suggested that you only add one form per page.


Now it's time to setup your contact information.


If you sell vehicles at more than one location then go to your admin area and expand the menu for "Cars for Sale", click on locations and add an entry for each lot.


After you have setup your locations click on "Contact Settings", fill out and save the form for each location, INCLUDING THE DEFAULT. It is important to check and make sure your Finance Disclaimer and Descriptions are legal for your state. We take no responsibility for the legality of the default entries.


Adjust your theme settings and add widgets as desired.


You're now ready to start adding vehicles to your site. You'll find a short form on the Dashboard to let you quickly add vehicles or you can click on "Add New" listed under "Cars for Sale"

Please make sure you add a price to your vehicles. If you don't wish the price to be seen you can select "Do Not Show Prices" on the contact settings page.

Vehicles will not display in the search results until they have been marked published AND a price has been added to them.


== Frequently Asked Questions ==

* How do I view just a basic list of my vehicles?

First go to your admin area and click settings, then select permalinks. Add this as a custom permalink; /%postname%/%post_id%
Now click save. You should now be able to go to http://my_site/cars-for-sale and see a list of all your vehicles.

* How do I add people to my staff page?


You'll need to add them as users to the website, you can make them subscribers if you'd like. On their profile page you'll see a section labeled "Extra Profile Information", add a photo (120x120 suggested), a job title, a location and then click a radio button that matches their duties. Click save and you should now see them listed on your staff page.


* How do I route the different forms to different people?


You do this on the "Contact Settings Page" under "Cars for Sale".


* How do I setup a sales person with an affiliate link?


On each user profile page there is a segment called "Advanced Sales Information", the custom URL can be used by the sales person to customize the site with their information. "Under Custom Site Leads" you'll need to put a check next to each type of form they should receive. If you have multiple locations you can also decide if they should receive leads for just their location or for all locations.


For example, let's say we have a sales person name Bill. He only sells new cars and has permission to bid on trades, but doesn't have permission to work finance. You would check the "New Car Sales" and "Trade" boxes. Bill will now receive all "New Car" & "Trade" contact forms from anyone who entered the site using his affiliate link. His name and phone number will also appear on all new vehicle pages.


The querystring for the affiliate link can be used on almost any page. Simply add it to the end of the url and you're set.


If you're using the Car Demon theme or Car Demon front page you can add custom headers for each sales person and even a custom slide on the homepage.


* What happens if someone using an affiliate link uses the email a friend button?


All links in the email they recieve will contain the affiliate link, so the original sales person will still receive any future contacts from their friend. This is especially handy if a sales person wants to email a car to a potential customer. If the sales person is viewing the site with their own affiliate link then any vehicle they email to a customer with the "Email a Friend" feature will contain all of their contact info.


* How do I clear an affiliate link?


If you add ?sales_code=0 to the url for your site it will remove the cookie that stores the sales persons affiliate information and will restore the site to normal lead routing.


* Why don't my vehicles appear in the search even though I've published them?


Please make sure you add an "Asking Price" to your vehicles. If you don't wish the price to be seen you can select "Do Not Show Prices" on the contact settings page.


* Why don't my vehicles have titles even though I've entered a title for them?


By default Car Demon will display vehicles titles as Year Make Model Stock #, the title field is currently only used for descriptive purposes on the back end.


* How do I get the inventory to load new vehicles when I scroll to the bottom of the page?


Depending on your theme you may need to install the wp-page-navi PlugIn. The Car Demon PlugIn looks for the existing page navigation with specific id tags and uses those as an indicator for when the page has ended and which set of vehicles to load next.


* Does Car Demon have any hooks, filters or shortcodes for developers to use?


Yes it does. We are currently working on documentation for developers that will detail these features. If you would like to know more then please visit our website www.CarDemons.com and fill out a contact form and let us know.


* I have created a language translation and would like to share it, where do I send it?


We would love to include your translation! Please visit our website www.CarDemons.com and fill out a contact request form and let us know, we will be more than happy to test your file and include it in our next release.


* Does Car Demon use taxonomies for categorizing vehicles and how can I leverage that?


Yes, Car Demon uses several custom taxonomies; condition, body_style, vehicle_year, make, model and location. These can be used to create pages containing vehicle categories, for example; Let's say you want to provide links to all the different body styles, to link to all of the coupes in inventory you would create a link to http:/yoursite.com/body_style/coupe to link to all trucks it would be http:/yoursite.com/body_style/truck. You can use the same logic to leverage the other custom taxonomies as well.


* I'm having trouble using custom taxonomies, I keep getting a 404 or page not found error.


Make sure you update your permalinks to /%postname%/%post_id% this should resolve most issues with using custom taxonomies.


* How do I override the vehicle inventory and display pages?

Evan Parsons has done a great tutorial on how to override the default templates. It's a great place to get started if you want to modify the look and feel;
http://www.evanparsons.net/blog/post/2012/11/08/Overriding-Car-Demons-theme-template-and-putting-newest-cars-on-the-front-page.aspx

In the near future we'll be releasing several different styles and support for some of the most popular themes.


* How do I use the shortcodes to include forms in my pages?


Shortcodes can be used without arguments and will display a radio selection to determine the location to send the form.

Shortcodes for [part_request], [service_request], [service_quote], [trade] & [finance_form] now have the optional argument "location" added to them. 
The location argument accepts the name assigned to that form for the location you wish to send the form to and hides the radio selection.
For example, let's say you have a location called "Our Used Car Lot" and you have two different part departments, one that handle domestic vehicle parts and one that handles imported vehicle parts.
You will need to create 2 locations "Our Used Car Lot Domestic" and "Our Used Car Lot Imports", under contact settings you would enter a different name for Parts under both locations, ie "Domestic Parts" & "Import Parts".
You can now use the part_request shortcode on two different pages and route each one to the correct department.
Exp. [part_request location="Domestic Parts"] and [part_request location="Import Parts"]


* I only have one location, how do I hide the location radio buttons on my forms?


You will need to set the location argument for your form shortcodes to the form's contact name entered under the default location.
Exp. [part_request location="Default Part Name"], this will hide the location radio buttons.


* How do I get rid of the drop down on the Contact Us Form? I want it to always go to the same person.


The contact form shortcode, [contact_us], has an argument of "send_to" that accepts a single email address.
If you set this argument in your shortcode it will hide the drop down and send the contact form to the address you supplied.
Exp. [contact_us send_to="me@my_site.com"]


* How do I add a form as a Popup?


Two new optional arguments have been added to several of the form shortcodes; popup_id and popup_button.

These may be used with the following shortcodes; contact_us, part_request, service_form & service_quote
At this time they are NOT available for; trade or finance_form

By setting the popup_id argument to a unique value you tell Car Demon to simply add a button to the page that opens your form in a popup lightbox.
The popup_button argument allows you to customize what the button says.
For Example [part_request popup_id="1" popup_button="Request Parts"] this would create a button that says "Request Parts" which opens the parts request form in a popup lightbox.


== Screenshots ==
1. This is a quick look at the inventory management screen. You can quickly change prices and mark vehicles sold without opening each vehicle.

2. Here's a glance of what you can do with Car Demon and some of it's extensions. The site you see here is using the Car Demon Theme and the Car Demon Front Page PlugIn, both are available on our website; www.CarDemons.com

== Changelog ==
= 1.2.4 =
* Added link to user's custom landing page when using staff page shortcode.
* Tweaked the simple wide search from to include an option to search by stock number.
* Added custom photo to car contact array.
* Added shortcode 'highlight_staff', can be dropped into a page to display profile for specific sales person
* Added shortcode 'vehicle_cloud' to allow in page listing of inventory links, defaults to a list of body styles.
* Added shortcode 'vehicle_search_box', creates a small vehicle keyword search box. Uses arguements 'button' for the button text and 'message' to display a custom message above the search box.
* Increased maximum field length in calculator.
* Minor style updates.
= 1.2.3 =
* Making sure all changes from 1.2.1 made it into the updates
* Resolved issue with ADFxml not sending for all forms on IIS systems
* Minor CSS tweaks
* Updated Dutch Translation files from Ciprian Dracea.
* Fixed issue with Email a Friend form causing it to fail on submission.
* Added new shortcode [qualify] that creates a basic pre-qualification finance form. Has 3 optional arguements; location, popup_id, popup_button.
* Fixed spelling error in British Translation file.
* Added popup image option for single vehicle pages. If yes is selected then hovering over vehicle thumbnails will popup full size image.
* Added Portuguese Translation files thanks to the efforts of Danilo Favero (danilofavero).
* Restyled admin filter options for Cars for Sale, labels now line up properly.
* Updated .po file with all the new fields to be translated.
= 1.2.2 =
* SVN ate version 1.2.1, it did not digest well.
= 1.2.1 =
* Added options to Car Demon Settings to manage vehicle sorting options.
* Added British Translation file thanks to the efforts of Steven Coutts.
* Added Italian Translation file thanks to the efforts of WordPress.org User: Qax.
* Added option on vehicle edit page to show or hide vehicle option tabs if desired.
* Added option in Car Demon Settings to hide vehicle option tabs on All vehicles if desired.
* Continuing to clean up CSS.
* Split finance form into multiple pages so segments can be reused.
* Fixed error with calculator widget that caused prices with , to error.
* Fixed issue with vehicle descriptions not formatting correctly.
* Added Facebook meta fields to control Title, URL and Image sent using Facebook share button.
= 1.2.0 =
* Continued refining Template pages to reduce code needed to add new styles.
* Add several action hooks to templates; car_demon_sidebar, car_demon_vehicle_sidebar, car_demon_before_main_content, car_demon_after_main_content
* Added new arguments to several form shortcodes; popup_id and popup_button, these optional arguments allow you to add a button to a page that opens the form in a popup lightbox. See the FAQ for usage.
* Added admin option to display sold vehicles in search results if desired.
* Added admin option to BCC site admin when form is filled out.
* Added extra layer of data sanitization for Admin area inputs.
* A big thank you goes out to Ciprian Dracea for giving assistance in adding the shortcodes to all of the forms and helping to refine the PlugIn.
= 1.1.9 =
* Fixed issue with Vehicle Edit page that was causing options to not appear.
* Minor CSS tweaks.
* Moved template related functions to their own file in include folder.
* Continued refining Template pages to reduce code needed to add new styles.
* Move person.gif file to root images folder.
= 1.1.8 =
* Added admin setting to allow you to add text or HTML directly before the listings begin.
* Continued to refine template pages and style options.
* Added admin setting to use Post Title for Vehicle Title instead of 'Year Make Model'.
* Continued refining Template pages to reduce code needed to add new styles.
= 1.1.7 =
* Corrected issue preventing prices on vehicle edit list page from updating.
* Corrected issue causing images to not insert in posts.
* Cleaned up search and archive template. Moved custom queries to their own functions to make adding additional theme styles easier.
* Enhanced shotcodes for Forms to give users more power in reusing forms on different pages and controlling where each form goes.
* Shortcodes for part_request, service_request, service_quote, trade & finance_form now have the argument "location" added to them.
* Shortcode for contact_us now has the argument "send_to" added to it.
* See the FAQ on how to use the shortcodes.
= 1.1.6 =
* Adding updated translations for Dutch and Romanian, thanks again to the efforts of Ciprian Dracea (Drake).
= 1.1.5 =
* Fixed issue with vehicle archive page that caused archive pages to load with error.
* Minor css tweaks.
* Added option to add dynamic ribbons to all vehicles based on condition, mileage or price.
= 1.1.4 =
* Removed unused js files.
* Continued cleaning code to meet WordPress coding standards.
* Continued tweaking CSS.
* Fixed profile photo upload issue, path to js file had been changed and code was not updated.
* Added location support even if no vehicles have been added to a location.
* Fixed issue with newly added vehicles not updating sold status causing vehicles to not show.
* Added translations for Dutch and Romanian thanks to the efforts of Ciprian Dracea (Drake).
* Fixed issue with localization not working, again this was thanks to the efforts of Ciprian Dracea (Drake).
* Built new .po file.
* Removed banners on main photos by default and added ability to select an included banner or add a custom banner to each vehicle.
* For best results custom banners should be 112x112 transparent png or gif.
* Included currency symbol code into vehicle search form.
= 1.1.3 =
* Built first .po file for translation.
* Cleaned up more css.
* Added option to put currency symbol after price.
= 1.1.2 =
* Added ability to select custom currency symbol for prices.
= 1.1.1 =
* Fixed issue on adding new vehicle that caused error to popup.
* More css clean up. Trying to make code as clean and logical as possible.
= 1.1.0 =
* Moved vehicle options to more logical place on vehicle edit page and did away with the popup lightbox to edit options.
* More code cleaning and consolidation, enqueued more scripts and styles.
* Began process of making code semantic, moving inline styles to css classes. Lots to go.
= 1.0.92 =
* Minor code clean up and consolidation.
* Cleaned up misc js and css, enqueued multiple scripts and style sheets.
* Resolved jQuery no conflict issue on trade and finance form.
= 1.0.8 =
* Changed label on VinQuery.com ID  to VinQuery.com Access Code to match correct terminology.
* Added option to manually select VinQuery Report Type.
= 1.0.7 =
* Did some minor code consolidation.
= 1.0.6 =
* Fixed css issue with search widget kicking field onto next row when viewing on iPad.
* Cleaned up search forms.
= 1.0.5 =
* 1.0.4 Would not update to repository. Rebuit new tag and committed.
= 1.0.4 =
* Fixed issue on single vehicle page to prevent error when pulling similiar body styles when no body style has been entered.
* Updated ReadMe file for clarity on adding vehicles.
* Removed unneeded jQuery references.
* Changed paths for jQuery form handlers.
= 1.0.3 =
* Resolved issue with mini add vehicle form on dashboard.
= 1.0.2 =
* Cleaned up vehicle edit page and removed redundant items.
* Tried to make it easier to manage the options on each vehicle.
* Moved vehicle price fields in with other vehicle options.
* Changed function used to retrieve main thumbnail image on single vehicle page.
* Sanitized additional data input on admin side to prevent rogue editor from causing potential issues.
= 1.0.1 = 
* Fixed jQuery conflict on single cars theme page
= 1.0.0 =
* Initial Public Release

== Upgrade Notice ==
None