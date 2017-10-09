<html>
<body>
    <form method="POST" action="{{$url}}">
        {{ csrf_field() }}
<?php
$prev_value = '';
foreach ($fields as $legend => $inputs)
{
    $close_fieldset = ($prev_value != '' && $prev_value != $legend);
    $open_fieldset  = ($prev_value == '' || $prev_value != $legend);
    if ($close_fieldset)
    {
?>
        </fieldset>
<?php
    } //close_fieldset
    if ($open_fieldset)
    {
?>
        <fieldset>
            <legend>{{$legend}}</legend>
<?php
    } //open_fieldset
    foreach ($inputs as $input)
    {
?>
                <label>{{$input['name']}}</label>
                @if ('text' == $input['type'])
                    <input type="text" name="{{$input['name']}}" value="{{$input['value']}}"/>
                @elseif ('select' == $input['type'])
                    <select name="{{$input['name']}}">
                    @foreach ($input['options'] as $option)
                        <option value="{{$option}}">{{$option}}</option>
                    @endforeach
                    </select>
                @endif
                <br />
<?php
    } // foreach inputs

    $prev_value = $legend;
} //foreach fields
?>
        </fieldset>
        <input type="submit" value="submit"/>
    </form>
</body>
</html>