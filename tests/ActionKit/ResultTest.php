<?php
use ActionKit\Result;

class ResultTest extends \PHPUnit\Framework\TestCase 
{
    public function testResult()
    {
        $result = new Result;
        ok($result);

        $result->success('Success Tset');
        is('success', $result->type);
        is(true, $result->isSuccess());
        ok($result->message);

        ok($result->error('Error Tset'));
        is('error', $result->type);
        is(true, $result->isError());
        ok($result->getMessage());

        ok($result->valid('Valid Tset'));
        is('valid', $result->type);
        is(true, $result->isValidation());

        ok($result->invalid('Valid Tset'));
        is('invalid', $result->type);
        is(true, $result->isValidation());

        ok($result->completion('country', 'list', ['tw', 'jp', 'us']));
        is('completion', $result->type);
        is(true, $result->isCompletion());

        ok($result->desc('description'));
        ok($result->debug('debug'));

        ok($result->toArray());
        ok($result->__toString());
    }

    public function testResultData()
    {
        $result = new Result;
        ok($result->data(['data1' => 'value1', 'data2' => 'value2']));
        ok($result->data('data1', 'value1'));
        ok($result->addData('data2', 'value2'));
        ok($result->mergeData(['data3' => 'value3', 'data4' => 'value4']));
    }
}