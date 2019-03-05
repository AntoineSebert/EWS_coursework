# Enterprise Web Systems Coursework

## 3-Tier RSS Feed Tool

For the coursework component of this module you are going to develop a 3-tier web application that implements an RSS feed tool. It will support multiple users, multiple RSS feeds, and when a user logs in to the system it will present to the user a set of articles drawn from the collated feeds a user has registered within the system. For this you will code:

1. A presentation layer, making use of HTML, Javascript and CSS
2. An application layer, making use of PHP
3. A data layer, making use of mySQL or MongoDB

The application should be designed according to the MVC design pattern and communication between each separate subsystem should be handled via XML or JSON.

RSS is an XML based protocol by which blogs, news-sites and other web resources release their updates to interested parties. This means that people can choose whether to visit the site or make use of an external tool to track updated content. You can read about the RSS protocol here: https://en.wikipedia.org/wiki/RSS

RSS data typically looks something like the following, although the specifics and details will vary from feed to feed:
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
	<channel>
		<title>RSS Title</title>
		<description>This is an example of an RSS feed</description>
		<link>http://www.example.com/main.html</link>
		<lastBuildDate>Mon, 06 Sep 2010 00:01:00 +0000</lastBuildDate>
		<pubDate>Sun, 06 Sep 2009 16:20:00 +0000</pubDate>
		<ttl>1800</ttl>
		<item>
			<title>Example entry</title>
			<description>Here is some text containing an interesting description.</description>
			<link>http://www.example.com/blog/post/1</link>
			<guid isPermaLink="false">7bd204c6-1655-4c27-aeee-53f933c5395f</guid>
			<pubDate>Sun, 06 Sep 2009 16:20:00 +0000</pubDate>
		</item>
	</channel>
</rss>
```

Your system will need to implement the following key features:

* A login system, making use of appropriate security measures such as salting.
  * Users will be able to record a list of feeds in which they are interested, and this has to be stored separately for each user account.
* A web API that permits users to add, remove or modify the RSS feeds that are associated with their account.
  * It should also give a consolidated list of articles that have been released on all RSS feeds since the last login.
* The application needs to store user and RSS feed information. Caching of feeds across user accounts is not necessary but would be meritorious.
* A front-end that permits users to access the functionality of the API and browse through the new entries in their feeds.

The software you develop must adhere to the design guidelines discussed during the course of the module:

* Appropriate use of the MVC design pattern
* Adherence to PAD layers within the 3-tier architecture
* Proper decomposition of responsibilities into the appropriate tier elements
* Should be asynchronous at the front-end and implementation agnostic at the back-end