<?php

namespace DMT\WebservicesNl\Client\Command\Handler\MethodNameInflector;

use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

/**
 * Class ClassNameWithoutSuffixInflector
 *
 * @package DMT\WebservicesNl\Client
 */
class ClassNameWithoutSuffixInflector implements MethodNameInflector
{
    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var int
     */
    protected $suffixLength = 0;

    /**
     * ClassNameWithoutSuffixInflector constructor.
     *
     * @param string $suffix
     */
    public function __construct(string $suffix = 'Command')
    {
        $this->suffix = $suffix;
        $this->suffixLength = strlen($suffix);
    }

    /**
     * Return the method name to call on the command handler and return it.
     *
     * Examples:
     *  - \CompleteTaskCommand     => $handler->completeTask()
     *  - \My\App\DoThingCommand   => $handler->doThing()
     *
     * @param object $command
     * @param object $commandHandler
     *
     * @return string
     */
    public function inflect($command, $commandHandler)
    {
        $commandName = get_class($command);

        $start = strpos($commandName, '\\') !== false ? strrpos($commandName, '\\') + 1 : 0;
        $length = strpos($commandName, $this->suffix, -$this->suffixLength) !== false ? $this->suffixLength : null;

        return lcfirst($length ? substr($commandName, $start, -$length) : substr($commandName, $start));
    }
}
