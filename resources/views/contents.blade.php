<style>

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

</style>

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
    Welcome to use ApiInfo!
    <textarea id="json-input" autocomplete="off">
    @foreach($docExample as $example)
        {{trim($example['location'])}}
    @endforeach
    </textarea>
    {{-- {{$docExample[0]['location']}} --}}
    </textarea>
    <pre class="pre" id=""><?php echo $jsonStr;?></pre>
    <pre class="pre" id="result">
    </pre>

</div>

<script type="text/javascript">

    var songResJson={

          "service": "ALL",

          "qt": 581,

          "content": {

            "answer": {

              "song": "你能看到我",

              "album": "是的,我看见了",

              "artist": "啊哈......",

              "pic_url": "http://p1.music.126.net/-u3WgIXsFNCW7d8Jy7pCEA==/5921969627395387.jpg"

            },

            "scene": "music",
            "array": [
                {"log": 123}
            ]

          }

        }

        document.getElementById('result').innerHTML = syntaxHighlight(songResJson);

    // $('#result').html(syntaxHighlight(songResJson));

</script>


