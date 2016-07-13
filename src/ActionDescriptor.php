<?php
namespace ActionKit;

interface ActionDescriptor
{
    /**
     * Return the dynamic description of the execution.
     *
     * @return string
     */
    public function describe();

    /**
     * Return the description of this action class, `description` method
     * returns the human readable description for logging.
     *
     * by default it returns the class name of current instance.
     *
     * @return string
     */
    public function description();
}




