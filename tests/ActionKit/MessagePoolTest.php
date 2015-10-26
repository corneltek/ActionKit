<?php
use ActionKit\MessagePool;

class MessagePoolTest extends PHPUnit_Framework_TestCase
{
    public function testMessagePool()
    {
        $messages = new MessagePool;
        $messages->loadMessages([
            'file.required'  => 'File Field %1 is required. %2',
            'param.required' => 'Field %1 is required.',
            'validation.error' => 'Please check your input.',
        ]);

        $msg = $messages->translate('file.required', 'name', 'do something');
        $this->assertEquals('File Field name is required. do something', $msg);

        $msg = $messages->format('%1 %2 %3',[1,2,3]);
        $this->assertEquals('1 2 3', $msg);
    }

    public function testFormat()
    {
        $messages = new MessagePool;
        $msg = $messages->format('%name works in google', ['name' => 'John' ]);
        $this->assertEquals('John works in google', $msg);
    }

}
