SlatePHP
========

Origin
------------------------------

SlatePHP is a port of the [tripit/slate](https://github.com/tripit/slate/) project. Why did I create this project?

1. I had some issues getting Middleman running and building the documentation on some of the hardware I had access to
2. Not everyone can or wants to install ruby. Ruby can be a tad hard to setup for some but PHP is quite the opposite.


Getting Started with SlatePHP
------------------------------

### Prerequisites

You're going to need:

 - **Linux or OS X** — Windows may work, but is unsupported.
 - A recent version of PHP installed. SlatePHP is built with PHP v5.5.18

### Getting Set Up

 1. Fork this repository on Github.
 2. Clone *your forked repository* (not our original one) to your hard drive with `git clone https://github.com/YOURUSERNAME/slate.git`
 3. `cd slatePHP`
 4. Start the built-in PHP webserver: `php -S localhost:8080 -t source/`

You can now see the docs at <http://localhost:8080>. Whoa! That was fast!

### Working with SlatePHP

The index.php file in your source will automatically pull in changes made to your config.json and *.md files. Play around with the example code to get a feel for how the project is setup.

### Building and Deploying SlatePHP

To build, simply run `php build.php` in the project root. This will create a `dist` folder with all your assets and a single index.html file. Upload the assets and the html file to your webserver.

## Questions? Issues? Comments?

Open up an issue [here](https://github.com/lucknerjb/SlatePHP/issues)!
