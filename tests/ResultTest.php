<?php
use ActionKit\Result;

class ResultTest extends \PHPUnit\Framework\TestCase 
{
    public function testResult()
    {
        $result = new Result;
        $this->assertNotNull($result);

        $result->success('Success Tset');
        $this->assertEquals('success', $result->type);
        $this->assertEquals(true, $result->isSuccess());
        $this->assertNotNull($result->message);

        $this->assertNotNull($result->error('Error Tset'));
        $this->assertEquals('error', $result->type);
        $this->assertEquals(true, $result->isError());
        $this->assertNotNull($result->getMessage());

        $this->assertNotNull($result->valid('Valid Tset'));
        $this->assertEquals('valid', $result->type);
        $this->assertEquals(true, $result->isValidation());

        $this->assertNotNull($result->invalid('Valid Tset'));
        $this->assertEquals('invalid', $result->type);
        $this->assertEquals(true, $result->isValidation());

        $this->assertNotNull($result->completion('country', 'list', ['tw', 'jp', 'us']));
        $this->assertEquals('completion', $result->type);
        $this->assertEquals(true, $result->isCompletion());

        $this->assertNotNull($result->desc('description'));
        $this->assertNotNull($result->debug('debug'));

        $this->assertNotNull($result->toArray());
        $this->assertNotNull($result->__toString());
    }

    public function testResultData()
    {
        $result = new Result;
        $this->assertNotNull($result->data(['data1' => 'value1', 'data2' => 'value2']));
        $this->assertNotNull($result->data('data1', 'value1'));
        $this->assertNotNull($result->addData('data2', 'value2'));
        $this->assertNotNull($result->mergeData(['data3' => 'value3', 'data4' => 'value4']));
    }
}