
        <link href="{{URL::asset('/vendor/apiinfo/js/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<style>
    body {
        margin-right: 10px;
        max-width: 1080px;
    }
    .pre {
        outline: 1px solid #ccc;
        background-color: #333;
        color: #ccc;
        padding: 5px; margin: 5px;
    }
    .string { color: rgb(20, 200, 20); }
    .number { color: darkorange; }
    .boolean { color: blue; }
    .null { color: magenta; }
    .key { color: yellow; }
    .methodPOST{
        background: rgb(73, 204, 144);
        font-size: 14px;
        font-weight: 700;
        min-width: 80px;
        text-align: center;
        text-shadow: rgba(0, 0, 0, 0.1) 0px 1px 0px;
        font-family: sans-serif;
        color: rgb(255, 255, 255);
        padding: 6px 15px;
        border-radius: 3px;
    }
    .methodPUT{
        background: rgb(252, 161, 48);
        font-size: 14px;
        font-weight: 700;
        min-width: 80px;
        text-align: center;
        text-shadow: rgba(0, 0, 0, 0.1) 0px 1px 0px;
        font-family: sans-serif;
        color: rgb(255, 255, 255);
        padding: 6px 15px;
        border-radius: 3px;
    }
    .methodDELETE{
        background: rgb(249, 62, 62);
        font-size: 14px;
        font-weight: 700;
        min-width: 80px;
        text-align: center;
        text-shadow: rgba(0, 0, 0, 0.1) 0px 1px 0px;
        font-family: sans-serif;
        color: rgb(255, 255, 255);
        padding: 6px 15px;
        border-radius: 3px;
    }
    .methodGET{
        background: rgb(97, 175, 254);
        font-size: 14px;
        font-weight: 700;
        min-width: 80px;
        text-align: center;
        text-shadow: rgba(0, 0, 0, 0.1) 0px 1px 0px;
        font-family: sans-serif;
        color: rgb(255, 255, 255);
        padding: 6px 15px;
        border-radius: 3px;
    }
    .methodANY{
        background: rgb(155, 11, 112);
        font-size: 14px;
        font-weight: 700;
        min-width: 80px;
        text-align: center;
        text-shadow: rgba(0, 0, 0, 0.1) 0px 1px 0px;
        font-family: sans-serif;
        color: rgb(255, 255, 255);
        padding: 6px 15px;
        border-radius: 3px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 0 0 20px 0;
        padding-right: 20px;
    }
    thead {
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }
    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }
    th {
        background-color: #f5f5f5;
    text-align: left;
    font-family: "Source Sans Pro", sans-serif;
    font-weight: 700;
    padding: 4px 8px;
    border: #e0e0e0 1px solid;
    }
    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }
    td {
        vertical-align: top;
        padding: 10px 8px 5px 8px;
        border: #e0e0e0 1px solid;
    }
    .required{
        background-color: rgb(249, 62, 62);
        color: #f5f5f5;
        padding: 2px 2px 2px 2px;
    }
    .nullable{
        background-color: rgb(11, 112, 11);
        color: #f5f5f5;
        padding: 2px 2px 2px 2px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
<script type="text/javascript">

function syntaxHighlight(json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);

    }

    json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');

    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
        var cls = 'number';

        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';

            } else {
                cls = 'string';

            }

        } else if (/true|false/.test(match)) {
            cls = 'boolean';

        } else if (/null/.test(match)) {
            cls = 'null';

        }
        return '<span class="' + cls + '">' + match + '</span>';

    });
}
</script>
<div>
    <h1>{{$treeGroupName}} - {{$methodDoc}} - {{$docVersion}}</h1>
    <h4>{!! $description !!}</h4>
    <p>
        <span class="method{{$method}}">{{$method}}</span>
    </p>
    <p class="pre">
        <span class="path">{{$uri}}</span>
    </p>
    <h3>参数</h3>
    <table>
        <thead>
            <tr>
            <th>字段</th>
            <th>类型</th>
            <th>描述</th>
            <th>是否必须</th>
            <th>默认值</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($params as $paramInfo)
            <tr>
                <td>{{$paramInfo['name']}}</td>
                <td>{{$paramInfo['type']}}</td>
                <td>{{$paramInfo['doc']}}</td>
                <td>{{$paramInfo['required']}}</td>
                <td>{{$paramInfo['defaultValue']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <ul class="nav nav-tabs nav-tabs-examples">
        <li class="active">
          <a href="Example">Response (example):</a>
        </li>
      </ul>
    <pre class="pre" id="resultExample">
    </pre>
    @foreach ($docExample as $exampleKey => $exampleValue)
    <ul class="nav nav-tabs nav-tabs-examples">
        <li class="active">
          <a href="Example">{{$exampleValue['description']}} (example):</a>
        </li>
      </ul>
    <pre class="pre" id="resultExample{{$exampleKey}}">
    </pre>
    <script>
        $('#resultExample{{$exampleKey}}').html(
            syntaxHighlight(
                {!! $exampleValue['location'] !!}
            )
        );
    </script>
    @endforeach
    <h3>发送示例请求</h3>
    <form style="display: inline;" method="{{$method}}" action="/{{$uri}}">
        <fieldset>
            <h3>参数</h3>
            <h4><input type="checkbox" data-sample-request-param-group-id="sample-request-param-0" name="param" value="0" class="sample-request-param sample-request-switch" checked="">参数
              <select name="method" class="sample-header-content-type sample-header-content-type-switch">
                <option value="undefined" selected="">ajax-auto</option>
                <option value="body-json">body/json</option>
                <option value="body-form-data">body/form-data</option>
              </select>
            </h4>
            @foreach ($params as $paramInfo)
            <div class="form-group">
                <label class="col-md-3 control-label" for="example-params-{{$paramInfo['name']}}">{{$paramInfo['name']}} - [<span class="{{$paramInfo['required']}}">{{$paramInfo['required']}}</span>]</label>
                <div class="input-group">
                  <input id="example-params-{{$paramInfo['name']}}" type="text" placeholder="{{$paramInfo['name']}}" class="form-control sample-request-param" name="{{$paramInfo['name']}}">
                  <div class="input-group-addon">{{$paramInfo['type']}}</div>
                </div>
            </div>
            @endforeach
            <div class="form-group">
                <div class="controls pull-right">
                <button class="btn btn-primary" type="submit">发送</button>
                </div>
          </div>
        </fieldset>
    </form>
    <pre class="pre" id="result" style="display: none;">
    </pre>

</div>

<script type="text/javascript">
    $('#resultExample').html(syntaxHighlight({!! $returnDoc !!}));
    // $('#result').html(syntaxHighlight(songResJson));
</script>


