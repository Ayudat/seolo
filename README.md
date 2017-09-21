# Seolo

Utilidades SEO para proyectos Laravel 5.4 en adelante. Incluye:
- Editor de textos que se pinten usando el helper `t()`.
- Editor de texto alternativo de las imágenes a las que se les ponga la clase CSS `seolo`.
- Editor de etiquetas meta y title.
- Editor de días festivos.

### Añadir Seolo al proyecto

Insertar la siguente línea en la sección **require** del **composer.json** del proyecto:
```ssh
"ayudat/seolo": "master-dev"
```
Hacer un **composer update** y un **npm install**.

En el **composer.json** añadimos la línea `"Ayudat\\Seolo\\": "vendor/ayudat/seolo/src"`
```ssh
"autoload": {
    "classmap": [
        "database/seeds",
        "database/factories"
    ],
    "psr-4": {
        "App\\": "app/",
        "Ayudat\\Seolo\\": "vendor/ayudat/seolo/src"
    }
},
```
En **config/app.php** añadimos el service provider: `Ayudat\Seolo\SeoloServiceProvider::class`

Ejecutamos:
```ssh
$ composer dump-autoload
```

### Assets y publicación

En el **webpack.mix.js** añadimos, despúes de let mix = ...:
```ssh
mix.options({
  processCssUrls: false // Do not process/optimize relative stylesheet url()'s, for asset_()
});
```

Si no vamos a usar Vue, comentamos en **resources/assets/js/app.js** todo menos la línea: **require('./bootstrap')**.

Quedaría, aproximadamente:
```ssh
...

require('./bootstrap');

//window.Vue = require('vue');

...

//Vue.component('example', require('./components/Example.vue'));

//const app = new Vue({
//    el: '#app'
//});
```

Hacemos un **npm run dev** para generar los ficheros base CSS del proyecto, por si queremos usarlos en un backend o algo así.

Una vez hecho, en el **webpack.mix.js**, podemos comentar las líneas de mix que haya, **para que no se compilen más y supongan una pérdida de tiempo en cada "npm run dev"**, y añadimos al final:
```ssh
mix.sass('resources/assets/sass/seolo.scss', 'public/css');
```
Seolo ya incluye el fichero _variables.scss y el bootstrap, después de esta línea del mix, podemos añadir los scss de nuestro proyecto.

Ya podemos publicar y compilar, para obtener los ficheros y el CSS de Seolo *(esto conviene hacerlo cada vez que se haga un composer update, para asegurar los assets y demás)*:
```ssh
$ php artisan vendor:publish --force --provider="Ayudat\Seolo\SeoloServiceProvider" && npm run dev
```

### Rutas

En routes/web.php, nombrar la ruta index **ya que el sistema de tags lo necesita**, ojo: **todas las rutas han de tener un "name" al que se asociarán sus datos SEO**.
```ssh
Route::get('/', function () {
    return view('welcome');
})->name('index');
```

### Migraciones

Tras **configurar la base de datos en el .env**, ejecutamos las migraciones, que crearán la tabla "seolo_texts":
```ssh
$ php artisan migrate:refresh --seed
```
En el caso de que suceda un error que contenga algo parecido a **"specified key was too long error"**, edita tu **AppServiceProvider.php** y añade:
```ssh
use Illuminate\Support\Facades\Schema;

public function boot()
{
    Schema::defaultStringLength(191);
}
```
Y luego intenta ejecutar el *artisan* de nuevo.

### Autenticación

Posibilitar el sistema de autenticación, para que se activen los añadidos de Seolo se necesita un usuario autenticado, en principio cualquiera, para más profundidad en permisos o usuarios que pueden "ver" Seolo, tendrás que modificarlo ;)
```ssh
$ php artisan make:auth
```

Una vez hecho esto, **necesitas tener al menos un usuario en la base de datos**, crea un seed para poblar la tabla de usuarios con al menos uno, de tal manera que se pueda hacer login, en el manual de Laravel viene cómo hacerlo, en **Database > Seeding**.

