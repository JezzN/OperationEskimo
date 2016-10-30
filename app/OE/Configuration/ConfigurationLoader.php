<?php
namespace App\OE\Configuration;

use App\OE\Configuration\Configuration;
use App\OE\Forum\Post;

class ConfigurationLoader
{
    /**
     * Configuration is dona via forum threads, this will map a configuration type to a forum
     * thread which can then be parsed for the configuration.
     *
     * @var array
     */
    private $configurations = [
        'roster' => 142,
        'recruitment' => 211
    ];

    /**
     * Retrieve the configuration for a specified category.
     *
     * @return \stdClass
     * @param $category
     * @author Jeremy
     */
    public function get($category)
    {
        $this->guardAgainstInvalidConfiguration($category);

        $configuration = Configuration::where('category', $category)->first();

        return $configuration ? $configuration->configuration : new \stdClass();
    }

    /**
     * Parse all configurations and add them to the database.
     *
     * @author Jeremy
     */
    public function parseAll()
    {
        foreach( $this->configurations as $category => $postId ) {

            try {
                $post = Post::findOrFail($postId);

                $configuration = $this->extractConfigurationFromPost($post);

                $this->insertConfigurationIntoDatabase($configuration, $category);
            } catch ( \Exception $e ) {
                continue;
            }
        }
    }

    private function insertConfigurationIntoDatabase($configuration, $category)
    {
        $storedConfiguration = Configuration::where('category', $category)->first();

        if( ! $storedConfiguration ) {
            $storedConfiguration = new Configuration();
            $storedConfiguration->category = $category;
        }

        $storedConfiguration->configuration = $configuration;
        $storedConfiguration->save();
    }

    /**
     * Attempts to search the post for a JSON string and extracts it as configuration.
     *
     * @return mixed
     * @param Post $post
     * @author Jeremy
     */
    private function extractConfigurationFromPost(Post $post)
    {
        if( preg_match('/{(.|\n)+}/', $post->content, $matches) !== 1 ) {
            throw new \LogicException("No configuration in post");
        }

        return json_decode(str_replace(PHP_EOL, '', strip_tags(stripslashes($matches[0]))));
    }


    private function guardAgainstInvalidConfiguration($configuration)
    {
        if( ! isset($this->configurations[$configuration]) ) {
            throw new \InvalidArgumentException("The configuration {$configuration} does not exist");
        }
    }
}