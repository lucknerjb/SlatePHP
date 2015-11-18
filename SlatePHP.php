<?php
/**
 *
 * SlatePHP
 * http://lucknerjb.com/open-source/slate-php
 *
 * (c) Luckner Jr Jean-Baptiste
 * http://lucknerjb.com
 *
 * SlatePHP is a PHP port of the tripit/slate project, built in ruby. Why?
 *
 * While implementing the Slate project, I ran into some issues that caused segfaults.
 * After not being able to fix these issues for the longest time, I decided to try and
 * re-create the tool in PHP. Another reason why I decided to create this project is that
 * not everyone has installed or can install ruby and its dependencies on their machines.
 * For those developers and companies who primarily use PHP, this makes using Slate much simpler.
 *
 *
 * For the full license information, view the LICENSE file that was distributed with this source code.
 */

/*
@TODO:
    - Move all the regexes into vars
    - Write specs
*/

require_once dirname(__FILE__) . '/vendor/autoload.php';

class SlatePHP {
    protected $fileContents;
    protected $config;

    public function __construct() {
        $this->config = json_decode(file_get_contents(dirname(__FILE__) . '/config.json'));
    }

    public function setSourceDirectory($dir)
    {
        $this->sourceDir = $dir;
    }

    public function setFile($fileName)
    {
        if (file_exists("{$this->sourceDir}/{$fileName}.md")) {
            $this->fileContents = file_get_contents("{$this->sourceDir}/{$fileName}.md");
        }
        else if (file_exists("{$this->sourceDir}/_{$fileName}.md")) {
            $this->fileContents = file_get_contents("{$this->sourceDir}/_{$fileName}.md");
        }
        else {
            // @TODO: Throw an exception
            $this->fileContents = '';
        }

        return $this;
    }

    public function evaluate($contents = '')
    {
        // Do we have any "slate_includes"?
        if (stristr($contents, 'slate_include')) {
            $regex = "/(\s+)slate_include:\s+'(.*)'(,\s+\[(.*)\])?/";
            preg_match($regex, $contents, $output);

            // Include found?
            if ($output) {
                $attributes = [];
                $leadingSpaces = $output[1];
                $fileName = $output[2];
                $attributesString = isset($output[3]) ? $output[3] : '';

                // Parse our attributes
                if (strlen($attributesString)) {
                    $tmpAttrs = explode(',', $attributesString);
                    foreach($tmpAttrs as $attr) {
                        $attrParts = explode(':', $attr);
                        $attributes[trim($attrParts[0])] = trim($attrParts[1]);
                    }
                }

                // Set attributes / vars
                foreach($attributes as $variable => $value) {
                    $$variable = $value;
                }

                // Get partial content and replace in actual content
                // @TODO: Move this to $this->baseDir
                $sourceDir = dirname(__FILE__) . '/source';

                if (file_exists("{$sourceDir}/{$fileName}.md")) {
                    $partialContents = file_get_contents("{$sourceDir}/includes/{$fileName}.md");
                }
                else if (file_exists("{$sourceDir}/_{$fileName}.md")) {
                    $partialContents = file_get_contents("{$sourceDir}/includes/_{$fileName}.md");
                }
                else {
                    // @TODO: Throw an exception
                    $partialContents = '';
                }

                // Apply the leading spaces to each line of the partial content
                $partialContentLines = explode("\n", $partialContents);
                foreach($partialContentLines as &$line) {
                    $line = "{$leadingSpaces}{$line}";
                }
                $partialContents = implode("\n", $partialContentLines);

                // Evaluate the partial as well in case it calls partials
                $partialContents = $this->evaluate($partialContents);

                // Replace the slate_include line with the content that we pulled
                $contents = preg_replace("/slate_include.*/", $partialContents, $contents);

                // Unset the vars that we created above
                foreach($attributes as $variable => $value) {
                    unset($$variable);
                }
            }
        }

        // Real dirty, temp proof of concept
        $var = 'BASE_URL';
        $contents = str_replace('%CONFIG.BASE_URL%', $this->config->custom->$var, $contents);

        return $contents;
    }

    public function parse()
    {
        // Evaluate the content
        $this->fileContents = $this->evaluate($this->fileContents);

        // Now that we have the full content, includes included, let's parse the Markdown
        $parsedContents = Parsedown::instance()->text($this->fileContents);

        // Add the header ID attributes for the tocify plugin
        $parsedContents = $this->addHeaderIdAttributes($parsedContents);

        return $parsedContents;
    }

    public function addHeaderIdAttributes($contents)
    {
        // @TODO: Use preg_replace_callback to make the replacements lowercase
        $contents = preg_replace("/\s?<h1>(.*)<\/h1>/", "<h1 id=\"$1\">$1</h1>", $contents);
        $contents = preg_replace("/\s?<h2>(.*)<\/h2>/", "<h2 id=\"$1\">$1</h2>", $contents);

        return $contents;
    }
}
