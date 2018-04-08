# fastd fractal

### Usage

首先定义 Tranformer
```php
<?php
namespace Transformer;

use League\Fractal\TransformerAbstract;

class AppTransformer extends TransformerAbstract
{
    public function transform($app)
    {
        return [
            'id' => $app->id,
            'name' => $app->name,
        ];
    }
}

```

```php
<?php
namespace Controller;

use Models\App;
use FastD\Http\ServerRequest;
use Transformer\AppTransformer;
use FastD\Http\Response;

class AppsController
{
    public function show(ServerRequest $request)
    {
        $app = App::find($request->getAttribute('id'));
        
        return fractal()->item($app, AppTransformer::class, Response::HTTP_OK);
    }
}
```

请求后, 将输出

```
HTTP 200 OK
{"id": 1, "name": "testing"}
```