### Idioma

Los mensajes de Seolo vienen en inglés y español. Según el "locale" que se tenga definido en **config/app.php**

### Modificaciones en el  layout

En el <head> del layout del proyecto, sustituir la etiqueta `title` por lo siguiente *(ojo con el og:image, es para cuando la web se comparte en Facebook, se ha de crear la imagen de 300x300, o quitar esa línea)*:
```ssh
<?php $routeName = Illuminate\Support\Facades\Route::current()->getName(); ?>
<title>{{ tag($routeName, 'tab', config('app.name')) }}</title>
<meta property="og:title" content="{{ tag($routeName, 'title') }}"/>
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route($routeName) }}"/>
<meta property="og:description" content="{{ tag($routeName, 'description') }}"/>
<meta property="og:image" content="{{ route('index') }}/images/logo-fb.png"/>
<meta name="description" content="{{ tag($routeName, 'description') }}"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
```

Antes del </body>:

Quizás aquí, tendrás que añadir el javascript del proyecto, que deberá incluir **jQuery**:
```ssh
<script src="{{ asset_('js/app.js') }}"></script>
```

y ya luego:

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

Cualquier texto llamado con `t()` y que no tenga key en los ficheros de traducciones, Seolo intentará encontrarlo en la tabla `seolo_texts` de base de datos, si lo encuentra y se está logado, se hará editable. Si se usa t() con formato "blade", usa la forma: `{!! t('...') !!}`, para que el html que Seolo necesita se pinte correctamente.

A cualquier imagen con esta estructura se le podrá editar el `alt`:
```ssh
<img src="{{ asset_('images/test.png') }}" alt="{{ alt('test') }}" class="seolo" data-seolokey="test"/>
```
Donde `seolo-alt.test` sería la key de esa imagen en la base de datos de Seolo, si la imagen tiene un ancla alrededor, su href se anulará para permitir la edición del alt de la imagen que contiene. Se pueden guardar textos alternativos vacíos, por si no se quiere utilizar el *alt* en alguna imagen.

La forma que adoptan los textos de las tags es, por ejemplo para la ruta "index":
`seolo-tag.index.tab`, para la etiqueta 'title'
`seolo-tag.index.title`, para el meta 'og:title'
`seolo-tag.index.description`, para el meta 'description' y en el 'og:description'

En `seolo-festives` se guardará el texto de días festivos.

### Helpers

Seolo incluye unos helpers para operar con él, son:

`asset_($asset)`
Devuelve la ruta a un asset (imagen, js, css), añadiéndole `?_=time al final`, donde 'time' es el timestamp de la fecha de modificación del asset, esto hace que cada vez que se modifica una imagen o un css, los navegadores clientes lo cacheen de nuevo. es **importante** saber que, si se mueve la carpeta 'public' del proyecto y no se encuentran bien los assets usando este helper, quizás haya que usar la variable de configuración 'seolo.public-path' para definir el directorio en donde está (el publish de Seolo genera un config/seolo.php).

`t($key, $amount = 1, $replace = [], $editable = true)`
Funciona igual que 'trans_choice', pero si no encuentra la $key en los ficheros de traducción del proyecto, busca en la tabla seolo_texts, si está ahí, muestra el texto, y si se está logado, además permite editarlo.

`tag($routeName, $key, $default = '')`
Devuelve el dato tag de una ruta que tenga 'name' (por ejemplo 'index'), $key puede valer [tab|title|description], normalmente es usada por Seolo en el bloque que se incluye en el `head` de un layout (visto en una sección anterior de este mismo documento).

`alt($key, $default = '')`
Devuelve el texto alternativo de una imagen (visto su uso en una etiqueta `img` de ejemplo en una sección anterior de este mismo documento).

`inSchedule()`
Devuelve verdadero si se está en horario laboral, falso si se está fuera de horario o en un día festivo. **Importante:** recuerda poner el timezone a "Europe/Madrid" en **config/app.php**
