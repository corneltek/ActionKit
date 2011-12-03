<div class="action-result action-result-{$result->type}">

{if $result->isError()}
    <div class="errors">
        {if $result->message}
            <div class="error-title">
                {$result->message}
            </div>
        {/if}
        {foreach $result->validations as $k => $v}
            {if $v.invalid}
                <div class="error-message">{$v.invalid}</div>
            {/if}
        {/foreach}
    </div>

{elseif $result->isSuccess()}
    <div class="success">
        <div class="result-message"> {$result->message} </div>
    </div>
{/if}

</div>
