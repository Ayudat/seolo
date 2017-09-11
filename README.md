# Seolo

Utilidades SEO para proyectos Laravel 5.4 en adelante.

### Paso 1

Comentar en **resources/assets/js/app.js** todo menos la línea: **require('./bootstrap')** y compilar:
```sh
$ npm run dev
```

### Paso 2

En **webpack.mix.js**, comentar las líneas de mix que haya (para que no se compilen más), añadir:
```sh
mix.sass('resources/assets/sass/seolo.scss', 'public/css');
```

### Paso 3

Publicar y compilar de nuevo para obtener los ficheros y el CSS de Seolo *(esto conviene hacerlo cada vez que se haga un composer update, para asegurar los assets y demás)*:
```sh
$ php artisan vendor:publish --force --provider="Ayudat\Seolo\SeoloServiceProvider" && npm run dev
```

### Paso 4

En el head del layout, sustituir <tiltle>...</title> por lo siguiente *(ojo con el og:image, es para cuando la web se comparte en Facebook, se ha de crear la imagen de 300x300, o quitar esa línea)*:
```ssh
<title>{{ tag($routeName, 'tab', config('app.name')) }}</title>
<meta property="og:title" content="{{ tag($routeName, 'title') }}"/>
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route($routeName) }}"/>
<meta property="og:description" content="{{ tag($routeName, 'description') }}"/>
<meta property="og:image" content="{{ route('index') }}/images/logo-fb.png"/>
<meta name="description" content="{{ tag($routeName, 'description') }}"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Paso 5

En routes/web.php, nombrar la ruta index (para los tags), con al menos (todas las rutas han de tener un "name" al que se asociarán sus datos SEO):
```ssh
Route::get('/', function () { return view('seolo'); })->name('index');
```

### Paso 6

El el layout antes del </body>:
```ssh
@if (Illuminate\Support\Facades\Auth::user())
    <link href="{{ asset_('css/seolo.css') }}" rel="stylesheet" type="text/css">
    @include('seolo::text')
    @include('seolo::tags')
    @include('seolo::alt')
    @include('seolo::festives')
@endif
```

### Anotaciones

Cualquier texto llamado con `t()` y que no tenga key en los ficheros de traducciones, Seolo intentará encontrarlo en la base de datos, si lo encuentra y se está logado, se hará editable.

A cualquier imagen con esta estructura se le podrá editar el `alt`:
```ssh
<img src="{{ asset_('images/test.png') }}" alt="{{ alt('test') }}" class="seolo" data-seolokey="test"/>
```
Donde "test" sería la key de esa imagen en la base de datos de Seolo, si la imagen tiene un ancla alrededor, su href se anulará para permitir la edición del alt de la imagen que contiene.
